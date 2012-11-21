<?php

namespace Application\Model;

class BlogEntry
{
    private $title;
    private $text;
    private $date;
    
    public function __get($name)
    {
        return $this->$name;
    }
    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
