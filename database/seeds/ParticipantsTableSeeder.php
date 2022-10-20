<?php

use Faker\Factory;
use App\Models\Event;
use Illuminate\Database\Seeder;

class ParticipantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $events = Event::all();
        $users = \App\User::all()->toArray();

        foreach($events as $event) {

            $participantCount = rand(1,22);

            for($x = 1; $x <= $participantCount; $x++) {

                $selectedById = null;
                $selectedDate = null;

                $userPicker = rand(0, (count($users) - 1));
                $user = $users[$userPicker];

                $phone = '';
                for($i = 0; $i < 10; $i++) {
                    $numberPicker = rand(0,9);
                    $phone .= $numberPicker;
                }

                $zipCode = '';
                for($i = 0; $i < 5; $i++) {
                    $numberPicker = rand(0,9);
                    $zipCode .= $numberPicker;
                }

                if($event->isCompleted()){
                    $winnerPicker = rand(0,5);

                    if ($winnerPicker == 5) {
                        $selectedById = $user['id'];
                        $selectedDate = $event->end_date;
                    }
                }

                $language = 'English';

                DB::table('participants')->insert([
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'email' => $faker->email,
                    'phone' => $phone,
                    'zip_code' => $zipCode,
                    'language' => $language,
                    'is_customer' => (bool) ($event->id % 3),
                    'is_contact' => (bool) ($event->id % 2),
                    'event_id' => $event->id,
                    'selected_by_id' => $selectedById,
                    'selected_date' => $selectedDate,
                    'created_by_id' => $user['id'],
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ]);
            }
        }
    }
}
