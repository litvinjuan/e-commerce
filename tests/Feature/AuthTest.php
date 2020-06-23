<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Store\Models\Admin;
use Store\Models\Customer;
use Store\Models\Supplier;
use Store\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUsersCanLogin()
    {
        factory(User::class)->create(['email' => 'user@test.com', 'password' => Hash::make('secret')]);
        $user = User::first();

        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect($user->loginRedirectRoute());
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function testAdminsCanLogin()
    {
        /** @var User $user */
        $user = factory(Admin::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect($user->loginRedirectRoute());
        $this->assertAuthenticatedAs($user);
        $this->assertInstanceOf(Admin::class, $user);
    }

    /** @test */
    public function testCustomersCanLogin()
    {
        /** @var User $user */
        $user = factory(Customer::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect($user->loginRedirectRoute());
        $this->assertAuthenticatedAs($user);
        $this->assertInstanceOf(Customer::class, $user);
    }

    /** @test */
    public function testSuppliersCanLogin()
    {
        /** @var User $user */
        $user = factory(Supplier::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect($user->loginRedirectRoute());
        $this->assertAuthenticatedAs($user);
        $this->assertInstanceOf(Supplier::class, $user);
    }

    /** @test */
    public function testUsersCanLogout()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com', 'password' => Hash::make('secret')]);
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function testUsersCanRegister()
    {
        $response = $this->post('/register', [
            'email' => 'user@test.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect((new Customer())->loginRedirectRoute());
        $this->assertCount(1, User::all());
        $this->assertEquals('user@test.com', User::first()->email);
        $this->assertAuthenticatedAs(User::first());
    }
}
