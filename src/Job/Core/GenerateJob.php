<?php
namespace Kanok\Generators\Job\Core;
use App\Jobs\Job;
use Kanok\Generators\Contracts\CreateJobContract;


/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 20/06/15
 * Time: 11:07
 */

class GenerateJob extends Job implements CreateJobContract {

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->writeOutput($this->prepareOptionsForWriting());
    }

    /**
     *  generates a config set for writing
     *
     * @return object
     */
    function prepareOptionsForWriting()
    {
        // TODO: Implement prepareOptionsForWritng() method.
    }

    /**
     * Writes out the migration
     *
     * @param $options
     */
    public function writeOutput($options)
    {
        // TODO: Implement writeOutput() method.
    }

    /**
     * returns the proper filename
     *
     * @return string
     */
    public function prepareFile()
    {
        // TODO: Implement prepareFile() method.
    }

    /**
     * Binds the stub for migration
     *
     * @param $fields
     * @return string
     */
    public function prepareStub($fields)
    {
        // TODO: Implement prepareStub() method.
    }

    /**
     * Creates a string for laravel binding
     *
     * @return string
     */
    public function bindFields()
    {
        // TODO: Implement bindFields() method.
    }
}