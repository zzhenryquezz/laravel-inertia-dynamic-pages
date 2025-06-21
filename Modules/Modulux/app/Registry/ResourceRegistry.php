<?php

namespace Modules\Modulux\Registry;

use Modules\Modulux\Resource\Resource;

class ResourceRegistry
{
    /**
     * @var array<Resource>
     */
    protected static array $resources = [];

    /**
     * Register a resource class.
     * 
     */
    public static function register(Resource $class): void
    {
        if (!in_array($class, self::$resources, true)) {
            self::$resources[] = $class;
        }
    }

    /**
     * Get all registered resource classes.
     *
     * @return array<Resource>
     */
    public static function all(): array
    {
        return self::$resources;
    }
}
