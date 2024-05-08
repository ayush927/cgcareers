<?php

    $pagecount = $pdf->setSourceFile($pdfUrl.$pageName.'.pdf');
    $tpl = $pdf->importPage($pagecount);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    $pdf->SetFontSize('10','B');
    $pdf->SetXY(40,169);
    if($remark_date){
    $pdf->MultiCell(50,6,$remark_date);}
    $pdf->SetFontSize('12');
    $pdf->SetXY(35,60);
    if($c_remark){
    $pdf->MultiCell(150,8,$c_remark);}
    $pdf->SetFont('arial');
    $pdf->SetXY(24, 210);
    $pdf->Cell(0,20,date('d M Y'), 0, 0,'L',false);
    $pdf->SetXY(115, 215);
    if($signature){
    $pdf->Cell(0,20,$pdf->Image($signature,$pdf->GetX(), $pdf->GetY(),30), 0, 0,'R',false);}
?>