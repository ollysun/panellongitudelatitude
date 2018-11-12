<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Panel;

class PanelTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPanelIndex()
    {
        factory(Panel::class)->create([
            'serial' => 'abcdef012345678',
            'longitude' => 2,
            'latitude' => 90
        ]);

        factory(Panel::class)->create([
            'serial' => 'abcdef012345656',
            'longitude' => 9,
            'latitude' => 78
        ]);

        $response = $this->json('GET', '/api/panels')
            ->assertStatus(200)
            ->assertJson([
                [ 'serial' => 'abcdef012345678', 'longitude' => 2, 'latitude' => 90],
                [ 'serial' => 'abcdef012345656', 'longitude' => 9, 'latitude' => 78]
            ])
            ->assertJsonStructure([
                '*' => ['id', 'serial', 'longitude','latitude', 'created_at', 'updated_at'],
            ]);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $response->assertStatus(201);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreFailure()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0123456789',
            'longitude' => 0
        ]);

        $response->assertStatus(400);
    }

    public function testPanelUpdateSuccessful()
    {
        $panel = factory(Panel::class)->create([
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $payload = [
            'serial'    => 'abcdef012345448',
            'longitude' => 70,
            'latitude'  => 70
        ];

        $response = $this->json('PUT', '/api/panels/' . $panel->id, $payload)
            ->assertStatus(200);
    }

    public function testPanelUpdateFail()
    {
        $panel = factory(Panel::class)->create([
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $payload = [
            'serial'    => 'abcdef012345678',
            'longitude' => 9,
        ];

        $response = $this->json('PUT', '/api/panels/' . $panel->id, $payload)
            ->assertStatus(400);
    }

//    public function testShouldHaveSerial()
//    {
//        $panel = factory(Panel::class)->create(['serial' => '1wer897666']);
//        $this->assertEquals('1wer897666', $panel->serial);
//    }


//    public function testOneHourElectricityByPanelId()
//    {
//        $panel = factory(Panel::class)->create([
//            'serial'    => 'abcdef012345678',
//            'longitude' => 0,
//            'latitude'  => 0
//        ]);
//        $response = $this->json('GET', '/api/panels/' . $panel->id . '/one_hour_electricities');
//        $response->assertStatus(200);
//    }

}
