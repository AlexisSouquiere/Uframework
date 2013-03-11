<?php

namespace Model;

class Flash
{
    public function __construct($key = null, $message = null)
    {
        if (isset($key) && isset($message)) {
            $_SESSION[$key] = $message;
        }
    }

    public function getMessage($key)
    {
        if (isset($_SESSION[$key])) {
            $myMessage = $_SESSION[$key];
            unset($_SESSION[$key]);

            return $myMessage;
    }

        return null;
    }

    public function setMessage($key, $message)
    {
    $_SESSION[$key] = $message;
    }

}
