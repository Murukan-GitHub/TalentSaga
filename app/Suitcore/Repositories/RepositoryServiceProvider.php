<?php 

namespace Suitcore\Repositories;

use Illuminate\Support\ServiceProvider;
use Suitcore\Repositories\Eloquent\EventRepository;
use Suitcore\Repositories\Eloquent\AuthorRepository;
use Suitcore\Models\Event as EventModel;
use Author;

class RepositoryServiceProvider extends ServiceProvider {

    /**
    * Register
    */
    public function register()
    {
        $this->registerEventRepository();
        $this->registerAuthorRepository();
    }

    /**
    * Register Event Repository
    */
    public function registerEventRepository()
    {
        $this->app->bind(
            'Suitcore\Repositories\Contract\EventRepositoryInterface',
            function($app)
            {
                return new EventRepository( new EventModel );
            }
        );
    }

    /**
    * Register Author Repository
    */
    public function registerAuthorRepository()
    {
        $this->app->bind(
            'Suitcore\Repositories\Contract\AuthorRepositoryInterface',
            function($app)
            {
                return new AuthorRepository( new Author );
            }
        );
    }
}
