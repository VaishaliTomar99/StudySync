<?php

session_start();

include 'includes/db.php';

require('fpdf/fpdf.php');

$user_id = $_SESSION['user_id'];

$tasks =
mysqli_query($conn,
"SELECT * FROM tasks
 WHERE user_id='$user_id'");

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',18);

$pdf->Cell(190,10,
'StudySync AI Task Report',
0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,10,'Subject',1);
$pdf->Cell(70,10,'Task',1);
$pdf->Cell(40,10,'Priority',1);
$pdf->Cell(40,10,'Status',1);

$pdf->Ln();

$pdf->SetFont('Arial','',11);

while($row=mysqli_fetch_assoc($tasks)){

$pdf->Cell(40,10,$row['subject'],1);
$pdf->Cell(70,10,$row['task_title'],1);
$pdf->Cell(40,10,$row['priority'],1);
$pdf->Cell(40,10,$row['status'],1);

$pdf->Ln();

}

$pdf->Output();

?>