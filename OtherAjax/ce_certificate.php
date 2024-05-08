<?php

  ob_start();
  $email = base64_decode($_GET['email']);
  $grp = base64_decode($_GET['grp']);
  include('dbconn.php');
  include ('phpqrcode/qrlib.php');
  use setasign\Fpdi\Fpdi;
  require_once('fpdf181/fpdf181/fpdf.php');
  require_once('fpdi2/fpdi2/src/autoload.php');
  // Create new Landscape PDF
  $pdf = new FPDI();
  $sql = "select * from certificate_template where c_group='$grp'";
  $res = mysqli_query($con,$sql);
  $row = mysqli_fetch_array($res);
  extract( $row );
  $pt = '../'.$row['template'];
  $logo = '../'.$row['logo'];

  $sign1 = '../'.$row['sign_one'];
  $sign2 = '../'.$row['sign_two'];
  $sign3 = '../'.$row['sign_three'];

  $sql2 = "select * from user_details where email='$email'";
  $res2 = mysqli_query($con,$sql2);
  $row2 = mysqli_fetch_array($res2);
  // echo "<pre>";
  // print_r( $row2 );
  // die;
  // $certificate_file_name = $row2['certification_type'].'_'.str_replace( ' ' , '_' ,strtolower($row['c_group']) );
    $certificate_file_name = $row2['certification_type'];
    $sql3 = "select * from partner_counselor_status where email='$email' and grp='$grp'";
    $res3 = mysqli_query($con,$sql3);
    $row3 = mysqli_fetch_array($res3);
  // print_r( $row3 );
  // die;
  if(!empty($row3['ce_time'])){
    $dt = date("d F Y", strtotime($row3['ce_time']));
  }
  else{
    $dt = $row3['ce_time'];
  }
    // echo $certificate_file_name;
    // die;
  $pagecount = $pdf->setSourceFile($pt);
  if( $certificate_file_name != '' ){
    $pt = 'cetificate_template/'.$certificate_file_name.'.pdf';
    
    $cetrification_type_arr = [
      'C3A' => 'Certified Career Counselling Associate',
      'C3E' => 'Certified Career Counselling Enterprise',
      'C3P' => 'Certified Career Counselling Professional'
    ];

    $certificate_name = $cetrification_type_arr[$row2['certification_type']];
    $text = $_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING'];
    // $imageName = $email.'_'.$certificate_file_name.'.png';
    $imageName = 'qrcodes/image.png';
    echo QRcode::png($text, $imageName);
    $pagecount = $pdf->setSourceFile($pt);
    $tpl = $pdf->importPage(1);

    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('28');
    $pdf->SetTextColor(137, 62, 36);
    $pdf->SetXY(147,68);
    $pdf->Cell(20, 10,$row2['fullname'], 0, 0, 'C');

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('20');
    $pdf->SetTextColor(137, 62, 36);
    $pdf->SetXY(147,104);
    $pdf->Cell(20, 10 , $certificate_name, 0, 0, 'C');

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('18');
    $pdf->SetTextColor(137, 62, 36);
    $pdf->SetXY( 64, 117.5 );
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->MultiCell(230,10, $dt , 0, 'C', false);

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(20, 10);
    $pdf->Cell(20, 10,$pdf->Image($imageName,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(35,128);
    $pdf->Cell(20, 10,$pdf->Image($sign1,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(210,128);
    $pdf->Cell(20, 10,$pdf->Image($sign2,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');


    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(120,128);
    $pdf->Cell(20, 10,$pdf->Image($sign3,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

  }
  else{
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(125,16);
    $pdf->Cell(20, 10,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('times','B');
    $pdf->SetFontSize('28');
    $pdf->SetTextColor(0,150,255);
    $pdf->SetXY(147,50);
    $pdf->Cell(20, 10,$certificate_name, 0, 0, 'C');

    //name reseller
    $sql2 = "select * from user_details where email='$email'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);

    $pdf->SetFont('times','B');
    $pdf->SetFontSize('28');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(147,75);
    $pdf->Cell(20, 10,$row2['fullname'], 0, 0, 'C');
    $sql3 = "select * from partner_counselor_status where email='$email' and grp='$grp'";
    $res3 = mysqli_query($con,$sql3);
    $row3 = mysqli_fetch_array($res3);

    if(!empty($row3['ce_time'])){ 
      $dt = date("d F Y", strtotime($row3['ce_time']));
    }
    else{
      $dt = $row3['ce_time'];
    }

    $pdf->SetFont('times','I');
    $pdf->SetFontSize('20');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(35,100);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->MultiCell(230,10,$middle_content."\n".$dt,0,'C',false);

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(35,148);
    $pdf->Cell(20, 10,$pdf->Image($sign1,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(120,148);
    $pdf->Cell(20, 10,$pdf->Image($sign2,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('11');
    $pdf->SetXY(210,148);
    $pdf->Cell(20, 10,$pdf->Image($sign3,$pdf->GetX(), $pdf->GetY(),60), 0, 0, 'C');

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('18');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(45,160);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$name_one, 0, 0, 'C');

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('18');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(135,160);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$name_two, 0, 0, 'C');

    $pdf->SetFont('arial','B');
    $pdf->SetFontSize('18');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(230,160);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$name_three, 0, 0, 'C');

    //detail one 
    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(45,173);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_one_one, 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(135,173);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_one_two, 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(230,173);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_one_three, 0, 0, 'C');

    //detail two 
    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(45,180);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_two_one, 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(135,180);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_two_two, 0, 0, 'C');

    $pdf->SetFont('arial');
    $pdf->SetFontSize('12');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(230,180);
    // $pdf->MultiCell(200, 10,$middle_content,'C');
    $pdf->Cell(20, 10,$detail_two_three, 0, 0, 'C');
  }

  ob_end_clean();
  $pdf->AliasNbPages();
  $pdf->Output();
  // $pdf2->Output();
  ob_end_flush();
?>