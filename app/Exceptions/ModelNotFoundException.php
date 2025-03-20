<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public function __construct($model = null, $message = "Model not found")
    {
        $message = "The model: {$model} was not found. " . $message;

        parent::__construct($message);
    }

}
