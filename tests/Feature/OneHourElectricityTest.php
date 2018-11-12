<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();
        factory(OneHourElectricity::class)->make([ 'panel_id' => $panel->id ])->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(1, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithoutElectricity()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial='.$panel->serial);

        $response->assertStatus(200);

        $this->assertCount(0, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutExistingPanel()
    {
        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=testserial');

        $response->assertStatus(404);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutPanelSerial()
    {
        $response = $this->json('GET', '/api/one_hour_electricities');
        $response->assertStatus(404);
    }

    public function testStoreElectricitySuccess()
    {
        $panel = factory(Panel::class)->create();
        $response = $this->json('POST', '/api/one_hour_electricities', [
            'panel_serial'    => $panel->serial,
            'kilowatts' => 90,
            'hour'  => '2018-11-04 18:58:39'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(
                [
                    'panel_id',
                    'kilowatts',
                    'hour',
                    'created_at',
                    'updated_at',
                ]
            );
    }

    public function testStoreElectricityFail()
    {
        $response = $this->json('POST', '/api/one_hour_electricities',[
            'hour'  => date('Y-m-d h:i:s')
        ]);
        $response->assertStatus(400);
    }


    public function testUpdateSuccessful()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();

        $oneHour = factory(OneHourElectricity::class)->create([
            'id' => 2,
            'panel_id'=> 5,
            'kilowatts' => 11,
            'hour' => '2018-10-01 18:40:59'
        ]);

        $payload = [
            'panel_id' => $panel->id,
            'kilowatts' => 4,
            'hour' => date('Y-m-d h:i:s')
        ];

        $response = $this->json('PUT', '/api/one_hour_electricities/' . $oneHour->id, $payload)
            ->assertStatus(200);
    }

    public function testUpdateFail()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();
        $oneHour = factory(OneHourElectricity::class)->create([
            'id' => 2,
            'panel_id'=> 5,
            'kilowatts' => 11,
            'hour' => '2018-10-01 18:40:59'
        ]);

        $payload = [
            'panel_id' => $panel->id,
            'kilowatts' => 4,
        ];

        $response = $this->json('PUT', '/api/one_hour_electricities/' . $oneHour->id, $payload)
            ->assertStatus(400);
    }

    public function testElectricityByPanelId()
    {
        $panel = factory(Panel::class)->make();
        $panel->save();

        factory(OneHourElectricity::class)->create([
            'id' => 2,
            'panel_id'=> 5,
            'kilowatts' => 11,
            'hour' => '2018-10-01 18:40:59'
        ]);
//        $payload = [
//            'panel_id' => $panel->id,
//            'kilowatts' => 4,
//            'hour' => date('Y-m-d h:i:s')
//        ];

        $response = $this->json('GET', '/api/one_hour_electricities/' . $panel->id)
            ->assertStatus(200);
    }

    public function testElectricityByPanelIdfail()
    {
//        $panel = factory(Panel::class)->make();
//        $panel->save();
//
//        factory(OneHourElectricity::class)->create([
//            'id' => 2,
//            'panel_id'=> 5,
//            'kilowatts' => 11,
//            'hour' => '2018-10-01 18:40:59'
//        ]);
//        $payload = [
//            'panel_id' => $panel->id,
//            'kilowatts' => 4,
//            'hour' => date('Y-m-d h:i:s')
//        ];

        $response = $this->json('GET', '/api/one_hour_electricities/' . 'serial')
            ->assertStatus(400);
    }

}
