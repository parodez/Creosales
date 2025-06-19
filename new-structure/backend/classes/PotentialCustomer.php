<?php

require_once __DIR__ . '/ContactPerson.php';
require_once __DIR__ . '/Evaluation.php';
require_once __DIR__ . '/Facility.php';
require_once __DIR__ . '/Partner.php';
require_once __DIR__ . '/Population.php';
require_once __DIR__ . '/Program.php';
require_once __DIR__ . '/Service.php';
require_once __DIR__ . '/Location.php';

class PotentialCustomer
{
    // public int $id, $user_id;
    // public string $name, $type, $facility, $tuition, $sector, $municipality, $province, $region;

    // public Location $location;
    // public ContactPerson $contactPerson;
    // public Evaluation $evaluation;
    // public Population $population;
    // public $programs = [], $services = [], $partners = [], $facilities = [];

    public function __construct(
        public int $id,
        public string $name,
        public int $type,
        public string $location,
        public string $facility,
        public string $tuition,
        public int $sector_id,
        public int $user_id,
        public array $contactPerson = [],
        public array $evaluation = [],
        public array $population = [],
        public array $programs = [],
        public array $services = [],
        public array $partners = [],
        public array $facilities = []
    ) {}

    // public function setEvaluation(Evaluation $evaluation) {
    //     $this->evaluation = $evaluation;
    // }

    // public function setContactPerson(ContactPerson $contactPerson) {
    //     $this->contactPerson = $contactPerson;
    // }
    // public function setPrograms(Program $program) {
    //     $this->programs[] = $program;
    // }
    // public function setServices (Service $service) {
    //     $this->services[] = $service;
    // }
    // public function setPartners (Partner $partner) {
    //     $this->partners[] = $partner;
    // }
    // public function setFacilities (Facility $facility) {
    //     $this->facilities[] = $facility;
    // }
    // public function setPopulation (Population $population) {
    //     $this->population = $population;
    // }
}