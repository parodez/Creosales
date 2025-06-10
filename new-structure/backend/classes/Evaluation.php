<?php

class  Evaluation {
    public int $id;
    public string $rating, $result, $date;

    public function __construct($id, $rating, $result, $date) {
       $this->id = $id;
       $this->rating = $rating;
       $this->result = $result;
       $this->date = $date;
    }
}

?>