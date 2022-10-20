<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Participant;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Utils\TokenGenerator;

class ClearDataAndSeedAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all data from the database and seed the initial admins.';

    protected $adminAccounts = [
        [
            'first_name' => 'Brooke',
            'last_name' => 'Kemp',
            'email' => 'brooke.kemp5@t-mobile.com',
            'password' => 'secretP4ss*',
        ],
        [
            'first_name' => 'Katie',
            'last_name' => 'Bandstra',
            'email' => 'katie.bandstra@t-mobile.com',
            'password' => 'secretP4ss*',
        ],
        [
            'first_name' => 'Fusion',
            'last_name' => 'Dev',
            'email' => 'fusiondev@thisisfusion.com',
            'password' => 'Fusion911!',
        ],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('This will delete all data in the database, and reseed the administrator accounts. Are you sure you want to continue?')) {

            if ($this->confirm('Are you sure (data will be lost forever)?')) {
                // We asked twice, now remove all users/events/participants

                // Clear Data
                Participant::query()->delete();
                Event::query()->delete();
                User::query()->delete();

                // Seed Admins
                $now = new \DateTime();
                $generator = new TokenGenerator();

                foreach ($this->adminAccounts as $adminAccountData)
                {
                    DB::table('users')->insert([
                        'first_name' => $adminAccountData['first_name'],
                        'last_name' => $adminAccountData['last_name'],
                        'email' => $adminAccountData['email'],
                        'password' => bcrypt($adminAccountData['password']),
                        'unique_key' => $generator->generate(32),
                        'api_token' => $generator->generate(32),
                        'is_admin' => 1,
                        'accepted_date' => $now,
                        'approved_date' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
