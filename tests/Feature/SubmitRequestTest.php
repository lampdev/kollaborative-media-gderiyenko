<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubmitRequestTest extends TestCase
{
    public function testValidationFails()
    {
        $response = $this->postJson('/api/submit', [
            'name' => 'John Doe',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'message' => ['The message field is required.'],
                ],
            ]);
    }
}
