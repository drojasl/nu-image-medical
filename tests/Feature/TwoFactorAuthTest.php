<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\ExternalApiService;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * System request 2FA code after login
     *
     * @return void
     */
    public function test_request_2fa_after_login()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->get('/home')->assertRedirect('/tfa');
    }
    
    /**
     * System allows go home after 2FA code is verified
     *
     * @return void
     */
    public function test_allows_continue_after_2fa_code_is_verified()
    {
        $user = User::factory()->create(['tfa_verified' => true]);
        $this->actingAs($user);
        $this->get('/home')->assertStatus(200);
    }

    /**
     * System generates 2FA code
     *
     * @return void
     */
    public function test_generate_2fa_code()
    {
        $user = User::factory()->create();
        $this->assertEmpty($user->tfa_code);
        $user->generateTFA();
        $this->assertNotNull($user->tfa_code);
        $this->assertIsNumeric($user->tfa_code);
        $this->assertEquals(4, strlen($user->tfa_code));
    }

    /**
     * 2FA code is resent
     *
     * @return void
     */
    public function test_2fa_code_resent_succesfully()
    {
        $externalApiServiceMock = $this->createMock(ExternalApiService::class);
        $externalApiServiceMock->method('sendTFA')->willReturn(['message' => 'success']);

        $user = User::factory()->create();
        $this->actingAs($user);
        $this->post('/tfa/resend');
        $this->assertNotNull($user->tfa_code);
    }

    /**
     * Validate 2FA code succesfully
     *
     * @return void
     */
    public function test_validate_2fa_code_succesfully()
    {
        $user = User::factory()->create(['tfa_code'=>'1234']);
        $this->actingAs($user);
        $this->post('/tfa', ['tfa_code'=>'1234']);
        $this->assertTrue($user->tfa_verified);
    }

    /**
     * Validate wrong 2FA code succesfully
     *
     * @return void
     */
    public function test_wrong_validate_2fa_code_succesfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->post('/tfa', ['tfa_code'=>'1234']);
        $this->assertFalse($user->tfa_verified);
    }

    /**
     * Validate 2FA code generate error
     *
     * @return void
     */
    public function test_validate_2fa_code_generates_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/tfa', ['tfa_code'=>'1234']);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);
        $this->assertEquals('Invalid 2FA code', session('errors')->get('error')[0]);
    }

    /**
     * System cleans 2FA code
     *
     * @return void
     */
    public function test_clean_2fa_code()
    {
        $user = User::factory()->create(['tfa_code'=>'1234']);
        $this->actingAs($user);
        $this->post('/tfa', ['tfa_code'=>'1234']);
        $this->assertTrue($user->tfa_verified);
        $this->assertNotNull($user->tfa_code);

        $user->cleanTFA();
        
        $this->assertNull($user->tfa_code);
        $this->assertFalse($user->tfa_verified);
    }
}
