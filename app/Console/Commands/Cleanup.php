<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercator:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup temporary files';

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
        // clean reports
        $folder = storage_path('app/reports');
        $files = glob($folder . '/*.docx');
        $cnt = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $cnt++;
            }
        }
        $this->info('Removed ' . $cnt . ' files from ' . $folder);

        // clean laravel.log
        // unlink(storage_path('logs').'/laravel.log');
        file_put_contents(storage_path('logs').'/laravel.log', '');
        $this->info('Laravel logs cleared.');
    }
}
