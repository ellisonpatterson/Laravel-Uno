<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use App\Facades\Helper;
use App\Facades\Game;
use App\Engine\HelperEngine;
use App\Engine\GameEngine;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('padding', function ($paddedLength) {
            $length = $this->count();
            $offItems = $this->items;

            array_walk($offItems, function($value, $key) use(&$offItems, $length, $paddedLength) {
                $currentLength = count($offItems);
                if ($currentLength < $paddedLength) {
                    $offItems[$currentLength] = $offItems[$currentLength % $length];
                }
            });

            return new static($offItems);
        });

        Collection::macro('previous', function ($key, $value = null) {
            if (func_num_args() == 1) {
                $value = $key; $key = 'id';
            }

            return $this->roundRobin($this->searchAfterValues($key, $value) - 1, 'desc');
        });

        Collection::macro('next', function ($key, $value = null) {
            if (func_num_args() == 1) {
                $value = $key; $key = 'id';
            }

            return $this->roundRobin($this->searchAfterValues($key, $value) + 1, 'asc');
        });

        Collection::macro('searchAfterValues', function ($key, $value) {
            return $this->values()->search(function ($item, $k) use ($key, $value) {
                return data_get($item, $key) == $value;
            });
        });

        Collection::macro('roundRobin', function ($value, $direction) {
            if (!$found = $this->get($value)) {
                if ($direction == 'asc') {
                    return $this->first();
                }

                if ($direction == 'desc') {
                    return $this->last();
                }
            }

            return $found;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('helper', function (Application $app) {
            return new HelperEngine();
        });

        $this->app->bind('game', function (Application $app) {
            return new GameEngine();
        });
    }
}
