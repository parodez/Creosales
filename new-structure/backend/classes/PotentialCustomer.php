<?php 

require_once __DIR__ . '/ContactPerson.php';
require_once __DIR__ . '/Evaluation.php';
require_once __DIR__ . '/Facility.php';
require_once __DIR__ . '/Partner.php';
require_once __DIR__ . '/Population.php';
require_once __DIR__ . '/Program.php';
require_once __DIR__ . '/Service.php';

class PotentialCustomer {
    public int $id, $user_id;
    public string $name, $type, $location, $facility, $tuition, $sector;

    public ContactPerson $contactPerson;
    public Evaluation $evaluation;
    public Population $population;
    public $programs = [], $services = [], $partners = [], $facilities = [];

    public function __construct($id, $name, $type, $location, $facility, $tuition, $sector, $user_id) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->location = $location;
        $this->facility = $facility;
        $this->tuition = $tuition;
        $this->sector = $sector;
        $this->user_id = $user_id;
    }

    public function setEvaluation(Evaluation $evaluation) {
        $this->evaluation = $evaluation;
    }

    public function setContactPerson(ContactPerson $contactPerson) {
        $this->contactPerson = $contactPerson;
    }
    public function setPrograms(Program $program) {
        $this->programs[] = $program;
    }
    public function setServices (Service $service) {
        $this->services[] = $service;
    }
    public function setPartners (Partner $partner) {
        $this->partners[] = $partner;
    }
    public function setFacilities (Facility $facility) {
        $this->facilities[] = $facility;
    }
    public function setPopulation (Population $population) {
        $this->population = $population;
    }
}

?>