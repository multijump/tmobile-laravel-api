<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $now = new DateTime();

        $now->sub(new DateInterval("P2M"));

        $users = \App\User::all()->toArray();

        $faker = Factory::create();

        for($x = 1; $x <= 100; $x++) {

            $storeNumber = rand(1, 20000);

            $userPicker = rand(0, (count($users) - 1));
            $user = $users[$userPicker];

            $clonedDate = clone($now);
            $dayInterval = rand(1,100);
            $startDate = $clonedDate->add(new DateInterval("P" . $dayInterval . "D"));
            $clonedStartDate = clone($startDate);
            $endDate = $clonedStartDate->add(new DateInterval("P1M"));

            $description = 'This is an an event description.';
            $region = 'West';
            $state = 'CA';

            DB::table('events')->insert([
                'title' => ucfirst($faker->word) . ' Event - Store #' . $storeNumber,
                'store_number' => $storeNumber,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'description' => $description,
                'region' => $region,
                'state' => $state,
                'default_language' => "English",
                'workfront_id' => null,
                'has_surveys_enabled' => false,
                'is_archived' => false,
                'public' => true,
                'created_by_id' => $user['id'],
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);
        }
    }
}
