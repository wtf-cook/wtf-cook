<?php
namespace Wtf\Recipes\Providers;

use App\Providers\PackageServiceProvider;
use Illuminate\Events\Dispatcher;
use Wtf\Recipes\Http\Controllers\RecipesController;

class RecipesServiceProvider extends PackageServiceProvider
{
    /**
     * Load views for this package
     *
     * @var bool
     */
    protected $loadViews = true;

    /**
     * The routes to scan
     *
     * @var array
     */
    protected $scanRoutes = [
        RecipesController::class,
    ];
}