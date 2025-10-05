<?php
ob_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db_connect.php';

// Use PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ✅ Ensure student ID is passed
if (!isset($_POST['id'])) {
    die("No student ID provided.");
}

$student_id = $_POST['id'];

// ✅ Get current school year
$current_sy = $pdo->query("SELECT * FROM school_years WHERE is_current = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$current_sy_label = $current_sy ? $current_sy['school_year'] : 'Unknown School Year';

// ✅ Fetch student details with readable names
$stmt = $pdo->prepare("
    SELECT 
        s.id, s.first_name, s.last_name, s.address, s.contact,
        p.program_code, yl.year_level, sec.section_name
    FROM students s
    LEFT JOIN programs p ON s.program_id = p.id
    LEFT JOIN year_levels yl ON s.year_level_id = yl.id
    LEFT JOIN sections sec ON s.section_id = sec.id
    WHERE s.id = ?
");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

// ✅ Fetch all violations and sanctions for this student
$sql = "
SELECT 
    rv.id AS record_id,
    v.violation,
    sv.description,
    sv.location,
    sv.date_time,
    u_report.username AS reported_by,
    rv.remarks,
    rv.date_recorded,
    sa.sanction,
    u_record.username AS recorded_by
FROM record_violations rv
LEFT JOIN student_violations sv ON rv.student_violations_id = sv.id
LEFT JOIN violations v ON sv.violation_id = v.id
LEFT JOIN sanctions sa ON rv.sanction_id = sa.id
-- 👇 reported_by comes from student_violations.user_id
LEFT JOIN users u_report ON sv.user_id = u_report.id
-- 👇 recorded_by comes from record_violations.user_id
LEFT JOIN users u_record ON rv.user_id = u_record.id
WHERE sv.student_id = ?
ORDER BY rv.date_recorded DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$violations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ---- HEADER 1: School Year ----
$sheet->setCellValue('A1', 'School Year:');
$sheet->setCellValue('B1', $current_sy_label);

// ---- HEADER 2: Student Info ----
$sheet->fromArray(
    [
        ['ID', 'First Name', 'Last Name', 'Program', 'Year Level', 'Section', 'Address', 'Contact']
    ],
    NULL,
    'A3'
);
$sheet->fromArray(
    [
        [
            $student['id'],
            $student['first_name'],
            $student['last_name'],
            $student['program_code'],
            $student['year_level'],
            $student['section_name'],
            $student['address'],
            $student['contact']
        ]
    ],
    NULL,
    'A4'
);

// ---- HEADER 3: Violations ----
$sheet->fromArray(
    [
        ['No.', 'Violation', 'Description', 'Location', 'Date Reported', 'Reported By', 'Sanction', 'Remarks', 'Date Recorded', 'Recorded By']
    ],
    NULL,
    'A6'
);

// ---- Fill violations ----
$row = 7;
$count = 1;
foreach ($violations as $v) {
    $sheet->setCellValue("A{$row}", $count);
    $sheet->setCellValue("B{$row}", $v['violation']);
    $sheet->setCellValue("C{$row}", $v['description']);
    $sheet->setCellValue("D{$row}", $v['location']);
    $sheet->setCellValue("E{$row}", $v['date_time']);
    $sheet->setCellValue("F{$row}", $v['reported_by']);
    $sheet->setCellValue("G{$row}", $v['sanction']);
    $sheet->setCellValue("H{$row}", $v['remarks']);
    $sheet->setCellValue("I{$row}", $v['date_recorded']);
    $sheet->setCellValue("J{$row}", $v['recorded_by']);
    $row++;
    $count++;
}

// ✅ Auto-size columns
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ✅ Title formatting
$sheet->getStyle('A:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:B1')->getFont()->setBold(true);
$sheet->getStyle('A3:I3')->getFont()->setBold(true);
$sheet->getStyle('A6:J6')->getFont()->setBold(true);

// ✅ File name
$fileName = "Violations_Summary_{$student['last_name']}_{$student['first_name']}.xlsx";

// ✅ Output to browser (download)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$fileName\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
ob_end_clean();
?>


