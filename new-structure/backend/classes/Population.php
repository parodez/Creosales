<?php 

class Population {
    public int $id, $count;
    public $subpopulation = [];

    public function __construct($id, $count, $subpopulation) {
        $this->id = $id;
        $this->count = $count;
        $this->subpopulation = $subpopulation;
    }
}

?>