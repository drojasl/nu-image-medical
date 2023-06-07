<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\ExternalApiService;

class VinControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Display Vin view to search
     *
     * @return void
     */
    public function test_display_vin_view_to_search()
    {
        $user = User::factory()->create(['tfa_verified' => true]);
        $this->actingAs($user);
        $response = $this->get('/vin');

        $response->assertStatus(200);
        $response->assertViewHas('search', false);
    }


    /**
     * Display Vin/Salvage search data
     *
     * @return void
     */
    public function test_display_vin_salvage_search_data()
    {
        $vinData = [
            'vin'=>"4F2YU09161KM33122",
            'year'=>"2001",
            'make'=>"MAZDA",
            'model'=>"TRIBUTE",
            'trim_level'=>"LX",
            'engine'=>"3.0L V6 DOHC 24V",
            'style'=>"SPORT UTILITY 4-DR",
            'made_in'=>"UNITED STATES",
            'steering_type'=>"R&P",
            'anti_brake_system'=>"Non-ABS | 4-Wheel ABS",
            'tank_size'=>"16.40 gallon",
            'overall_height'=>"69.90 in.",
            'overall_length'=>"173.00 in.",
            'overall_width'=>"71.90 in.",
            'standard_seating'=>"5",
            'optional_seating'=>null,
            'highway_mileage'=>"24 miles/gallon",
            'city_mileage'=>"18 miles/gallon",
        ];

        $salvageData = [
            'images'=>['a', 'b', 'c'],
            'vehicle_title'=>'NY - MV-907A SALVAGE CERTIFICATE',
            'mileage'=>'628 (ACTUAL)',
            'primary_damage'=>'REAR END',
            'secondary_damage'=>'FR',
            'loss_type'=>'COLLISION',
        ];

        $externalApiServiceMock = $this->createMock(ExternalApiService::class);
        $externalApiServiceMock->method('decodeVin')->willReturn($vinData);
        $externalApiServiceMock->method('salvageCheck')->willReturn($salvageData);

        $user = User::factory()->create(['tfa_verified' => true]);
        $this->actingAs($user);
        $response = $this->post('/vin', ['vin'=>'4T4BF1FKXER340134']);

        $response->assertStatus(200);
        $response->assertViewHas('search', true);
        $response->assertViewHas('vin', $vinData);
        $response->assertViewHas('salvage', $salvageData);
    }
}
