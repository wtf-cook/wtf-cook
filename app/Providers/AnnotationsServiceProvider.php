<?php
namespace App\Providers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Class AnnotationsServiceProvider
 * @package App\Providers
 */
class AnnotationsServiceProvider extends ServiceProvider
{
    /**
     * The classes to scan for event annotations
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * The classes to scan for route annotations
     *
     * @var array
     */
    protected $scanRoutes = [
        LoginController::class,
        ForgotPasswordController::class,
        RegisterController::class,
        ResetPasswordController::class,
    ];

    /**
     * The classes to scan for model annotations
     *
     * @var array
     */
    protected $scanModels = [];

    protected $scanWhenLocal = false;

    protected $scanControllers = false;

    protected $scanEverything = false;

    /**
     * AnnotationsServiceProvider constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @return array
     */
    public function eventScans()
    {
        $events = parent::eventScans();

        return $this->mergeScansFromEvent($events, 'events');
    }

    /**
     * @return array
     */
    public function routeScans()
    {
        $routes = parent::routeScans();

        return $this->mergeScansFromEvent($routes, 'routes');
    }

    /**
     * @param $scans
     * @param $type
     * @return array
     */
    protected function mergeScansFromEvent($scans, $type)
    {
        foreach (Event::fire("wtfcook.scan.$type") as $others) {
            if (is_array($others)) {
                $scans = array_merge($scans, $others);
            }
        }

        return $scans;
    }
}