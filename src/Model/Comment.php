<?php

namespace Model;

class Comment
{
    private $id;
    private $username;
    private $body;
    private $createdAt;

    public function __construct()
    {
        $num = func_num_args();

        switch($num) {
        case 0 :
            $this->id = null;
            $this->username = null;
            $this->body = null;
            $this->createdAt = new \DateTime(null);
            break;
        case 4 :
            $this->id = func_get_arg(0);
            $this->username = func_get_arg(1);
            $this->body = func_get_arg(2);
            $this->createdAt = func_get_arg(3);
            break;

        default :
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($name)
    {
        $this->username = $name;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
