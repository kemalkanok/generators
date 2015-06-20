<?php
/**
 * Created by PhpStorm.
 * User: kemalkanok
 * Date: 19/06/15
 * Time: 15:28
 */
namespace Kanok\Generators\Contracts;

interface CreateJobContract
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle();

    /**
     *  generates a config set for writing
     *
     * @return object
     */
    function prepareOptionsForWriting();

    /**
     * Writes out the migration
     *
     * @param $options
     */
    public function writeOutput($options);

    /**
     * returns the proper filename
     *
     * @return string
     */
    public function prepareFile();

    /**
     * Binds the stub for migration
     *
     * @param $fields
     * @return string
     */
    public function prepareStub($fields);

    /**
     * Creates a string for laravel binding
     *
     * @return string
     */
    public function bindFields();
}