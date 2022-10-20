<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Utils\TokenGenerator;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime();
        $generator = new TokenGenerator();

        $user1 = DB::table('users')->insert([
            'first_name' => 'James',
            'last_name' => 'Fawcett',
            'email' => 'james@thinktpi.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Adam',
            'last_name' => 'Dowell',
            'email' => 'adam@thinktpi.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'James',
            'last_name' => 'Roche',
            'email' => 'jroche@thisisfusion.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'James',
            'last_name' => 'Roche',
            'email' => 'jproche@outlook.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 0,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'James',
            'last_name' => 'Roche',
            'email' => 'jamesroche1982@icloud.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Store',
            'last_name' => 'User',
            'email' => 'katie.bandstra@t-mobile.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 0,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Store',
            'last_name' => 'User',
            'email' => 'testuser@t-mobile.com',
            'password' => bcrypt('secretP4ss*'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Brendon',
            'last_name' => 'Reyell',
            'email' => 'breyell@160over90.com',
            'password' => bcrypt('qwertyuiop'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 1,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'first_name' => 'Brendon-user',
            'last_name' => 'Reyell',
            'email' => 'breyell2@160over90.com',
            'password' => bcrypt('qwertyuiop'),
            'unique_key' => $generator->generate(32),
            'api_token' => $generator->generate(32),
            'is_admin' => 0,
            'accepted_date' => $now,
            'approved_date' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        for ($x = 0; $x < 100; $x++)
        {
            DB::table('users')->insert([
                'first_name' => 'Store',
                'last_name' => 'User '. $x,
                'email' => 'testuser' . $x .'@t-mobile.com',
                'password' => bcrypt('secret'),
                'unique_key' => $generator->generate(32),
                'api_token' => $generator->generate(32),
                'is_admin' => 0,
                'accepted_date' => $now,
                'approved_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
