<?php

ob_start();

$code = base64_decode($_GET['code']);

$report_variation = $_GET['report_variation'];



include('dbconn.php');

$sql = "select * from user_code_list where code='$code'";

$res = mysqli_query($con,$sql);

$row = mysqli_fetch_array($res);

$test_type = $row['solution'];

// echo $test_type;
// 
// die;

//check protocol HTTPS/HTTP

if (isset($_SERVER['HTTPS']) &&

    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||

    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&

    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {

    $protocol = 'https://';

}

else {

    $protocol = 'http://';

}

// echo $protocol.$_SERVER['SERVER_NAME'];

// die;

//check solution type 

if($test_type=='PPE')

{

    header('location:ppe_report.php?code='.$_GET['code']);

}

else if($test_type=='WLA')

{

    header('location:wla_report.php?code='.$_GET['code']);

}

else if($test_type=='WLS')

{

    header('location:wls_report.php?code='.$_GET['code']);

}

else if($test_type=='WPA')

{

    header('location:wpa_report.php?code='.$_GET['code']);

}

else if($test_type=='CM')

{

    header('location:ps_report.php?code='.$_GET['code']);

}

else if($test_type=='SDP1')

{

    header('location:sdp1_report.php?code='.$_GET['code']);

}

else if($test_type=='SDP2')

{

    header('location:sdp2_report.php?code='.$_GET['code']);

}

else if($test_type=='SDP3')

{

    header('location:sdp3_report.php?code='.$_GET['code']);

}

else if($test_type=='JRAP')

{

    header('location:jrap_report.php?code='.$_GET['code']);

}

else if($test_type=='UCE')
{

    if( !isset( $report_variation ) ){
        header('location:uce_report_1.php?code='.$_GET['code']);
    }

    elseif( $report_variation == 'one' ){
        header('location:uce_report_1.php?code='.$_GET['code']);
    }

    elseif( $report_variation == 'two' ){
        $report_variation = 'two';
        header('location:uce_report_'.$report_variation.'.php?code='.$_GET['code']);
    }
    
    elseif( $report_variation == 'three' ){
        $report_variation = 'three';
        header('location:uce_report_'.$report_variation.'.php?code='.$_GET['code']);
    }

}

else if($test_type=='OCS')

{   //echo "in if ocs";die();

    header('location:ocs_report.php?code='.$_GET['code']);

}

else if($test_type=='QCE')

{

    header('location:qce_report.php?code='.$_GET['code']);

}

else if($test_type=='CAT')

{

    header('location:cat_report.php?code='.$_GET['code']);

}

/* else if($test_type=='Disha')

{

    header('location:disha_report.php?code='.$_GET['code']);

} */

else if($test_type=='Disha')



{

    header('location:'.$protocol.''.$_SERVER['SERVER_NAME'].'/reports/disha/'.$_GET['code']);

}

else if($test_type=='ECP')

{

  header('location:'.$protocol.''.$_SERVER['SERVER_NAME'].'/reports/ecp/'.$_GET['code']);

}







?>