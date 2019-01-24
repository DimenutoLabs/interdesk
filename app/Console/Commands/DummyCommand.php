<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DummyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dummy:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if ( env('APP_DEBUG') != true && env('APP_ENV') != 'development')
        {
            echo "This command can be used only on develipment enviroment and with debug activated";
            exit;
        } else {
            echo "This command can be used only on develipment enviroment and with debug activated";
        }
    }
}
