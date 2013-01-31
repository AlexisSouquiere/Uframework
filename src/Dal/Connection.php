<?php

namespace Dal;

class Connection extends \PDO
{
    public function __construct($dsn, $user, $password)
    {
        parent::__construct($dsn, $user, $password);
    }


}
