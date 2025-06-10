<?php

class ContactPerson {
    public int $id;
    public string $name, $position, $email, $number;

    public function __construct($id, $name, $position, $email, $number) {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->email = $email;
        $this->number = $number;
    } 
}

?>