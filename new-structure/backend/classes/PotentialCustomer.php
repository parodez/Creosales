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
    public function __construct(
        public int $id,
        public string $name,
        public int $type,
        public Location $location,
        public string $facility,
        public string $tuition,
        public string $sector,
        public int $user_id,
        public array $contactPerson = [],
        public array $evaluation = [],
        public array $population = [],
        public array $programs = [],
        public array $services = [],
        public array $partners = [],
        public array $facilities = []
    ) {}
}