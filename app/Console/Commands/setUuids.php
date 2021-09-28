<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class setUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:uuids {table}';

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
            DB::table($this->argument('table'))
                ->where('uuid', null)
                ->update(['uuid' => Str::uuid()]);
        } catch (Exception $e) {
            echo ("Error! " . $e->getMessage());
        }

        echo ('Done!');
    }
}
