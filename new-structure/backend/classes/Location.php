<?php
class Location
{
    public string $fullAddress;
    public string $municipality;
    public string $province;
    public array $region = [
        'name' => '',
        'description' => ''
    ];

    public function __construct($fullAddress)
    {
        $this->fullAddress = $fullAddress;
    }

    public function setLocationInfo($municipality, $province, $region)
    {
        $this->municipality = $municipality;
        $this->province = $province;
        $this->region = $region;
    }
}