<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'throw:testmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tests mail sending';

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
        Mail::raw('test',function($m){
            $m->to('office@infora.ro');
        });
    }
}
