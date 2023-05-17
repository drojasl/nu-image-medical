<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Client;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test client is created succesfully
     *
     * @return void
     */
    public function test_client_create_succesfully()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'comments' => $this->faker->sentence(7),
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(201);

        $client = Client::first();

        $this->assertCount(1, Client::all());
        $this->assertEquals($client->name, $clientData['name']);
        $this->assertEquals($client->email, $clientData['email']);
        $this->assertEquals($client->phone, $clientData['phone']);
        $this->assertEquals($client->comments, $clientData['comments']);
    }

    /**
     * Test client fails by input validation
     *
     * @return void
     */
    public function test_client_create_validate_name()
    {
        $clientData = [
            'name' => '',
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'comments' => $this->faker->sentence(7),
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(422);
    }

    /**
     * Test client fails by input validation
     *
     * @return void
     */
    public function test_client_create_validate_email()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => '',
            'phone' => $this->faker->phoneNumber,
            'comments' => $this->faker->sentence(7),
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(422);
    }

    /**
     * Test client fails by input validation
     *
     * @return void
     */
    public function test_client_create_validate_phone()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '',
            'comments' => $this->faker->sentence(7),
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(422);
    }

    /**
     * Test client created succesfully with empty comments
     *
     * @return void
     */
    public function test_client_create_works_with_empty_comments()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'comments' => '',
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(201);
    }

    /**
     * Test client fails by input validation
     *
     * @return void
     */
    public function test_client_create_validate_comments_too_long()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'comments' => $this->faker->sentence(70),
        ];
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(422);
    }

    /**
     * Test client fails by DB integrity
     *
     * @return void
     */
    public function test_client_create_validate_db_integrity()
    {
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'comments' => $this->faker->sentence(7),
        ];
        $response = $this->post('/api/client/create', $clientData);
        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(503);
        $response->assertJson([
            'message' => 'Error saving in database'
        ]);
    }
}
