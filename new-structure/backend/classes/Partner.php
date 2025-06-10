<?php

class Partner {
    public int $id, $years_with_partner;
    public string $name;

    public function __construct($id, $years_with_partner, $name) {
        $this->id = $id;
        $this->years_with_partner = $years_with_partner;
        $this->name = $name;
    }
}

?>