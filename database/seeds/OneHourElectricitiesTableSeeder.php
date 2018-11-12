<?php

use Illuminate\Database\Seeder;
use \App\Models\OneHourElectricity;


class OneHourElectricitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        OneHourElectricity::truncate();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 5; $i++) {
            OneHourElectricity::create([
                'panel_id' => ++$i,
                'kilowatts' => random_int(5,20),
                'hour' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
