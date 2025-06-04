<?php
require ('../vendor/autoload.php'); // Ensure PhpSpreadsheet is installed via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Database connection
include ('../connection.php');

// Start session and get user ID
session_start();
$user_id = $_SESSION['user_id']; // Fetch user ID from session

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Add Profile Information Sheet
$profileSheet = $spreadsheet->getActiveSheet();
$profileSheet->setTitle('Sponsor Clients Information');

// Updated profile SQL query to exclude province and municipality but keep region and region description
$profile_sql = "SELECT c.client_name, 
                       cp.contactperson_name, 
                       cp.contactperson_position, 
                       cp.contactperson_email, 
                       cp.contactperson_number, 
                       s.sector_name, 
                       c.client_location, 
                       r.region_name, 
                       r.region_description
                FROM tbl_client c
                LEFT JOIN tbl_sector s ON c.sector_id = s.sector_id
                LEFT JOIN tbl_contactperson cp ON c.client_id = cp.client_id
                LEFT JOIN tbl_province p ON CONVERT(c.client_location USING utf8mb4) COLLATE utf8mb4_general_ci 
                                          LIKE CONCAT('%', CONVERT(p.province_name USING utf8mb4) COLLATE utf8mb4_general_ci, '%')
                LEFT JOIN tbl_region r ON p.region_id = r.region_id
                WHERE c.user_id = ? AND s.sector_name = 'Sponsor'
                ORDER BY c.client_name ASC, cp.contactperson_name ASC";

$stmt = $conn->prepare($profile_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile_result = $stmt->get_result();

// Set profile headers
$profileSheet->setCellValue('A1', 'Name');
$profileSheet->setCellValue('B1', 'Contact Person');
$profileSheet->setCellValue('C1', 'Position');
$profileSheet->setCellValue('D1', 'Email');
$profileSheet->setCellValue('E1', 'Contact Number');
$profileSheet->setCellValue('F1', 'Sector');
$profileSheet->setCellValue('G1', 'Location');
$profileSheet->setCellValue('H1', 'Region');
$profileSheet->setCellValue('I1', 'Region Description');

$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$profileSheet->getStyle('A1:I1')->applyFromArray($headerStyle);

// Populate profile data with cleaner look for multiple contact persons
$row = 2;
$currentClient = null;
while ($profile_data = $profile_result->fetch_assoc()) {
    if ($currentClient !== $profile_data['client_name']) {
        if ($currentClient !== null) {
            // Add an empty row to separate clients
            $row++;

            // Add subtle background to the empty row for better separation
            $profileSheet->getStyle("A$row:I$row")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2'] // Light gray for separation
                ]
            ]);
            $row++;
        }

        $currentClient = $profile_data['client_name'];

        // Add client details in the first row for this client
        $profileSheet->setCellValue('A' . $row, $profile_data['client_name'] ?: 'Not Available');
        $profileSheet->setCellValue('F' . $row, $profile_data['sector_name'] ?: 'Not Available');
        $profileSheet->setCellValue('G' . $row, $profile_data['client_location'] ?: 'Not Available');
        $profileSheet->setCellValue('H' . $row, $profile_data['region_name'] ?: 'Not Available');
        $profileSheet->setCellValue('I' . $row, $profile_data['region_description'] ?: 'Not Available');

        // Apply thicker borders to the first row of each client
        $profileSheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
    }

    // Add contact person details (one row per contact person)
    $profileSheet->setCellValue('B' . $row, $profile_data['contactperson_name'] ?: 'Not Available');
    $profileSheet->setCellValue('C' . $row, $profile_data['contactperson_position'] ?: 'Not Available');
    $profileSheet->setCellValue('D' . $row, $profile_data['contactperson_email'] ?: 'Not Available');
    $profileSheet->setCellValue('E' . $row, $profile_data['contactperson_number'] ?: 'Not Available');

    // Apply borders to the row
    $profileSheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $row++;
}

// Auto-size all used columns
foreach ($profileSheet->getColumnIterator() as $column) {
    $profileSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

// Add Evaluation Data Sheet
$evalSheet = $spreadsheet->createSheet();
$evalSheet->setTitle('Sponsor Clients Evaluation');

// Fetch evaluated client evaluation data for Sponsor sector
$eval_sql = "SELECT c.client_name, cr.criteria_criterion, r.rating_score, r.rating_notes, e.evaluation_rating, e.evaluation_result, e.evaluation_date
             FROM tbl_client c
             INNER JOIN tbl_evaluation e ON c.client_id = e.client_id
             INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
             INNER JOIN tbl_criteria cr ON r.criteria_id = cr.criteria_id
             WHERE c.user_id = ? AND c.sector_id = (SELECT sector_id FROM tbl_sector WHERE sector_name = 'Sponsor')
             ORDER BY c.client_name, cr.criteria_id ASC";

$stmt = $conn->prepare($eval_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$eval_result = $stmt->get_result();

// Set evaluation headers
$evalSheet->setCellValue('A1', 'Client Name');
$evalSheet->setCellValue('B1', 'Overall Rating');
$evalSheet->setCellValue('C1', 'Result');
$evalSheet->setCellValue('D1', 'Evaluation Date');
$evalSheet->setCellValue('E1', 'Criteria');
$evalSheet->setCellValue('F1', 'Rating');
$evalSheet->setCellValue('G1', 'Notes');

$evalSheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// Populate evaluation data
$row = 2;
$currentClient = '';
if ($eval_result->num_rows > 0) {
    while ($eval_data = $eval_result->fetch_assoc()) {
        if ($currentClient !== $eval_data['client_name']) {
            if ($currentClient !== '') {
                // Add an empty row to separate clients
                $row++;
                
                // Add a thicker border to the last row of the previous client
                $evalSheet->getStyle('A' . ($row - 2) . ':G' . ($row - 2))->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                
                // Add subtle background to the empty row for better separation
                $evalSheet->getStyle('A' . ($row - 1) . ':G' . ($row - 1))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'] // Light gray for separation
                    ]
                ]);
            }
            
            $currentClient = $eval_data['client_name'];
            
            // Add a thicker top border and highlight for the first row of each client
            $evalSheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
            $evalSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F4F7'] // Light blue for client header
                ]
            ]);
            
            $evalSheet->setCellValue('A' . $row, $eval_data['client_name']);
            $evalSheet->setCellValue('B' . $row, $eval_data['evaluation_rating']);
            $evalSheet->setCellValue('C' . $row, $eval_data['evaluation_result']);
            $evalSheet->setCellValue('D' . $row, $eval_data['evaluation_date']);
        }
        
        $evalSheet->setCellValue('E' . $row, $eval_data['criteria_criterion']);
        $evalSheet->setCellValue('F' . $row, $eval_data['rating_score']);
        $evalSheet->setCellValue('G' . $row, $eval_data['rating_notes'] ?: 'No Notes');
        $row++;
    }
} else {
    $evalSheet->setCellValue('A2', 'No data evaluated');
    $evalSheet->mergeCells('A2:G2');
    $evalSheet->getStyle('A2')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000'], 'size' => 14],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);
}

// Add a border to the last client's data
$evalSheet->getStyle('A' . ($row - 1) . ':G' . ($row - 1))->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

// Add borders to all cells in the evaluation sheet
$evalSheet->getStyle('A1:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Format columns
// Auto-size all used columns
foreach ($profileSheet->getColumnIterator() as $column) {
    $profileSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
}

foreach ($evalSheet->getColumnIterator() as $column) {
    $colLetter = $column->getColumnIndex();
    if ($colLetter != 'G') { // Not for notes column
        $evalSheet->getColumnDimension($colLetter)->setAutoSize(true);
    }
}

// Set notes column width
$evalSheet->getColumnDimension('G')->setAutoSize(false);
$evalSheet->getColumnDimension('G')->setWidth(50);

// Ensure the notes column wraps text
$evalSheet->getStyle('G2:G' . ($row - 1))->getAlignment()->setWrapText(true);

// Fix data information to the top-left
$evalSheet->getStyle('A2:G' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
$evalSheet->freezePane('A2');

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Sponsor_Clients_Export_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
