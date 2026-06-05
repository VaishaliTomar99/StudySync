<?php

session_start();

include 'includes/db.php';

require('fpdf/fpdf.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'
 ORDER BY deadline ASC");

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',20);

$pdf->SetTextColor(106,90,249);

$pdf->Cell(190,15,'StudySync AI - Task Report',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','B',11);

$pdf->SetFillColor(106,90,249);

$pdf->SetTextColor(255,255,255);

$pdf->Cell(35,12,'Subject',1,0,'C',true);

$pdf->Cell(65,12,'Task Title',1,0,'C',true);

$pdf->Cell(30,12,'Deadline',1,0,'C',true);

$pdf->Cell(25,12,'Priority',1,0,'C',true);

$pdf->Cell(35,12,'Status',1,1,'C',true);

$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','',10);

while($row = mysqli_fetch_assoc($query)){

    $subject = $row['subject'];

    $task = substr($row['task_title'],0,32);

    $deadline = date('d M Y',
        strtotime($row['deadline']));

    $priority = $row['priority'];

    $status = $row['status'];

    $pdf->Cell(35,12,$subject,1);

    $pdf->Cell(65,12,$task,1);

    $pdf->Cell(30,12,$deadline,1);

    $pdf->Cell(25,12,$priority,1);

    $pdf->Cell(35,12,$status,1);

    $pdf->Ln();
}

$pdf->Ln(10);

$pdf->SetFont('Arial','I',10);

$pdf->SetTextColor(120,120,120);

$pdf->Cell(190,10,
'Generated from StudySync AI Dashboard',
0,1,'C');

$pdf->Output('D','StudySync_Report.pdf');

?>