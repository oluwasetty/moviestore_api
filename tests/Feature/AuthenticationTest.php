<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use WithFaker;

    public function testMustEnterEmailAndPassword()
    {
        $response = $this->json('POST', 'api/login');

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "status"
            ]);
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => $this->faker->email(),
            'password' => bcrypt('password'),
        ]);

        $response = $this->json('POST', 'api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "user" => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                    ],
                    "authorisation" => [
                        'token',
                        'type'
                    ]
                ],
                "status"
            ]);

        $this->assertAuthenticated();
    }
}
