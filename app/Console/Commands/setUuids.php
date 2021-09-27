<?php

namespace App\Console\Commands;

use App\Models\MovieList;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        $movielists = MovieList::where('uuid', null)->get();
        foreach ($movielists as $list) {
            $list->uuid = Str::uuid();
            $list->save();
        }

        echo ('Done!');
    }
}
