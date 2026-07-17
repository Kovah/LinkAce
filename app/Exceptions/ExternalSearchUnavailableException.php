<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class ExternalSearchUnavailableException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The configured external search engine is currently unavailable.', 0, $previous);
    }
}
