<?php

namespace Kanok\Generators\Job;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Kanok\Generators\Libs\Config;

class GeneratePackage extends Job implements SelfHandling
{
    /**
     * @var
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $configPath = 'packages.' . $this->data->type;
        $packageData = $this->getPackageDetails($configPath);
        if(isset($packageData->migration) && $packageData->migration)
        {
            $this->dispatch(new CreateMigration($this->data));
        }
        if(isset($packageData->model) && $packageData->model)
        {
            $this->dispatch(new CreateModel($this->data));
        }
    }

    /**
     * @param $configPath
     * @return mixed
     */
    private function getPackageDetails($configPath)
    {
        return (new Config())->get($configPath);
    }


}
