<?php namespace Kanok\Generators\Console;


use Illuminate\Console\Command;
use Kanok\Generators\Libs\Config;

class GenerateMvcConsole extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'gen:pack';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates A Whole package of Model , View , Controller , Command , Request , etc..';




    /**
     * Create a new command instance.
     */

	public function __construct( )
	{
		parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

        dd ((new Config())->get('package'));
        $this->info('completed: Package Generated Successfully');
    }





}
