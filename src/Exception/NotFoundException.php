<?php

namespace Exception;

class NotFoundException extends HttpException
{
    public function __construct($message = null)
    {
        parent::__construct(404, $message);
    }
}
