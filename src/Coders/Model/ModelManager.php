<?php

/**
 * Created by Cristian.
 * Date: 02/10/16 08:24 PM.
 */

namespace Xptela\EloquentModelGenerator\Coders\Model;

use ArrayIterator;
use Illuminate\Support\Arr;
use IteratorAggregate;

class ModelManager implements IteratorAggregate
{
    /**
     * @var \Xptela\EloquentModelGenerator\Coders\Model\Factory
     */
    protected $factory;

    /**
     * @var \Xptela\EloquentModelGenerator\Coders\Model\Model[]
     */
    protected $models = [];

    /**
     * ModelManager constructor.
     *
     * @param \Xptela\EloquentModelGenerator\Coders\Model\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $schema
     * @param string $table
     * @param \Xptela\EloquentModelGenerator\Coders\Model\Mutator[] $mutators
     * @param bool $withRelations
     *
     * @return \Xptela\EloquentModelGenerator\Coders\Model\Model
     */
    public function make($schema, $table, $mutators = [], $withRelations = true)
    {
        $mapper = $this->factory->makeSchema($schema);

        $blueprint = $mapper->table($table);

        if (Arr::has($this->models, $blueprint->qualifiedTable())) {
            return $this->models[$schema][$table];
        }

        $model = new Model($blueprint, $this->factory, $mutators, $withRelations);

        if ($withRelations) {
            $this->models[$schema][$table] = $model;
        }

        return $model;
    }

    /**
     * Get Models iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->models);
    }
}
