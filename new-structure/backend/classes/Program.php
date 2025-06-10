<?php

class Program {
    public int $id;
    public string $type;

    public function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }
}

?>