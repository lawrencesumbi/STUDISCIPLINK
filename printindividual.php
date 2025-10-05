<?php
require __DIR__ . '/db_connect.php';
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$student_violation_id = $_GET['id'] ?? null;

if (!$student_violation_id) {
    die("No student violation ID provided.");
}

// Fetch student violation details with joins
$sql = "
SELECT 
    s.first_name,
    s.last_name,
    p.program_name AS program,
    y.year_level AS year_level,
    sec.section_name AS section,
    s.address,
    s.contact,

    v.violation AS violation_type,
    sv.description AS violation_description,
    sv.location,
    sv.date_time AS date_reported,

    sa.sanction AS sanction_type,
    rv.remarks AS sanction_remarks,
    rv.date_recorded,

    rb.username AS reported_by_username,
    rec.username AS recorded_by_username

FROM student_violations sv
JOIN students s ON sv.student_id = s.id
JOIN violations v ON sv.violation_id = v.id
LEFT JOIN record_violations rv ON rv.student_violations_id = sv.id
LEFT JOIN sanctions sa ON rv.sanction_id = sa.id
LEFT JOIN users rb ON sv.user_id = rb.id
LEFT JOIN users rec ON rv.user_id = rec.id
LEFT JOIN programs p ON s.program_id = p.id
LEFT JOIN year_levels y ON s.year_level_id = y.id
LEFT JOIN sections sec ON s.section_id = sec.id
WHERE sv.id = :student_violation_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['student_violation_id' => $student_violation_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("No record found for this student violation.");
}

// Create Word document
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// --- HEADER ---
$section->addImage('img/scclogo.jpg', [
    'width' => 125,
    'height' => 125,
    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
]);
$section->addText(
    "ST. CECILIA'S COLLEGE CEBU INC.",
    ['bold' => true, 'size' => 16],
    ['alignment' => 'center']
);
$section->addTextBreak(1);

// --- STUDENT DETAILS ---
$section->addText("STUDENT DETAILS:", ['bold' => true, 'size' => 12]);
$section->addText("First Name: " . $data['first_name']);
$section->addText("Last Name: " . $data['last_name']);
$section->addText("Program: " . ($data['program'] ?? 'N/A'));
$section->addText("Year Level: " . ($data['year_level'] ?? 'N/A'));
$section->addText("Section: " . ($data['section'] ?? 'N/A'));
$section->addText("Address: " . ($data['address'] ?? 'N/A'));
$section->addText("Contact: " . ($data['contact'] ?? 'N/A'));
$section->addTextBreak(1);

// --- VIOLATION DETAILS ---
$section->addText("VIOLATION DETAILS:", ['bold' => true, 'size' => 12]);
$section->addText("Violation Type: " . ($data['violation_type'] ?? 'N/A'));
$section->addText("Description: " . ($data['violation_description'] ?? 'N/A'));
$section->addText("Location: " . ($data['location'] ?? 'N/A'));
$section->addText("Date Reported: " . ($data['date_reported'] ?? 'N/A'));
$section->addTextBreak(1);

// --- SANCTION DETAILS ---
$section->addText("SANCTION DETAILS:", ['bold' => true, 'size' => 12]);
$section->addText("Sanction Type: " . ($data['sanction_type'] ?? 'N/A'));
$section->addText("Remarks: " . ($data['sanction_remarks'] ?? 'N/A'));
$section->addText("Date Recorded: " . ($data['date_recorded'] ?? 'N/A'));
$section->addTextBreak(2);

// --- FOOTER (Reported By / Recorded By) ---
$table = $section->addTable();

// Add one row with two cells
$table->addRow();

// Left cell - Reported By
$table->addCell(5000)->addText(
    "Reported By: " . ($data['reported_by_username'] ?? 'N/A'),
    ['size' => 11],
    ['alignment' => 'left']
);

// Right cell - Recorded By
$table->addCell(5000)->addText(
    "Recorded By: " . ($data['recorded_by_username'] ?? 'N/A'),
    ['size' => 11],
    ['alignment' => 'right']
);


// --- SAVE FILE ---
$fileName = "Student_Violation_{$data['last_name']}_{$data['first_name']}.docx";
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
exit;
