<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Showing;
use App\Models\Schedule;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class defaultSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sets a default schedule for accounts that don't have one.";

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
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            try {
                Schedule::where('owner', $user->id)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $uuid = Str::uuid();

                Schedule::create([
                    "name" => "Default",
                    "isPublic" => false,
                    "uuid" => $uuid,
                    "slug" => $uuid,
                    "owner" => $user->id
                ]);
            }
        }

        $this->attachShowings($users);
    }

    private function attachShowings($users)
    {
        foreach ($users as $user) {
            $schedule = Schedule::where('owner', $user->id)->firstOrFail();

            Showing::where('owner', $user->id)
                ->where('schedule_id', null)
                ->update(['schedule_id' => $schedule->id]);
        }
    }
}
