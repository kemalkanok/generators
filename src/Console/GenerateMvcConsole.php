<?php namespace Kanok\Generators\Console;

use Kanok\Generators\Command\GenerateControllerCommand;
use Kanok\Generators\Command\GenerateCrudCommand;
use Kanok\Generators\Command\GenerateMigrationCommand;
use Kanok\Generators\Command\GenerateModelCommand;
use Illuminate\Console\Command;
use Kanok\Generators\Command\GenerateRequestCommand;
use Kanok\Generators\Command\UpdateRoutesCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateMvcConsole extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'generate:package';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates A Whole package of Model , View , Controller , Request , etc..';




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

    }





}
