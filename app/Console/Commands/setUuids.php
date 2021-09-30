<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\User;

class setUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:uuids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for null uuids and sets them.';

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
        try {
            $users = User::where('uuid', null)->get();

            foreach ($users as $user) {
                $user->uuid = Str::uuid();
                $user->save();
            }
        } catch (Exception $e) {
            echo ("Error! " . $e->getMessage());
        }

        echo ('Done!');
    }
}
