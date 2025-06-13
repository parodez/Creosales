<?php

class User {
    public int $id;
    public string $first_name, $last_name, $position, $department;

    public function __construct($id, $first_name, $last_name, $position, $department) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->position = $position;
        $this->department = $department;
    }

    public function getFullName() {
        return $this->first_name . " " . $this->last_name;
    }
}