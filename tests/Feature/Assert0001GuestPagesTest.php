<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestResult;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Assert0001GuestPagesTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomeRedirect()
    {
        $resposne = $this->get('/');
        $resposne->assertStatus(302);
    }

    public function testLoginPage()
    {
        $resposne = $this->get('/login');
        $resposne->assertStatus(200);
    }

    public function testRegisterPage()
    {
        $resposne = $this->get('/register');
        $resposne->assertStatus(200);
    }

    public function testDashboardPage()
    {
        $resposne = $this->get('/dashboard');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

    public function testNotificationsAjaxPage()
    {
        $resposne = $this->get('/notifications');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

    public function testNotificationsPage()
    {
        $resposne = $this->get('/notifications/list');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

    public function testPasswordChangePage()
    {
        $resposne = $this->get('/password/change');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

    public function testCreateTicketPage()
    {
        $resposne = $this->get('/ticket/create');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

    public function testTicketUpdatePage()
    {
        $resposne = $this->get('/ticket/1/edit');
        $resposne->assertStatus(302);
        $resposne->assertLocation('/login');
    }

}
