<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class InternshipTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testApplyPageShowsNoOpenRoundMessage(): void
    {
        $this->seedUser();

        $response = $this->withSession($this->authSession())->get('apply');

        $response->assertOK();
        $response->assertSee('There is currently no open call for applications.');
    }

    public function testSuccessfulApplicationSubmissionStoresAllFields(): void
    {
        $this->seedUser();
        $this->seedRound(true);
        $token = $this->getCsrfToken('apply');

        $response = $this->withSession($this->authSession())->post('apply', [
            csrf_token() => $token,
            'student_id' => 'ST-12345',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.75',
            'total_credits' => '160',
            'earned_credits' => '150',
            'internship_type' => 'Capstone',
            'team_member_count' => '4',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-11-30',
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '+8801712345678',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => 'https://tech.example.com',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
        ]);

        $response->assertRedirectTo(site_url('applications'));

        $application = $this->db->table('internship_applications')->where('user_id', 1)->get()->getRowArray();
        $this->assertNotEmpty($application);
        $this->assertSame('ST-12345', $application['student_id']);
        $this->assertSame('Capstone', $application['internship_type']);
        $this->assertSame('4', (string) $application['team_member_count']);
        $this->assertSame('3.75', (string) $application['current_cgpa']);
        $this->assertSame('150', (string) $application['earned_credits']);
        $this->assertSame('HH Sonet', $application['full_name']);
        $this->assertSame('Man', $application['gender_identity']);
    }

    public function testConditionalFieldsAreClearedForIndustryApplications(): void
    {
        $this->seedUser();
        $this->seedRound(true);
        $token = $this->getCsrfToken('apply');

        $response = $this->withSession($this->authSession())->post('apply', [
            csrf_token() => $token,
            'student_id' => 'ST-54321',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.20',
            'total_credits' => '160',
            'earned_credits' => '145',
            'internship_type' => 'Industry',
            'team_member_count' => '7',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-11-30',
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '01712345678',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => '',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
        ]);

        $response->assertRedirectTo(site_url('applications'));

        $application = $this->db->table('internship_applications')->where('student_id', 'ST-54321')->get()->getRowArray();
        $this->assertNotEmpty($application);
        $this->assertNull($application['team_member_count']);
    }

    public function testDuplicateRoundSubmissionIsPrevented(): void
    {
        $this->seedUser();
        $this->seedRound(true);
        $this->seedApplication();
        $token = $this->getCsrfToken('apply');

        $response = $this->withSession($this->authSession())->post('apply', [
            csrf_token() => $token,
            'student_id' => 'ST-12345',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.75',
            'total_credits' => '160',
            'earned_credits' => '150',
            'internship_type' => 'Capstone',
            'team_member_count' => '4',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-11-30',
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '+8801712345678',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => 'https://tech.example.com',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
        ]);

        $response->assertRedirectTo(site_url('applications/1'));
    }

    public function testMissingConditionalFieldsAreRejected(): void
    {
        $this->seedUser();
        $this->seedRound(true);
        $token = $this->getCsrfToken('apply');

        $response = $this->withSession($this->authSession())->post('apply', [
            csrf_token() => $token,
            'student_id' => 'ST-12345',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.75',
            'total_credits' => '160',
            'earned_credits' => '150',
            'internship_type' => 'Capstone',
            'team_member_count' => '',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-11-30',
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '+8801712345678',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => 'https://tech.example.com',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
        ]);

        $response->assertRedirectTo(site_url('apply'));
        $errors = session('errors');
        $this->assertArrayHasKey('team_member_count', $errors);
    }

    public function testEndDateMustBeLaterThanStartDate(): void
    {
        $this->seedUser();
        $this->seedRound(true);
        $token = $this->getCsrfToken('apply');

        $response = $this->withSession($this->authSession())->post('apply', [
            csrf_token() => $token,
            'student_id' => 'ST-12345',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.75',
            'total_credits' => '160',
            'earned_credits' => '150',
            'internship_type' => 'Capstone',
            'team_member_count' => '3',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-08-01',
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '+8801712345678',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => 'https://tech.example.com',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
        ]);

        $response->assertRedirectTo(site_url('apply'));
        $this->assertArrayHasKey('internship_end_date', session('errors'));
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

    private function seedRound(bool $open = false): void
    {
        $this->hasInDatabase('universities', [
            'id' => 1,
            'name' => 'Jashore University of Science and Technology',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->hasInDatabase('departments', [
            'id' => 1,
            'university_id' => 1,
            'name' => 'Computer Science and Engineering',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->hasInDatabase('application_rounds', [
            'id' => 1,
            'round_number' => 1,
            'title' => 'Internship Round 1',
            'description' => 'Round description',
            'opens_at' => $open ? date('Y-m-d H:i:s', strtotime('-1 hour')) : date('Y-m-d H:i:s', strtotime('+1 day')),
            'closes_at' => $open ? date('Y-m-d H:i:s', strtotime('+1 hour')) : date('Y-m-d H:i:s', strtotime('+2 day')),
            'status' => $open ? 'Open' : 'Draft',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function seedApplication(): void
    {
        $this->hasInDatabase('internship_applications', [
            'id' => 1,
            'round_id' => 1,
            'user_id' => 1,
            'full_name' => 'HH Sonet',
            'gender_identity' => 'Man',
            'student_id' => 'ST-12345',
            'university_id' => 1,
            'department_id' => 1,
            'current_cgpa' => '3.75',
            'total_credits' => '160.00',
            'earned_credits' => '150.00',
            'internship_type' => 'Capstone',
            'team_member_count' => 4,
            'supervisor_name' => 'Dr. Supervisor',
            'supervisor_email' => 'supervisor@example.com',
            'supervisor_university' => 'External University',
            'supervisor_department' => 'CSE',
            'supervisor_designation' => 'Professor',
            'supervisor_phone' => '+8801712345678',
            'internship_start_date' => '2026-09-01',
            'internship_end_date' => '2026-11-30',
            'placement_organization_name' => 'Tech Corp',
            'organization_website_url' => 'https://tech.example.com',
            'mentor_name' => 'Mentor Person',
            'mentor_email' => 'mentor@example.com',
            'status' => 'Submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
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
        $this->assertNotEmpty($matches[1] ?? null);
        return $matches[1];
    }
}
