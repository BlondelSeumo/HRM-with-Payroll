<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create(
            [
                'name' => 'Free Plan',
                'price' => 0,
                'duration' => 'Lifetime',
                'max_users' => 1,
                'max_employees' => 5,
                'storage_limit' => 1024,
                'enable_chatgpt' => 'on',
                'image' => 'free_plan.png',
            ]
        );
    }
}
