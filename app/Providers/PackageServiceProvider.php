<?php
namespace App\Providers;


use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    protected $packageName = null;
    protected $loadViews   = false;
    protected $scanRoutes = [];
    protected $scanEvents = [];
    protected $listen     = [];
    protected $subscribe  = [];

    public function boot(Dispatcher $events)
    {
        parent::boot($events);

        /**
         * If the package name has not been set by the service provider we assume the package has a service
         * provider named ExamplePackageServiceProvider and set the package name to be the snake case version
         * So in this case we'd set packageName to example_package
         */
        if (is_null($this->packageName)) {
            $classBaseName = class_basename($this);
            if (ends_with($classBaseName, 'ServiceProvider')) {
                $this->packageName = snake_case(str_replace("ServiceProvider", "", $classBaseName));
            }
        }

        /**
         * If the loadViews is set to true, then look for a folder called Views in the package folder
         * and load the views from it using the snake_case package name
         */
        if (is_null($this->packageName) && $this->loadViews === true) {
            $path = __DIR__ . "/../../wtfcook/" . studly_case($this->packageName) . "/Views";
            if (is_dir($path)) {
                $this->loadViews($path, $this->packageName);
            }
        }

        $events->listen('wtfcook.scan.routes', function () {
            return $this->scanRoutes;
        });

        $events->listen('wtfcook.scan.events', function () {
            return $this->scanEvents;
        });

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }
    }

    public function register()
    {
        // nothing to see here
    }
}