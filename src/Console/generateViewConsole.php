<?php

namespace Kanok\Generators\Console;

use Illuminate\Console\Command;

class generateViewConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:view {fields : the array of fields}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates a view';

    /**
     * Create a new command instance.
     *
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
        $fields = $this->argument('fields');
        $route = env('gen_route');

        $fields = explode(',',$fields);
        $this->info('index.blade');


        foreach($fields as $field)
        {
            echo $field;
        }
        //
        $this->info('completed');
    }
}
