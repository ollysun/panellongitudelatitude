<?php

use Illuminate\Database\Seeder;
use \App\Models\Panel;

class PanelsTableSeeder extends Seeder
{
    public static function AlphaNumeric($length)
    {
        $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $clen   = strlen( $chars )-1;
        $id  = '';

        for ($i = 0; $i < $length; $i++) {
            $id .= $chars[mt_rand(0,$clen)];
        }
        return ($id);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Panel::truncate();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 5; $i++) {
            Panel::create([
                'serial' => self::AlphaNumeric(16),
                'longitude' => random_int(-180,180),
                'latitude' => random_int(-90,90)
            ]);
        }
    }
}
