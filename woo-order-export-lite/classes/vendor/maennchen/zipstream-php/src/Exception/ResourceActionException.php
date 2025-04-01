<?php

declare(strict_types=1);

namespace WOE\ZipStream\Exception;

use WOE\ZipStream\Exception;

/**
 * This Exception gets invoked if a resource like `fread` returns false
 */
class ResourceActionException extends Exception
{
    /**
     * @var ?resource
     */
    public $resource;

    /**
     * @param resource $resource
     */
    public function __construct(
        public readonly string $function,
        $resource = null,
    ) {
        $this->resource = $resource;
        parent::__construct('Function ' . $function . 'failed on resource.');
    }
}
