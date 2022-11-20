<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;

class ExportTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:export {dbname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports data from tables to a csv';

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
        $table_name = $this->argument("dbname");
        $fp = fopen("storage/app/public/$table_name-export.csv", "w");

          try {
            $rows = DB::table($table_name)
                ->whereNotNull("id")
                ->get()
                ->map(function($item, $key) {
                    return (array) $item;
                })
                ->all();

            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
        } catch (Exception $e) {
            echo ("Error! " . $e->getMessage());
        }

        fclose($fp);
        print("Done!\n");
        return 0;
    }
}
