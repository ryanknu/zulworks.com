<?php

namespace Bar;

class Item
{
    private $name;
    private $description;
    private $image;
    private $price;
    private $stocked;
    private $category;
    
    public function toArray()
    {
        return array(
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'price'       => $this->price,
            'stocked'     => $this->stocked,
            'category'    => $this->category,
        );
    }
    
    public function exchangeArray($data)
    {
        $this->name        = $data['name'];
        $this->description = $data['description'];
        $this->image       = $data['image'];
        $this->price       = $data['price'];
        $this->stocked     = $data['stocked'];
        $this->category    = $data['category'];
    }
}
