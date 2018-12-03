<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests:start {--unity} {--v}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Tests';

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
        $folder = '';
        if (!$this->option('unity')) {
            $folder = 'tests/Feature';
        }

        exec('php vendor/phpunit/phpunit/phpunit ' . $folder, $output, $return_var);
        $output = join("\n", $output);

        $match = preg_match("/[\s\S]+Failed asserting[\s\S]+/", $output);
        $match = $match | preg_match("/[\s\S]+Error:[\s\S]+/", $output);

        if ( $match ) {
            $this->alert("Runned tests with fail!");
        } else {
            $this->question("All tests passed successfully!");
        }

        if ( $this->option('v') || $this->option('verbose') ) {
            echo $output;
        }

    }
}
