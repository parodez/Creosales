<?php

require_once __DIR__ . '/../classes/PotentialCustomer.php';
require_once __DIR__ . '/../classes/Location.php';

class CustomerManager
{
    public function __construct(
        private $pdo
    ) {}

    public function getAllCustomers()
    {
        $stmt = $this->pdo->query('SELECT potentialcustomer_id as pid, potentialcustomer_name as name, potentialcustomer_type as ptype, potentialcustomer_location as loc, potentialcustomer_facility as fac, potentialcustomer_tuition as tuition, sector_name as sec, user_id as user FROM tbl_potentialcustomer as pc LEFT JOIN tbl_sector as s ON pc.sector_id = s.sector_id');
        $potentialCustomers = [];

        while ($row1 = $stmt->fetch()) {
            //* BASIC CUSTOMER DATA
            $potentialCustomer_id = $row1['pid'];
            $potentialCustomer_name = $row1['name'];
            $potentialCustomer_type = $row1['ptype'];
            $potentialCustomer_location = $row1['loc'];
            $potentialCustomer_facility = $row1['fac'];
            $potentialCustomer_tuition = $row1['tuition'];
            $potentialCustomer_sectorId = $row1['sec'];
            $potentialCustomer_userId = $row1['user'];

            //* LOCATION DATA
            $fullAddress = $potentialCustomer_location;
            $splitLocation_array = explode(', ', $potentialCustomer_location);
            $province = $splitLocation_array[count($splitLocation_array) - 1];
            $municipality = $splitLocation_array[count($splitLocation_array) - 2];
            $locationData = new Location($fullAddress);
            $tempStmt = $this->pdo->prepare('SELECT r.region_name, r.region_description FROM tbl_region AS r LEFT JOIN tbl_province AS p ON r.region_id=p.region_id WHERE province_name = ?');
            $tempStmt->execute([$province]);
            $tempRow = $tempStmt->fetch();
            $locationData->setLocationInfo($municipality, $province, ['name' => $tempRow['region_name'], 'description' => $tempRow['region_description']]);

            //* CONTACT PERSON DATA
            $tempStmt = $this->pdo->prepare('SELECT * FROM tbl_contactperson WHERE potentialcustomer_id = ?');
            $tempStmt->execute([$potentialCustomer_id]);
            $tempRow = $tempStmt->fetch();
            $potentialCustomer_contactPerson = [
                'id' => $tempRow['contactperson_id'],
                'name' => $tempRow['contactperson_name'],
                'position' => $tempRow['contactperson_position'],
                'email' => $tempRow['contactperson_email'],
                'number' => $tempRow['contactperson_number'],
            ];

            //* EVALUATION DATA
            $tempStmt = $this->pdo->prepare('SELECT * FROM tbl_evaluation WHERE potentialcustomer_id = ?');
            $tempStmt->execute([$potentialCustomer_id]);
            $tempRow = $tempStmt->fetch();
            $potentialCustomer_evaluation = [
                'id' => $tempRow['evaluation_id'],
                'rating' => $tempRow['evaluation_rating'],
                'result' => $tempRow['evaluation_result'],
                'date' => $tempRow['evaluation_date']
            ];

            //* POPULATION DATA
            $tempStmt = $this->pdo->prepare('SELECT * FROM tbl_population WHERE potentialcustomer_id = ?');
            $tempStmt->execute([$potentialCustomer_id]);
            $tempRow = $tempStmt->fetch();
            $potentialCustomer_population = [
                'id' => $tempRow['population_id'],
                'count' => $tempRow['population_count']
            ];

            //* SUBPOPULATION DATA
            $tempStmt = $this->pdo->prepare('SELECT * FROM tbl_subpopulation WHERE population_id = ?');
            $tempStmt->execute([$potentialCustomer_population['id']]);
            $subPopulation = [
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
            while ($tempRow = $tempStmt->fetch()) {
                $subPopulation[$tempRow['gradelevel_id']] = $tempRow['subpopulation_count'];
            }
            $potentialCustomer_population['subPopulation'] = $subPopulation;

            //* PROGRAM DATA
            $tempStmt = $this->pdo->prepare('SELECT ep.existingprograms_id, p.program_id, p.program_type FROM tbl_existingprograms AS ep LEFT JOIN tbl_program AS p ON ep.program_id=p.program_id WHERE potentialcustomer_id = ?');
            $tempStmt->execute([$potentialCustomer_id]);
            $potentialCustomer_programs = [];
            while ($tempRow = $tempStmt->fetch()) {
                $potentialCustomer_programs[] = [
                    'id' => $tempRow['existingprograms_id'],
                    'type' => $tempRow['program_type']
                ];
            }

            //* SERVICE DATA
            $tempStmt = $this->pdo->prepare('SELECT es.existingservices_id, s.services_type FROM tbl_existingservices AS es LEFT JOIN tbl_services AS s ON es.services_id=s.services_id WHERE es.potentialcustomer_id=?');
            $tempStmt->execute([$potentialCustomer_id]);
            $potentialCustomer_services = [];
            while ($tempRow = $tempStmt->fetch()) {
                $potentialCustomer_services[] = [
                    'id' => $tempRow['existingservices_id'],
                    'type' => $tempRow['services_type']
                ];
            }

            //* PARTNER DATA
            $tempStmt = $this->pdo->prepare('SELECT ep.existingpartners_id, ep.existingpartners_years, p.partners_name FROM tbl_existingpartners AS ep LEFT JOIN tbl_partners AS p ON ep.partners_id=p.partners_id WHERE ep.potentialcustomer_id=?');
            $tempStmt->execute([$potentialCustomer_id]);
            $potentialCustomer_partners = [];
            while ($tempRow = $tempStmt->fetch()) {
                $potentialCustomer_partners[] = [
                    'id' => $tempRow['existingpartners_id'],
                    'years' => $tempRow['existingpartners_years'],
                    'name' => $tempRow['partners_name']
                ];
            }

            //* FACILITIES DATA
            $tempStmt = $this->pdo->prepare('SELECT ef.existingfacilities_id, f.facility_type FROM tbl_existingfacilities AS ef LEFT JOIN tbl_facility AS f ON ef.facility_id=f.facility_id WHERE ef.potentialcustomer_id=?');
            $tempStmt->execute([$potentialCustomer_id]);
            $potentialCustomer_facilities = [];
            while ($tempRow = $tempStmt->fetch()) {
                $potentialCustomer_facilities[] = [
                    'id' => $tempRow['existingfacilities_id'],
                    'type' => $tempRow['facility_type']
                ];
            }

            //* INSERT ALL DATA INTO CUSTOMER OBJECT
            $potentialCustomers[$potentialCustomer_id] = new PotentialCustomer(
                $potentialCustomer_id,
                $potentialCustomer_name,
                $potentialCustomer_type,
                $locationData,
                $potentialCustomer_facility,
                $potentialCustomer_tuition,
                $potentialCustomer_sectorId,
                $potentialCustomer_userId,
                $potentialCustomer_contactPerson,
                $potentialCustomer_evaluation,
                $potentialCustomer_population,
                $potentialCustomer_programs,
                $potentialCustomer_services,
                $potentialCustomer_partners,
                $potentialCustomer_facilities
            );
        }

        return $potentialCustomers;
    }
    public function getPassedCustomers($potentialCustomers)
    {
        $passedCustomers = [];

        foreach ($potentialCustomers as $potentialCustomer) {
            if ($potentialCustomer->evaluation['result'] == 'Passed') {
                $passedCustomers[$potentialCustomer->id] = $potentialCustomer;
            }
        }
        return $passedCustomers;
    }
}