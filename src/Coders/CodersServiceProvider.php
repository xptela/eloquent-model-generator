<?php

namespace Xptela\EloquentModelGenerator\Coders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Xptela\EloquentModelGenerator\Coders\Console\CodeModelsCommand;
use Xptela\EloquentModelGenerator\Coders\Model\Config;
use Xptela\EloquentModelGenerator\Coders\Model\Factory as ModelFactory;
use Xptela\EloquentModelGenerator\Support\Classify;

/**
 *
 */
class CodersServiceProvider extends ServiceProvider
{
    protected bool $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CodeModelsCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelFactory();
    }

    /**
     * Register Model Factory.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton(ModelFactory::class, function ($app) {
            return new ModelFactory(
                $app->make('db'),
                $app->make(Filesystem::class),
                new Classify(),
                new Config([
                    '*' => [
                        'path'                    => base_path('app/Models'),
                        'namespace'               => 'App\\Models',
                        'parent'                  => 'Illuminate\\Database\\Eloquent\\Model',
                        'use'                     => [],
                        'connection'              => false,
                        'timestamps'              => false,
                        'soft_deletes'            => false,
                        'date_format'             => 'Y-m-d H:i:s',
                        'per_page'                => 15,
                        'base_files'              => true,
                        'snake_attributes'        => true,
                        'indent_with_space'       => 0,
                        'qualified_tables'        => true,
                        'hidden'                  => ['location', 'password'],
                        'guarded'                 => [],
                        'casts'                   => [],
                        'except'                  => ['migrations',],
                        'only'                    => [],
                        'table_prefix'            => '',
                        'lower_table_name_first'  => false,
                        'model_names'             => [],
                        'relation_name_strategy'  => 'related',
                        'with_property_constants' => false,
                        'pluralize'               => true,
                        'override_pluralize_for'  => [],
                        'fillable_in_base_files'  => false,
                        'enable_return_types'     => false,
                    ],
                ])
            );
        });
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [ModelFactory::class];
    }
}
