<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class SignupTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testSignupPageIsReachable(): void
    {
        $response = $this->get('signup');

        $response->assertOK();
        $response->assertSee('Create account');
        $response->assertSee('Student Name');
        $response->assertSee('Gender Identity');
    }

    public function testSuccessfulRegistrationCreatesHashedPasswordAndStoresSurveyData(): void
    {
        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
            'full_name' => 'Rahim Uddin',
            'email' => 'Rahim@University.edu',
            'date_of_birth' => '2000-01-15',
            'phone' => '+8801712345678',
            'gender_identity' => 'Woman',
            'disability_status' => 'No',
            'disability_type' => '',
            'ethnic_minority_status' => 'No',
            'ethnic_group_name' => '',
            'password' => 'Strong@123',
            'confirm_password' => 'Strong@123',
        ]);

        $response->assertRedirectTo(site_url('login'));

        $this->seeInDatabase('users', [
            'full_name' => 'Rahim Uddin',
            'email' => 'rahim@university.edu',
            'date_of_birth' => '2000-01-15',
            'phone' => '+8801712345678',
            'gender_identity' => 'Woman',
            'disability_status' => 'No',
            'disability_type' => null,
            'ethnic_minority_status' => 'No',
            'ethnic_group_name' => null,
            'status' => 'active',
        ]);

        $user = $this->db->table('users')->where('email', 'rahim@university.edu')->get()->getRowArray();
        $this->assertNotSame('Strong@123', $user['password_hash']);
        $this->assertTrue(password_verify('Strong@123', $user['password_hash']));
    }

    public function testMissingRequiredFields(): void
    {
        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
        ]);

        $response->assertRedirectTo(site_url('signup'));
        $this->assertArrayHasKey('full_name', session('errors'));
        $this->assertArrayHasKey('email', session('errors'));
        $this->assertArrayHasKey('date_of_birth', session('errors'));
        $this->assertArrayHasKey('phone', session('errors'));
        $this->assertArrayHasKey('gender_identity', session('errors'));
        $this->assertArrayHasKey('disability_status', session('errors'));
        $this->assertArrayHasKey('ethnic_minority_status', session('errors'));
        $this->assertArrayHasKey('password', session('errors'));
        $this->assertArrayHasKey('confirm_password', session('errors'));
    }

    public function testInvalidSurveyValuesAreRejected(): void
    {
        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
            'full_name' => 'Ra',
            'email' => 'not-an-email',
            'date_of_birth' => '2035-01-01',
            'phone' => '12345',
            'gender_identity' => 'Alien',
            'disability_status' => 'Maybe',
            'ethnic_minority_status' => 'Maybe',
            'password' => 'weakpass',
            'confirm_password' => 'different',
        ]);

        $response->assertRedirectTo(site_url('signup'));

        $errors = session('errors');
        $this->assertArrayHasKey('full_name', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('phone', $errors);
        $this->assertArrayHasKey('gender_identity', $errors);
        $this->assertArrayHasKey('disability_status', $errors);
        $this->assertArrayHasKey('ethnic_minority_status', $errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertArrayHasKey('confirm_password', $errors);
    }

    public function testFutureDateOfBirthIsRejected(): void
    {
        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
            'full_name' => 'Future Student',
            'email' => 'future@example.com',
            'date_of_birth' => '2035-01-01',
            'phone' => '01712345680',
            'gender_identity' => 'Woman',
            'disability_status' => 'No',
            'ethnic_minority_status' => 'No',
            'password' => 'Strong@123',
            'confirm_password' => 'Strong@123',
        ]);

        $response->assertRedirectTo(site_url('signup'));
        $this->assertArrayHasKey('date_of_birth', session('errors'));
    }

    public function testConditionalSurveyFieldsAreRequiredWhenEnabled(): void
    {
        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
            'full_name' => 'Another User',
            'email' => 'another@example.com',
            'date_of_birth' => '2001-08-16',
            'phone' => '01712345679',
            'gender_identity' => 'Man',
            'disability_status' => 'Yes',
            'disability_type' => '',
            'ethnic_minority_status' => 'Yes',
            'ethnic_group_name' => '',
            'password' => 'Strong@123',
            'confirm_password' => 'Strong@123',
        ]);

        $response->assertRedirectTo(site_url('signup'));
        $errors = session('errors');
        $this->assertArrayHasKey('disability_type', $errors);
        $this->assertArrayHasKey('ethnic_group_name', $errors);
    }

    public function testDuplicateEmailAndPhoneAreRejected(): void
    {
        $this->hasInDatabase('users', [
            'full_name' => 'Existing User',
            'email' => 'existing@example.com',
            'phone' => '+8801712345678',
            'password_hash' => password_hash('Strong@123', PASSWORD_DEFAULT),
            'gender' => 'male',
            'gender_identity' => 'Woman',
            'disability_status' => 'No',
            'disability_type' => null,
            'ethnic_minority_status' => 'No',
            'ethnic_group_name' => null,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getCsrfToken('signup');

        $response = $this->post('signup', [
            csrf_token() => $token,
            'full_name' => 'Another User',
            'email' => 'existing@example.com',
            'date_of_birth' => '2001-08-16',
            'phone' => '+8801712345678',
            'gender_identity' => 'Man',
            'disability_status' => 'No',
            'ethnic_minority_status' => 'No',
            'password' => 'Strong@123',
            'confirm_password' => 'Strong@123',
        ]);

        $response->assertRedirectTo(site_url('signup'));
        $this->assertSame('An account already exists with this email address.', session('error'));
    }

    public function testCsrfRejection(): void
    {
        try {
            $this->post('signup', [
                'full_name' => 'Rahim Uddin',
                'email' => 'rahim@example.com',
                'date_of_birth' => '2000-01-15',
                'phone' => '01712345678',
                'gender_identity' => 'Woman',
                'disability_status' => 'No',
                'ethnic_minority_status' => 'No',
                'password' => 'Strong@123',
                'confirm_password' => 'Strong@123',
            ]);

            $this->fail('Expected CSRF rejection did not occur.');
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof \CodeIgniter\Security\Exceptions\SecurityException);
        }
    }

    public function testLoginSucceedsForExistingUser(): void
    {
        $this->hasInDatabase('users', [
            'full_name' => 'HH Sonet',
            'email' => 'hhsonet@gmail.com',
            'date_of_birth' => '1995-06-20',
            'phone' => '+8801745379745',
            'password_hash' => password_hash('Man@0000', PASSWORD_DEFAULT),
            'gender' => 'prefer_not_to_say',
            'gender_identity' => 'Man',
            'disability_status' => 'No',
            'disability_type' => null,
            'ethnic_minority_status' => 'No',
            'ethnic_group_name' => null,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $token = $this->getCsrfToken('login');

        $response = $this->post('login', [
            csrf_token() => $token,
            'email' => 'hhsonet@gmail.com',
            'password' => 'Man@0000',
        ]);

        $response->assertRedirectTo(site_url('dashboard'));
        $this->assertSame(true, session('is_logged_in'));
        $this->assertSame('HH Sonet', session('full_name'));
    }

    private function getCsrfToken(string $path): string
    {
        $response = $this->get($path);
        $body = $response->getBody();

        preg_match('/name="' . preg_quote(csrf_token(), '/') . '" value="([^"]+)"/', $body, $matches);
        $this->assertNotEmpty($matches[1] ?? null, 'Could not locate CSRF token in the rendered form.');

        return $matches[1];
    }
}
