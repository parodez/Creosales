<?php
class Location {
    public string $name;
    public $municipality = [
        'id'=>'N/A',
        'province'=>'N/A',
        'name'=>'N/A',
    ];
    public $province = [
        'id'=>'N/A',
        'region'=>'N/A',
        'name'=>'N/A'
    ];
    public $region = [
        'id'=>'N/A',
        'name'=>'N/A',
        'desc'=>'N/A'
    ];

    public function __construct($name) {
        $this->name = $name;
    }

    public function setLocationInfo($municipality, $province, $region) {
        $this->municipality = $municipality;
        $this->province = $province;
        $this->region = $region;
    }
}