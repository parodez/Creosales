<?php 

require_once 'ContactPerson.php';
require_once 'Evaluation.php';
require_once 'Facility.php';
require_once 'Partner.php';
require_once 'Population.php';
require_once 'Program.php';
require_once 'Service.php';

class PotentialCustomer {
    // Customer Info
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