<?php

use Illuminate\Database\Seeder;
use App\Models\Card\Card;

class CardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Card::getEnum('color') as $color) {
            for ($number = 0; $number <= 9; $number++) {
                if ($number) {
                    DB::table('card')->insert([
                        'type' => Card::NORMAL_TYPE,
                        'number' => $number,
                        'color' => $color,
                    ]);
                }

                DB::table('card')->insert([
                    'type' => Card::NORMAL_TYPE,
                    'number' => $number,
                    'color' => $color,
                ]);
            }

            foreach ([Card::SKIP_TYPE, Card::REVERSE_TYPE, Card::DRAW2_TYPE] as $cardType) {
                for ($typeCount = 0; $typeCount < 2; $typeCount++) {
                    DB::table('card')->insert([
                        'type' => $cardType,
                        'color' => $color,
                    ]);
                }
            }
        }

        // foreach ([Card::WILD_TYPE, Card::WILDDRAW4_TYPE] as $wildType) {
            // for ($wildCount = 0; $wildCount < 4; $wildCount++) {
                // DB::table('card')->insert([
                    // 'type' => $wildType,
                // ]);
            // }
        // }
    }
}
