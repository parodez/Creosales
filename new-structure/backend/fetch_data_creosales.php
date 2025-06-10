<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include ('connection.php');

if (!isset($_SESSION['user'])) {
    die("Error: User session not found. Session data: " . print_r($_SESSION, true));
}

if (!isset($_SESSION['user_type'])) {
    die("Error: User type not found in session. Session data: " . print_r($_SESSION, true));
}

if (!isset($_SESSION['user_id'])) {
    die("Error: User ID not found in session. Session data: " . print_r($_SESSION, true));
}

$user_id = intval($_SESSION['user_id']);
$user_type = intval($_SESSION['user_type']);

$potentialcustomer_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$client_sql = "SELECT c.potentialcustomer_name,
                      c.potentialcustomer_location, 
                      s.sector_name,
                      p.province_id
               FROM tbl_potentialcustomer c
               LEFT JOIN tbl_sector s ON c.sector_id = s.sector_id
               LEFT JOIN tbl_province p ON CONVERT(c.potentialcustomer_location USING utf8mb4) COLLATE utf8mb4_general_ci 
                                           LIKE CONCAT('%', CONVERT(p.province_name USING utf8mb4) COLLATE utf8mb4_general_ci, '%') 
               WHERE c.potentialcustomer_id = $potentialcustomer_id";

$client_result = $conn->query($client_sql);
$client = $client_result->fetch_assoc();
if (!$client) {
    $client = [
        'potentialcustomer_name' => 'Not Available',
        'potentialcustomer_location' => 'Not Available',
        'sector_name' => 'Not Available',
        'province_id' => null
    ];
} else {
    $client['potentialcustomer_name'] = isset($client['potentialcustomer_name']) && trim($client['potentialcustomer_name']) !== ''
        ? $client['potentialcustomer_name']
        : 'Not Available';
    $client['client_email'] = isset($client['client_email']) && trim($client['client_email']) !== ''
        ? $client['client_email']
        : 'Not Available';
    $client['client_contactperson'] = isset($client['client_contactperson']) && trim($client['client_contactperson']) !== ''
        ? $client['client_contactperson']
        : 'Not Available';
    $client['client_contactnumber'] = isset($client['client_contactnumber']) && trim($client['client_contactnumber']) !== ''
        ? $client['client_contactnumber']
        : 'Not Available';
    $client['potentialcustomer_location'] = isset($client['potentialcustomer_location']) && trim($client['potentialcustomer_location']) !== ''
        ? $client['potentialcustomer_location']
        : 'Not Available';
}

$province_id = $client['province_id'] ?? null;
if ($province_id) {
    $location_sql = "SELECT p.province_name, 
                            m.municipality_name, 
                            r.region_name, 
                            r.region_description
                     FROM tbl_province p
                     LEFT JOIN tbl_municipality m ON m.province_id = p.province_id
                     LEFT JOIN tbl_region r ON p.region_id = r.region_id
                     WHERE p.province_id = $province_id";

    $location_result = $conn->query($location_sql);
    $location = $location_result->fetch_assoc();
}

$evaluation_sql = "SELECT cr.criteria_criterion,
                          r.rating_id,
                          r.rating_score, 
                          e.evaluation_rating, 
                          e.evaluation_result, 
                          e.evaluation_date, 
                          r.rating_notes 
                   FROM tbl_evaluation e
                   INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
                   INNER JOIN tbl_criteria cr ON r.criteria_id = cr.criteria_id
                   WHERE e.potentialcustomer_id = ?
                   ORDER BY cr.criteria_id ASC";

$stmt = $conn->prepare($evaluation_sql);
$stmt->bind_param("i", $potentialcustomer_id);
$stmt->execute();
$evaluation_result = $stmt->get_result();

$evaluations = [];
while ($row = $evaluation_result->fetch_assoc()) {
    $row['rating_score'] = number_format((float)$row['rating_score'], 1, '.', '');
    $evaluations[] = $row;
}
$stmt->close();

$images_sql = "SELECT images_name, images_path, images_date 
               FROM tbl_images 
               WHERE evaluation_id IN (
                   SELECT evaluation_id 
                   FROM tbl_evaluation 
                   WHERE potentialcustomer_id = ?
               )";
$stmt = $conn->prepare($images_sql);
$stmt->bind_param("i", $potentialcustomer_id);
$stmt->execute();
$images_result = $stmt->get_result();

$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row;
}
$stmt->close();

$eval_summary_sql = "SELECT evaluation_id, evaluation_rating, evaluation_result, evaluation_date 
                    FROM tbl_evaluation 
                    WHERE potentialcustomer_id = ? 
                    ORDER BY evaluation_date DESC 
                    LIMIT 1";

$stmt = $conn->prepare($eval_summary_sql);
$stmt->bind_param("i", $potentialcustomer_id);
$stmt->execute();
$eval_summary = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Initialize sorting and filtering variables
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'potentialcustomer_name';
$orderDir = isset($_GET['orderDir']) ? $_GET['orderDir'] : 'ASC';
$sectorFilter = isset($_GET['sector']) ? $_GET['sector'] : '';
$reviewFilter = isset($_GET['review']) ? $_GET['review'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$adminFilter = isset($_GET['admin']) ? intval($_GET['admin']) : '';

// Pagination
$recordsPerPage = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Adjust query based on user type
if ($user_type === 0) {
    // Admin: Fetch all clients
    $sql = "SELECT c.potentialcustomer_id, c.potentialcustomer_name, c.potentialcustomer_location, s.sector_name, e.evaluation_result 
            FROM tbl_potentialcustomer c
            JOIN tbl_sector s ON c.sector_id = s.sector_id
            LEFT JOIN tbl_evaluation e ON c.potentialcustomer_id = e.potentialcustomer_id
            WHERE 1=1";
} else {
    // Regular user: Fetch only clients evaluated by the logged-in user
    $sql = "SELECT c.potentialcustomer_id, c.potentialcustomer_name, c.potentialcustomer_location, s.sector_name, e.evaluation_result 
            FROM tbl_potentialcustomer c
            JOIN tbl_sector s ON c.sector_id = s.sector_id
            JOIN tbl_evaluation e ON c.potentialcustomer_id = e.potentialcustomer_id
            WHERE e.potentialcustomer_id = c.potentialcustomer_id AND c.user_id = $user_id";
}

// Add filters if they exist
if (!empty($sectorFilter)) {
    $sql .= " AND s.sector_name = '$sectorFilter'";
}
if (!empty($reviewFilter)) {
    $sql .= " AND e.evaluation_result = '$reviewFilter'";
}
if (!empty($searchTerm)) {
    $sql .= " AND (c.potentialcustomer_name LIKE '%$searchTerm%' OR c.potentialcustomer_location LIKE '%$searchTerm%')";
}
if (!empty($adminFilter)) {
    $sql .= " AND c.user_id = $adminFilter";
}

// Add ordering
$sql .= " ORDER BY $orderBy $orderDir";

// Get total count for pagination
$countResult = $conn->query($sql);
$totalRecords = $countResult->num_rows;
$totalPages = ceil($totalRecords / $recordsPerPage);

// Add limit for pagination
$sql .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

// Get unique sectors for the dropdown
$sectorQuery = "SELECT DISTINCT sector_name FROM tbl_sector ORDER BY sector_name";
$sectorResult = $conn->query($sectorQuery);
$sectors = [];
while ($row = $sectorResult->fetch_assoc()) {
    $sectors[] = $row['sector_name'];
}

// Get unique evaluation results for the dropdown
$reviewQuery = "SELECT DISTINCT evaluation_result FROM tbl_evaluation WHERE evaluation_result IS NOT NULL ORDER BY evaluation_result";
$reviewResult = $conn->query($reviewQuery);
$reviews = [];
while ($row = $reviewResult->fetch_assoc()) {
    $reviews[] = $row['evaluation_result'];
}

// Fetch list of users who have evaluated clients (for admin filter)
$userQuery = "
    SELECT DISTINCT u.user_id, CONCAT(u.user_firstname, ' ', u.user_lastname) AS full_name
    FROM tbl_user u
    JOIN tbl_potentialcustomer c ON u.user_id = c.user_id
    JOIN tbl_evaluation e ON c.potentialcustomer_id = e.potentialcustomer_id
    JOIN tbl_credentials cred ON u.user_id = cred.user_id
    WHERE cred.user_type = 1
    ORDER BY full_name
";
$userResult = $conn->query($userQuery);
$users = [];
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_client') {
    $potentialcustomer_id = intval($_POST['potentialcustomer_id']);
    $potentialcustomer_name = $conn->real_escape_string($_POST['potentialcustomer_name']);
    $sector_name = $conn->real_escape_string($_POST['sector_name']);
    $potentialcustomer_location = $conn->real_escape_string($_POST['potentialcustomer_location']);

    // Fetch sector_id based on sector_name
    $sector_query = "SELECT sector_id FROM tbl_sector WHERE sector_name = '$sector_name' LIMIT 1";
    $sector_result = $conn->query($sector_query);
    $sector_row = $sector_result->fetch_assoc();
    $sector_id = $sector_row['sector_id'] ?? null;

    if ($sector_id) {
        // Update client details
        $update_query = "
            UPDATE tbl_potentialcustomer 
            SET potentialcustomer_name = '$potentialcustomer_name', 
                sector_id = $sector_id, 
                potentialcustomer_location = '$potentialcustomer_location' 
            WHERE potentialcustomer_id = $potentialcustomer_id
        ";

        if ($conn->query($update_query)) {
            echo json_encode(['status' => 'success', 'message' => 'Client details updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update client details.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid sector name.']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_contact_details') {
    $potentialcustomer_id = intval($_GET['potentialcustomer_id']);

    $contact_sql = "
        SELECT 
            contactperson_id,
            contactperson_position,
            contactperson_name,
            contactperson_email,
            contactperson_number
        FROM tbl_contactperson
        WHERE potentialcustomer_id = ?
    ";

    $stmt = $conn->prepare($contact_sql);
    $stmt->bind_param("i", $potentialcustomer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $contact_details = [];
    while ($row = $result->fetch_assoc()) {
        $contact_details[] = $row;
    }

    $stmt->close();

    echo json_encode(['status' => 'success', 'data' => $contact_details]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_contactperson') {
        $contactperson_id = isset($_POST['contactperson_id']) ? intval($_POST['contactperson_id']) : 0;
        
        if ($contactperson_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid contact person ID.']);
            exit();
        }

        $contactperson_name = $conn->real_escape_string($_POST['contactperson_name']);
        $contactperson_position = $conn->real_escape_string($_POST['contactperson_position']);
        $contactperson_email = $conn->real_escape_string($_POST['contactperson_email']);
        $contactperson_number = $conn->real_escape_string($_POST['contactperson_number']);

        $update_query = $conn->prepare("
            UPDATE tbl_contactperson 
            SET contactperson_name = ?,
                contactperson_position = ?,
                contactperson_email = ?,
                contactperson_number = ?
            WHERE contactperson_id = ?
        ");

        if (!$update_query) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
            exit();
        }

        $update_query->bind_param("ssssi", 
            $contactperson_name,
            $contactperson_position,
            $contactperson_email,
            $contactperson_number,
            $contactperson_id
        );

        if ($update_query->execute()) {
            if ($update_query->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Contact person updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No changes were made or contact person not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update contact person.']);
        }

        $update_query->close();
        exit();
    } elseif ($_POST['action'] === 'add_contactperson') {
        // Handle adding a new contact person
        $potentialcustomer_id = intval($_POST['potentialcustomer_id']);
        $contactperson_name = $conn->real_escape_string($_POST['contactperson_name']);
        $contactperson_position = $conn->real_escape_string($_POST['contactperson_position']);
        $contactperson_email = $conn->real_escape_string($_POST['contactperson_email']);
        $contactperson_number = $conn->real_escape_string($_POST['contactperson_number']);

        $insert_query = "
            INSERT INTO tbl_contactperson (contactperson_name, contactperson_position, contactperson_email, contactperson_number, potentialcustomer_id) 
            VALUES ('$contactperson_name', '$contactperson_position', '$contactperson_email', '$contactperson_number', $potentialcustomer_id)
        ";

        if ($conn->query($insert_query)) {
            echo json_encode(['status' => 'success', 'message' => 'Contact person added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add contact person.']);
        }
        exit();
    }
}
// PASSED START
// prpb

// Queries the number of clients per sector
// $query = "
// WITH programs AS (
//     SELECT 
//     ep.potentialcustomer_id,
//     GROUP_CONCAT(p.program_type SEPARATOR ', ') AS existing_programs
//     FROM tbl_existingprograms ep
//     JOIN tbl_program p ON ep.program_id = p.program_id
//     GROUP BY ep.potentialcustomer_id
// ),
// services AS (
//     SELECT 
//     es.potentialcustomer_id,
//     GROUP_CONCAT(s.services_type SEPARATOR ', ') AS existing_services
//     FROM tbl_existingservices es
//     JOIN tbl_services s ON es.services_id = s.services_id
//     GROUP BY es.potentialcustomer_id
// ),
// partners AS (
//     SELECT 
//     epar.potentialcustomer_id,
//     GROUP_CONCAT(par.partners_name SEPARATOR ', ') AS existing_partners
//     FROM tbl_existingpartners epar
//     JOIN tbl_partners par ON epar.partners_id = par.partners_id
//     GROUP BY epar.potentialcustomer_id
// ),
// facilities AS (
//     SELECT
//     ef.potentialcustomer_id,
//     GROUP_CONCAT(f.facility_type SEPARATOR ', ') AS existing_facilities
//     FROM tbl_existingfacilities ef
//     JOIN tbl_facility f ON ef.facility_id = f.facility_id
//     GROUP BY ef.potentialcustomer_id
// )
    
// SELECT
// pc.potentialcustomer_id,
// pc.potentialcustomer_name,
// pop.population_count,
// prog.existing_programs,
// serv.existing_services,
// part.existing_partners,
// fac.existing_facilities,
// cont.contactperson_name,
// cont.contactperson_position,
// cont.contactperson_email,
// cont.contactperson_number
// FROM tbl_potentialcustomer pc
// JOIN tbl_population pop ON pc.potentialcustomer_id = pop.potentialcustomer_id
// LEFT JOIN programs prog ON pc.potentialcustomer_id = prog.potentialcustomer_id
// LEFT JOIN services serv ON pc.potentialcustomer_id = serv.potentialcustomer_id
// LEFT JOIN partners part ON pc.potentialcustomer_id = part.potentialcustomer_id
// LEFT JOIN facilities fac ON pc.potentialcustomer_id = fac.potentialcustomer_id
// LEFT JOIN tbl_contactperson cont ON pc.potentialcustomer_id = cont.potentialcustomer_id;33
// ";
// $queryResult = mysqli_query($conn, $query);

// // Initialize clientData values
// $passedCustomerData = [];
// $totalPassed = 0;

// while ($row = mysqli_fetch_assoc($queryResult)) {
//     $passedCustomerData[$row['potentialcustomer_id']] = [
//         'name' => $row['potentialcustomer_name'],
//         'population' => $row['population_count'],
//         'programs' => $row['existing_programs'],
//         'services' => $row['existing_services'],
//         'partners' => $row['existing_partners'],
//         'facilities' => $row['existing_facilities'],
//         'contact' => [
//             $row['contactperson_name'],
//             $row['contactperson_position'],
//             $row['contactperson_email'],
//             $row['contactperson_number']
//             ]
//     ];

//     $totalPassed += 1;
// }

// PASSED END

// TODO: Separate classes into its own folder and files
// CUSTOMER ALL DATA
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

class ContactPerson {
    public int $id;
    public string $name, $position, $email, $number;

    public function __construct($id, $name, $position, $email, $number) {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->email = $email;
        $this->number = $number;
    } 
}

class Program {
    public int $id;
    public string $type;

    public function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }
}

class Service {
    public int $id;
    public string $type;

    public function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }
}

class Partner {
    public int $id, $years_with_partner;
    public string $name;

    public function __construct($id, $years_with_partner, $name) {
        $this->id = $id;
        $this->years_with_partner = $years_with_partner;
        $this->name = $name;
    }
}

class Facility {
    public int $id;
    public string $type;

    public function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }
} 

class Population {
    public int $id, $count;
    public $subpopulation = [];

    public function __construct($id, $count, $subpopulation) {
        $this->id = $id;
        $this->count = $count;
        $this->subpopulation = $subpopulation;
    }
}

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

$potentialCustomers = [];

?>