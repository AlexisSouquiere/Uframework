<?php

namespace Model;

class Location
{
    private $id;
    private $name;
    private $createdAt;
    private $comments = array();

    public function __construct()
    {
        $num = func_num_args();

        switch($num) {
        case 0 :
            $this->id = null;
            $this->name = null;
            $this->createdAt = new \DateTime(null);
            break;
        case 3 : 
            $this->id = func_get_arg(0);
            $this->name = func_get_arg(1);
            $this->createdAt = func_get_arg(2);
            break;

        default : 
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }
}
