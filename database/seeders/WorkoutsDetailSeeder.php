<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutsDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workouts_detail')->insert([
            [
                'workouts_details_id' => 1,
                'workouts_id' => 1,
                'name' => 'Arm Circles',
                'sub_category' => 'Pemanasan',
                'description' => 'Latihan untuk menggerakkan lengan dan bahu.',
                'duration' => 10,
                'video_link' => 'https://www.youtube.com/watch?v=Lha66p0ZXUc',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:06:18',
            ],
            [
                'workouts_details_id' => 2,
                'workouts_id' => 1,
                'name' => 'Neck Stretch',
                'sub_category' => 'Pemanasan',
                'description' => 'Peregangan leher untuk mengurangi ketegangan.',
                'duration' => 15,
                'video_link' => 'https://www.youtube.com/watch?v=GBmrncZApes',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:06:18',
            ],
            [
                'workouts_details_id' => 3,
                'workouts_id' => 1,
                'name' => 'Dynamic Hamstring Stretch',
                'sub_category' => 'Pemanasan',
                'description' => 'Peregangan dinamis untuk hamstring.',
                'duration' => 10,
                'video_link' => 'https://www.youtube.com/watch?v=zejTuBTEkfY',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:06:18',
            ],
            [
                'workouts_details_id' => 4,
                'workouts_id' => 1,
                'name' => 'Cobra Stretch',
                'sub_category' => 'Latihan Inti',
                'description' => 'Peregangan untuk memperpanjang punggung dan dada.',
                'duration' => 20,
                'video_link' => 'https://www.youtube.com/watch?v=JDcdhTuycOI',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:55:09',
            ],
            [
                'workouts_details_id' => 5,
                'workouts_id' => 1,
                'name' => 'Seated Forward Fold',
                'sub_category' => 'Latihan Inti',
                'description' => 'Peregangan tubuh bagian bawah untuk kelenturan hamstring.',
                'duration' => 30,
                'video_link' => 'https://www.youtube.com/watch?v=iTkL_pTj6DM',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:55:09',
            ],
            [
                'workouts_details_id' => 6,
                'workouts_id' => 1,
                'name' => 'Butterfly Stretch',
                'sub_category' => 'Pendinginan',
                'description' => 'Peregangan kaki dan pinggul.',
                'duration' => 30,
                'video_link' => 'https://www.youtube.com/watch?v=4J7kbCmPScQ',
                'created_at' => '2024-11-29 14:06:18',
                'updated_at' => '2024-11-29 14:55:09',
            ],
        ]);
    }
}
