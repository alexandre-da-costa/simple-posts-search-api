<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private array $payload;
    private TestResponse $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email
        ];
    }

    public function test_user_can_register_successfully_with_correct_payload()
    {
        // Manually force the presence of mixed case letters, as Str::password() does not guarantee this for now.
        $password = Str::lower(Str::password()) . Str::upper($this->faker->randomLetter);

        $this->response = $this->attemptToRegisterWithPassword($password);

        $this->response->assertCreated();
        $this->response->assertJsonFragment($this->payload);
        $user = User::where($this->response->json())->first();
        $this->assertTrue($user->checkPassword($password));
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $this->createUser(['email' => $this->payload['email']]);
        $password = Str::password();

        $this->response = $this->attemptToRegisterWithPassword($password);

        $this->runInvalidEmailAssertions();
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $this->payload['email'] = $this->faker->domainName;
        $password = Str::password();

        $this->response = $this->attemptToRegisterWithPassword($password);

        $this->runInvalidEmailAssertions();
    }

    public function test_user_cannot_register_with_invalid_passwords()
    {
        $invalidPasswords = [
            Str::password(length: 7),
            Str::password(numbers: false),
            Str::password(letters: false),
            Str::lower(Str::password()),
            'Password123!', // Leaked password
        ];

        foreach ($invalidPasswords as $invalidPassword) {
            $this->response = $this->attemptToRegisterWithPassword($invalidPassword);
            $this->runInvalidPasswordAssertions();
        }
    }

    private function runInvalidPasswordAssertions()
    {
        $this->runInvalidAssertionsFor('password');
    }

    private function runInvalidEmailAssertions()
    {
        $this->runInvalidAssertionsFor('email');
    }

    private function runInvalidAssertionsFor($field)
    {
        $this->response->assertUnprocessable();
        $this->response->assertJsonValidationErrorFor($field);
        $this->assertDatabaseMissingThisPayload();
    }

    private function assertDatabaseMissingThisPayload()
    {
        $this->assertDatabaseMissing('users', $this->payload);
    }

    private function getEndpointUrl(): string
    {
        return config('app.api_url') . '/auth/register';
    }

    private function attemptToRegisterWithPassword(string $password): \Illuminate\Testing\TestResponse
    {
        return $this->postJson($this->getEndpointUrl(),
            $this->payload + [
                'password' => $password
            ]);
    }
}
