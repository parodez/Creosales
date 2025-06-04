<?php
require ('../vendor/autoload.php'); // Ensure PhpSpreadsheet is installed via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Database connection
include ('../connection.php');

if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);

    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Add Profile Information Sheet
    $profileSheet = $spreadsheet->getActiveSheet();
    $profileSheet->setTitle('Profile Information');

    // Fetch client profile data with region and region description
    $profile_sql = "SELECT c.client_name, 
                           c.client_location, 
                           s.sector_name,
                           r.region_name,
                           r.region_description
                    FROM tbl_client c
                    LEFT JOIN tbl_sector s ON c.sector_id = s.sector_id
                    LEFT JOIN tbl_province p ON CONVERT(c.client_location USING utf8mb4) COLLATE utf8mb4_general_ci 
                                              LIKE CONCAT('%', CONVERT(p.province_name USING utf8mb4) COLLATE utf8mb4_general_ci, '%')
                    LEFT JOIN tbl_region r ON p.region_id = r.region_id
                    WHERE c.client_id = ?";

    $stmt = $conn->prepare($profile_sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $profile_result = $stmt->get_result();
    $profile_data = $profile_result->fetch_assoc();

    // Fetch contact persons for the client
    $contact_sql = "SELECT contactperson_name, contactperson_position, contactperson_email, contactperson_number
                    FROM tbl_contactperson
                    WHERE client_id = ?";
    $stmt = $conn->prepare($contact_sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $contact_result = $stmt->get_result();

    // Set profile headers
    $profileSheet->setCellValue('A1', 'Client Information');
    $profileSheet->mergeCells('A1:B1');
    $profileSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $profileSheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Add client details
    $profile_fields = [
        'Name' => 'client_name',
        'Sector' => 'sector_name',
        'Location' => 'client_location',
        'Region' => 'region_name',
        'Region Description' => 'region_description'
    ];

    $row = 2;
    foreach ($profile_fields as $label => $field) {
        $profileSheet->setCellValue('A' . $row, $label);
        $profileSheet->setCellValue('B' . $row, $profile_data[$field] ?: 'Not Available');

        // Bold labels and align values to the left
        $profileSheet->getStyle("A$row")->getFont()->setBold(true);
        $profileSheet->getStyle("B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Apply borders
        $profileSheet->getStyle("A$row:B$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;
    }

    // Add a separator row
    $row++;
    $profileSheet->setCellValue('A' . $row, 'Contact Persons');
    $profileSheet->mergeCells('A' . $row . ':B' . $row);
    $profileSheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
    $profileSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $row++;

    // Add contact person headers
    $profileSheet->setCellValue('A' . $row, 'Name');
    $profileSheet->setCellValue('B' . $row, 'Position');
    $profileSheet->setCellValue('C' . $row, 'Email');
    $profileSheet->setCellValue('D' . $row, 'Contact Number');

    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $profileSheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($headerStyle);
    $row++;

    // Populate contact person data
    if ($contact_result->num_rows > 0) {
        while ($contact_data = $contact_result->fetch_assoc()) {
            $profileSheet->setCellValue('A' . $row, $contact_data['contactperson_name'] ?: 'Not Available');
            $profileSheet->setCellValue('B' . $row, $contact_data['contactperson_position'] ?: 'Not Available');
            $profileSheet->setCellValue('C' . $row, $contact_data['contactperson_email'] ?: 'Not Available');
            $profileSheet->setCellValue('D' . $row, $contact_data['contactperson_number'] ?: 'Not Available');

            // Apply borders
            $profileSheet->getStyle("A$row:D$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }
    } else {
        $profileSheet->setCellValue('A' . $row, 'No contact persons available');
        $profileSheet->mergeCells('A' . $row . ':D' . $row);
        $profileSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // Auto-size all used columns
    foreach ($profileSheet->getColumnIterator() as $column) {
        $profileSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }

    // Add Evaluation Data Sheet
    $evalSheet = $spreadsheet->createSheet();
    $evalSheet->setTitle('Evaluation Data');

    // Fetch evaluation summary
    $summary_sql = "SELECT evaluation_rating, evaluation_result, evaluation_date 
                   FROM tbl_evaluation WHERE client_id = ? ORDER BY evaluation_date DESC LIMIT 1";
    $stmt = $conn->prepare($summary_sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $summary_result = $stmt->get_result();
    $summary_data = $summary_result->fetch_assoc();

    // Set Evaluation Summary title
    $evalSheet->setCellValue('A1', 'Evaluation Summary');
    $evalSheet->mergeCells('A1:C1');
    $evalSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $evalSheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Add summary data with bold labels
    $evalSheet->setCellValue('A2', 'Overall Rating:');
    $evalSheet->setCellValue('B2', $summary_data['evaluation_rating'] ?: 'Not Available');
    $evalSheet->setCellValue('A3', 'Result:');
    $evalSheet->setCellValue('B3', $summary_data['evaluation_result'] ?: 'Not Available');
    $evalSheet->setCellValue('A4', 'Evaluation Date:');
    $evalSheet->setCellValue('B4', $summary_data['evaluation_date'] ?: 'Not Available');

    $evalSheet->getStyle('A2:A4')->getFont()->setBold(true);
    $evalSheet->getStyle('B2:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // Fetch criteria evaluation data
    $eval_sql = "SELECT cr.criteria_criterion, r.rating_score, r.rating_notes
                 FROM tbl_evaluation e
                 INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
                 INNER JOIN tbl_criteria cr ON r.criteria_id = cr.criteria_id
                 WHERE e.client_id = ? ORDER BY cr.criteria_id ASC";

    $stmt = $conn->prepare($eval_sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $eval_result = $stmt->get_result();

    // Set criteria headers
    $evalSheet->setCellValue('A6', 'Criteria');
    $evalSheet->setCellValue('B6', 'Rating');
    $evalSheet->setCellValue('C6', 'Notes');

    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $evalSheet->getStyle('A6:C6')->applyFromArray($headerStyle);
    $evalSheet->getStyle('A6:C6')->getFont()->setBold(true);
    $evalSheet->getStyle('A6:C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $evalSheet->getStyle('B7:B11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // Populate evaluation data
    $row = 7;
    while ($eval_data = $eval_result->fetch_assoc()) {
        $evalSheet->setCellValue('A' . $row, $eval_data['criteria_criterion'] ?: 'Not Available');
        $evalSheet->setCellValue('B' . $row, $eval_data['rating_score'] ?: 'Not Available');
        $evalSheet->setCellValue('C' . $row, $eval_data['rating_notes'] ?: 'Not Available');

        // Ensure decimal values are displayed correctly
        $evalSheet->getStyle('B7:B' . $row)->getNumberFormat()->setFormatCode('0.0');

        // Apply borders
        $evalSheet->getStyle("A$row:C$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

    }
     // Word wrap
     $evalSheet->getStyle('C7:C' . ($row - 1))->getAlignment()->setWrapText(true);

     $evalSheet->getStyle('A7:C' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

     // For the rows with long content, you might want to set a higher row height
     for ($i = 7; $i < $row; $i++) {
         $evalSheet->getRowDimension($i)->setRowHeight(-1); // Set to -1 for auto-height

     }
     $evalSheet->getColumnDimension('C')->setWidth(50); // Adjust width as needed

    // Generate Excel file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Client_' . ($profile_data['client_name'] ?: 'Unknown') . '_Export_' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');

    // Auto-size all used columns
    foreach ($profileSheet->getColumnIterator() as $column) {
        $profileSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
  

    // Auto-size columns A and B, set fixed width for column C
    foreach (['A', 'B'] as $column) {
        $evalSheet->getColumnDimension($column)->setAutoSize(true);
    }
    $evalSheet->getColumnDimension('C')->setWidth(50); // Adjust width as needed
    $writer->save('php://output');
    exit;
}
