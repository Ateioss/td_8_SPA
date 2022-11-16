<?php

class Animal
{
    public string $name;
    public string $type;
    public int $id = -1;

    public function __construct(string $name, string $type, int $id = -1){
        $this->name=$name;
        $this->type=$type;
        $this->id=$id;
    }
}