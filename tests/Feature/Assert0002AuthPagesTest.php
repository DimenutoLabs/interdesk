<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Assert0002AuthPagesTest extends TestCase
{

    public function testPrepareAssert() {
        $user = new User();
        $user->name = "Jhon Doe";
        $user->email = "jhon.doe@example.com";
        $user->is_admin = 1;
        $user->password = Hash::make('123456');
        $user->save();

        $this->assertEquals(1, $user->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRootPage()
    {
//        $this->be( new User() );

        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
