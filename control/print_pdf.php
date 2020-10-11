<?php


require('fpdf/fpdf.php');

$pdf= new FPDF();

$pdf->SetAuthor('Lana Kovacevic');
$pdf->SetTitle('FPDF tutorial');

$pdf->SetFont('times','B',20);
$pdf->SetTextColor(50,60,100);

$pdf->AddPage('P');
$pdf->SetDisplayMode('real','default');




$pdf->SetXY(50,20);
$pdf->SetDrawColor(50,60,100);
$pdf->Cell(100,10,'FPDF Tutorial',1,0,'C',0);


$pdf->SetXY(10,50);
$pdf->SetFontSize(5);
$pdf->Write(5,'Рас два три. ');

$pdf->Output('example1.pdf','I');
?>