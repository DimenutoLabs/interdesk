<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Assert0002AuthPagesTest extends TestCase
{

    protected function setUp()
    {
        //Truncate User Table
        parent::setUp();
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        //Create user admin with password change needed
        $user = new User();
        $user->name = "Jhon Doe";
        $user->email = "jhon.doe@example.com";
        $user->is_admin = true;
        $user->password = Hash::make('123456');
        $user->save();

        //Create user admin with password change needed
        $user = new User();
        $user->name = "Jhon Doe Change Pass";
        $user->email = "jhon.doe.change.pass@example.com";
        $user->is_admin = true;
        $user->force_update_password = true;
        $user->password = Hash::make('123456');
        $user->save();

        //Create normal user with password change needed
        $user = new User();
        $user->name = "Charles Albert";
        $user->email = "charles.albert@example.com";
        $user->is_admin = false;
        $user->password = Hash::make('123456');
        $user->save();

        //Create normal user with password change needed
        $user = new User();
        $user->name = "Charles Albert Change Pass";
        $user->email = "charles.albert.change.pass@example.com";
        $user->is_admin = false;
        $user->force_update_password = true;
        $user->password = Hash::make('123456');
        $user->save();
    }

    public function testLoginAssert() {
        //Do login as admin
        $this->doLogin(true);
        $response = $this->get('/');
        $response->assertLocation('/dashboard');

        //Do login as admin and with force update
        $this->doLogin(true, true);
        $response = $this->followingRedirects()->get('/');
        $response->assertSee('new_password');

        //Do login as normal user
        $this->doLogin();
        $response = $this->get('/');
        $response->assertLocation('/dashboard');

        //Do login as admin and with force update
        $this->doLogin(false, true);
        $response = $this->followingRedirects()->get('/');
        $response->assertSee('new_password');
    }

    public function testChangePasswordAssert() {
        //Access password change as admin
        $this->doLogin(true);
        $response = $this->get('/password/change');
        $response->assertSee('name="password"');

        //Access password change as admin with force password
        $this->doLogin(true, true);
        $response = $this->get('/password/change');
        $response->assertDontSee('name="password"');

        //Access password change as normal user
        $this->doLogin();
        $response = $this->get('/password/change');
        $response->assertSee('name="password"');

        //Access password change as normal user with force password
        $this->doLogin(false, true);
        $response = $this->get('/password/change');
        $response->assertDontSee('name="password"');
    }

    public function testTicketCreationAssert() {
        //Access ticket creation as admin
        $this->doLogin(true);
        $response = $this->get('/ticket/create');
        $response->assertViewIs('tickets.form_new');

        //Access ticket creation as admin and with force update
        $this->doLogin(true, true);
        $response = $this->followingRedirects()->get('/ticket/create');
        $response->assertSee('new_password');

        //Access ticket creation as normal user
        $this->doLogin();
        $response = $this->get('/ticket/create');
        $response->assertViewIs('tickets.form_new');

        //Access ticket creation as admin and with force update
        $this->doLogin(false, true);
        $response = $this->followingRedirects()->get('/ticket/create');
        $response->assertSee('new_password');
    }

    public function doLogin($admin = false, $force = false) {
        $user = User::where('is_admin', $admin);
        if ($force) {
            $user = $user->where('force_update_password', true);
        }
        $user = $user->first();
        $this->be( $user );
    }
}
