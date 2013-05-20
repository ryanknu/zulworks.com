<?php

namespace Bar;

class Category
{
    private $name;
    private $description;
    private $image;
    
    public function toArray()
    {
        return array(
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
        );
    }
    
    public function exchangeArray($data)
    {
        $this->name        = $data['name'];
        $this->description = $data['description'];
        $this->image       = $data['image'];
    }
}
