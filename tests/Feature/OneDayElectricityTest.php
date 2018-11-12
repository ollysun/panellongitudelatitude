<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneDayElectricityTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOneDayElectricitySuccessful()
    {
        $panel = factory(Panel::class)->create([
            'serial' => 'abcdef012345678',
            'longitude' => 2,
            'latitude' => 90
        ]);

        factory(OneHourElectricity::class)->create([
            'panel_id' => $panel->id,
            'kilowatts' => 9,
            'hour' => date('Y-m-d h:i:s')
        ]);

        factory(OneHourElectricity::class)->create([
            'panel_id' => $panel->id,
            'kilowatts' => 87,
            'hour' => date('Y-m-d h:i:s')
        ]);
        $response = $this->json('GET', '/api/one_day_electricities?panel_serial='.$panel->serial);
        $response->assertStatus(200);
    }
}
