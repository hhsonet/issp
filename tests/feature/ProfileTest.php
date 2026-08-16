<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ProfileTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testProfilePageRequiresLogin(): void
    {
        $response = $this->get('profile');

        $response->assertRedirectTo(site_url('login'));
    }

    public function testAuthenticatedUserCanViewProfilePage(): void
    {
        $this->seedUser();

        $response = $this->withSession($this->authSession())->get('profile');

        $response->assertOK();
        $response->assertSee('Profile Settings');
        $response->assertSee('Full Name');
        $response->assertSee('Change Password');
    }

    public function testProfileUpdateSavesPersonalInformation(): void
    {
        $this->seedUser();
        $token = $this->getCsrfToken('profile');

        $response = $this->withSession($this->authSession())->post('profile', [
            csrf_token() => $token,
            'full_name' => 'Updated Name',
            'phone' => '01712345679',
            'gender_identity' => 'Man',
        ]);

        $response->assertRedirectTo(site_url('profile'));
        $this->seeInDatabase('users', [
            'id' => 1,
            'full_name' => 'Updated Name',
            'phone' => '01712345679',
            'gender_identity' => 'Man',
        ]);
    }

    public function testPasswordUpdateChangesPassword(): void
    {
        $this->seedUser();
        $token = $this->getCsrfToken('profile');

        $response = $this->withSession($this->authSession())->post('profile/password', [
            csrf_token() => $token,
            'current_password' => 'Man@0000',
            'new_password' => 'NewPass@123',
            'confirm_new_password' => 'NewPass@123',
        ]);

        $response->assertRedirectTo(site_url('profile'));
        $user = $this->db->table('users')->where('id', 1)->get()->getRowArray();
        $this->assertTrue(password_verify('NewPass@123', $user['password_hash']));
    }

    public function testPasswordUpdateRejectsWrongCurrentPassword(): void
    {
        $this->seedUser();
        $token = $this->getCsrfToken('profile');

        $response = $this->withSession($this->authSession())->post('profile/password', [
            csrf_token() => $token,
            'current_password' => 'Wrong@123',
            'new_password' => 'NewPass@123',
            'confirm_new_password' => 'NewPass@123',
        ]);

        $response->assertRedirectTo(site_url('profile'));
        $this->assertSame('Current password is incorrect.', session('error'));
    }

    private function seedUser(): void
    {
        $this->hasInDatabase('users', [
            'id' => 1,
            'full_name' => 'HH Sonet',
            'email' => 'hhsonet@gmail.com',
            'phone' => '+8801745379745',
            'password_hash' => password_hash('Man@0000', PASSWORD_DEFAULT),
            'gender' => 'man',
            'gender_identity' => 'Man',
            'date_of_birth' => '1995-06-20',
            'disability_status' => 'No',
            'disability_type' => null,
            'ethnic_minority_status' => 'No',
            'ethnic_group_name' => null,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function authSession(): array
    {
        return [
            'is_logged_in' => true,
            'user_id' => 1,
            'full_name' => 'HH Sonet',
            'email' => 'hhsonet@gmail.com',
        ];
    }

    private function getCsrfToken(string $path): string
    {
        $response = $this->withSession($this->authSession())->get($path);
        $body = $response->getBody();

        preg_match('/name="' . preg_quote(csrf_token(), '/') . '" value="([^"]+)"/', $body, $matches);
        $this->assertNotEmpty($matches[1] ?? null, 'Could not locate CSRF token in the rendered form.');

        return $matches[1];
    }
}
