<?php

namespace Model;

class Comment
{
    private $id;
    private $username;
    private $body;
    private $createdAt;

    public function __construct($username, $body, $createdAt)
    {
        $this->username = $username;
        $this->body = $body;
        $this->createdAt = $createdAt;
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
