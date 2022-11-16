<?php

class User
{
    public string $firstname;
    public string $lastname;
    public string $password;
    public string $email;
    public int $id;

    public function __construct(string $lastname, string $password, string $firstname, string $email, int $id=-1){
        $this->firstname=$firstname;
        $this->email=$email;
        $this->lastname=$lastname;
        $this->password=$password;
        $this->id = $id;
    }

}