<?php

require_once '../classes/PotentialCustomer.php';

class CustomerManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCustomers() {
        $stmt = $this->pdo->query('SELECT potentialcustomer_id as pid, potentialcustomer_name as name, potentialcustomer_type as ptype, potentialcustomer_location as loc, potentialcustomer_facility as fac, potentialcustomer_tuition as tuition, sector_name as sec, user_id as user FROM tbl_potentialcustomer as pc LEFT JOIN tbl_sector as s ON pc.sector_id = s.sector_id');
        $customers = [];

        while ($row = $stmt->fetch()) {
            $customers[$row['pid']] = new PotentialCustomer(
                $row['pid'],
                $row['name'],
                $row['ptype'],
                $row['loc'],
                $row['fac'],
                $row['tuition'],
                $row['sec'],
                $row['user']
            );
        }

        //* Load Evaluation Data
        $stmt = $this->pdo->query('SELECT * FROM tbl_evaluation');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setEvaluation(new Evaluation(
                    $row['evaluation_id'],
                    $row['evaluation_rating'],
                    $row['evaluation_result'],
                    $row['evaluation_date']
                ));
            }
        }

        //* Load Contact Data
        $stmt = $this->pdo->query('SELECT * FROM tbl_contactperson');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setContactPerson(new ContactPerson(
                    $row['contactperson_id'],
                    $row['contactperson_name'],
                    $row['contactperson_position'],
                    $row['contactperson_email'],
                    $row['contactperson_number']
                ));
            }
        }

        //* Load Program Data
        $stmt = $this->pdo->query('SELECT existingprograms_id, potentialcustomer_id, program_type FROM tbl_existingprograms as ep LEFT JOIN tbl_program as p ON ep.program_id = p.program_id');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setPrograms(new Program (
                    $row['existingprograms_id'],
                    $row['program_type']
                ));
            }
        }
        
        //* Load Service Data
        $stmt = $this->pdo->query('SELECT existingservices_id, potentialcustomer_id, services_type FROM tbl_existingservices as es LEFT JOIN tbl_services as s ON es.services_id = s.services_id');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setServices(new Service (
                    $row['existingservices_id'],
                    $row['services_type']
                ));
            }
        }

        //* Load Partner Data
        $stmt = $this->pdo->query('SELECT existingpartners_id, existingpartners_years, potentialcustomer_id, partners_name FROM tbl_existingpartners as ep LEFT JOIN tbl_partners as p ON ep.partners_id = p.partners_id');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setPartners( new Partner (
                    $row['existingpartners_id'],
                    $row['existingpartners_years'],
                    $row['partners_name']
                ));
            }
        }

        //* Load Facility Data
        $stmt = $this->pdo->query('SELECT existingfacilities_id, potentialcustomer_id, facility_type FROM tbl_existingfacilities as ef LEFT JOIN tbl_facility as f ON ef.facility_id = f.facility_id');
        while ($row = $stmt->fetch()) {
            if (array_key_exists($row['potentialcustomer_id'], $customers)) {
                $customers[$row['potentialcustomer_id']]->setFacilities( new Facility (
                    $row['existingfacilities_id'],
                    $row['facility_type']
                ));
            }
        }

        //* Load Population Data
        $stmt1 = $this->pdo->query('SELECT population_id as pid, population_count as pcount, potentialcustomer_id as pcid FROM tbl_population as p');
        while ($row1 = $stmt1->fetch()) {
            if (array_key_exists($row1['pcid'], $customers)) {
                $stmt2 = $this->pdo->prepare('SELECT population_id as pid, gradelevel_id as gl, subpopulation_count as count FROM tbl_subpopulation WHERE population_id = ?');
                $stmt2->execute([$row1['pid']]);

                $subpopulation = [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0,
                    12 => 0,
                    13 => 0
                ];
                while ($row2 = $stmt2->fetch()) {
                    $subpopulation[$row2['gl']] = $row2['count'];
                }

                $customers[$row1['pcid']]->setPopulation( new Population (
                    $row1['pid'],
                    $row1['pcount'],
                    $subpopulation
                ));
            }
        }

        return $customers;
    }
    public function getPassedCustomers($customers) {
        $passedCustomers = [];

        foreach ($customers as $customer) {
            if ($customer->evaluation->result == 'Passed')
            {
                $passedCustomers[$customer->id] = $customer;
            }
        }
        return $passedCustomers;
    }
}

?>