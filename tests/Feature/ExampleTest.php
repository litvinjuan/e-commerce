<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Store\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicTest()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com', 'password' => Hash::make('secret')]);
        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }
}
