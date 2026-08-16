<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class DashboardTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGuestIsRedirectedFromDashboard(): void
    {
        $response = $this->get('dashboard');

        $response->assertRedirectTo(site_url('login'));
    }

    public function testAuthenticatedUserCanOpenDashboard(): void
    {
        $response = $this->withSession([
            'is_logged_in' => true,
            'user_id' => 1,
            'full_name' => 'HH Sonet',
            'email' => 'hhsonet@gmail.com',
        ])->get('dashboard');

        $response->assertOK();
        $response->assertSee('Welcome back, HH');
        $response->assertSee('Logout');
    }

    public function testLogoutRouteRequiresPost(): void
    {
        try {
            $this->get('logout');
            $this->fail('Expected GET /logout to be unavailable.');
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof \CodeIgniter\Exceptions\PageNotFoundException);
        }
    }
}
