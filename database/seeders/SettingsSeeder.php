<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'breaking'
            ],
            [
                'id' => 2,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'plunk'
            ],
            [
                'id' => 3,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'auth'
            ],
            [
                'id' => 4,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'lease'
            ],
            [
                'id' => 5,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'place'
            ],
            [
                'id' => 6,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'entry'
            ],
            [
                'id' => 7,
                'user_id' => User::where('id', 1)->first()->id,
                'type' => 'exams'
            ],
        ];
        Settings::insert($settings);
    }
}
