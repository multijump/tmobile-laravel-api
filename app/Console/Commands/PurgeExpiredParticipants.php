<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurgeExpiredParticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:participants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all participants from the database that subscribed to an event that has ended 90 days prior.';

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
        $carbon = Carbon::now();
        $participantIds = Participant::select('participants.*')
            ->join('events', 'participants.event_id', 'events.id')
            ->where('events.end_date', '<', $carbon->subDays(90))
            ->pluck('id');


        Participant::whereIn('id', $participantIds)->update([
            'email' => 'XXXXXXX',
            'phone' => 'XXXXXXX',
        ]);
    }
}
