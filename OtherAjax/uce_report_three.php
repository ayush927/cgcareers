<?php
    ob_start();
    use setasign\Fpdi\Fpdi;
    require_once('fpdf181/fpdf181/fpdf.php');
    require_once('fpdi2/fpdi2/src/autoload.php');
    require_once('uce_score_calculation.php');
    include('dbconn.php');
    
    $pdf = new FPDI();
    $pdfUrl = 'report_template/uce-v3/';
    
    $asmt_variation_3_02_24 = [
        'where_in' => "asmt_variant_3 = 1 AND q_group IN ( 1 , 'All' )"
    ];

    // $arr_stream_map = [
    //     ''
    // ]

    $suitable_exp = [
        'without_change' => [ 
            'career_list' => 'No careers suitable for you could be identified in',
            'inf' => 'We could not identify any careers suitable for you in your current stream. This could be due to non-alignment of your abilities or interests in a reasonable manner. You should speak to a Respicite certified career counsellor to identify your suitable careers who shall guide you towards appropriate careers and create an appropriate educational roadmap',
        ],
        'with_change' => [ 
            'career_list' => 'No careers suitable for you could be identified in',
            'inf' => 'We could neither identify any careers suitable for you in your current stream nor the other streams provided... You should speak to a Respicite certified career counsellor to identify your suitable careers and create an educational roadmap',
        ],
        'suitable_10th' => [
            'career_list' => 'No careers suitable for you could be identified',
            'inf' => 'We could not identify any careers suitable for you from your current inputs. You should speak to a Respicite certified career counsellor to identify your suitable careers and create an educational roadmap'
        ]
    ];

    $career_stream_graph_desc = 'Numbers in the bars represent count of top, good & optional career choices for you';
    function setToGlobal( $setArray ){
        foreach ( $setArray as $key => $value ) {
            $GLOBALS[$key] = $value;
        }
    }
    
    $sign_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . "://{$_SERVER['SERVER_NAME']}/dev/";
    $code = base64_decode($_GET['code']);
    
    //uce_report_v1
    //echo "uce_report_v1"."<br>";die();
    function pre( $data , $die = null ){
        echo "<pre>";
        print_r( $data );
        if( $die != null ){
            die;
        }
    }

    $table = [
        'user_details' => 'user_details',
        'code_list' => 'user_code_list',
        'user_variant_tb' => 'user_solution_variation_data',
        'solution_variation' => 'solution_variant_new',
        'report_template' => 'solution_report_template',
        'report_pages' => 'solution_report_pages',
        'reseller_homepage' => 'reseller_homepage',
        'uce_part1' => 'uce_part1',
        'test_details' => 'ppe_part1_test_details',
        'uce_part1_2' => 'uce_part1_2',
        'solution_pages' => 'solution_report_pages'
    ];

    function getQuery( $selectArray , $selectColumn = null ,  $echoQuery= null, $die = null)
    {
        $con = $GLOBALS['con'];
        $select = false;
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $group_by = false;
        $single = false;
        $where_in = false;
        $where_not_in = false;
        $where_not_in_numeric = false;
        $like = false;
        $num_rows = false;
    
        extract($selectArray);
        $query = 'SELECT ';
        if( $select ){
            $query.=$select.' FROM ';
        }
        else{
            $query.='* FROM ';
        }

        if( $table ){
            $query.= $table.' ';
        }

        if( $join ){
            if( is_array( $join ) ){
                foreach ($join as $key => $value) {
                    $query.= $value[2]." JOIN ".$value[0]." ON ".$value[1];
                }
            }
        }

        if( $where ){
            if( is_array($where) ){
                $query.=' WHERE ';
                $windex = 0;
                foreach ($where as $key => $value) {
                    $query.= ($windex > 0 ? ' AND ' : '').$key.( count(explode(' ',$key)) > 1 ? ' ' : '= ').( is_numeric($value) ? $value : '"'.$value.'" ' );
                    ++$windex;
                }
            }
        }
        
        if( $where ){
            if( $where_in ){
                if( is_array($where_in) ){
                    $query.=' AND ';
                    $wIindex = 0 ;
                    foreach ( $where_in as $key => $value) {
                        $query.= ( $wIindex > 0 ? ' AND ' : '' ).$key.' IN ( '.$value.' ) ';
                        ++$wIindex;
                    }
                }
            }
        }
        else{
            if( is_array($where_in) ){
                $query.=' WHERE ';
                $wIindex = 0 ;
                foreach ( $where_in as $key => $value) {
                    $query.= ( $wIindex > 0 ? ' AND ' : '' ).$key.' IN ( '.$value.' ) ';
                    ++$wIindex;
                }
            }

        }

        if( $or_where ){
            if( is_array($or_where) ){
                $query.=' WHERE (';
                $woIindex = 0;
                foreach ( $or_where as $key => $value){
                    $query .= ($woIindex > 0 ? ' OR (' : '') ;
                    $wIindex = 0;
                    foreach ( $value as $k => $v ) {
                        $query.= ( $wIindex > 0 ? ' AND ' : '' ).$k.( count(explode(' ',$k)) > 1 ? ' '.explode(' ',$k)[1].' "' : ' = "').$v.'" '.( $wIindex == (count($value)-1) ? ' )' : '');
                        ++$wIindex;
                    }
                    ++$woIindex;
                }
            }
        }

        if( $group_by ){
            if( is_array( $group_by ) ){
                $gIndex = 0;
                $query.= 'GROUP BY ';
                foreach ($group_by as $gkey => $gvalue){
                    $query.= ( $gIndex > 0 ? ', ' : '' ).$gvalue." ";
                    ++$gIndex;
                }
            }
        }

        if( $order ){
            if( is_array( $order ) ){
                $oIndex = 0;
                $query.= 'ORDER BY ';
                foreach ($order as $key => $value){
                    $query.= ( $oIndex > 0 ? ', ' : '' ).$key." ".$value." ";
                    ++$oIndex;
                }
            }
        }

        if( $limit ){
            if( is_array( $limit ) ){
                $query .= ' LIMIT '.$limit[0].' , '.$limit[1];
            }
        }

        // echo $query."<br>";
        if(  $echoQuery == 1 ){
            echo $query."<br>";
            if( $die == 1 ){
                die();
            }
        }

        if( $single ){
            // echo 1;
            return mysqli_fetch_assoc(mysqli_query($con,$query));
        }
        else{
            $data = mysqli_query($con, $query);
            // echo mysqli_num_rows($data);
            // die;
            if( mysqli_num_rows( $data ) > 0 ){
                while( $value = mysqli_fetch_array( $data ) ){
                    if( $selectColumn != null ){
                        $return[] = $value[$selectColumn];
                    }
                    else{
                        $return[] = $value;
                    }
                }  
                return $return;
            }
            else{
                return [];
            }
        }
        
    }

    function setSourceFile( $pageName = null , $noOfPage  = null , $pageType = null , $startFrom = 0 ){
        extract( $GLOBALS );
        if( $pageType != 'static' ){
            if( $pageName != null ){
                $pagecount = $pdf->setSourceFile($pdfUrl.$pageName.'.pdf');
                if( $noOfPage != null ){
                    $tpl = $pdf->importPage($noOfPage);
                }
                else{
                    $tpl = $pdf->importPage($pagecount);
                }
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            }
            else{
                $pagecount = $pdf->setSourceFile($pdfUrl.'blank_page.pdf');
                $tpl = $pdf->importPage($pagecount);
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            }
        }
        else{
            if( $pageName != null ){
                $pagecount = $pdf->setSourceFile($pdfUrl.$pageName.'.pdf');
                for ($i=$startFrom; $i < $noOfPage ; $i++){
                    $tpl = $pdf->importPage($i+1);
                    $pdf->AddPage();
                    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                }
            }
            else{
                $pagecount = $pdf->setSourceFile($pdfUrl.'blank_page.pdf');
                $tpl = $pdf->importPage($pagecount);
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            }
        }
        return $pdf;
    }

    function checking_size($logo,$pdf){   
        // echo "<pre>";
        // print_r($pdf);die();
        $size = getimagesize($logo);
        $wImg = $size[0];
        $hImg = $size[1];
        //echo "before";
        if($wImg<=512 && $hImg<=512)
        {   //echo "if";
            $pdf->SetXY(170, 8);
            $pdf->SetFont('arial');
            $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),30), 0, 0,'R',false);
        }
        else
        {   ///echo "else";
            $pdf->SetXY(150, 10);
            $pdf->SetFont('arial');
            $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),50), 0, 0,'R',false);   
        }
    }


    // $pdf->MakeFont( 'franklin-gothic-book-regular.ttf' , 'cp1252'  );

    // Import the first page from the PDF and add to dynamic PDF

    $detail_row = getQuery( [ 'table' => $table['code_list'] , 'where' => [ 'code' => $code ] , 'single' => true ]);
    $reseller_id = $detail_row['reseller_id'];
    $c_remark = $detail_row['c_remark'];
    $remark_date = $detail_row['remark_update_last'];
    $class = $detail_row['cls'];
    
    $r_detail_row = getQuery( [ 'table' => $table['reseller_homepage'] , 'where' => [ 'r_email' => $detail_row['reseller_id'] ] , 'single' => true ]);

    $logo_orig =  $r_detail_row['logo'];
    
    $sign = $r_detail_row['reseller_signature'];
    if($sign != ''){
        $signature = 'https://users.respicite.com/'.$sign;
    }
    else{
        $signature = "https://users.respicite.com/uploads/default-signature.jpg";
    }

    if( $logo_orig  != "" ){
        $logo = 'https://users.respicite.com/'.$logo_orig ;
    }
    else{
        $logo = 'https://users.respicite.com/uploads/default-logo.png';
    }

    // $detail_sql = "select * from ". $table['code_list']." where code='$code'";
    // $detail_res = mysqli_query($con,$detail_sql);
    // $detail_row = mysqli_fetch_array($detail_res);
    
    $requestVariantMap = getQuery( [ 'table' => $table['user_variant_tb'] , 'where' => [ 'requestId' => $detail_row['id'] ] , 'single' => true ]);
    
    $variantData = getQuery( [ 'table' => $table['solution_variation'] , 'where' => [ 'id' => $requestVariantMap['variationId'] ] , 'single' => true ]);
    
    $variantPages = getQuery( [ 'table' => $table['report_pages'] ]);
    
    
    $reportPageOrder = getQuery( [ 'select' => $table['report_pages'].'.page_name ,'.$table['report_template'].'.function_name' , 'table' => $table['report_template'] , 'where' => [ 'report_name' => $variantData['report_name'] ] , 'join' => [ [ $table['report_pages'] , $table['report_pages'].'.id = '.$table['report_template'].'.page_id', 'INNER' ] ] , 'order' => [ 'order_id' => 'ASC' ] ]);

    $pageNames = [];
    if( !empty( $reportPageOrder ) ){
        foreach ($reportPageOrder as $key => $value) {
            $pageNames[$value['page_name']] = $value['function_name'];
        }
    }

    // pre( $requestVariantMap , 1 );
    
    $detail_reseller = getQuery(  [ 'table' => $table['user_details'] , 'where' => [ 'email' =>  $detail_row['reseller_id'] ] , 'single' => true ] );
    
    // pre( $detail_reseller , 1 );

    // pre( [ $reportPageOrder , $pageNames , $variantPages ]);
    
    // Start Page 1 //
        $pagecount = $pdf->setSourceFile('report_template/UCE.pdf');
        $tpl = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    // End Start Page 1 //
    
    // pre( $pageNames , 1 );
    foreach( $pageNames as $page => $funcName ){
        $key = array_search($page, array_column($variantPages, 'page_name'));
        if( $key >= 0){
            $pageData = $variantPages[$key];
            if( $pageData['page_name'] == 'work_personality' || $pageData['page_name'] == 'work_personality_static' ){
                $pageData['page_name'] = 'work_personality_pages';
            }
            $functionName = $funcName;
            echo $functionName." - ".(str_replace('_', '-', $pageData['page_name'] ))." - ".$pageData['nature']."<br>";
            if( $pageData['nature'] == 'dynamic' ){
                // echo $pageData['page_name'];
                if( $pageData['page_name'] == 'work_personality_pages' ){
                    $pdf = $functionName(str_replace('_', '-', $pageData['page_name'] ) , 1 , $pageData['nature']);
                }
                else{
                    // echo $functionName;
                    // die;
                    $pdf = $functionName(str_replace('_', '-', $pageData['page_name']));
                }

            }
            elseif( $pageData['nature'] == 'static'  ){
                if( $pageData['page_name'] == 'work_personality_pages' ){
                    $pdf = $functionName(str_replace('_', '-', $pageData['page_name'] ) , $pageData['no_of_pages'] , $pageData['nature'] , 1 );
                }
                else{
                    $pdf = $functionName(str_replace('_', '-', $pageData['page_name'] ) , $pageData['no_of_pages'] , $pageData['nature'] );
                }
            }
            elseif($pageData['nature'] == 'static_perm'){
                $pdf = $functionName(str_replace('_', '-', $pageData['page_name'] ) , 1 , 'static' );
            }
        }
        
        // if( array_key_exists( $vPagesData['page_name'] , $pageNames ) ){
        // }
    }
    $GLOBALS['pdf'] = $pdf;
    // die;




    
// Start Page 2 //
    //Page to show counseler details included
    function client_details( $pageName ){
        $pdf = setSourceFile( $pageName );
        extract( $GLOBALS['detail_row'] );
        // pre( $GLOBALS['detail_row'] , 1 );
        $pdf->SetFont('arial');
        $pdf->SetFontSize('12');
        $pdf->SetXY(88,55);
        $pdf->Cell(20, 10,$name, 0, 0, 'L');
        $pdf->SetXY(88,66);
        $pdf->Cell(20, 10,$cls_nature.' '.$cls_type.' '.$cls, 0, 0, 'L');
        $pdf->SetXY(88,77);
        $pdf->Cell(20, 10,$dob, 0, 0, 'L');
        $pdf->SetXY(88,88);
        $pdf->Cell(20, 10,$gender, 0, 0, 'L');
        $pdf->SetXY(88,99);
        $pdf->Cell(20, 10,$mobile, 0, 0, 'L');
        $pdf->SetXY(88,110);
        $pdf->Cell(20, 10,$email, 0, 0, 'L');
        $pdf->SetXY(88,121);
        $pdf->Cell(20, 10,$address, 0, 0, 'L');
        $pdf->SetXY(88,139);
        $pdf->Cell(20, 10,$asignment_submission_date, 0, 0, 'L');
        $pdf->SetXY(88,182);
        $r2_detail_row = $GLOBALS['detail_reseller'];
        $r_detail_row = $GLOBALS['reseller_homepage'];
        // pre( $r2_detail_row , 1 );
        $r_name = $r2_detail_row['fullname'];
        $r_address = $r_detail_row['address'];
        $r_email = $r2_detail_row['email']; 
        $r_mobile = $r2_detail_row['mobile'];
        $pdf->Cell(20, 10,$r_name, 0, 0, 'L');
        $pdf->SetXY(88,199);
        $pdf->Cell(20, 10,$r_mobile, 0, 0, 'L');
        $pdf->SetXY(88,215);
        $pdf->Cell(20, 10,$r_email, 0, 0, 'L');
        $pdf->SetXY(88,234);
        $pdf->MultiCell(100, 7,  $r_address, 0, 'L');
        return $pdf;
    }
    // $pdf = client_details( 'client-details' );
// End Page 2 //

//  CUTTED Part
//  Start Page 3
    function careerExcellenceModel( $pageName ){
        $pdf = setSourceFile( $pageName );
        if($logo_orig){checking_size($logo,$pdf);}
        return $pdf;
    }
    // $pdf = careerExcellenceModel( 'career-excellence-model' );
//  End Page 3 //
    
//  Start Page 4 //
    function careerSituation($pageName){
        extract( $GLOBALS );
        $pdf = setSourceFile( $pageName );
        $para =  getQuery(  [ 'select' => 'sub_cat' , 'table' => $table['uce_part1'] , 'group_by' => [ 'sub_cat' ] ] );
        // pre( $para , 1 );
        // $para = [ 'Beliefs' , 'Awareness' , 'Information' ];
        $calculation = careerSituationLogic( $para );
        // pre( $calculation  , 1 );
        $pdf = careerSituationPrint( $calculation , $para );
        return $pdf;
    }

    function careerSituationPrint($calculation , $para){
        extract( $calculation );
        // pre( $para , 1 );
        $pdf = $GLOBALS['pdf'];
        $chartX=18;
        $chartY=205;
        //dimension
        $chartWidth=90;
        $chartHeight=50;
        //padding
        $chartTopPadding=10;
        $chartLeftPadding=10;
        $chartBottomPadding=10;
        $chartRightPadding=5;
        //chart box
        $chartBoxX=$chartX+$chartLeftPadding;
        $chartBoxY=$chartY+$chartTopPadding;
        $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
        $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
        // pre( [$chartBoxX ,  $chartBoxY , $chartBoxHeight , $chartBoxWidth ] , 1 );
        //bar width
        $barWidth=7;
        $data=Array(
            $para[0]['sub_cat'] => ['color'=>[150, 83, 52],'value'=>$per[0]],
            $para[1]['sub_cat'] => ['color'=>[150, 83, 52],'value'=>$per[1]],
            $para[2]['sub_cat'] => ['color'=>[150, 83, 52],'value'=>$per[2]]
        );
        // pre( $data , 1 );
        //data max
        $dataMax=0;
        foreach($data as $item){
            if($item['value']>$dataMax)$dataMax=100;
        }
        //data step
        $dataStep=20;
        //set font, line width, color
        $pdf->SetFont('Arial', '',9);
        $pdf->SetLineWidth(0);
        $pdf->SetDrawColor(0);
        // chart boundary
        // $pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
        // vertical axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY,
            $chartBoxX,
            $chartBoxY+$chartBoxHeight
        );
        //horizontal axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY+$chartBoxHeight,
            $chartBoxX+$chartBoxWidth,
            $chartBoxY+$chartBoxHeight
        );
        //vertical axis calculate chart y axis scale unit
        $yAxisUnits=$chartBoxHeight/$dataMax;
        //draw vertical y axis label
        for($i=0;$i<=$dataMax; $i+=$dataStep){
            //y position
            $yAxisPos=$chartBoxY+($yAxisUnits*$i);
            //draw y axis line
            $pdf->Line(
                $chartBoxX-2,
                $yAxisPos, 
                $chartBoxX,
                $yAxisPos
            );
            $pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
            //write label
            $pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
            
        }
        //horizontal axis set cell width
        $pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
        //cell width
        $xLabelWidth=$chartBoxWidth/ count($data);
        //loop horizontal axis and draw the bars
        $barXPos=0;
        foreach($data as $itemName=>$item){
            //print the label
            $pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
            //drawing the bar
            //bar color
            $pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
            //bar height
            $barHeight=$yAxisUnits*$item['value'];
            //bar x position
            $barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
            $barX=$barX-($barWidth/2);
            $barX=$barX+$chartBoxX;
            //bar y position
            $barY=$chartBoxHeight-$barHeight;
            $barY=$barY+$chartBoxY;
            //draw the bar
            $pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
            //increment $barXPos
            $barXPos++;
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->SetXY($chartX,$chartY);
        $row_inf_low = getQuery(  [ 'table' => 'inferences_table' , 'where' => [ 'CI' => 'Low' ] , 'single' => true ]);
        $row_inf_mid = getQuery(  [ 'table' => 'inferences_table' , 'where' => [ 'CI' => 'Medium' ] , 'single' => true ]);
        $row_inf_high = getQuery(  [ 'table' => 'inferences_table' , 'where' => [ 'CI' => 'High' ] , 'single' => true ]);
        // pre(  )
        if($per[1]<50)
        {
            $belief_inf = $row_inf_low['Beliefs'];
        }
        else if($per[1]>=50 && $per[1]<=75)
        {
            $belief_inf = $row_inf_mid['Beliefs'];
        }
        else if($per[1]>75)
        {
            $belief_inf = $row_inf_high['Beliefs'];
        }
        // FOr Beliefs : - 
            // pre( [ $belief_inf ] );
            $bel_inf_wc  = strlen($belief_inf);
            // echo  $bel_inf_wc ;
            // die;
            $bel_inf_wc_arr = [
                137 => [
                    'firstLineLength' => 36,
                    'belief_inf_y' => 210.5,
                    'fontsize' => 9,
                    'top' => 5,
                    'multiCellTop' => 7 
                ],
                195 => [
                    'firstLineLength' => 36,
                    'belief_inf_y' => 209.5,
                    'fontsize' => 9,
                    'top' => 4,
                    'multiCellTop' => 6
                ],
            ];

            foreach ($bel_inf_wc_arr as $key => $value) {
                if( $key >= $bel_inf_wc ){
                    $pickdata = $value;
                    break;
                }
            }

            extract(  $pickdata );

            $brl_word_array = explode( ' ' , $belief_inf );
            $new_bel_wc_index = 0;
            $new_bel_wc = '';
            foreach( $brl_word_array as $key => $word ){
                $new_bel_wc = $new_bel_wc." ".$word;
                if( strlen($new_bel_wc) > $firstLineLength ){
                    $new_bel_wc = substr( $new_bel_wc , 0 , strlen($new_bel_wc)-strlen($word) );
                    $new_bel_wc_index = $key - 1;
                    break;
                }
            }
            $pdf->SetFontSize($fontsize);
            $pdf->SetXY(132,$belief_inf_y);
            $pdf->CustomCell( 64,3,$new_bel_wc, 0, 1,'FJ',0 );
            $pdf->SetXY(111,$belief_inf_y+$top);
            $belief_inf = implode( ' ' , $brl_word_array );
            $pdf->MultiCell(84,$multiCellTop,substr($belief_inf , strlen($new_bel_wc) , strlen($belief_inf) ) ,0,'J');
        // End Belifs //
        //For Awareness inferences 
            if($per[0]<50)
            {
                $aw_inf = $row_inf_low['Awareness'];
            }
            else if($per[0]>=50 && $per[0]<=75)
            {
                $aw_inf = $row_inf_mid['Awareness'];
            }
            else if($per[0]>75)
            {
                $aw_inf = $row_inf_high['Awareness'];
            }
            

            // pre( [ $aw_inf ] , 1 );
            // $aw_inf = $aw_inf." ".substr( $aw_inf , 0 , 60 );
            $aw_inf_wc  = strlen($aw_inf);
            // echo  $aw_inf_wc ;
            // die;
            $aw_inf_wc_arr = [
                137 => [
                    'firstLineLength' => 24,
                    'aw_inf_y' => 234,
                    'fontsize' => 9,
                    'top' => 5,
                    'multiCellTop' => 7 
                ],
                195 => [
                    'firstLineLength' => 24,
                    'aw_inf_y' => 234,
                    'fontsize' => 9,
                    'top' => 4,
                    'multiCellTop' => 4
                ],
                257 => [
                    'firstLineLength' => 24,
                    'aw_inf_y' => 234,
                    'fontsize' => 8,
                    'top' => 4,
                    'multiCellTop' => 4
                ],
            ];

            foreach ($aw_inf_wc_arr as $key => $value) {
                if( $key >= $aw_inf_wc ){
                    $pickdata = $value;
                    break;
                }
            }

            extract(  $pickdata );

            $aw_word_array = explode( ' ' , $aw_inf );
            $new_aw_wc_index = 0;
            $new_aw_wc = '';
            foreach($aw_word_array as $key => $word){
                $new_aw_wc = $new_aw_wc." ".$word;
                if( strlen($new_aw_wc) > $firstLineLength ){
                    $new_aw_wc = substr( $new_aw_wc , 0 , strlen($new_aw_wc)-(strlen($word)+1) );
                    $new_aw_wc_index = $key - 1;
                    break;
                }
            }
            $pdf->SetFontSize($fontsize);
            $pdf->SetXY(150,$aw_inf_y);
            $pdf->CustomCell( 46,3,$new_aw_wc, 0, 1,'FJ',0 );
            $pdf->SetXY(111,$aw_inf_y+$top);
            $aw_inf = implode( ' ' , $aw_word_array );
            $pdf->MultiCell(84,$multiCellTop,substr($aw_inf , strlen($new_aw_wc) , strlen($aw_inf) ) ,0,'J');
        //End Awareness 
        
        //For Information inferences 
            if($per[2]<50)
            {
                $in_inf = $row_inf_low['Information'];
            }
            else if($per[2]>=50 && $per[2]<=75)
            {
                $in_inf = $row_inf_mid['Information'];
            }
            else if($per[2]>75)
            {
                $in_inf = $row_inf_high['Information'];
            }
            // $in_inf = $in_inf." ".substr( $in_inf , 0 , 60 );
            $in_inf_wc  = strlen($in_inf);
            // echo  $in_inf_wc ;
            // die;
            $in_inf_wc_arr = [
                137 => [
                    'firstLineLength' => 20,
                    'in_inf_y' => 258,
                    'fontsize' => 9,
                    'top' => 5,
                    'multiCellTop' => 7 
                ],
                195 => [
                    'firstLineLength' => 20,
                    'in_inf_y' => 258,
                    'fontsize' => 9,
                    'top' => 4,
                    'multiCellTop' => 4
                ],
                257 => [
                    'firstLineLength' => 20,
                    'in_inf_y' => 258,
                    'fontsize' => 8,
                    'top' => 4,
                    'multiCellTop' => 4
                ],
            ];

            foreach ($in_inf_wc_arr as $key => $value) {
                if( $key >= $in_inf_wc ){
                    $pickdata = $value;
                    break;
                }
            }

            extract(  $pickdata );

            $in_word_array = explode( ' ' , $in_inf );
            $new_in_wc_index = 0;
            $new_in_wc = '';
            foreach($in_word_array as $key => $word){
                $new_in_wc = $new_in_wc." ".$word;
                if( strlen($new_in_wc) > $firstLineLength ){
                    $new_in_wc = substr( $new_in_wc , 0 , strlen($new_in_wc)-(strlen($word)+1) );
                    $new_in_wc_index = $key - 1;
                    break;
                }
            }
            $pdf->SetFontSize($fontsize);
            $pdf->SetXY(156,$in_inf_y);
            $pdf->CustomCell( 40,3,$new_in_wc, 0, 1,'FJ',0 );
            $pdf->SetXY(111,$in_inf_y+$top);
            $in_inf = implode( ' ' , $in_word_array );
            $pdf->MultiCell(84,$multiCellTop,substr($in_inf , strlen($new_in_wc) , strlen($in_inf) ) ,0,'J');
            // pre( [ 'in_inf' => $in_inf , 'aw_inf' => $aw_inf , 'belief_inf' => $belief_inf ] , 1 );
            return $pdf; 
    }

    function careerSituationLogic( $para ){
        // pre( $para , 1 );
        extract( $GLOBALS ); 
        $cat_arr_02_24 = [];
        $count = count($para);
        for($i=0;$i<$count;$i++)
        {
            $score[$i]=0;
            $q_qty[$i]=0;
            $per[$i]=0;
            $cat_arr_02_24[] = $para[$i]['sub_cat'];
            $ansList = getQuery(  [ 'select' => $table['test_details'].'.*' , 'where' => [ 'sub_cat' => $para[$i]['sub_cat'] , 'code' => $code , 'solution' => 'uce_part1_1' ] , 'join' => [ [ $table['test_details'] , $table['test_details'].'.qno = '.$table['uce_part1'].'.qno' ,'INNER' ] ] , 'table' => $table['uce_part1'] ] );
            
            // pre( $ansList );
            foreach ($ansList as $key => $row4) {
                $ans = $row4['ans'];
                if($ans=='1'){$temp_ans = 4;}
                else if($ans=='2'){$temp_ans = 3;}
                else if($ans=='3'){$temp_ans = 1;}
                else if($ans=='4'){$temp_ans = 0;}
                $score[$i] = $score[$i] + $temp_ans;
                $q_qty[$i] = $q_qty[$i] + 1; 
            }

            $q_qty[$i] = $q_qty[$i] * 4;
            $per[$i] = $score[$i]*100 / $q_qty[$i];
        
        }
        // pre( [ 'count' => $count , 'cat_arr_02_24' => $cat_arr_02_24 , 'q_qty' => $q_qty , 'score' => $score , 'per' => $per ] , 1);
        return [ 'count' => $count , 'cat_arr_02_24' => $cat_arr_02_24 , 'q_qty' => $q_qty , 'score' => $score , 'per' => $per ];
    }
    // $pdf = careerSituation( 'career-situation-analysis' );
    /** VS **/
//  End Page 4 // 

//  Start Page 5 // 
    function workInterests($pageName){
        extract( $GLOBALS );
        $pdf = setSourceFile($pageName);
        if($logo_orig){checking_size($logo,$pdf);}
        $workIntrestCalculation = workICalculation();
        // setToGlobal($workIntrestCalculation);
        return workIPrint($workIntrestCalculation);
    }
    function workICalculation(){
        extract( $GLOBALS );
        // $p1_2_para  = getQuery( [ 'table' => $table['uce_part1_2'] , 'where' => [ 'asmt_variant_3' => 1 ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'group_by' => [ 'category' ] ] , 'category' );
        // pre( $p1_2_para );
        $p1_2_para  = [ 'R' , 'I' , 'A' , 'S' , 'E' , 'C' ];
        // pre( $p1_2_para  , 1);
        $p1_2_count = count($p1_2_para);
        for($i=0;$i<$p1_2_count;$i++)
        {
            $p1_2_score[$i]=0;
            $p1_2_q_qty[$i]=0;
            $p1_2_per[$i]=0;
            $p1_2_cat = $p1_2_para[$i];
            $p1_2_sql3  = getQuery( [ 'select' => 'ppe_part1_test_details.*' , 'table' => $table['uce_part1_2'] , 'where' => [ 'asmt_variant_3' => 1 , 'code' => $code , 'solution' => 'uce_part1_2' , 'category' => $p1_2_cat ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ $table['test_details'] , $table['test_details'].'.qno = '.$table['uce_part1_2'].'.qno' ,'INNER' ] ] ] );
            // pre(  $p1_2_sql3 , 1 );
            foreach ($p1_2_sql3 as $key => $p1_2_row4) {
                $ans = $p1_2_row4['ans'];
                if($ans=='1'){ $temp_ans = 0; }
                else if($ans=='2'){ $temp_ans = 1; }
                else if($ans=='3'){ $temp_ans = 3; }
                else if($ans=='4'){ $temp_ans = 4; }
                // pre(  [ $ans , $temp_ans ] );
                $p1_2_score[$i] = $p1_2_score[$i] + $temp_ans;
                $p1_2_q_qty[$i] = $p1_2_q_qty[$i] + 1; 
                // pre(  $p1_2_score );
            }
            // pre(  $p1_2_score , 1 );
            $p1_2_q_qty[$i] = $p1_2_q_qty[$i] * 4; 
            $p1_2_per[$i] = round($p1_2_score[$i]*100 / $p1_2_q_qty[$i],0);
        }
        // pre( [ 'p1_2_para' => $p1_2_para , 'p1_2_q_qty' => $p1_2_q_qty , 'p1_2_score' => $p1_2_score , 'p1_2_per' => $p1_2_per  ] , 1 );
        setToGlobal([ 'p1_2_para' => $p1_2_para , 'p1_2_q_qty' => $p1_2_q_qty , 'p1_2_score' => $p1_2_score , 'p1_2_per' => $p1_2_per  ]);
        return [ 'p1_2_para' => $p1_2_para , 'p1_2_q_qty' => $p1_2_q_qty , 'p1_2_score' => $p1_2_score , 'p1_2_per' => $p1_2_per  ];   
    }

    function workIPrint($workIntrestCalculation){
        extract( $workIntrestCalculation );
        $con = $GLOBALS['con'];
        // pre( $workIntrestCalculation , 1 );
        $p1_2_count = count($p1_2_para);
        $pdf = $GLOBALS['pdf'];
        $table = $GLOBALS['table'];
        $code = $GLOBALS['code'];
        // New Graph Verticle Code //
            $apart = 20;
            $total = 100;
            $chartX=35;
            $chartY=232;
            //dimension
            $chartWidth=80;
            $chartHeight=40;
            $chartTopPadding=0;
            $chartLeftPadding=0;
            $chartBottomPadding=0;
            $chartRightPadding=0;
            $chartBoxX=$chartX+$chartLeftPadding;
            $chartBoxY=$chartY+$chartTopPadding;
            $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
            $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
            $barWidth=7;
            $dataStep=20;
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetLineWidth(0);
            $pdf->SetDrawColor(0);
            $pdf->SetFillColor( '255' , '255' , '255' );
            $pdf->Line(
                $chartBoxX,
                $chartBoxY,
                $chartBoxX,
                $chartBoxY+$chartBoxHeight
            );
            $totalLines = ceil( $total/$apart );
            $chartIntoParts = ceil($chartWidth/$totalLines);
            $newVLineX = $chartBoxX ;
            $pdf->SetFont( 'Arial', 'B', 8 );
            $pdf->SetTextColor( 172,13,26 );
            $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
            $pdf->Cell(5,4, 0 , 0, 2,'L',false);
            for( $i=0; $i<$totalLines; $i++ ){
                //y position
                //draw  axis line
                $newVLineX = ( $newVLineX + $chartIntoParts );
                // pre( [ $newVLineX, $chartBoxY, $newVLineX, $chartBoxY+$chartBoxHeight ] );
                $pdf->Line(
                    $newVLineX,
                    $chartBoxY,
                    $newVLineX,
                    $chartBoxY+$chartBoxHeight
                );
                
                $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
                $pdf->Cell(5,4, (($i+1) * $apart) , 0, 2,'L',false);
            }
            $pdf->SetXY($chartX,$chartY);
            $start = $chartY;
            $y = $chartY+0.5;
            foreach( $p1_2_para as $k => $v){
                $step = round(( $chartWidth/$total )  , 2);
                $tg = $p1_2_per[$k];
                $pdf->SetXY( 5, $start );
                $start += 6.5;
                $pdf->Cell($chartX-10);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(($chartX),7,$v, 0, 2,'L',false);
                $x1 = $chartX;
                $x2 = $chartX;
                $pdf->SetFillColor( 152, 72, 6 );
                $pdf->SetTextColor(255 , 255 , 255);
                $pdf->SetXY($x1,$y);
                if( $tg <= $total && $tg > 0 ){
                    $x2 = $chartX + ( $step * $tg );
                    $pdf->Cell(( $step * $tg ),5,$tg, 0, 1,'C',1);
                }
                elseif( $tg > $total ){
                    $x2 = $chartX + ( $step * $total );
                    $pdf->Cell(( $step * $total ),5, $total." +", 0, 1,'C',1);
                }

                if( $tg == 0){
                    $pdf->SetXY($x1,$y);
                    $pdf->Cell($step,4, 0, 0, 1,'C',false);
                }
                $y = $y+6.5;
                $i++;
            }
        // New Graph Verticle Code //
        
        $get_score1 = getQuery( [ 'table' => 'top_value_db' , 'where' => [ 'code'  => $code , 'solution' => 'uce_part1_2'  ]  ] ); 
        // pre( $p1_2_para , 1 );
        if( empty( $get_score1 ) ){
            foreach ($p1_2_para as $key => $value) {
                $sc = $p1_2_per[$key];   
                $cat = $value; 
                echo "Interest Category :".$cat." , Value :".$sc."<br>";
                $sql_insert = "Insert into top_value_db(solution,code,category,per) values('uce_part1_2','$code','$cat','$sc')";
                mysqli_query($con,$sql_insert);
            }
        }
        // echo "1";
        // die;
        $pos = 240;
        $s = array();
        $cat_name=array();
        $cat_name['R'] = "Realistic";
        $cat_name['I'] = "Investigative";
        $cat_name['A'] = "Artistic";
        $cat_name['S'] = "Social";
        $cat_name['E'] = "Enterprising";
        $cat_name['C'] = "Conventional";

        $get_score = getQuery( [ 'table' => 'top_value_db' , 'where' => [ 'code'  => $code ] , 'order' => [ 'per' => 'DESC' ] , 'limit' => [ 0 , 3 ]  ] );

        foreach( $get_score as $key => $res_score_row ){
            $x= $res_score_row['category']."(".$cat_name[$res_score_row['category']].")";
            // echo $x;
            array_push($s,$res_score_row['category']);
            echo "Top Interest :".$x."<br>";
            $pdf->SetXY(132,$pos);
            $pdf->SetFont('arial','B',11);
            $pdf->SetTextColor(112,55,48);
            $pdf->Cell(0,20,$x, 0, 1,'C',false);
            $pos = $pos + 8;
        }
        // die;
        return $pdf;
    }
    // $pdf = workInterests( 'work-interests' );
//  End Page 5

//  Start page 6-7
    function work_interests_static($pageName , $noOfPage , $pageType ){
        return setSourceFile( $pageName , $noOfPage , $pageType );
    }
    // $pdf = work_interests_static( 'work-interests-static' , 2 , 'static' );
// End Start page 6-7
    
//  Start Page 8
    function work_values($pageName ){
        $pdf = setSourceFile( $pageName ); 
        work_values_calc();
        return work_values_print();
    }
    function work_values_calc(){
        extract( $GLOBALS );
        $sql_top = getQuery( [ 'select' => 'wls_part2_rank_ordring.grp, wls_part2_rank_ordring.qno,wls_part2_rank_ordring.ordr , wls_part2_1_detail.q_id' , 'table' => 'wls_part2_rank_ordring' , 'where' => [ 'code' => $code] , 'order' => [ 'wls_part2_rank_ordring.grp' => 'ASC' , 'wls_part2_rank_ordring.qno' => 'ASC'  ] , 'join' => [ [ 'wls_part2_1_detail' , 'wls_part2_1_detail.grp = wls_part2_rank_ordring.grp AND wls_part2_1_detail.qno = wls_part2_rank_ordring.qno' , 'INNER' ] ] ] );
        // pre( $sql_top , 1 );
        foreach ($sql_top as $key => $value) {
            if(!isset( $score[$value['q_id']] ) ){
                $score[$value['q_id']] = 0;
            }
            $temp_score = 6-$value['ordr'];
            $q_id = $value['q_id'];
            $score[$value['q_id']] = $score[$value['q_id']] + $temp_score;
        }
        // pre($score);
        $get_score1 = getQuery([ 'table' => 'temp_order_score' , 'where' => [ 'code' => $code ] ]);
        if( empty( $get_score1 ) ){
            foreach ( $score as $key => $value ) {
                $sql_insert = "insert into temp_order_score(q_id,code,score) values('".$key."','$code','$value')";
                mysqli_query($con,$sql_insert);
            }
        }
        else{
            foreach (  $score as $key => $value ) {
                $sql_update = "update temp_order_score SET score = '$value' where q_id='$key' AND code ='$code'";
                mysqli_query($con,$sql_update);
            }
        }
    }
    function work_values_print(){
        $code = $GLOBALS['code'];
        $pdf = $GLOBALS['pdf'];
        $i = 1;
        $sum_top_5_score = 0;
        $pos = 232;
        /*Heading */
        $pdf->SetXY(35,225);
        $pdf->SetFont('arial','B',16);
        // $pdf->SetTextColor(24,156,243);
        $pdf->SetTextColor(172,13,26);
        $pdf->MultiCell(100,5,"Your Top Needs");
        /* !Heading */
        $pdf->SetFont('arial','B',12);
        $get_score = getQuery( [ 'select' => 'sub_category , item , temp_order_score.q_id' , 'table' => 'temp_order_score' , 'where' => [ 'code' => $code  ] , 'limit' => [ 0 , 5 ] , 'order' => [ 'score' => 'DESC' ] , 'join' => [ [ 'wls_part2_2_detail' , 'wls_part2_2_detail.qno = temp_order_score.q_id' , 'INNER' ] ] ]);
        // pre( $get_score , 1 );
        foreach ($get_score as $key => $value){
            $pdf->SetXY(35,$pos);
            $pdf->SetFont('arial');
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(150,5,($key+1).'. '.$value['sub_category'].' - '.$value['item']);
            $y = $pdf->GetY();
            $pos = $y + 3;
        }

        $get_score = getQuery( [ 'select' => 'onet , qno , score' , 'table' => 'wls_part2_2_detail' , 'where' => [ 'temp_order_score.code' => $code  ] , 'join' => [ [ 'temp_order_score' , 'wls_part2_2_detail.qno = temp_order_score.q_id' ] ] ]);
        foreach ($get_score as $key => $value){
            if( !isset( $qqty[$value['onet']] ) ){
                $qqty[$value['onet']] = 0;
                $o_score[$value['onet']] = 0; 
                $o_per[$value['onet']] = 0;
                $f_score[$value['onet']] = 0;
            }
            $o_score[$value['onet']] = $o_score[$value['onet']] + $value['score'];
            $qqty[$value['onet']] = $qqty[$value['onet']] + 1;
        }
        $o_score[$value['onet']] = $o_score[$value['onet']] / $qqty[$value['onet']];
        $f_score[$value['onet']] = $o_score[$value['onet']]*100/30;
        // pre( $o_score );
        // pre( $f_score);
        setToGlobal([ 'o_score' => $o_score , 'f_score' => $f_score]);
        return $pdf;
    }
    // $pdf = work_values( 'work-values' );
//  End Page 8


//  Start Page 9 - 10
    function work_values_static($pageName , $noOfPage , $pageType ){
        return setSourceFile( $pageName , $noOfPage , $pageType );
    }
    // $pdf = work_values_static( 'work-values-static' , 2 , 'static' );
//  End Page 9 - 10

//  Start Page 11
    function work_personality_pages($pageName , $noOfPage , $pageType ){
        // pre( [ $pageName , $noOfPage , $pageType ] , 1 );
        $pdf = setSourceFile( $pageName  , $noOfPage , $pageType );
        $wPData = workPersonalityCalc();
        return workPersonalityPrint($wPData);
    }

    function workPersonalityCalc(){
        $code = $GLOBALS['code'];
        $res_p4 = getQuery( [ 'select' => 'qno , ans , solution' , 'table' => 'ppe_part1_test_details' , 'or_where' => [ [ 'code' => $code  , 'solution' => 'uce_part1_4_1' ] , [ 'code' => $code  , 'solution' => 'uce_part1_4_2' ] ]  ] );
        // pre( $res_p4 , 1 );
        $E = 0;
        $I = 0;
        $S = 0;
        $N = 0;
        $T = 0;
        $F = 0;
        $P = 0;
        $J = 0;
        foreach ($res_p4 as $key => $value) {
            $row_scr = getQuery( [ 'select' => 'Type , Points', 'table' => 'uce_part1_4_scoring' , 'where' => [ 'Q' => $value['qno'] , 'O' => $value['ans'] , 'part' => ( $value['solution'] == 'uce_part1_4_1' ? 'Part1' : 'Part2' ) ]  , 'single' => true ] );
            
            if($row_scr['Type']=='E')
            {
                $E=$E+$row_scr['Points'];
            }
            else if($row_scr['Type']=='I')
            {
                $I=$I+$row_scr['Points'];
            }
            else if($row_scr['Type']=='S')
            {
                $S=$S+$row_scr['Points'];
            }
            else if($row_scr['Type']=='N')
            {
                $N=$N+$row_scr['Points'];
            }
            else if($row_scr['Type']=='T')
            {
                $T=$T+$row_scr['Points'];
            } 
            else if($row_scr['Type']=='F')
            {
                $F=$F+$row_scr['Points'];
            }
            else if($row_scr['Type']=='P')
            {
                $P=$P+$row_scr['Points'];
            }
            else if($row_scr['Type']=='J')
            {
                $J=$J+$row_scr['Points'];
            }
        }
        // pre( [ 'E' => $E , 'I' => $I , 'S' => $S , 'N' => $N , 'T' => $T , 'F' => $F  , 'P' => $P , 'J' => $J ] , 1 );
        return [ 'E' => $E , 'I' => $I , 'S' => $S , 'N' => $N , 'T' => $T , 'F' => $F  , 'P' => $P , 'J' => $J ];

    }
    
    function workPersonalityPrint($wPData){
        extract( $wPData );       
        $pdf = $GLOBALS['pdf'];      
        $val_f = array();
        if($I>=$E)
        {
            $val1 = $I;
            $value1 = 'I';
            array_push($val_f,'I - Introversion');
        }
        else
        {
            $val1 = $E;
            $value1 = 'E';
            array_push($val_f,'E - Extraversion');
        }

        if($N>=$S)
        {
            $val2 = $N;
            $value2 = 'N';
            array_push($val_f,'N - Intuiting');
        }
        else
        {
            $val2 = $S;
            $value2 = 'S';
            array_push($val_f,'S - Sensing');
        }

        if($T>=$F)
        {
            $val3 = $T;
            $value3 = 'T';
            array_push($val_f,'T - Thinking');
        }
        else
        {
            $val3 = $F;
            $value3 = 'F';
            array_push($val_f,'F - Feeling');
        }

        if($P>=$J)
        {
            $val4 = $P;
            $value4 = 'P';
            array_push($val_f,'P - Perceiving');
        }
        else
        {
            $val4 = $J;
            $value4 = 'J';
            array_push($val_f,'J - Judging');
        }

        // $score_arr_part4_02_04 = [ 'E' => $E , 'I' => $I , 'S' => $S , 'N' => $N , 'T' => $T , 'F' => $F  , 'P' => $P , 'J' => $J ];
        // Logic Test uce v3 //
            echo "score <br>";
            // pre( $score_arr_part4_02_04 );
            
            echo "Personlity Code <br>";
            // pre( $val_f);
        // End Logic Test uce v3 //

        $pos = 130;
        foreach($val_f as $vl)
        {
            $pdf->SetXY(143,$pos);
            $pdf->SetTextColor(172,13,26);
            $pdf->MultiCell(150,6,$vl);
            $pos = $pos + 10;
        }

        $val1 = $val1/($I + $E);
        $val2 = $val2/($N + $S); 
        $val3 = $val3/ ($T + $F);
        $val4 = $val4/($P + $J); 

        $val1_per = $val1 * 100;

        $val2_per = $val2 * 100;

        $val3_per = $val3 * 100;

        $val4_per = $val4 * 100;

        $infre = array();
        if($val1_per<=75)
        {
            $val1_status = "Medium";
            
        }
        else
        {
            $val1_status = "High"; 
        }

        if($val2_per<=75)
        {
            $val2_status = "Medium";
            
        }
        else
        {
            $val2_status = "High"; 
        }

        if($val3_per<=75)
        {
            $val3_status = "Medium";
            
        }
        else
        {
            $val3_status = "High"; 
        }

        if($val4_per<=75)
        {
            $val4_status = "Medium";
            
        }
        else
        {
            $val4_status = "High"; 
        }
        $chartX=25;
        $chartY=125;
        //dimension
        $chartWidth=100;
        $chartHeight=50;
        //padding
        $chartTopPadding=10;
        $chartLeftPadding=10;
        $chartBottomPadding=10;
        $chartRightPadding=5;
        //chart box
        $chartBoxX=$chartX+$chartLeftPadding;
        $chartBoxY=$chartY+$chartTopPadding;
        $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
        $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
        //bar width
        $barWidth=7;

        $data=Array(
            $value1 =>['color'=>[172,13,26],'value'=>$val1_per],
            $value2 =>['color'=>[172,13,26],'value'=>$val2_per],
            $value3 =>['color'=>[172,13,26],'value'=>$val3_per],
            $value4 =>['color'=>[172,13,26],'value'=>$val4_per]
        );

        //data max
        $dataMax=0;
        foreach($data as $item){
            if($item['value']>$dataMax)$dataMax=100;
        } 
        //data step
        $dataStep=20;
        //set font, line width, color
        $pdf->SetFont('Arial', '',9);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0);
        //chart boundary
        // $pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
        //vertical axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY,
            $chartBoxX,
            $chartBoxY+$chartBoxHeight

        );
        //horizontal axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY+$chartBoxHeight,
            $chartBoxX+$chartBoxWidth,
            $chartBoxY+$chartBoxHeight

        );
        //vertical axis calculate chart y axis scale unit
        $yAxisUnits=$chartBoxHeight/$dataMax;
        //draw vertical y axis label
        for($i=0;$i<=$dataMax; $i+=$dataStep){
            //y position
            $yAxisPos=$chartBoxY+($yAxisUnits*$i);
            //draw y axis line
            $pdf->Line(
            $chartBoxX-2,
            $yAxisPos,
            $chartBoxX,
            $yAxisPos     
            );
            if($i==40)
            {
            $pdf->Line(
                $chartBoxX+85,
                $yAxisPos-1.85,
                $chartBoxX,
                $yAxisPos-1.85     
                    );  
            }
            else if($i==20)
            {
            $pdf->Line(
                $chartBoxX+85,
                $yAxisPos-1.85,
                $chartBoxX,
                $yAxisPos-1.85     
                    );  
            }
            else if($i==60)
            {
            $pdf->Line(
                $chartBoxX+85,
                $yAxisPos-3,
                $chartBoxX,
                $yAxisPos-3     
                    );  
            }
            //set cell position for y axis labels
            $pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
            //write label
            $pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
            
        }
            //horizontal axis set cell width
        $pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
        //cell width
        $xLabelWidth=$chartBoxWidth/ count($data);
        //loop horizontal axis and draw the bars
        $barXPos=0;
        foreach($data as $itemName=>$item){
            //print the label
            $pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
            //drawing the bar
            //bar color
            $pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
            //bar height
            $barHeight=$yAxisUnits*$item['value'];
            //bar x position
            $barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
            $barX=$barX-($barWidth/2);
            $barX=$barX+$chartBoxX;
            //bar y position
            $barY=$chartBoxHeight-$barHeight;
            $barY=$barY+$chartBoxY;
            //draw the bar
            $pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
            //increment $barXPos
            $barXPos++;
        }
        $pdf->SetFontSize('28','B');
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY(65,175);
        $pdf->Cell(20,5,$value1.$value2.$value3.$value4, 0, 0,'C',false);
        $pdf->SetFontSize('14','B');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFontSize('11','B');
        $pos = 215;
        $row_inf1 = getQuery( [ 'table' => 'uce_part1_4_inference' , 'where' => [ 'vlu' => $value1 ] , 'single' => true ] );
        $ref = $row_inf1['Statement_1'].", ".$row_inf1['Statement_2'];
        array_push($infre,array('E-I Dimension',$ref));

        $row_inf2 = getQuery( [ 'table' => 'uce_part1_4_inference' , 'where' => [ 'vlu' => $value2 ] , 'single' => true ] );
        $ref = $row_inf2['Statement_1'].", ".$row_inf2['Statement_2'];
        array_push($infre,array('S-N Dimension',$ref));

        $row_inf3 = getQuery( [ 'table' => 'uce_part1_4_inference' , 'where' => [ 'vlu' => $value3 ] , 'single' => true ] );
        $ref = $row_inf3['Statement_1'].", ".$row_inf3['Statement_2'];
        array_push($infre,array('T-F Dimension',$ref));

        $row_inf4 = getQuery( [ 'table' => 'uce_part1_4_inference' , 'where' => [ 'vlu' => $value4 ] , 'single' => true ] );
        $ref = $row_inf4['Statement_1'].", ".$row_inf4['Statement_2'];
        array_push($infre,array('J-P Dimension',$ref));

        // pre( $infre , 1 );
        foreach($infre as $inf){
            $pdf->SetXY(20,$pos+4);
            $pdf->MultiCell(170,5,$inf[0].' - '.$inf[1],0,'L',false); 
            $pos = $pdf->GetY();
        }
        return $pdf;
    }

    // $pdf = work_personality_pages( 'work-personality-pages' , 1 , 'dynamic' );
// End Page 11

// Start Page 12-13-14-15
    function work_personality_static( $pageName , $noOfPage , $pageType , $startFrom ){
        return setSourceFile( $pageName , $noOfPage , $pageType , $startFrom );
    }
    // $pdf = work_personality_static( 'work-personality-pages' , 5 , 'static'  , 1 );
    // Start Page 12-13-14-15
    
// Start Page 16
    function learning_preferences_new( $pageName ){
        setSourceFile( $pageName );
        $lPdata = learning_preferences_logic();
        return learning_preferences_print($lPdata);
        
    }
    function learning_preferences_logic(){
        $code = $GLOBALS['code'];
        $v = 0;
        $a = 0;
        $r = 0;
        $k = 0;
        // Logic Test Block UCE 3 //
        $p = 0;
        $f = 0;
        $scr_v_02_24 = 0; 
        $scr_a_02_24 = 0; 
        $scr_r_02_24 = 0; 
        $scr_k_02_24 = 0; 
        $scr_p_02_24 = 0; 
        $scr_f_02_24 = 0; 
        // End Logic Test Block UCE 3 //
        $max_scr_02_24 = 2;
        $uce_part1_5 = getQuery( [ 'table' => 'ppe_part1_test_details' , 'where' => [ 'code' => $code , 'solution' => 'uce_part1_5'] ] );
        foreach( $uce_part1_5 as $key => $row ){
            // SELECT uce_part1_5_scoring_v3.* , SUM(3 - ppe_part1_test_details.ans) as tmp_score, uce_part1_5_scoring_v3.code as scorecode FROM `ppe_part1_test_details` INNER JOIN uce_part1_5_scoring_v3 on ppe_part1_test_details.qno = uce_part1_5_scoring_v3.qno and ppe_part1_test_details.ans = uce_part1_5_scoring_v3.option WHERE `solution` LIKE 'uce_part1_5' AND `ppe_part1_test_details`.`code` IN ( 'Aminzor504165' ) GROUP BY uce_part1_5_scoring_v3.code; 
            $x = explode(',',$row['ans']);
            $tmp_scr = 3 - $row['ans'];
            pre( [ $row['qno'] , $row['ans'] , $tmp_scr ] );
            $ln = count($x);
            for($i=0;$i<$ln;$i++)
            {
                echo $tmp_scr ."<br>";
                $qno = $row['qno'];
                $option = $x[$i];
                $row2 = getQuery( [ 'table' => 'uce_part1_5_scoring_v3' , 'where' => [ 'qno' => $qno , 'option' => $option ] , 'single' => true ] , null  , 1 );
                pre( $row2 ); 
                $pcode = $row2['code'];
                if($pcode=='V')
                {
                    $v = $v +1;
                    $scr_v_02_24 +=$tmp_scr;
                }
                else if($pcode=='A')
                {
                    $a = $a +1;
                    $scr_a_02_24 +=$tmp_scr;
                }
                else if($pcode=='R')
                {
                    $r = $r +1;
                    $scr_r_02_24 +=$tmp_scr;
                }
                else if($pcode=='K')
                {
                    $k= $k +1;
                    $scr_k_02_24 +=$tmp_scr;
                }
                // Logic test block uce 3 //
                else if($pcode=='P')
                {
                    $p= $p +1;
                    $scr_p_02_24 +=$tmp_scr;
                }
                else if($pcode=='F')
                {
                    $f= $f +1;
                    $scr_f_02_24 +=$tmp_scr;
                }
                // End Logic test block uce 3 //
                pre( [ 'scr_v_02_24' => $scr_v_02_24 , 'scr_a_02_24' => $scr_a_02_24  , 'scr_r_02_24' => $scr_r_02_24 , 'scr_k_02_24' => $scr_k_02_24 , 'scr_p_02_24' => $scr_p_02_24 , 'scr_f_02_24' => $scr_f_02_24 ]);
                pre( [ 'v' => $v , 'a' => $a  , 'r' => $r , 'k' => $k , 'p' => $p , 'f' => $f ] );
                
            }
        }
        // Logic test block uce 3 //
        $v_per = $scr_v_02_24/($v*$max_scr_02_24)*100;
        $a_per = $scr_a_02_24/($a*$max_scr_02_24)*100;
        $r_per = $scr_r_02_24/($r*$max_scr_02_24)*100;
        $k_per = $scr_k_02_24/($k*$max_scr_02_24)*100;
        $p_per = $scr_p_02_24/($p*$max_scr_02_24)*100;
        $f_per = $scr_f_02_24/($f*$max_scr_02_24)*100;
        pre( [ 'v_per' => $v_per , 'a_per' => $a_per  , 'r_per' => $r_per , 'k_per' => $k_per , 'p_per' => $p_per , 'f_per' => $f_per ] );
        return [ 'v_per' => $v_per , 'a_per' => $a_per  , 'r_per' => $r_per , 'k_per' => $k_per , 'p_per' => $p_per , 'f_per' => $f_per ];
    }

    function learning_preferences_print($lPdata){
        extract( $lPdata );
        // pre( $lPdata , 1 );
        $pdf = $GLOBALS['pdf'];
        $chartX=15;
        $chartY=220;
        //dimension
        $chartWidth=95;
        $chartHeight=45;
        //padding
        $chartTopPadding=10;
        $chartLeftPadding=10;
        $chartBottomPadding=10;
        $chartRightPadding=5;
        //chart box
        $chartBoxX=$chartX+$chartLeftPadding;
        $chartBoxY=$chartY+$chartTopPadding;
        $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
        $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
        //bar width
        $barWidth=7;
        
        $data=Array(
            'V' =>['color'=>[172,13,26],'value'=>$v_per],
            'A' =>['color'=>[172,13,26],'value'=>$a_per],
            'R' =>['color'=>[172,13,26],'value'=>$r_per],
            'K' =>['color'=>[172,13,26],'value'=>$k_per],
            'P' =>['color'=>[172,13,26],'value'=>$p_per],
            'F' =>['color'=>[172,13,26],'value'=>$f_per]
        );
        // pre(  $data , 1);
        //data max
        $dataMax=0;
        foreach($data as $item){
            if($item['value']>$dataMax)$dataMax=100;
        } 
        //data step
        $dataStep=20;
        //set font, line width, color
        $pdf->SetFont('Arial', '',9);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0);
        //chart boundary
        // $pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
        //vertical axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY,
            $chartBoxX,
            $chartBoxY+$chartBoxHeight
        
        );
        //horizontal axis line
        $pdf->Line(
            $chartBoxX,
            $chartBoxY+$chartBoxHeight,
            $chartBoxX+$chartBoxWidth,
            $chartBoxY+$chartBoxHeight
        
        );
        //vertical axis calculate chart y axis scale unit
        $yAxisUnits=$chartBoxHeight/$dataMax;
        //draw vertical y axis label
        for($i=0;$i<=$dataMax; $i+=$dataStep){
            //y position
            $yAxisPos=$chartBoxY+($yAxisUnits*$i);
            //draw y axis line
            $pdf->Line(
            $chartBoxX-2,
            $yAxisPos,
            $chartBoxX,
            $yAxisPos     
            );
            if($i==40)
            {
            $pdf->Line(
                $chartBoxX+80,
                $yAxisPos-1.85,
                $chartBoxX,
                $yAxisPos-1.85     
                    );  
            }
            else if($i==20)
            {
            $pdf->Line(
                $chartBoxX+80,
                $yAxisPos-1.85,
                $chartBoxX,
                $yAxisPos-1.85     
                    );  
            }
            else if($i==60)
            {
            $pdf->Line(
                $chartBoxX+80,
                $yAxisPos-3,
                $chartBoxX,
                $yAxisPos-3     
                    );  
            }
            //set cell position for y axis labels
            $pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
            //write label
            $pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
            
        }
        //horizontal axis set cell width
        $pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
        //cell width
        $xLabelWidth=$chartBoxWidth/ count($data);
        //loop horizontal axis and draw the bars
        $barXPos=0;
        foreach($data as $itemName=>$item){
            //print the label
            $pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
            //drawing the bar
            //bar color
            $pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
            //bar height
            $barHeight=$yAxisUnits*$item['value'];
            //bar x position
            $barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
            $barX=$barX-($barWidth/2);
            $barX=$barX+$chartBoxX;
            //bar y position
            $barY=$chartBoxHeight-$barHeight;
            $barY=$barY+$chartBoxY;
            //draw the bar
            $pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
            //increment $barXPos
            $barXPos++;
        }
        return $pdf;
    }
    // $pdf = learning_preferences_new( 'learning-preferences-new' );
    //learning inpreference.
// End Page 16


// uce_var_3_sui_10 Start Page 3 //
    function suitability_9th_10th($pageName){
        $pdf = setSourceFile( $pageName );
        workICalculation();
        work_abilities_logic();
        $suitableData = suitable_career_identification( $pdf , $GLOBALS['code'],  $GLOBALS['p1_2_per'] , $GLOBALS['apt_value'] );
        $pdf = $GLOBALS['pdf'];
        suitability_9th_10th_logic($suitableData);
        $pdf = suitability_9th_10th_print();
        return $pdf;
    }

    function check_greater( $sui_a , $sui_b , $a_score , $b_score ){
        // pre( [ $sui_a , $sui_b , $a_score , $b_score ] );
        $check_sui = [
            'Top Choice' => [ 'Good Choice' , 'Optional' , 'Avoid' ],
            'Good Choice' => [ 'Optional' , 'Avoid' ],
            'Optional' => [ 'Optional' , 'Avoid' ]
        ];
        $check = false;
        if ( $sui_a == $sui_b ){
            if( $a_score > $b_score ){
                $check = true;
            }
        }
        elseif ( isset( $check_sui[$sui_a] ) ) {
            if( in_array( $sui_b , $check_sui[$sui_a] ) ){
                $check = true;
            }
        }
        elseif ($sui_a != $sui_b) {
            if( $a_score < $b_score ){
                $check = false;
            }
        }

        return $check;
    }

    function count_all_car_reco( $career_reco  , $first_car_reco){
        pre( [ $career_reco  , $first_car_reco ] );
        $choiceArr = [];
        $check  = false;
        foreach ($career_reco as $key => $value){
            if( !isset($choiceArr[$value['car_reco']]) ){
                $choiceArr[$value['car_reco']] = 1;
            }
            else{
                $choiceArr[$value['car_reco']] += 1;
            }
        }
        // pre( $choiceArr );
        if( isset( $choiceArr[$first_car_reco] ) ){
            if( $choiceArr[$first_car_reco] >=3 ){
                $check = TRUE;
            } 
        }
        // return true;
        // return 1;
        return $check;
    }

    function isBranding(){
        $check = false;
        $r2_detail_row = $GLOBALS['detail_reseller'];
        if( $r2_detail_row['user_id'] == 'minzor' ){
            $check = true;
        }

        return $check;
    }

    function makeInference( $inf_data , $table , $inf_check ){
        pre( $inf_data );
        pre( $inf_check );
        $inf_arr = [];
        foreach ($inf_data as $key => $value) { 
            pre( $key );
            // if( array_key_exists( $key , $inf_arr ) ){
                if( array_key_exists( $key , $inf_check ) ){
                    // echo 1;
                    if( $inf_check[$key] == 1 ){
                        $get_inf = getQuery( [ 'where' => [ 'tbl_name' => $table , 'col_name' => $key , 'col_inf_id' => $value  ] , 'table' => 'tbl_inf_sui' , 'single' => true ] );
                        if( !empty( $get_inf ) ){
                            $inf_arr[$key] = $get_inf['Inference'].".";
                        }
                    }
                }
                else{
                    // echo 2;
                    $get_inf = getQuery( [ 'where' => [ 'tbl_name' => $table , 'col_name' => $key , 'col_inf_id' => $value ] , 'table' => 'tbl_inf_sui' , 'single' => true ]);
                    if( !empty( $get_inf ) ){
                        $inf_arr[$key] = $get_inf['Inference'].".";
                    }
                }
            // }
        }
        // pre( $inf_arr , 1 );
        return $inf_arr;
    }


    function stream_check( $stream_a , $stream_b ){
        $check = false;
        if( $stream_a != $stream_b ){
            $check = true;
        }
        return $check;
    }

    function suitability_9th_10th_logic($suitableData){
        $is_a_greater_b  = 0 ; $is_b_count_all = 0; $is_inf_bd = 0; 
        extract( $suitableData );
        // pre( $arr_car_reco , 1 );
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        foreach( $arr_car_reco as $key => $value ){
            if( $value['profession_name'] == $requestVariantMapData['profession'] ){
                $suitablePrintArray['profession']  = $value['profession_name'];
                $suitablePrintArray['stream']  = $value['stream'];
                $suitablePrintArray['score']  = round($value['scr_car']*100/0.64 , 0);
                $suitablePrintArray['lvl_int']  = $value['lvl_int'];
                $suitablePrintArray['lvl_apt']  = $value['lvl_apt'];
                $suitablePrintArray['car_reco']  = $value['car_reco'];
                // $suitablePrintArray  = $value;
            }
        }
        $score = array_column($arr_car_reco , 'scr_car');
        array_multisort($score, SORT_DESC, $arr_car_reco);
        // pre( $arr_car_reco );
        $index = 0;
        $firstCareer = [];
        foreach($arr_car_reco as $key => $value){
            if( $index < 4 ){
                if( $index == 0 ){
                    $firstCareer = $value;
                }
                $filter_car[$key] = $value;
            }
            $index++;
        }

        // pre( $suitablePrintArray );
        // pre( $filter_car );
        $is_a_greater_b =  check_greater( $suitablePrintArray['car_reco'] , $firstCareer['car_reco'] ,  $suitablePrintArray['score'] , round($firstCareer['scr_car']*100/0.64 , 0) );
        $inf_b_count_all_id = count_all_car_reco( $filter_car , $firstCareer['car_reco'] );
        $inf_bd_id = isBranding();
        $where = [
            'sui_a' => $suitablePrintArray['car_reco'],
            'sui_b' => $firstCareer['car_reco'],
            // 'is_possible' => 1 , 
            'is_a_greater_b' => ( $is_a_greater_b != '' ? $is_a_greater_b : 0 )
        ];
        
        $inf_check['inf_b_count_all_id'] = $inf_b_count_all_id; 
        $inf_check['inf_bd_id'] = $inf_bd_id; 
        
        $getInf = getQuery( 
            [
                'where' => $where , 
                'table' => 'tbl_uce_var_3_sui_10',
                'single' => true
            ]
            // , null , 1 , 1
        );
        $inf_final = '';
        if( !empty( $getInf ) ){
            $inf_final = implode( ' ',  array_values( makeInference( $getInf , 'tbl_uce_var_3_sui_10' , $inf_check ) ) );
        }
        // pre( [ 'suitablePrintArray' => $suitablePrintArray , 'filter_car' => $filter_car , 'inf_final' => $inf_final ] , 1 );
        setToGlobal( [ 'suitablePrintArray' => $suitablePrintArray , 'filter_car' => $filter_car , 'inf_final' => $inf_final ] );
    }
    function suitability_9th_10th_print(){
        $pdf = $GLOBALS['pdf'];
        $suitablePrintArray = $GLOBALS['suitablePrintArray'];
        $inf_final = $GLOBALS['inf_final'];
        $filter_car = $GLOBALS['filter_car'];
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        $currentStream = $requestVariantMapData['currentStream'];
        // pre(  $suitablePrintArray );
        // pre(  $filter_car , 1 );
        
        $pdf->SetTextColor(172,13,26);
        $pdf->SetFont('arial','B',17);
        $pdf->SetXY( 100 , 55 );
        $f_score = ( $suitablePrintArray['score'] > 100 ) ? 100 : $suitablePrintArray['score'];
        $pdf->MultiCell( 100, 5, $f_score );
        $pdf->SetFont( 'arial' , 'B' , 16 );
        $pdf->SetXY( 20 , 77 );
        $pdf->MultiCell( 170 ,5, $suitablePrintArray['profession'].' ('.$suitablePrintArray['stream'].')' , 0 , 'C' );

        $pdf->SetXY( 154 , 91 );
        $pdf->MultiCell(100,5, $suitablePrintArray['car_reco']);
        
        $pdf->SetFont('arial','B',16);
        $lvl_int = ( $suitablePrintArray['lvl_int'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_int'] == 'H' ? 'High' : 'Medium' ) );
        $lvl_apt = ( $suitablePrintArray['lvl_apt'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_apt'] == 'H' ? 'High' : 'Medium' ) );
        $pdf->SetXY( 40 , 135 );
        $pdf->Cell( 30,5, $lvl_int , 0 , 1 , 'R');
        $pdf->SetXY( 107 , 135 );
        $pdf->Cell( 30,5, $lvl_apt , 0 , 1 , 'C');
        // $pdf->Cell( 20,5, 'Medium' , 0 , 1 , 'R');
        $index = 0;
        $posy = 188;
        $pdf->SetFont('arial','B',10);
        $careers_exp = $GLOBALS['suitable_exp']['suitable_10th'];
        if( !empty( $filter_car ) ){
            foreach( $filter_car as $key => $value ){
                if( $value['profession_name'] != $suitablePrintArray['profession'] ){
                    if( $index < 3 ){
                        $cellHeight = 4;
                        if( strlen($value['profession_name']) < 50 && $index == 0 ){
                            $cellHeight = 7;
                        }
                        $pdf->SetXY( 13 , $posy );
                        $pdf->MultiCell(90, $cellHeight,  ucfirst($value['profession_name']));
                        $pdf->SetXY( 113 , $posy );
                        $pdf->MultiCell(50, 7,  ucfirst($value['stream']));
                        $pdf->SetXY( 155 , $posy );
                        // $score = round($value['car_reco']*100/0.64 , 0);
                        $score = $value['car_reco'];
                        // $pdf->MultiCell(30, 7,  ($score > 100 ? 100 : $score));
                        $pdf->MultiCell(30, 7,  $score);
                        $posy = $posy + 7.5;
                        $index++;
                    }
                }
            }
        }
        else{
            $pdf->SetXY( 13 , $posy );
            $pdf->MultiCell(90, 7,  $careers_exp['career_list']);
            $pdf->SetXY( 120 , $posy );
            $pdf->MultiCell(50, 6,  $currentStream );
        }
        // pre($careers_exp , 1);
        if( $inf_final == ''){
            $inf_final = $careers_exp['inf'];
        }
        $pdf->SetFont('arial','B',12);
        if( strlen($inf_final) > 500 ){
            $pdf->SetFont('arial','B',10);
        }
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY( 20 , 228 );
        $pdf->MultiCell(170,6,$inf_final);
        return $pdf;
    }
// End uce_var_3_sui_10 Start Page 3 //

// uce_var_3_sui_12_with_change Start Page 4 //
    
    function uce_var_3_sui_12_with_change($pageName){
        // pre( $pageName , 1 );
        $pdf = setSourceFile( $pageName );
        workICalculation();
        work_abilities_logic();
        $suitableData = suitable_career_identification( $pdf , $GLOBALS['code'],  $GLOBALS['p1_2_per'] , $GLOBALS['apt_value'] );
        // pre( $suitableData , 1 ); 
        $pdf = $GLOBALS['pdf'];
        suitability_12_logic($suitableData);
        suitability_12_print();
        return $pdf;
    }

    function suitability_12_logic($suitableData){
        extract( $suitableData );
        $arr_career = $GLOBALS['arr_career'];
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        $currentStream = $requestVariantMapData['currentStream'];
        $changeStream = json_decode( $requestVariantMapData['changeStream'] );
        $Vprofession = $requestVariantMapData['profession'];
        // pre( [ $requestVariantMapData , $arr_car_reco ]  , 1 );
        if( !empty( $arr_car_reco ) ){
            // pre( $Vprofession );
            // pre( $arr_car_reco  , 1 );
            foreach( $arr_car_reco as $key => $value ){
                if(  $value['profession_name']  ==  $Vprofession ){
                    $suitablePrintArray['profession']  = $value['profession_name'];
                    $suitablePrintArray['stream']  = $value['stream'];
                    $suitablePrintArray['score']  = round($value['scr_car']*100/0.64 , 0);
                    $suitablePrintArray['lvl_int']  = $value['lvl_int'];
                    $suitablePrintArray['lvl_apt']  = $value['lvl_apt'];
                    $suitablePrintArray['car_reco']  = $value['car_reco'];
                    // $suitablePrintArray  = $value;
                }
            }
        }
        // pre( $arr_career , 1 );

        $filter_car = [];
        $firstCareer = [];
        $index = 0;
        foreach ($arr_career as $choices => $value) {
            if( $index < 3  ){
                foreach ($value[$currentStream] as $k => $v){
                    if( $index < 3  ){
                        if( !empty( $v ) ){
                            if( $index < 3  ){
                                if( $v['careerName'] !=  $Vprofession){
                                    $v[ 'stream' ] =  $currentStream;
                                    if( $index == 0 ){
                                        $firstCareer =  $v;
                                    }
                                    $filter_car[]  = $v ;
                                    $index++;
                                }
                            }
                        }
                    }
                }
            }
        }
       
        $secondCareer = [];
        $change_filter_car_list = [];
        foreach ($changeStream as $career){
            foreach ($arr_career as $choices => $value) {
                foreach ($value[$career] as $k => $v){
                    if( !empty( $v ) ){
                        if( $v['careerName'] !=  $Vprofession){
                            $v[ 'stream' ] =  $career;
                            $change_filter_car_list[]  = $v ;
                        }
                    }
                }
            }
        }
        $score = array_column($change_filter_car_list, 'careerScore');
        array_multisort($score, SORT_DESC, $change_filter_car_list);
        
        $secondCareer = [];
        $change_filter_car = [];
        $changeIndex = 0 ; 
        foreach($change_filter_car_list as $key => $value){
            if( $changeIndex < 4 ){
                if( $changeIndex == 0 ){
                    $secondCareer = $value;
                }
                $change_filter_car[$key] = $value;
            }
            $changeIndex++;
        }
        // pre($suitablePrintArray ); 
        // pre($firstCareer ); 
        // pre($filter_car ); 
        // pre($secondCareer ); 
        // pre($change_filter_car ); 
        // $is_a_greater_b =  check_greater( $suitablePrintArray['car_reco'] , $firstCareer['car_reco'] ,  $suitablePrintArray['score'] , round($firstCareer['scr_car']*100/0.64 , 0) );
        $inf_b_count_all_id = count_all_car_reco( $filter_car , $firstCareer['car_reco'] );
        $inf_c_count_all_id = count_all_car_reco( $change_filter_car , $secondCareer['car_reco'] );
        $inf_bd_id = isBranding();
        // pre( [$suitablePrintArray['stream'] , $firstCareer['stream']] );
        $inf_a_str_chg_id = stream_check( $suitablePrintArray['stream'] , $firstCareer['stream'] );
        $where = [
            'sui_a' => $suitablePrintArray['car_reco'],
            'sui_b' => $firstCareer['car_reco'],
            'sui_c' => $secondCareer['car_reco'],
            // 'is_possible' => 1 , 
            // 'is_a_greater_b' => ( $is_a_greater_b != '' ? $is_a_greater_b : 0 )
        ];
        $inf_check['inf_bc_all_id'] = '';
        $inf_check['inf_b_all_id'] = ''; 
        $inf_check['inf_c_all_id'] = ''; 
        if( $inf_b_count_all_id == 1  && $inf_c_count_all_id == 1 ){
            $inf_check['inf_bc_all_id'] = 1;
        }
        else{
            if( $inf_b_count_all_id == 1 ){
                $inf_check['inf_b_all_id'] = 1; 
            }
            
            if( $inf_c_count_all_id == 1 ){
                $inf_check['inf_c_all_id'] = 1; 
            }
        }

        // pre( $inf_check );


        if( $inf_bd_id == 1 ){
            $inf_check['inf_bd_id'] = 1; 
        }
        
        $getInf = getQuery( 
            [ 
                'where' => $where , 
                'table' => 'tbl_uce_var_3_sui_12_with_change',
                'single' => true
            ] 
            // , null , 1 , 1 
        );
        $inf_final = '';
        if( !empty( $getInf ) ){
            // pre( $getInf  , 1);
            $inf_final = implode( ' ',  array_values( makeInference( $getInf , 'tbl_uce_var_3_sui_12_with_change' , $inf_check ) ) );
        }
        // pre( [  'inf_b_count_all_id' => $inf_b_count_all_id , 'inf_c_count_all_id' => $inf_c_count_all_id , 'inf_bd_id' => $inf_bd_id ,  'inf_a_str_chg_id' => $inf_a_str_chg_id , 'inf_final' => $inf_final ] , 1 );

        // pre( [ 'suitablePrintArray' => $suitablePrintArray , 'filter_car' => $filter_car , 'change_filter_car' => $change_filter_car ] , 1 );

        setToGlobal( [ 'suitablePrintArray' => $suitablePrintArray , 'filter_car' => $filter_car , 'change_filter_car' => $change_filter_car , 'inf_final' => $inf_final ] );

    }

    function suitability_12_print(){
        $pdf = $GLOBALS['pdf'];
        $suitablePrintArray = $GLOBALS['suitablePrintArray'];
        $inf_final = $GLOBALS['inf_final'];
        $filter_car = $GLOBALS['filter_car'];
        $change_filter_car = $GLOBALS['change_filter_car'];
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        $currentStream = $requestVariantMapData['currentStream'];
        $changeStream = json_decode( $requestVariantMapData['changeStream'] );
        // pre( $suitablePrintArray );
        // pre( $filter_car );
        // pre( $change_filter_car , 1 );
        $pdf->SetFont( 'arial','B',17 );
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY( 100 , 51 );
        $f_score = ( $suitablePrintArray['score'] > 100 ) ? 100 : $suitablePrintArray['score'];
        $pdf->MultiCell(100,5,$f_score);
        $pdf->SetFont( 'arial','B',16 );
        $pdf->SetXY( 20 , 73 );
        $pdf->MultiCell( 170,5, $suitablePrintArray['profession'].' ('.$suitablePrintArray['stream'].')' , 0 , 'C');

        $pdf->SetXY( 152 , 87 );
        $pdf->Cell(100,5, $suitablePrintArray['car_reco'] , 0 , 1 , 'L');

        $lvl_int = ( $suitablePrintArray['lvl_int'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_int'] == 'H' ? 'High' : 'Medium' ) );
        $lvl_apt = ( $suitablePrintArray['lvl_apt'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_apt'] == 'H' ? 'High' : 'Medium' ) );
        $pdf->SetXY( 40 , 122 );
        $pdf->Cell( 30,5, $lvl_int , 0 , 1 , 'R');
        
        $pdf->SetXY( 104 , 122 );
        $pdf->Cell( 26,5, $lvl_apt , 0 , 1 , 'C');
        $index = 0;
        $posy = 159;
        $pdf->SetFont('arial','B',10);
        // pre( $filter_car , 1);
        $careers_exp = $GLOBALS['suitable_exp']['with_change'];
        if( !empty( $filter_car ) ){
            foreach( $filter_car as $key => $value ){
                // pre( [ $value['careerName'] , $suitablePrintArray['profession'] ] ) ;
                if( $value['careerName'] != $suitablePrintArray['profession'] ){
                    if( $index < 3 ){
                        $cellHeight = 4;
                        if( strlen($value['careerName']) < 50 && $index == 0 ){
                            $cellHeight = 7;
                        }
                        $pdf->SetXY( 13 , $posy );
                        $pdf->MultiCell(90, $cellHeight,  ucfirst($value['careerName']));
                        $pdf->SetXY( 123 , $posy );
                        $pdf->MultiCell(50, 7,  ucfirst($value['stream']));
                        $pdf->SetXY( 168 , $posy );
                        // $score = round($value['car_reco']*100/0.64 , 0);
                        $score = $value['car_reco'];
                        // $pdf->MultiCell(30, 7,  ($score > 100 ? 100 : $score));
                        $pdf->MultiCell(30, 7,  $score);
                        $posy = $posy + 7.5;
                        $index++;
                    }
                }
            }
        }
        else{  
            $pdf->SetXY( 13 , $posy );
            $pdf->MultiCell(90, 7,  $careers_exp['career_list']);
            $pdf->SetXY( 120 , $posy );
            $pdf->MultiCell(50, 6,  $currentStream );
        }
        
        // die;
        $posy  =  204;
        if( !empty( $change_filter_car ) ){
            $changeindex = 0;
            foreach( $change_filter_car as $key => $value ){
                if( $value['careerName'] != $suitablePrintArray['profession'] ){
                    if( $changeindex < 3 ){
                        $cellHeight = 4;
                        if( strlen($key) < 50 && $index == 0 ){
                            $cellHeight = 7;
                        }
                        $pdf->SetXY( 13 , $posy );
                        $pdf->MultiCell(90, $cellHeight,  ucfirst($value['careerName']));
                        $pdf->SetXY( 123 , $posy );
                        $pdf->MultiCell(50, 7,  ucfirst($value['stream']));
                        $pdf->SetXY( 168 , $posy );
                        // $score = round($value['car_reco']*100/0.64 , 0);
                        $score = $value['car_reco'];
                        // $pdf->MultiCell(30, 7,  ($score > 100 ? 100 : $score));
                        $pdf->MultiCell(30, 7,  $score);
                        $posy = $posy + 7.5;
                        $changeindex++;
                    }
                }
            }
        }
        else{
            $pdf->SetXY( 13 , $posy );
            $pdf->MultiCell(90, 7,  $careers_exp['career_list']);
            $pdf->SetXY( 120 , $posy );
            $pdf->MultiCell(50, 6,  implode( ', ', $changeStream ) );
        }
        if( $inf_final == ''){
            $inf_final = $careers_exp['inf'];
        }
        $pdf->SetFont('arial','B',12);
        if( strlen($inf_final) > 500 ){
            $pdf->SetFont('arial','B',10);
        }
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY( 15 , 246 );
        $pdf->MultiCell(180,5,$inf_final);
        return $pdf;
    }

// End uce_var_3_sui_10 Start Page 4 //

// uce_var_3_sui_12_without_change Start Page 4 //
    
    function uce_var_3_sui_12_without_change($pageName){
        // pre( $pageName , 1 );
        $pdf = setSourceFile( $pageName );
        workICalculation();
        work_abilities_logic();
        $suitableData = suitable_career_identification( $pdf , $GLOBALS['code'],  $GLOBALS['p1_2_per'] , $GLOBALS['apt_value'] );
        $pdf = $GLOBALS['pdf'];
        // pre( $suitableData['arr_car_reco'] , 1 );
        suitability_12_without_logic($suitableData);
        suitability_12_without_print();
        return $pdf;
    }
    
    function suitability_12_without_logic($suitableData){
        extract( $suitableData );
        $arr_career = $GLOBALS['arr_career'];
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        $currentStream = $requestVariantMapData['currentStream'];
        $Vprofession = $requestVariantMapData['profession'];
        // pre( $requestVariantMapData );
        if( !empty( $arr_car_reco ) ){
            // echo 'profession_name :-'.$value['profession_name'];
            // pre( $requestVariantMapData  );
            // pre( $arr_car_reco , 1 );
            foreach( $arr_car_reco as $key => $value ){
                if(  $value['profession_name']  ==  $Vprofession ){
                    $suitablePrintArray['profession']  = $value['profession_name'];
                    $suitablePrintArray['stream']  = $value['stream'];
                    $suitablePrintArray['score']  = round($value['scr_car']*100/0.64 , 0);
                    $suitablePrintArray['lvl_int']  = $value['lvl_int'];
                    $suitablePrintArray['lvl_apt']  = $value['lvl_apt'];
                    $suitablePrintArray['car_reco']  = $value['car_reco'];
                    // $suitablePrintArray  = $value;
                }
            }
        }
        // pre( $suitablePrintArray , 1 );

        $filter_car = [];
        $firstCareer = [];
        $index = 0;
        foreach ($arr_career as $choices => $value) {
            if( $index < 3  ){
                foreach ($value[$currentStream] as $k => $v){
                    if( $index < 3  ){
                        if( !empty( $v ) ){
                            if( $index < 3  ){
                                if( $v['careerName'] !=  $Vprofession){
                                    $v[ 'stream' ] =  $currentStream;
                                    $filter_car[]  = $v ;
                                    if( $index == 0 ){
                                        $firstCareer =  $v;
                                    }
                                    $index++;
                                }
                            }
                        }
                    }
                }
            }
        }
        // pre( $firstCareer );
        $is_a_greater_b =  check_greater( $suitablePrintArray['car_reco'] , $firstCareer['car_reco'] ,  $suitablePrintArray['score'] , round($firstCareer['scr_car']*100/0.64 , 0) );
        $inf_b_count_all_id = count_all_car_reco( $filter_car , $firstCareer['car_reco'] );
        $inf_bd_id = isBranding();
        $inf_a_str_diff = stream_check( $suitablePrintArray['stream'] , $firstCareer['stream'] );
        // echo 'this : - '.$inf_b_count_all_id ; 
        // die;
        $where = [
            'sui_a' => $suitablePrintArray['car_reco'],
            'sui_b' => $firstCareer['car_reco'],
            // 'is_possible' => 1 , 
            'is_a_greater_b' => ( $is_a_greater_b != '' ? $is_a_greater_b : 0 )
        ];
        
        $inf_check['inf_b_count_all_id'] = $inf_b_count_all_id; 
        
        $inf_check['inf_bd_id'] =  $inf_bd_id;
        
        $inf_check['inf_a_str_diff'] =  $inf_a_str_diff;

        // if( $inf_a_str_diff == 1 ){
        //     $where['inf_a_str_diff'] = ''; 
        // }
        // else{
        //     $where['inf_a_str_diff'] = '1'; 
        // }
        
        $getInf = getQuery(
            [ 
                'where' => $where , 
                'table' => 'tbl_uce_var_3_sui_12_without_change',
                'single' => true
            ]
            , null , 1
        );
        $inf_final = '';
        if( !empty( $getInf ) ){
            $inf_final = implode( ' ',  array_values( makeInference( $getInf , 'tbl_uce_var_3_sui_12_without_change' ,  $inf_check ) ) );
        }
        // pre( [ 'is_a_greater_b' => $is_a_greater_b , 'inf_b_count_all_id' => $inf_b_count_all_id , 'inf_bd_id' => $inf_bd_id ,  'inf_a_str_diff' => $inf_a_str_diff , 'inf_final' => $inf_final ] , 1 );
        // pre( [ 'is_a_greater_b' => $is_a_greater_b , 'inf_b_count_all_id' => $inf_b_count_all_id , 'inf_bd_id' => $inf_bd_id ,  'inf_a_str_diff' => $inf_a_str_diff ] , 1 );
        setToGlobal( [ 'suitablePrintArray' => $suitablePrintArray , 'filter_car' => $filter_car , 'inf_final' => $inf_final ] );
    }

    function suitability_12_without_print(){
        $pdf = $GLOBALS['pdf'];
        $suitablePrintArray = $GLOBALS['suitablePrintArray'];
        $inf_final = $GLOBALS['inf_final'];
        $filter_car = $GLOBALS['filter_car'];
        $requestVariantMapData = $GLOBALS['requestVariantMap'];
        $currentStream = $requestVariantMapData['currentStream'];
        // echo 'Hello';
        // pre( $currentStream , 1 );
        // pre( $suitablePrintArray );
        $pdf->SetFont( 'arial','B',17 );
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY( 100 , 53 );
        $f_score = ( $suitablePrintArray['score'] > 100 ) ? 100 : $suitablePrintArray['score'];
        $pdf->MultiCell( 100 ,5,$f_score);
        $pdf->SetFont( 'arial','B',16 );
        $pdf->SetXY( 20 , 76 );
        $pdf->MultiCell( 170 , 5, $suitablePrintArray['profession'].' ('.$suitablePrintArray['stream'].')' , 0 , 'C');

        $pdf->SetXY( 151 , 91 );
        $pdf->MultiCell(100,5, $suitablePrintArray['car_reco']);

        $pdf->SetXY( 45 , 135 );
        $lvl_int = ( $suitablePrintArray['lvl_int'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_int'] == 'H' ? 'High' : 'Medium' ) );
        $lvl_apt = ( $suitablePrintArray['lvl_apt'] == 'L' ? 'Low' : ($suitablePrintArray['lvl_apt'] == 'H' ? 'High' : 'Medium' ) );
        $pdf->Cell( 30,5, $lvl_int , 0 , 1 , 'R');
        
        $pdf->SetXY( 109 , 135 );
        $pdf->Cell( 30,5, $lvl_apt , 0 , 1 , 'C');
        $index = 0;
        $posy = 188;
        $pdf->SetFont('arial','B',10);
        if( !empty( $filter_car ) ){
            foreach( $filter_car as $key => $value ){
                if( $value['careerName'] != $suitablePrintArray['profession'] ){
                    if( $index < 3 ){
                        $cellHeight = 4;
                        if( strlen($value['careerName']) < 50 && $index == 0 ){
                            $cellHeight = 6;
                        }
                        $pdf->SetXY( 13 , $posy );
                        $pdf->MultiCell(90, $cellHeight,  ucfirst($value['careerName']));
                        $pdf->SetXY( 120 , $posy );
                        $pdf->MultiCell(50, 6,  ucfirst($value['stream']));
                        $pdf->SetXY( 161 , $posy );
                        // $score = round($value['car_reco']*100/0.64 , 0);
                        $score = $value['car_reco'];
                        // $pdf->MultiCell(30, 6,  ($score > 100 ? 100 : $score));
                        $pdf->MultiCell(30, 6,  $score);
                        if( strlen($value['careerName']) > 50 && $index == 0 ){
                            $posy = $posy + 8.5;
                        }
                        else{
                            $posy = $posy + 7.5;
                        }
                        $index++;
                    }
                }
            }
        }
        else{
            $careers_exp = $GLOBALS['suitable_exp']['without_change'];
            $pdf->SetXY( 13 , $posy );
            $pdf->MultiCell(90, 7,  $careers_exp['career_list']);
            $pdf->SetXY( 120 , $posy );
            $pdf->MultiCell(50, 6,  $currentStream );
        }
        // pre($careers_exp , 1);
        if( $inf_final == ''){
            $inf_final = $careers_exp['inf'];
        }
        $pdf->SetFont('arial','B',12);
        if( strlen($inf_final) > 500 ){
            $pdf->SetFont('arial','B',10);
        }
        $pdf->SetTextColor(172,13,26);
        $pdf->SetXY( 20 , 228 );
        $pdf->MultiCell(175,6,$inf_final);
        return $pdf;
    }
// End uce_var_3_sui_12_without_change Start Page 4 //

// uce_var_3_sui_10 Start Page 4 //
    function brand_page_first($pageName){
        $detail_reseller = $GLOBALS['detail_reseller'];
        $pdfUrl = $GLOBALS['pdfUrl'];
        $pdf = $GLOBALS['pdf'];
        if( file_exists($pdfUrl.(str_replace('_', '-', $pageName )).'-'.$detail_reseller['user_id'].'.pdf') ){
            $pdf = setSourceFile( (str_replace('_', '-', $pageName )).'-'.$detail_reseller['user_id'] );
            brand_page_first_logic();
            brand_page_first_print();
        }
        return $pdf;
    }
    function brand_page_first_logic(){
    }
    function brand_page_first_print(){
    }
// End uce_var_3_sui_10 Start Page 4 //


// Start Page 17
    
    function work_abilities_new( $pageName ){
        $pdf = setSourceFile( $pageName );
        $work_ab_data = work_abilities_logic();
        setToGlobal( $work_ab_data );
        extract($work_ab_data);
        // pre( $GLOBALS['apt_value'] , 1 );
        return work_abilities_print($per);
    }

    function work_abilities_logic( ){
        $code = $GLOBALS['code'];
        $class = $GLOBALS['class'];
        // pre( $res_2_1_result , 1 );
        $score_2_1 = 0;
        $nm_1 = 0;
        $res_2_1_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part2.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2' , 'code' => $code , 'asmt_variant_3' => 1 , 'part' => 'part1'  ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part2' , 'uce_part2.qno = ppe_part1_test_details.qno' ] ]  ] );
        foreach($res_2_1_result as $key => $row_2_1_result ){
            if($row_2_1_result['ans'] == $row_2_1_result['r_ans']){
                $score_2_1 = $score_2_1 + 1;
            }
            //No Need To change in This but general review 
            $nm_1 = $nm_1 + 1;
        }

        $scr_apt_02_24['AR'] = [ 'part' => 'uce_part2' , 'scr' => $score_2_1 , 'count' => $nm_1 , 'max_scr' => $nm_1 , 'per' => 0 ];

        $score_2_2 = 0;
        $nm_2 = 0;
        $sql_2_2_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part2_2.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2_2' , 'code' => $code , 'asmt_variant_3' => 1 ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part2_2' , 'uce_part2_2.qno = ppe_part1_test_details.qno' ] ] ] );
        foreach($sql_2_2_result as $key => $row_2_2_result ){
            if($row_2_2_result['ans'] == $row_2_2_result['r_ans']){
                $score_2_2 = $score_2_2 + 1;
            }
            //No Need To change in This but general review 
            $nm_2 = $nm_2 + 1;
        }

        $scr_apt_02_24['VR'] = [ 'part' => 'uce_part2_2' , 'scr' => $score_2_2 , 'count' => $nm_2 , 'max_scr' => $nm_2 , 'per' => 0 ];
        
        
        $score_2_3 = 0;
        $nm_3 = 0;
        $sql_2_3_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part2.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2_3' , 'code' => $code , 'asmt_variant_3' => 1 , 'part' => 'part3' ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part2' , 'uce_part2.qno = ppe_part1_test_details.qno' ] ] ] );
        foreach($sql_2_3_result as $key => $row_2_3_result ){
            if($row_2_3_result['ans'] == $row_2_3_result['r_ans']){
                $score_2_3 = $score_2_3 + 1;
            }
            $nm_3 = $nm_3 + 1;
        }

        $scr_apt_02_24['SA'] = [ 'part' => 'uce_part2_3' ,'scr' => $score_2_3 , 'count' => $nm_3 , 'max_scr' => $nm_3 , 'per' => 0 ];

        $score_2_4 = 0;
        $nm_4 = 0;
        $sql_2_4_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part2.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2_4' , 'code' => $code , 'asmt_variant_3' => 1 , 'part' => 'part4' ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part2' , 'uce_part2.qno = ppe_part1_test_details.qno' ] ] ] );
        foreach($sql_2_4_result as $key => $row_2_4_result ){
            //Special Condition due to timer started 
            if( $row_2_4_result['ans'] == 0 ){
                $score_2_4 = $score_2_4;
            }
            //Special Condition End
            elseif($row_2_4_result['ans']==$row_2_4_result['r_ans']){
                $score_2_4 = $score_2_4 + 1;
            }
            else{
                $score_2_4 = $score_2_4 - 0.25;
            }
            $nm_4 = $nm_4 + 1;
        }
        $scr_apt_02_24['COM'] = [ 'part' => 'uce_part2_4', 'scr' => $score_2_4 , 'count' => $nm_4 , 'max_scr' => $nm_4 , 'per' => 0 ];

        $score_2_5 = 0;
        $nm_5 = 0;
        $sql_2_5_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part5.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2_5' , 'code' => $code , 'asmt_variant_3' => 1 ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part5' , 'uce_part5.qno = ppe_part1_test_details.qno' ] ] ] );

        foreach($sql_2_5_result as $key => $row_2_5_result ){
            // Special Condition due to timer Start
            if( $row_2_5_result['ans'] == 0 ){
                $score_2_5 = $score_2_5;
            }
            // Special Condition due to timer End
            elseif($row_2_5_result['ans']==$row_2_5_result['r_ans'])
            {
                $score_2_5 = $score_2_5 + 1;
            }
            else
            {
                $score_2_5 = $score_2_5 - 1;
            }
            $nm_5 = $nm_5 + 1;
        }

        $scr_apt_02_24['NC'] = [ 'part' => 'uce_part2_5' , 'scr' => $score_2_5 , 'count' => $nm_5 , 'max_scr' => $nm_5 , 'per' => 0 ];

        $score_2_6 = 0;
        $nm_6 = 0;
        $sql_2_6_result = getQuery( [ 'select' => 'ppe_part1_test_details.qno , ppe_part1_test_details.ans , uce_part2.r_ans' , 'table' => 'ppe_part1_test_details' , 'where' => [ 'solution' => 'uce_part2_6' , 'code' => $code , 'asmt_variant_3' => 1 , 'part' => 'part6' ] , 'where_in' => [ 'q_group' => '1 , "All"' ] , 'join' => [ [ 'uce_part2' , 'uce_part2.qno = ppe_part1_test_details.qno' ] ] ] );
        foreach($sql_2_6_result as $key => $row_2_6_result ){
            // Special Condition for timer Start
            if( $row_2_6_result['ans'] == 0 ){
                $score_2_6 = $score_2_6;
            }
            // Special Condition for timer End
            elseif($row_2_6_result['ans']==$row_2_6_result['r_ans'])
            {
                $score_2_6 = $score_2_6 + 1;
            }
            else
            {
                $score_2_6 = $score_2_6 - 0.3333;
            }
            $nm_6 = $nm_6 + 1;
        }

        $scr_apt_02_24['OM'] = [ 'part' => 'uce_part2_6' , 'scr' => $score_2_6 , 'count' => $nm_6 , 'max_scr' => $nm_6 , 'per' => 0 ];

        // pre( $scr_apt_02_24 );

        $data = getQuery( [ 'select' => 'param , std_q_cnt' , 'table' => 'uce_apt_calculation' , 'group_by' => [ 'param' ] ] );
        // pre( $data  );
        foreach ($data as $key => $value) {
            $apt_que_count_02_24[$value['param']] = $value['std_q_cnt'];
        }
        $per = [];
        $apt_value = array();
        for($i=1;$i<=6;$i++)
        {

            if($i==1)
            {
                $param = 'AR';
                $s_type = $score_2_1;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];
            }
            else if($i==2)
            {
                $param = 'VR';
                $s_type = $score_2_2;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];

            }
            else if($i==3)
            {
                $param = 'SA';
                $s_type = $score_2_3;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];
            }
            else if($i==4)
            {
                $param = 'COM';
                $s_type = $score_2_4;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];

            }
            else if($i==5)
            {
                $param = 'NC';
                $s_type = $score_2_5;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];

            }
            else if($i==6)
            {
                $param = 'OM';
                $s_type = $score_2_6;
                $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
                $s_type = $scr_apt_02_24[$param]['scl_scr'];
            }

            echo "Currently Working Here"."<br>";
            echo "Param :".$param."<br>";
            echo "Score :".$s_type."<br>";

            $row = getQuery( [ 'table' => 'uce_apt_calculation' , 'where' => [ 'param' => $param , 'class' => $class , 'score >' => $s_type ] , 'limit' => [ 0 , 1] , 'single' => true ]);

            $high_score = $row['score'];

            echo "High Score :".$high_score."<br>";
                
            $high_l = $row['fs'];

            if($high_l>0){
                $low_l = $high_l - 1;
            } else {
                $low_l = $high_l;
            }

            $row = getQuery( [ 'table' => 'uce_apt_calculation' , 'where' => [ 'param' => $param , 'class' => $class , 'fs' => $low_l ] , 'limit' => [ 0 , 1] , 'single' => true ]);

            // $sql = "select * from uce_apt_calculation where param='$param' and class='$class' and fs='$low_l' limit 1";
            // $res = mysqli_query($con,$sql);
            // $row = mysqli_fetch_array($res);
            //$low_l = $row['fs'];

            $low_score = $row['score'];
            echo "Low Score :".$low_score."<br>";

            //$low_score;
            $f_score = $high_score - $low_score; 

            $y = $s_type - $low_score;
            echo "s_type = ".$s_type."<br>";
            if ($s_type <=0){
                $per[$i] =0;
                $z = 0;
                array_push($apt_value,$z);
            }
            else 
            {
                if ($s_type == $low_score || $s_type == $high_score)
                {
                    echo "This condition is true <br>";
                    //original before 17 Jan 2022
                    /*  $z = $s_type;
                    array_push($apt_value,$z);
                    $per[$i] = $z/7*100; */
                    
                    
                    //changes on 17 Jan 2022
                    $f_score = $high_score - $low_score;
                    echo "Score Range :".$f_score;
                    $y = $s_type - $low_score;
                    $delta = $y/$f_score;

                    $z = $delta + $low_l;
                    array_push($apt_value,$z);
                    $per[$i] = $z/7*100;
                    //End of changes on 17 Jan 2022
                    // echo "per = ".$per[$i]."<br>";
                } 
            
                else 
                {
                    $f_score = $high_score - $low_score;
                    echo "Score Range :".$f_score;
                    $y = $s_type - $low_score;
                    $delta = $y/$f_score;

                    $z = $delta + $low_l;
                    array_push($apt_value,$z);
                    $per[$i] = $z/7*100;
                }
                    
            }

            echo "Type :".$param."<br>";   
        }
        // pre( $per , 1 );
        for ($i=0; $i<6 ; $i++) {
            $x = ( $i == 0 ? 'AR' : ( $i == 1 ? 'VR' : ( $i == 2 ? 'SA' : ($i == 3 ? 'COM' : ( $i == 4 ? 'NC' : 'OM' )))));
            $scr_apt_02_24[$x]['per'] = $per[$i+1];
        }
        // pre( $apt_que_count_02_24 );
        // pre( $scr_apt_02_24 );
        setToGlobal( [ 'apt_value' => $apt_value ] );
        return [ 'per' => $per , 'apt_value' => $apt_value ];
    }

    function work_abilities_print( $per ){

        $pdf = $GLOBALS['pdf'];
        $con = $GLOBALS['con'];
        $code = $GLOBALS['code'];
        $asmt_variation_3_02_24 = $GLOBALS['asmt_variation_3_02_24'];
        $pt = value_level_mapper($per[1]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,72);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);

        $pt = value_level_mapper($per[2]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,92);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
            
            
        $pt = value_level_mapper($per[3]);
        $SA_Level = $pt['level'];

        echo "<br>SA Level : $SA_Level<br>";
        // die();
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,112);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
            
            
        $pt = value_level_mapper($per[4]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,132);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
            
                
        $pt = value_level_mapper($per[5]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,154);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
            
                
        $pt = value_level_mapper($per[6]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('12');
        $pdf->SetXY(140,177);
        $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
            
            
        $per_2_7 = uce_part3_score_calculation_v3_02_24($code, $asmt_variation_3_02_24 ,  $con );
        
        // pre( $per_2_7 , 1 );
        $pt = value_level_mapper($per_2_7[0]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('14');
        $pdf->SetXY(142,232);
        $pdf->MultiCell(50,12,$pt['level'],0,'C',false);

        $pt = value_level_mapper($per_2_7[1]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('14');
        $pdf->SetXY(142,252);
        $pdf->MultiCell(50,12,$pt['level'],0,'C',false);

        $pt = value_level_mapper($per_2_7[2]);
        $cols = explode(",", $pt['color']);
        $pdf->SetFontSize('14');
        $pdf->SetXY(142,274);
        $pdf->MultiCell(50,2,$pt['level'],0,'C',false);
        return $pdf;
    }

    // $pdf = work_abilities_new( 'work-abilities-new' ); 
        
    //UCE Part 2 - Aptitude Part 1

    //2.1
    // $score_2_1 = 0;

    // $nm_1 = 0;

    // $sql_2_1_result = "select * from ppe_part1_test_details where solution='uce_part2' and code='$code'";
    // $res_2_1_result = mysqli_query($con,$sql_2_1_result);
    // while($row_2_1_result = mysqli_fetch_array($res_2_1_result))
    // {
    //     $qno = $row_2_1_result['qno'];
    //     $ans = $row_2_1_result['ans'];
    //     // $sql_2_1 = "select * from uce_part2 where  part='part1' and qno='$qno'";
    //     // Logic test block uce 3//
    //         $sql_2_1 = "select * from uce_part2 where part='part1' and qno='$qno' AND ".             $asmt_variation_3_02_24['where_in'];
            
    //     // End Logic test block uce 3//
    //     $res_2_1 = mysqli_query($con,$sql_2_1);
    //     $row_2_1 = mysqli_fetch_array($res_2_1);
    //     $r_ans = $row_2_1['r_ans'];
    //     if($ans == $r_ans)
    //     {
    //         $score_2_1 = $score_2_1 + 1;
    //     }
    //     //No Need To change in This but general review 
    //     $nm_1 = $nm_1 + 1;
    // }

    // echo 'AR Score: '.$score_2_1."<br>";
    // //part 2.2
    // // die;
    // $scr_apt_02_24['AR'] = [ 'part' => 'uce_part2' , 'scr' => $score_2_1 , 'count' => $nm_1 , 'max_scr' => $nm_1 , 'per' => 0 ];
    // $score_2_2 = 0;
    // $nm_2 = 0;
    // $sql_2_2_result = "select * from ppe_part1_test_details where solution='uce_part2_2' and code='$code'";
    // $res_2_2_result = mysqli_query($con,$sql_2_2_result);
    // while($row_2_2_result = mysqli_fetch_array($res_2_2_result))
    // {
    //     $qno = $row_2_2_result['qno'];
    //     $ans = $row_2_2_result['ans'];
    //     $sql_2_2 = "select * from uce_part2_2 where qno='$qno' AND ".$asmt_variation_3_02_24['where_in'];
    //     // pre( $sql_2_2 , 1 );
    //     $res_2_2 = mysqli_query($con,$sql_2_2);
    //     $row_2_2 = mysqli_fetch_array($res_2_2);
    //     $r_ans = $row_2_2['r_ans'];
    //     if($ans==$r_ans)
    //     {
    //         $score_2_2 = $score_2_2 + 1;
    //     }
    //     $nm_2 = $nm_2 + 1;
    // }

    // echo 'VR Score :'.$score_2_2."<br>";
    // $scr_apt_02_24['VR'] = [ 'part' => 'uce_part2_2' , 'scr' => $score_2_2 , 'count' => $nm_2 , 'max_scr' => $nm_2 , 'per' => 0 ];
    // // part 2.3
    // $score_2_3 = 0;
    // $nm_3 = 0;
    // $sql_2_3_result = "select * from ppe_part1_test_details where solution='uce_part2_3' and code='$code'";
    // $res_2_3_result = mysqli_query($con,$sql_2_3_result);
    // while($row_2_3_result = mysqli_fetch_array($res_2_3_result))
    // {
    //     $qno = $row_2_3_result['qno'];
    //     $ans = $row_2_3_result['ans'];
    //     $sql_2_3 = "select * from uce_part2 where part='part3' and qno='$qno' AND ".$asmt_variation_3_02_24['where_in'];
    //     $res_2_3 = mysqli_query($con,$sql_2_3);
    //     $row_2_3 = mysqli_fetch_array($res_2_3);
    //     $r_ans = $row_2_3['r_ans'];
    //     if($ans==$r_ans)
    //     {
    //         $score_2_3 = $score_2_3 + 1;
    //     }
    //     $nm_3 = $nm_3 + 1;
    // }

    // echo 'SA Score :'.$score_2_3."<br>";

    // $scr_apt_02_24['SA'] = [ 'part' => 'uce_part2_3' ,'scr' => $score_2_3 , 'count' => $nm_3 , 'max_scr' => $nm_3 , 'per' => 0 ];
    // // part 2.4
    // $score_2_4 = 0;
    // $nm_4 = 0;
    // $sql_2_4_result = "select * from ppe_part1_test_details where solution='uce_part2_4' and code='$code'";
    // $res_2_4_result = mysqli_query($con,$sql_2_4_result);
    // while($row_2_4_result = mysqli_fetch_array($res_2_4_result))
    // {
    //     $qno = $row_2_4_result['qno'];
    //     $ans = $row_2_4_result['ans'];
    //     $sql_2_4 = "select * from uce_part2 where part='part4' and qno='$qno' AND ".$asmt_variation_3_02_24['where_in'];
    //     $res_2_4 = mysqli_query($con,$sql_2_4);
    //     $row_2_4 = mysqli_fetch_array($res_2_4);
    //     $r_ans = $row_2_4['r_ans'];
    //     //Special Condition due to timer started 
    //     if( $ans == 0 ){
    //         $score_2_4 = $score_2_4;
    //     }
    //     //Special Condition End
    //     elseif($ans==$r_ans)
    //     {
    //         $score_2_4 = $score_2_4 + 1;
    //     }
    //     else
    //     {
    //         $score_2_4 = $score_2_4 - 0.25;
    //     }
    //     $nm_4 = $nm_4 + 1;
    // }

    // echo 'COM Score :'.$score_2_4."<br>";

    // $scr_apt_02_24['COM'] = [ 'part' => 'uce_part2_4', 'scr' => $score_2_4 , 'count' => $nm_4 , 'max_scr' => $nm_4 , 'per' => 0 ];
    // // part 2.5
    // $score_2_5 = 0;
    // $nm_5 = 0;
    // $sql_2_5_result = "select * from ppe_part1_test_details where solution='uce_part2_5' and code='$code'";
    // $res_2_5_result = mysqli_query($con,$sql_2_5_result);
    // while($row_2_5_result = mysqli_fetch_array($res_2_5_result))
    // {
    //     $qno = $row_2_5_result['qno'];
    //     $ans = $row_2_5_result['ans'];
    //     $sql_2_5 = "select * from uce_part5 where qno='$qno' AND ".$asmt_variation_3_02_24['where_in'];
    //     $res_2_5 = mysqli_query($con,$sql_2_5);
    //     $row_2_5 = mysqli_fetch_array($res_2_5);
    //     $r_ans = $row_2_5['r_ans'];
    //     // Special Condition due to timer Start
    //     if( $ans == 0 ){
    //         $score_2_5 = $score_2_5;
    //     }
    //     // Special Condition due to timer End
    //     elseif($ans==$r_ans)
    //     {
    //         $score_2_5 = $score_2_5 + 1;
    //     }
    //     else
    //     {
    //         $score_2_5 = $score_2_5 - 1;
    //     }
    //     $nm_5 = $nm_5 + 1;
    // }

    // echo 'NC Score :'.$score_2_5."<br>";
    // $scr_apt_02_24['NC'] = [ 'part' => 'uce_part2_5' , 'scr' => $score_2_5 , 'count' => $nm_5 , 'max_scr' => $nm_5 , 'per' => 0 ];

    // // part 2.6
    // $score_2_6 = 0;
    // $nm_6 = 0;
    // $sql_2_6_result = "select * from ppe_part1_test_details where solution='uce_part2_6' and code='$code'";
    // $res_2_6_result = mysqli_query($con,$sql_2_6_result);
    // while($row_2_6_result = mysqli_fetch_array($res_2_6_result))
    // {
    //     $qno = $row_2_6_result['qno'];
    //     $ans = $row_2_6_result['ans'];
    //     $sql_2_6 = "select * from uce_part2 where part='part6' and qno='$qno' AND ".$asmt_variation_3_02_24['where_in'];
    //     // echo $sql_2_6;
    //     // die;
    //     $res_2_6 = mysqli_query($con,$sql_2_6);
    //     $row_2_6 = mysqli_fetch_array($res_2_6);
    //     $r_ans = $row_2_6['r_ans'];
    //     // Special Condition for timer Start
    //     if( $ans == 0 ){
    //         $score_2_6 = $score_2_6;
    //     }
    //     // Special Condition for timer End
    //     elseif($ans==$r_ans)
    //     {
    //         $score_2_6 = $score_2_6 + 1;
    //     }
    //     else
    //     {
    //         $score_2_6 = $score_2_6 - 0.3333;
    //     }
    //     $nm_6 = $nm_6 + 1;
    // }

    // echo 'OM Score :'.$score_2_6."<br>";
    // $scr_apt_02_24['OM'] = [ 'part' => 'uce_part2_6' , 'scr' => $score_2_6 , 'count' => $nm_6 , 'max_scr' => $nm_6 , 'per' => 0 ];
    // // // die;

    // // pre( $scr_apt_02_24 , 1 );

    // //ar
    // // Logic test block uce v3 //
    //     $sql = "select DISTINCT param , std_q_cnt FROM uce_apt_calculation";
    //     $res = mysqli_query($con,$sql);
    //     while( $data = mysqli_fetch_array($res) ){
    //         // pre( $data , 1 );
    //         $apt_que_count_02_24[$data['param']] = $data['std_q_cnt'];
    //     }
    // // Logic test block uce v3 //
    // // pre( $apt_que_count_02_24 );
    // // pre( $scr_apt_02_24 , 1 );
    // $per = [];
    // $apt_value = array();
    // for($i=1;$i<=6;$i++)
    // {

    //     if($i==1)
    //     {
    //         $param = 'AR';
    //         $s_type = $score_2_1;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];
    //     }
    //     else if($i==2)
    //     {
    //         $param = 'VR';
    //         $s_type = $score_2_2;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];

    //     }
    //     else if($i==3)
    //     {
    //         $param = 'SA';
    //         $s_type = $score_2_3;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];
    //     }
    //     else if($i==4)
    //     {
    //         $param = 'COM';
    //         $s_type = $score_2_4;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];

    //     }
    //     else if($i==5)
    //     {
    //         $param = 'NC';
    //         $s_type = $score_2_5;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];

    //     }
    //     else if($i==6)
    //     {
    //         $param = 'OM';
    //         $s_type = $score_2_6;
    //         $scr_apt_02_24[$param]['scl_scr'] = $s_type * $apt_que_count_02_24[$param]/$scr_apt_02_24[$param]['count'];
    //         $s_type = $scr_apt_02_24[$param]['scl_scr'];
    //     }

    //     echo "Currently Working Here"."<br>";
    //     echo "Param :".$param."<br>";
    //     echo "Score :".$s_type."<br>";

    //     $sql = "select * from uce_apt_calculation where param = '$param' and class = '$class' and score > '$s_type' limit 1";
    //     echo "Query :".$sql."<br>";
    //     $res = mysqli_query($con,$sql);
    //     $row = mysqli_fetch_array($res);

    //     // echo "<pre>";
    //     // print_r( $row );

    //     $high_score = $row['score'];



    //     echo "High Score :".$high_score."<br>";
            
    //     $high_l = $row['fs'];

    //     if($high_l>0){
    //         $low_l = $high_l - 1;
    //     } else {
    //         $low_l = $high_l;
    //     }

    //     $sql = "select * from uce_apt_calculation where param='$param' and class='$class' and fs='$low_l' limit 1";
    //     $res = mysqli_query($con,$sql);
    //     $row = mysqli_fetch_array($res);

    //     //$low_l = $row['fs'];

    //     $low_score = $row['score'];
    //     echo "Low Score :".$low_score."<br>";

    //     //$low_score;
    //     $f_score = $high_score - $low_score; 

    //     $y = $s_type - $low_score;
    //     echo "s_type = ".$s_type."<br>";
    //     if ($s_type <=0){
    //         $per[$i] =0;
    //         $z = 0;
    //         array_push($apt_value,$z);
    //     }
    //     else 
    //     {
    //         if ($s_type == $low_score || $s_type == $high_score)
    //         {
    //             echo "This condition is true <br>";
    //             //original before 17 Jan 2022
    //             /*  $z = $s_type;
    //             array_push($apt_value,$z);
    //             $per[$i] = $z/7*100; */
                
                
    //             //changes on 17 Jan 2022
    //             $f_score = $high_score - $low_score;
    //             echo "Score Range :".$f_score;
    //             $y = $s_type - $low_score;
    //             $delta = $y/$f_score;

    //             $z = $delta + $low_l;
    //             array_push($apt_value,$z);
    //             $per[$i] = $z/7*100;
    //             //End of changes on 17 Jan 2022
    //             // echo "per = ".$per[$i]."<br>";
    //         } 
        
    //         else 
    //         {
    //             $f_score = $high_score - $low_score;
    //             echo "Score Range :".$f_score;
    //             $y = $s_type - $low_score;
    //             $delta = $y/$f_score;

    //             $z = $delta + $low_l;
    //             array_push($apt_value,$z);
    //             $per[$i] = $z/7*100;
    //         }
                
    //     }

    //         echo "Type :".$param."<br>";

        
    // }
    // // pre( $per , 1 );
    // for ($i=0; $i<6 ; $i++) {
    //     $x = ( $i == 0 ? 'AR' : ( $i == 1 ? 'VR' : ( $i == 2 ? 'SA' : ($i == 3 ? 'COM' : ( $i == 4 ? 'NC' : 'OM' )))));
    //     $scr_apt_02_24[$x]['per'] = $per[$i+1];
    // }

    // pre( $apt_que_count_02_24 );
    
    // pre( $scr_apt_02_24 );


    // aptitude value mapping
    // $pt = value_level_mapper($per[1]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,72);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);


    // $pt = value_level_mapper($per[2]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,92);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
        
        
    // $pt = value_level_mapper($per[3]);
    // $SA_Level = $pt['level'];

    // echo "<br>SA Level : $SA_Level<br>";
    // // die();
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,112);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
        
        
    // $pt = value_level_mapper($per[4]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,132);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
        
            
    // $pt = value_level_mapper($per[5]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,154);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
        
            
    // $pt = value_level_mapper($per[6]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('12');
    // $pdf->SetXY(140,177);
    // $pdf->MultiCell(50,8,$pt['level'],0,'C',false);
        
        
    // $per_2_7 = uce_part3_score_calculation_v3_02_24($code, $asmt_variation_3_02_24);
    
    // // pre( $per_2_7 , 1 );
    // $pt = value_level_mapper($per_2_7[0]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('14');
    // $pdf->SetXY(142,232);
    // $pdf->MultiCell(50,12,$pt['level'],0,'C',false);

    // $pt = value_level_mapper($per_2_7[1]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('14');
    // $pdf->SetXY(142,252);
    // $pdf->MultiCell(50,12,$pt['level'],0,'C',false);

    // $pt = value_level_mapper($per_2_7[2]);
    // $cols = explode(",", $pt['color']);
    // $pdf->SetFontSize('14');
    // $pdf->SetXY(142,274);
    // $pdf->MultiCell(50,2,$pt['level'],0,'C',false);

    // die;
// End Page 17

// Start Page 18-19-20

    function work_abilities_static( $pageName , $noOfPage, $pageType ){
        return setSourceFile( $pageName , $noOfPage, $pageType );
    }

    // $pdf = work_abilities_static( 'work-abilities-static' , 3 , 'static' );
    // End Page 18-19-20
    // Start Page 21

    function value_level_mapper($per_val){
        echo "value Per :- ".$per_val."<br>";
        $status['level'] = '';
        $status['color'] = '';
        if($per_val<40)
        {
            $status['level'] = 'Low';
            $status['color'] = '247,89,73';
        } 
        else
        if($per_val<60)
        {
            $status['level'] = 'Medium';
            $status['color'] = '234,250,33';
        }
        else
        if($per_val<75)
        {
            $status['level'] = 'High';
            $status['color'] = '95,250,33';
        }
        else
        if($per_val<=100 )
        {
            $status['level'] = 'Very High';
            $status['color'] = '74,191,27';
        }
        return $status;
    }

    function cluster_stream_graphs( $pageName ){
        $pdf = setSourceFile( $pageName );
        $suidata = suitable_career_identification( $pdf , $GLOBALS['code'],  $GLOBALS['p1_2_per'] , $GLOBALS['apt_value']);
        extract( $suidata );
        $pdf= $GLOBALS['pdf'];
        echo "Print Stream";
        // pre( $arr_stream_map );
        $pdf =  print_cluster( $pdf , $arr_cluster_map );
        $pdf =  print_stream( $pdf , $arr_stream_map , $pagecount );
        return  $pdf ;
    }

    // $data = cluster_stream_graphs('cluster-stream-graphs');
    // End Page 21
    // Start Page 22
    // extract($data);

    function career_listing($pageName){
        $con = $GLOBALS['con'];
        $pdf = setSourceFile( $pageName );
        $GLOBALS['career_listing_page'] = $pageName;
        $pdf = $GLOBALS['pdf'];
        $arr_car_reco = $GLOBALS['arr_car_reco'];
        $arr_career = $GLOBALS['arr_career'];
        // pre(  $arr_car_reco);
        // pre(  $arr_career , 1 );
        $total = 20;
        $heading = null;
        $variantData = $GLOBALS['variantData'];
        $requestVariantMap = $GLOBALS['requestVariantMap'];
        $arr_car_to_print = $GLOBALS['arr_car_to_print'];
        $arr_edu_map = $GLOBALS['arr_edu_map'];
        $filter = 'uncheck';
        // pre( $variantData , 1);
        // pre( $arr_car_to_print );
        // pre( $arr_edu_map , 1);
        if( $variantData['stream_action'] == 'Identify suitable streams for 11th-12th' ){
            foreach ($GLOBALS['arr_stream'] as $key => $value) {
                $stream_arr[] = $value['11th_12th'];
            }

            $filter = 'check';
            // pre( $stream_arr , 1 );
            $arr_edu_map = get_edu(  $arr_car_to_print , 'career_edu_v2' , $stream_arr );

        }
        elseif( $variantData['stream_action'] == 'Continue with existing streams' ){
            $filter = 'check';
            $currentStreamArr = [];
            $start = 0;
            $stream_arr = [ $requestVariantMap['currentStream'] ];
            if( in_array( 'PCMB' , $stream_arr ) ){
                $stream_arr[] = 'PCM';
                $stream_arr[] = 'PCB';
            }
            if( in_array( 'Humanities' , $stream_arr ) ){
                $stream_arr[] = 'Any Stream';
            }
            $heading = 'Your current stream is '.$requestVariantMap['currentStream'].'. You have requested to provice careers without changing the stream. Hence, below careers are only from '.( implode( ',' , $stream_arr ) );
            // pre( $arr_car_reco , 1);
            foreach($arr_car_reco as $key => $value) {
                if( $value['car_reco'] != 'Avoid' ){
                    if( $start < $total ){
                        $sql = "SELECT Cluster, profession_name, stream_no, 11th_12th, Education, indian_name  FROM  career_edu_v2  WHERE profession_name ='".mysqli_real_escape_string( $con , $value['profession_name'] )."'";
                        $result = mysqli_query( $con , $sql );
                        if( mysqli_num_rows($result) > 1 ){
                            while( $streams = mysqli_fetch_array($result) ){
                                if( in_array( $streams['11th_12th'] , $stream_arr ) ){
                                    $value['stream'] = $streams['11th_12th'];
                                    $currentStreamArr[] = $value;
                                    $start++;
                                }
                            }
                        }
                        else{
                            $streams = mysqli_fetch_array(  $result );
                            if( in_array( $streams['11th_12th'] , $stream_arr ) ){
                                $value['stream'] = $streams['11th_12th'];
                                $currentStreamArr[] = $value;
                                $start++;
                            }
                        }
                    }
                }
                // pre( [ $value['stream'] , $requestVariantMap['currentStream'] ] );
            }
            // $score = array_column($currentStreamArr, 'scr_car');
            // array_multisort($score, SORT_DESC, $currentStreamArr);
            // $arr_choices[$key] = $value;
            // pre( $currentStreamArr , 1 );
            $arr_car_to_print = $currentStreamArr;
            // pre( $arr_car_to_print );
            $arr_edu_map = get_edu(  $arr_car_to_print , 'career_edu_v2' , $stream_arr );
        }
        elseif (  $variantData['stream_action'] == 'Open to change existing streams' ) {
            $filter = 'check';
            $currentStreamArr = [];
            $changeStream = json_decode($requestVariantMap['changeStream']);
            $stream_arr =  array_unique( array_merge( [ $requestVariantMap['currentStream'] ] , $changeStream ) );
            // pre( $stream_arr , 1 );
            if( in_array( 'PCMB' , $stream_arr ) ){
                $stream_arr[] = 'PCM';
                $stream_arr[] = 'PCB';
            }
            if( in_array( 'Humanities' , $stream_arr ) ){
                $stream_arr[] = 'Any Stream';
            }
            $stream_arr =  array_unique($stream_arr);
            $heading = 'Your current stream is '.$requestVariantMap['currentStream'].'. However, you are open to change streams to '.( implode( ',' , $changeStream ) ).'. Hence, below careers include '.( implode( ',' , $stream_arr ) );
            foreach ($arr_car_reco as $key => $value) {
                if( $value['car_reco'] != 'Avoid' ){
                    $sql = "SELECT Cluster, profession_name, stream_no, 11th_12th, Education, indian_name  FROM  career_edu_v2  WHERE profession_name ='".mysqli_real_escape_string( $con , $value['profession_name'] )."'";
                    $result = mysqli_query( $con , $sql );
                    if( mysqli_num_rows($result) > 1 ){
                        while( $streams = mysqli_fetch_array($result) ){
                            if( in_array( $streams['11th_12th'] , $stream_arr ) ){
                                $value['stream'] = $streams['11th_12th'];
                                $currentStreamArr[] = $value;
                                $start++;
                            }
                        }
                    }
                    else{
                        $streams = mysqli_fetch_array(  $result );
                        if( in_array( $streams['11th_12th'] , $stream_arr ) ){
                            $value['stream'] = $streams['11th_12th'];
                            $currentStreamArr[] = $value;
                            $start++;
                        }
                    }
                }
            }
            // pre( $currentStreamArr , 1 );
            // $score = array_column($currentStreamArr, 'scr_car');
            // array_multisort($score, SORT_DESC, $currentStreamArr);
            $start = 0;
            $arr_car_to_print = [];
            foreach ($currentStreamArr as $key => $value) {
                if( $start < $total ){
                    $car_to_print[] = $value;
                    $start++;
                }
            }
            $arr_car_to_print = $car_to_print;
            // pre( $arr_car_to_print );
            $arr_edu_map = get_edu(  $arr_car_to_print , 'career_edu_v2' , $stream_arr );
           
        }
        // pre( $arr_edu_map , 1 );
        // pre( $GLOBALS['car_count_stream'] , 1);
        // pre( [ $arr_edu_map , $GLOBALS['car_count_stream'] , $arr_car_to_print ] ,  1 );
        $code = $GLOBALS['code'];
        // pre( $filter , 1 );
        return print_careers( $pdf , $arr_car_to_print , $arr_edu_map , $code , $heading ,  $filter );
    }
    // $pdf = career_listing( 'career-listing' );    
    // End Page 22
    
    function suitable_career_identification( $pdf , $code ,  $usr_int , $usr_apt , $car_count = 20 ){
        $con = $GLOBALS['con'];
        // pre( [ $code ,  $usr_int , $usr_apt ] , 1 );
        //i. Get unique careers from career_apt table.
        $arr_tbl = [
            'apt' => 'career_apt_v2',
            'int' => 'career_int_v2',
            'lvl_map' => 'career_var_lvl_map',
            'edu' => 'career_edu_v2',
            'sui' => 'career_sui_v2'
        ];
        $qry = "SELECT * FROM ".$arr_tbl['apt']." GROUP BY profession_name";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_apt[] = $data;
        }
        
        
        $qry = "SELECT DISTINCT Cluster FROM ".$arr_tbl['apt']." ORDER BY Cluster ";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_cluster[] = $data;
        }
        
        
        $qry = "SELECT DISTINCT 11th_12th FROM ".$arr_tbl['edu']." ORDER BY 11th_12th";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_stream[] = $data;
        }
        
        
        // pre( $arr_cluster );
        // pre( $arr_stream , 1 );
        
        $qry = "SELECT * FROM ".$arr_tbl['int']." GROUP BY profession_name ";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_int[] = $data;
        }
        
        // pre( $arr_int , 1);
        $qry = "SELECT * FROM ".$arr_tbl['lvl_map']." WHERE param = 'interest'";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_int_lvl[] = $data;
        }
        $qry = "SELECT * FROM ".$arr_tbl['lvl_map']." WHERE param = 'aptitude'";
        $result = mysqli_query($con,$qry);
        
        while( $data = mysqli_fetch_array($result) ){
            $arr_apt_lvl[] = $data;
        }
            // pre( $car_int );
        // echo "<pre>";
        // pre( $arr_apt_lvl );
        
        // pre( $arr_apt );
        $arr_car_reco = [];
        
        // pre( $arr_apt_lvl , 1 );
        foreach( $arr_apt as $k => $v ){

            $profession_name = $v['profession_name'];
            $common_name = $v['name_common'];
            $key = array_search( $profession_name, array_column($arr_int, 'profession_name') );
            $car_int = [ $arr_int[$key]['J1'] , $arr_int[$key]['J2'], $arr_int[$key]['J3']  ];
            $scr_int = round( scr_int_v2($usr_int , $car_int) , 3);
            $lvl_int = map_val_lvl( $scr_int , $arr_int_lvl );

            $stream  = getQuery( [ 'table' => $arr_tbl['edu'] , 'where' => [ 'profession_name' => $profession_name ] , 'single' => true , 'select' => '11th_12th' ] );
            // echo "level ";
            // pre( "<br>INT level ".$lvl_int );
            // $lvl_apt_SA = 'L'; // TO define the function
            $lvl_apt_SA = $v['mandatory_apt'];
            
            $apt_high = $arr_apt_lvl[2]['lvl_low'];
            
            $scr_apt = round( scr_apt_v2( $usr_apt, $v , $lvl_apt_SA , $apt_high) , 3);
            // pre( "<br>APT Score ".$scr_apt );
            $lvl_apt = map_val_lvl($scr_apt , $arr_apt_lvl);
            
            // pre( "<br>APT level ".$lvl_apt);
            // pre( "<br>APT level ".$lvl_apt );
            // pre( $car_int );
            
            $scr_car = round($scr_apt*$scr_int , 3);
            
            // echo "<br> score ";
            // pre( "<br>Car score ".$scr_car);
            // HH (Top Choice), HM/MH (Good Choice), MM/LH(Optional), HL/LM/ML/LL (Avoid)
            // $scr_car = mt_rand(0.00*10, 1.00*10) / 10;
            // pre( $scr_car , 1 );
            
            $car_reco = '';
            if( $lvl_int == 'H' && $lvl_apt == 'H' ){
                $car_reco = 'Top Choice';
                $arr_car_reco[ $profession_name ][ 'cluster' ] = $v['Cluster'] ;
                $arr_car_reco[ $profession_name ][ 'car_reco' ] = $car_reco ;
                $arr_car_reco[ $profession_name ][ 'stream' ] = $stream['11th_12th'] ;
                $arr_car_reco[ $profession_name ][ 'path' ] = $v['Path'] ;
                $arr_car_reco[ $profession_name ][ 'common_name' ]=$common_name;
                $arr_car_reco[ $profession_name ][ 'scr_int' ] = $scr_int ;
                $arr_car_reco[ $profession_name ][ 'scr_apt' ] = $scr_apt ;
                $arr_car_reco[ $profession_name ][ 'lvl_int' ] = $lvl_int ;
                $arr_car_reco[ $profession_name ][ 'lvl_apt' ] = $lvl_apt ;
                $arr_car_reco[ $profession_name ][ 'scr_car' ] = $scr_car ;
            }
            elseif( ( $lvl_int == 'H' && $lvl_apt == 'M' ) || ( $lvl_int == 'M' && $lvl_apt == 'H' ) ){
                $car_reco = 'Good Choice';
                $arr_car_reco[ $profession_name ][ 'cluster' ] = $v['Cluster'] ;
                $arr_car_reco[ $profession_name ][ 'car_reco' ] = $car_reco ;
                $arr_car_reco[ $profession_name ][ 'stream' ] = $stream['11th_12th'] ;
                $arr_car_reco[ $profession_name ][ 'path' ] = $v['Path'] ;
                $arr_car_reco[ $profession_name ][ 'common_name' ]=$common_name;
                $arr_car_reco[ $profession_name ][ 'scr_int' ] = $scr_int ;
                $arr_car_reco[ $profession_name ][ 'scr_apt' ] = $scr_apt ;
                $arr_car_reco[ $profession_name ][ 'lvl_int' ] = $lvl_int ;
                $arr_car_reco[ $profession_name ][ 'lvl_apt' ] = $lvl_apt ;
                $arr_car_reco[ $profession_name ][ 'scr_car' ] = $scr_car ;
            }
            elseif( ( $lvl_int == 'M' && $lvl_apt == 'M' ) || ( $lvl_int == 'L' && $lvl_apt == 'H' ) ){
                $car_reco = 'Optional';
                $arr_car_reco[ $profession_name ][ 'cluster' ] = $v['Cluster'] ;
                $arr_car_reco[ $profession_name ][ 'car_reco' ] = $car_reco ;
                $arr_car_reco[ $profession_name ][ 'stream' ] = $stream['11th_12th'] ;
                $arr_car_reco[ $profession_name ][ 'path' ] = $v['Path'] ;
                $arr_car_reco[ $profession_name ][ 'common_name' ]=$common_name;
                $arr_car_reco[ $profession_name ][ 'scr_int' ] = $scr_int ;
                $arr_car_reco[ $profession_name ][ 'scr_apt' ] = $scr_apt ;
                $arr_car_reco[ $profession_name ][ 'lvl_int' ] = $lvl_int ;
                $arr_car_reco[ $profession_name ][ 'lvl_apt' ] = $lvl_apt ;
                $arr_car_reco[ $profession_name ][ 'scr_car' ] = $scr_car ;
            }
            
            // elseif( ( $lvl_int == 'H' && $lvl_apt == 'L' ) || ( $lvl_int == 'M' && $lvl_apt == 'L' ) || ( $lvl_int == 'L' && $lvl_apt == 'M' ) ){
            //     $car_reco = 'Explore';
            //     $arr_car_reco[ $profession_name ][ 'cluster' ] = $v['Cluster'] ;
            //     $arr_car_reco[ $profession_name ][ 'path' ] = $v['Path'] ;
            //     $arr_car_reco[ $profession_name ][ 'common_name' ]=$common_name;
            //     $arr_car_reco[ $profession_name ][ 'scr_int' ] = $scr_int ;
            //     $arr_car_reco[ $profession_name ][ 'scr_apt' ] = $scr_apt ;
            //     $arr_car_reco[ $profession_name ][ 'car_reco' ] = $car_reco ;
            //     $arr_car_reco[ $profession_name ][ 'lvl_int' ] = $lvl_int ;
            //     $arr_car_reco[ $profession_name ][ 'lvl_apt' ] = $lvl_apt ;
            //     $arr_car_reco[ $profession_name ][ 'scr_car' ] = $scr_car ;
            // }
            else{
                $car_reco = 'Avoid';
                $arr_car_reco[ $profession_name ][ 'cluster' ] = $v['Cluster'] ;
                $arr_car_reco[ $profession_name ][ 'car_reco' ] = $car_reco ;
                $arr_car_reco[ $profession_name ][ 'stream' ] = $stream['11th_12th'] ;
                $arr_car_reco[ $profession_name ][ 'path' ] = $v['Path'] ;
                $arr_car_reco[ $profession_name ][ 'common_name' ]=$common_name;
                $arr_car_reco[ $profession_name ][ 'scr_int' ] = $scr_int ;
                $arr_car_reco[ $profession_name ][ 'scr_apt' ] = $scr_apt ;
                $arr_car_reco[ $profession_name ][ 'lvl_int' ] = $lvl_int ;
                $arr_car_reco[ $profession_name ][ 'lvl_apt' ] = $lvl_apt ;
                $arr_car_reco[ $profession_name ][ 'scr_car' ] = $scr_car ;
            }
        }

        // pre( $arr_car_reco , 1 );
       
        // die;
        $arr_choice_names = [ 'Top Choice' , 'Good Choice' , 'Optional' ];
        // $arr_choice_names = [ 'Top Choice' , 'Good Choice' , 'Optional' , 'Explore' ];
        
        $arr_choices = [ 'top_choice' => [] , 'good_choice' => []  , 'optional' => [] ];
        
        $arr_choices_new = [ 'top_choice' => [] , 'good_choice' => []  , 'optional' => [] , 'avoid' => [] ];
        // $arr_choices = [ 'top_choice' => [] , 'good_choice' => []  , 'optional' => [] , 'explore ' => [] ];
        // die;
        // pre( $arr_car_reco , 1 );

        foreach( $arr_car_reco as $key => $value ){
            $value['profession_name'] = $key;
            $value['code'] = $code;
            if( $value['car_reco'] == 'Top Choice' ){
                $arr_choices['top_choice'][] = $value;
                $arr_choices_new['top_choice'][] = $value;
            }
            elseif( $value['car_reco'] == 'Good Choice' ){
                $arr_choices['good_choice'][] = $value;
                $arr_choices_new['good_choice'][] = $value;
            }
            elseif( $value['car_reco'] == 'Optional' ){
                $arr_choices['optional'][] = $value;
                $arr_choices_new['optional'][] = $value;
            }
            elseif($value['car_reco'] == 'Avoid'){
                $arr_choices_new['Avoid'][] = $value;
            }
        }
        // logic test block uce v3 //
        // $arr_choice_op = [ 'top_choice' , 'good_choice' , 'optional' ];
        // $scr_t_rand = [ '0.7' , '0.75' , '0.8' , '0.85' , '0.9' , '0.95' , '1.0' , '1.05'   ];
        // $scr_g_rand = [ '0.5' , '0.55' , '0.6' , '0.65' ];
        // $scr_o_rand = [ '0.4' , '0.45' ];
        // foreach ($arr_car_reco as $key => $value) {
        //     $value['profession_name'] = $key;
        //     $value['code'] = $code;
        //     $choice = $arr_choice_op[rand(0 , 2)];
        //     $value['car_reco'] = ucwords(str_replace('_' , ' ' , $choice));
        //     if( $value['car_reco'] == 'Top Choice' ){
        //         $value['scr_car'] = $scr_t_rand[rand(0 , count($scr_t_rand))];
        //     }
        //     elseif( $value['car_reco'] == 'Good Choice' ){
        //         $value['scr_car'] = $scr_g_rand[rand(0 , count($scr_g_rand))];
        //     }
        //     if( $value['car_reco'] == 'Optional' ){
        //         $value['scr_car'] = $scr_o_rand[rand(0 , count($scr_o_rand))];
        //     }
        //     $arr_choices[$choice][] = $value;
        // }
        // End logic test block uce v3 //
        // echo "Choices Arr : <br>";
        foreach( $arr_choices as $key => $value ){
            if( !empty( $value ) ){
                $score = array_column($value, 'scr_car');
                array_multisort($score, SORT_DESC, $value);
                $arr_choices[$key] = $value;
            }
        }
        $final_sort_car = [];
        foreach( $arr_choices_new as $key => $value ){
            if( !empty( $value ) ){
                $score = array_column($value, 'scr_car');
                array_multisort($score, SORT_DESC, $value);
                $final_sort_car = array_merge( $final_sort_car , $value );
            }
        }
        // pre( $final_sort_car ,  1);
        // pre( $final_sort_car ,  1);
        $arr_car_to_print = [];
        foreach( $arr_choices as $key => $value ){
            // pre( $value);
            $tot_count = count($arr_car_to_print);
            if( $tot_count < $car_count ){
                $count = count( $value );
                $delta = $car_count - $tot_count;
                if( $count > $delta ){
                    for( $i = 0 ; $i < $delta ; $i++ ){
                        array_push($arr_car_to_print , $value[$i]);
                    }
                    // array_push($arr_car_to_print , array_slice( $value  , 0 , $delta-1 , true ));
                }
                else{
                    foreach ( $value as $k => $v ){
                        array_push($arr_car_to_print , $v);
                    }
                }
            }
        }
        // pre( $arr_car_to_print ,1 );
        foreach($arr_car_to_print as $key => $value ){
            extract( $value );
            $sql="INSERT INTO student (code,profession_name,lvl_int,scr_int,lvl_apt,scr_apt,scr_car, car_reco, )VALUES('$code','$profession_name','$lvl_int','$scr_int','$lvl_apt','$scr_apt','$scr_car','$car_reco')";
            // echo $sql;
        }
        $arr_cluster_map = car_cluster_map( $arr_choices , $arr_cluster , $arr_choice_names );
        // pre( [ $arr_choices ,  $arr_stream ]  , 1);
        $arr_stream_map = car_stream_map($con, $arr_choices , $arr_stream , $arr_choice_names );
        
        $arr_edu_map = get_edu(  $arr_car_to_print , $arr_tbl['edu'] );
        
        $pdf->SetFont('arial','B',11);

        $pdf->SetTextColor(0,0,0);

        setToGlobal([ 'pdf' => $pdf , 'arr_cluster_map' => $arr_cluster_map , 'arr_stream_map' => $arr_stream_map , 'arr_car_to_print' => $arr_car_to_print , 'arr_edu_map' => $arr_edu_map , 'arr_car_reco' => $final_sort_car , 'arr_stream' => $arr_stream  ]);

        return [ 'arr_cluster_map' => $arr_cluster_map , 'arr_stream_map' => $arr_stream_map , 'arr_car_to_print' => $arr_car_to_print , 'arr_edu_map' => $arr_edu_map , 'arr_car_reco' => $final_sort_car ];
        
        // $pagecount = $pdf->setSourceFile('report_template/uce-v3/career-listing.pdf');
        // $tpl = $pdf->importPage($pagecount);
        // $pdf->AddPage();
        // $pdf->useTemplate( $tpl, ['adjustPageSize'=>true]);
        // print_careers( $pdf , $arr_car_to_print , $arr_edu_map , $code );

    }
    
    function get_edu(  $arr_car , $tbl_name , $selectedStream = null , $die = null ){
        $con = $GLOBALS['con'];
        $arr_edu = [];
        $car_count_stream = [];
        // pre( $arr_car ,  1);
        foreach( $arr_car as $key => $car ){
            // echo "1";
            $careers = $car['profession_name'];
            $scr_car = $car['scr_car'];
            $sql = "SELECT Cluster, profession_name, stream_no, 11th_12th, Education, indian_name  FROM  ".$tbl_name." WHERE profession_name ='".mysqli_real_escape_string( $con ,$careers )."'";
            if( $die != null ){
                echo $sql;
                die; 
            }
            $result = mysqli_query( $con , $sql );
            if( $selectedStream != null ){
                // pre( $arr_car , 1 );
                if( mysqli_num_rows($result) > 1 ){
                    echo 'Added';
                    $inArray = false;
                    while( $streams = mysqli_fetch_array($result) ){
                        // if(in_array( $streams['11th_12th'] , $selectedStream)){
                        //     $inArray = true;
                        // }
                        // pre( [ $selectedStream , $streams['11th_12th'] ]);
                        if( in_array( $streams['11th_12th'] , $selectedStream )  ){
                            $car_count_stream[$key] = mysqli_num_rows($result);
                            $streams['scr_car'] = $scr_car;
                            $arr_edu[$key][] = $streams ;
                        }
                        // elseif( $streams['11th_12th'] == 'Any Stream' &&  $inArray === true ){
                        //     $car_count_stream[$key] = mysqli_num_rows($result);
                        //     $streams['scr_car'] = $scr_car;
                        //     $arr_edu[$key][] = $streams ;
                        // }
                    }
                }
                else{
                    $streams = mysqli_fetch_array(  $result );
                    // pre( [ $selectedStream , $streams['11th_12th'] ]);
                    if( in_array( $streams['11th_12th'] , $selectedStream ) ){
                        $car_count_stream[$key] = mysqli_num_rows($result);
                        $streams['scr_car'] = $scr_car;
                        $arr_edu[$key] = $streams;
                    }
                }
                // pre( $arr_edu );
            }
            else{
                $car_count_stream[$key] = mysqli_num_rows($result);
                $streams = mysqli_fetch_array(  $result );
                $streams['scr_car'] = $scr_car;
                $arr_edu[$key] = $streams;
            }
            // pre( $arr_edu );
        }
        // if( $selectedStream != null ){
        //     die;
        // }
        setToGlobal( [ 'car_count_stream' => $car_count_stream ] );
        return $arr_edu;
    }
    
    function car_cluster_map($arr_choices , $arr_cluster , $arr_choice_names){
        $arr_count = [];
        // pre( $arr_choices , 1 );
        foreach( $arr_cluster as $cluster ){
            foreach( $arr_choice_names as $reco ){
                $arr_count[$reco][$cluster['Cluster']] = 0;
            }
        }
        foreach( $arr_choices as $k => $choices ){
            if( !empty( $choices ) ){
                foreach( $choices as $career ){
                    foreach( $arr_cluster as $key => $cluster ){
                        foreach( $arr_choice_names as $reco ){
                            if( $career['cluster'] ==  $cluster['Cluster'] && $career['car_reco'] == $reco ){
                                $arr_count[$reco][$cluster['Cluster']] += 1;  
                            }
                        } 
                    }
                }
            }
        }
        return $arr_count;
    }

    function car_stream_map( $con, $arr_choices , $arr_stream , $arr_choice_names){
        $con = $GLOBALS['con'];
        $arr_count = [];
        foreach( $arr_stream as $stream ){
            foreach( $arr_choice_names as $reco ){
                $arr_count[$reco][$stream['11th_12th']] = 0;
                $arr_career[$reco][$stream['11th_12th']] = [];
            }
        }
        $sql = 'SELECT * FROM  career_edu_v2 WHERE 11th_12th = "';
        foreach( $arr_choices as $k => $choices ){
            if( !empty( $choices ) ){
                foreach( $choices as $choice_value ){
                    foreach( $arr_stream as $stream ){
                        foreach( $arr_choice_names as  $reco ){
                            if( $k == str_replace(' ', '_', strtolower($reco))){
                                $query = $sql.$stream['11th_12th'].'" AND  profession_name = "'.mysqli_real_escape_string($con, $choice_value['profession_name']).'"';
                                $result = mysqli_query( $con , $query );
                                if( mysqli_num_rows ( $result ) > 0 ){
                                    if( in_array( $reco , [ 'Good Choice', 'Top Choice' ] ) ){
                                        $filter[ mysqli_fetch_array($result)['11th_12th']][$stream['11th_12th']][ $reco ][] = $choice_value['profession_name'];
                                    }
                                    // pre( [ $choice_value['profession_name'] , mysqli_fetch_array($result)['11th_12th'] , $stream['11th_12th'], $reco  ] );
                                    $arr_count[$reco][$stream['11th_12th']] += 1;
                                    $arr_career[$reco][$stream['11th_12th']][]= [
                                        'careerName' => $choice_value['profession_name'],
                                        'careerScore' => round($choice_value['scr_car']*100/0.64 , 0),
                                        // 'car_reco' => $choice_value['car_reco'];
                                        'car_reco' => $reco
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        pre( $filter );
        // pre( $arr_career , 1 );
        setToGlobal( [ 'arr_career' => $arr_career ] );
        return $arr_count;
    }
    
    function scr_apt_v2($apt_usr, $apt_prof, $lvl_apt_SA , $apt_high){
        // echo "APT LVL";
        // pre( $apt_usr);
        // echo "APT PROF";
        // pre( $apt_prof , 1 );
        $arr_apt_names = [ 'AR' , 'VR' , 'SA' , 'COM' , 'NC' , 'OM' ];
        $arr_apt_param = [ 'lvl' => 'v' , 'imp' => 'w' ];
        $arr_lvl_map = [];
        $profession_name = $apt_prof['profession_name'];
        $flag = 0;
        foreach( $arr_apt_names as $k => $v ){
            // echo $a
            $a = $apt_usr[$k];
            $b = $apt_prof[$v."_".$arr_apt_param['lvl']];
            // echo "<br> a -".$a." b-".$b." <br>";
            $arr_lvl_map[] = ( $a >= $b   ?  1 : 0 );
            // if( $lvl_apt_SA == $v && $a >= 7*$apt_high ){ $flag = 0; }
            if( $lvl_apt_SA == $v && $a < 7*$apt_high ){
                $flag = 1;
            }
            // elseif( $lvl_apt_SA != $v ){ $flag = 0; }
        }
        
        $scr_apt = 0;
        foreach( $arr_apt_names as $k => $v ){
            $scr_apt = $scr_apt + $apt_prof[$v."_".$arr_apt_param['imp']]*$arr_lvl_map[$k];
        }
        
        if( $flag == 1 ){
            return 0;
        }
        return $scr_apt;
        // pre( "<br> Score - ".$scr_apt."<br>" );
        
    }
    
    function scr_int_v2($usr_int, $car_int , $norm = false){
        // echo "<pre>";
        // print_r($usr_int);
        if( $norm == true ){
            $scale = 1;
            $usr_int = normalise_v2($usr_int);
        }
        else{
            $scale = 10;
        }
        
        $int_name_map = [ 0 =>'R' , 1 => 'I' , 2 => 'A', 3 => 'S' , 4 => 'E' , 5 => 'C' ];
        // $int_name_map = [ ['R'] , ['I'] , ['A'], ['S'] , ['E'] , ['C'] ];
        $int_weight_map = [ 0.6 , 0.3 , 0.1 ];
        $int_val = 0;
        $int_tot = 0;
        $wgt_tot = 0;
        for( $i =0 ; $i < 3; $i++ ){
            $key = array_search( $car_int[$i], $int_name_map);
            // echo "<br> Key - ".$key." <br> user int - ".$usr_int[$key]."<br> weight -".$int_weight_map[$i];
            if( $key != '' ){
                $wgt_tot = $wgt_tot + $int_weight_map[$i];
                // $int_val = $usr_int[$key]*$int_weight_map[$i];
                $int_tot=$int_tot+$usr_int[$key];
                $int_val =$int_val+ $usr_int[$key]*$int_weight_map[$i];
            }
        }
        if( $int_tot != 0 ){
            $int_val = round( $int_val /($int_tot*$wgt_tot) , 2);
        }
        else{
            $int_val = 0;
        }
        return $int_val;
            // $usr_int_2 = $usr_int;
            // arsort($usr_int_2);
            // $key= array_keys($usr_int_2);
            // $int_scr = 0;
            // for($i=0 ; $i <3 ; $i++){
                
            //     $top_int[$i] = $int_name_map[$key[$i]];
            //     $top_int_val[$i] = $usr_int[$key[$i]];
            //     $int_scr = $int_scr+($top_int_val[$i]*$int_weight_map[$i]/$scale);
            //     echo "<br>";
            //     print_r($top_int[$i] )."<br>";
            //     echo "<br>";
            //     print_r( $top_int_val[$i] )."<br>";
            // }
            // echo $int_scr."<br>";
            // print_r($car_int )."<br>";
            // die;
    }
    
    function map_val_lvl( $scr , $arr_lvl ){
        foreach( $arr_lvl as $v ){
            if( $scr >= $v['lvl_low'] && $scr <= $v['lvl_high'] ){
                return $v['lvl_name'];
                break;
            }
        }
        return false;
    }
    
    function normalise_v2($arr_data){
        $arr_sum = array_sum( $arr_data );
        $arr_norm = [];
        foreach( $arr_data as $k => $v  ){
            $arr_norm[] = round($v/$arr_sum , 2);
        }
        return $arr_norm;
    }

    // To be coded to calculate user interest //
    function fetch_usr_int_v2($code){}

    function fetch_car_int_v2(){}

    function fetch_career_apt_v2(){}

    function fetch_career_int_v2(){}
    
    if($logo_orig){checking_size($logo,$pdf);}//page 11 of report
    
    function print_stream($pdf , $arr_stream_map , $pagecount){
        // pre( $arr_stream_map , 1);
        // logic test block uce v3 //
        // foreach ($arr_stream_map as $key => $value) {
        //     foreach ($value as $stream => $val){
        //         if( $key == 'Top Choice' ){
        //             $val = rand( 0 , 10  );
        //         }
        //         elseif( $key == 'Good Choice' ){
        //             $val = rand( 0 , 10 );
        //         }
        //         elseif( $key == 'Optional' ){
        //             $val = rand( 0 , 20 );
        //         }
        //         $arr_stream_map[$key][$stream] = $val;
        //     }
        // }
        // End logic test block uce v3 //
        // pre( $arr_stream_map  );
        $apart = 5;
        $total = 25;
        $chartX=55;
        $chartY=218;
        $chartWidth=140;
        $chartHeight=45;
        $chartTopPadding=0;
        $chartLeftPadding=0;
        $chartBottomPadding=0;
        $chartRightPadding=0;
        $chartBoxX=$chartX+$chartLeftPadding;
        $chartBoxY=$chartY+$chartTopPadding;
        $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
        $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
        $barWidth=7;
        $dataStep=20;
        $pdf->SetFont('Arial', '',9);
        $pdf->SetLineWidth(0);
        $pdf->SetDrawColor(0);
        $pdf->SetFillColor( '255' , '255' , '255' );
        $pdf->Line(
            $chartBoxX,
            $chartBoxY,
            $chartBoxX,
            $chartBoxY+$chartBoxHeight
        );
        $totalLines = ceil( $total/$apart );
        $chartIntoParts = ceil($chartWidth/$totalLines);
        $newVLineX = $chartBoxX ;
        $pdf->SetFont( 'Arial', 'B', 8 );
        $pdf->SetTextColor( 172,13,26 );
        $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
        $pdf->Cell(5,4, 0 , 0, 2,'L',false);
        for( $i=0; $i<$totalLines; $i++ ){
            //y position
            //draw  axis line
            $newVLineX = ( $newVLineX + $chartIntoParts );
            // pre( [ $newVLineX, $chartBoxY, $newVLineX, $chartBoxY+$chartBoxHeight ] );
            $pdf->Line(
                $newVLineX,
                $chartBoxY,
                $newVLineX,
                $chartBoxY+$chartBoxHeight
            );
            
            $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
            $pdf->Cell(5,4, (($i+1) * $apart) , 0, 2,'L',false);
        }
        $pdf->SetXY($chartX,$chartY);
        $y = $chartY;
        $t = $arr_stream_map['Top Choice'];
        $g = $arr_stream_map['Good Choice'];
        $o = $arr_stream_map['Optional'];
        $serialize = [];
        $index = 0;
        foreach( $t as $k => $v){
            $serialize[$index] = [ 'cluster' => $k , 'value' => ($t[$k]+$g[$k]+$o[$k]) , 'sum' => [ $t[$k]+$g[$k]  , $o[$k] ] ];
            $index++;
        }
        $keys = array_column($serialize, 'value');
        array_multisort($keys, SORT_DESC, $serialize);
        // pre( $serialize , 1 );
        $i = 0;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(172,13,26);
        $start = $chartY;
        $y = $chartY+3; 
        foreach( $serialize as $k => $v){
            $key = $v['cluster'];
            $step = round(( $chartWidth/$total )  , 2);
            $tg = $t[$key]+$g[$key];
            $og = $o[$key];
            $pdf->SetXY( 5, $start );
            $start += 7.2;
            $pdf->SetTextColor(172,13,26);
            $pdf->Cell(5);
            $pdf->Cell(($chartX-15),8,$key, 0, 2,'R',false);
            $x1 = $chartX;
            $x2 = $chartX;
            $pdf->SetFillColor(152, 72, 6);
            $pdf->SetTextColor( 255 , 255 , 255 );
            $pdf->SetXY($x1,$y);
            if( $tg <= $total && $tg > 0 ){
                $x2 = $chartX + ( $step * $tg );
                $pdf->Cell(( $step * $tg ),4,$tg, 0, 1,'C',1);
            }
            elseif( $tg > $total ){
                $x2 = $chartX + ( $step * $total );
                $pdf->Cell(( $step * $total ),4, $total."+", 0, 1,'C',1);
            }
            if( $tg < $total ){
                if( $og <= $total && $og > 0 ){
                    $pdf->SetFillColor(222, 145, 87);
                    $pdf->SetXY($x2,$y);
                    if( ($tg+$og) > $total ){
                        $last = ( $tg + $og ) - $total;
                        $pdf->Cell(( $step * ( $og - $last ) ),4,( $og - $last )."+", 0, 1,'C',1);
                    }
                    else{
                        $pdf->Cell(( $step * $og ),4,( $og ), 0, 1,'C',1);
                    }
                    // $pdf->Cell(( $step * $og ),4,$og, 0, 1,'C',1);
                }
                elseif( $og > $total ){
                    $pdf->SetFillColor(222, 145, 87);
                    $pdf->SetXY($x2,$y);
                    $pdf->Cell(( $step * ( $total - $tg ) ),4, ( $total - $tg )."+", 0, 1,'C',1);
                }
            }
            if( $og == 0 && $tg == 0){
                $pdf->SetXY($x1,$y);
                $pdf->Cell($step,4, 0, 0, 1,'C',false);
            }
            $y = $y+7.2;
            $i++;
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor( 152, 72, 6 );
        $pdf->Rect(80,270,4,4 , 'F');
        $pdf->SetXY(85,270);
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Cell(45,4, '( Top Choice + Good Choice )', 0, 1,'L',false);
        $pdf->SetFillColor( 222, 145, 87 );
        $pdf->Rect(140,270,4,4 , 'F');
        $pdf->SetXY(145,270);
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Cell(40,4, 'Optional Choice', 0, 1,'L',false);
        return $pdf;
    }

    // Indicators Left
    function print_cluster( $pdf , $arr_cluster_map ){
        $desc = $GLOBALS['career_stream_graph_desc'];

        // logic test block uce v3 //
        // foreach ($arr_cluster_map as $key => $value) {
        //     foreach ($value as $cluster => $val){
        //         if( $key == 'Top Choice' ){
        //             $val = rand( 0 , 10  );
        //         }
        //         elseif( $key == 'Good Choice' ){
        //             $val = rand( 0 , 10 );
        //         }
        //         elseif( $key == 'Optional' ){
        //             $val = rand( 0 , 20 );
        //         }
        //         $arr_cluster_map[$key][$cluster] = $val;
        //     }
        // }
        // End logic test block uce v3 //
        // pre(  $arr_cluster_map  );
        $pdf = $GLOBALS['pdf'];
        $apart = 5;
        $total = 25;
        $chartX=80;
        $chartY=58;
        $chartWidth=115;
        $chartHeight=116;
        $chartTopPadding=0;
        $chartLeftPadding=0;
        $chartBottomPadding=0;
        $chartRightPadding=0;
        $chartBoxX=$chartX+$chartLeftPadding;
        $chartBoxY=$chartY+$chartTopPadding;
        $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
        $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
        $barWidth=7;
        $dataStep=20;
        $pdf->SetFont('Arial', '',9);
        $pdf->SetLineWidth(0);
        $pdf->SetDrawColor(0);
        $pdf->SetFillColor( '255' , '255' , '255' );
        $pdf->Line(
            $chartBoxX,
            $chartBoxY,
            $chartBoxX,
            $chartBoxY+$chartBoxHeight
        );
        $totalLines = ceil( $total/$apart );
        $chartIntoParts = ceil($chartWidth/$totalLines);
        $newVLineX = $chartBoxX ;
        $pdf->SetFont( 'Arial', 'B', 8 );
        $pdf->SetTextColor( 172,13,26 );
        $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
        $pdf->Cell(5,4, 0 , 0, 2,'L',false);
        for( $i=0; $i<$totalLines; $i++ ){
            //y position
            //draw  axis line
            $newVLineX = ( $newVLineX + $chartIntoParts );
            // pre( [ $newVLineX, $chartBoxY, $newVLineX, $chartBoxY+$chartBoxHeight ] );
            $pdf->Line(
                $newVLineX,
                $chartBoxY,
                $newVLineX,
                $chartBoxY+$chartBoxHeight
            );
            
            $pdf->SetXY( ( $newVLineX - 2 ), ($chartBoxY+$chartBoxHeight+1) );
            $pdf->Cell(5,4, (($i+1) * $apart) , 0, 2,'L',false);
        }
        $pdf->SetXY($chartX,$chartY);
        $y = $chartY;
        $t = $arr_cluster_map['Top Choice'];
        $g = $arr_cluster_map['Good Choice'];
        $o = $arr_cluster_map['Optional'];
        $serialize = [];
        $index = 0;
        foreach( $t as $k => $v){
            $serialize[$index] = [ 'cluster' => $k , 'value' => ($t[$k]+$g[$k]+$o[$k]) , 'sum' => [ $t[$k]+$g[$k]  , $o[$k] ] ];
            $index++;
        }
        $keys = array_column($serialize, 'value');
        array_multisort($keys, SORT_DESC, $serialize);
        // pre( $serialize );
        $i = 0;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(172,13,26);
        $start = $chartY;
        $y = $chartY+3; 
        foreach( $serialize as $k => $v){
            $key = $v['cluster'];
            $step = round(( $chartWidth/$total )  , 2);
            $tg = $t[$key]+$g[$key];
            $og = $o[$key];
            $pdf->SetXY( 5, $start );
            $start += 7.2;
            $pdf->Cell(5);
            $pdf->SetTextColor(172,13,26);
            $pdf->Cell(($chartX-10),8,$key, 0, 2,'R',false);
            $x1 = $chartX;
            $x2 = $chartX;
            $pdf->SetFillColor(152, 72, 6);
            $pdf->SetTextColor(255 , 255 , 255);
            $pdf->SetXY($x1,$y);
            if( $tg <= $total && $tg > 0 ){
                $x2 = $chartX + ( $step * $tg );
                $pdf->Cell(( $step * $tg ),5,$tg, 0, 1,'C',1);
            }
            elseif( $tg > $total ){
                $x2 = $chartX + ( $step * $total );
                $pdf->Cell(( $step * $total ),5, $total."+", 0, 1,'C',1);
            }


            if( $tg < $total ){
                if( $og <= $total && $og > 0 ){
                    $pdf->SetFillColor(222, 145, 87);
                    $pdf->SetXY($x2,$y);
                    if( ($tg+$og) > $total ){
                        $last = ( $tg + $og ) - $total;
                        $pdf->Cell(( $step * ( $og - $last ) ),5,( $og - $last )."+", 0, 1,'C',1);
                    }
                    else{
                        $pdf->Cell(( $step * $og ),5,( $og ), 0, 1,'C',1);
                    }
                }
                elseif( $og > $total ){
                    $pdf->SetFillColor(222, 145, 87);
                    $pdf->SetXY($x2,$y);
                    $pdf->Cell(( $step * ( $total - $tg ) ),5, ( $total - $tg )."+", 0, 1,'C',1);
                }
            }
            if( $og == 0 && $tg == 0){
                $pdf->SetXY($x1,$y);
                $pdf->Cell($step,4, 0, 0, 1,'C',false);
            }
            $y = $y+7.2;
            $i++;
        }
        // $pdf->SetXY(5,120);
        // $pdf->Cell(250,7, $desc , 0, 1,'C',false);
        return $pdf;
    }
    
    function print_careers( $pdf , $arr_to_print , $arr_edu_map , $code , $heading = null , $filter = null ){
        $car_str_cnt_map = $GLOBALS['car_count_stream'];
        // pre( $arr_to_print );
        // pre( $GLOBALS['car_count_stream'] );
        // pre( $arr_edu_map , 1 );
        // $tpl = $pdf->importPage(1);
        // $pdf->addPage();
        // $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
        $con = $GLOBALS['con'];
        // $pdf = $GLOBALS['pdf'];
        $pdf->SetFont('Arial','B',24);
        $pdf->SetXY(10,22);
        $pdf->SetTextColor(152, 72, 6);
        $pdf->SetFillColor(255,255,255);
        if( $heading != null ){
            $pdf->Cell(80,6,'Career Suggestions',0,0,'L',true);
            $pdf->SetXY(10,32);
            $pdf->SetFont('Arial','B',12);
            $pdf->MultiCell(185,6,$heading);
        }
        else{
            $pdf->Cell(80,6,'Career Suggestions',0,0,'L',true);
        }
        $pdf->SetFont('Arial','B',9);
        $pdf->SetXY(10,46);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFillColor(152, 72, 6);
        $pdf->Cell(40,8,'Profession Name',1,0,'L',true); // First header column 
        $pdf->Cell(65,8,'Cluster',1,0,'L',true); // First header column 
        $pdf->Cell(20,8,'11Th / 12Th',1,0,'L',true); // Second header column
        // $pdf->Cell(40,8,'Higher Education',1,0,'L',true); // Third header column 
        $pdf->Cell(15,8,'I-Match',1,0,'L',true); // Fourth header column 
        $pdf->Cell(15,8,'A-Match',1,0,'L',true); // Fourth header column 
        $pdf->Cell(30,8,'Recommendation',1,1,'L',true); // Fourth header column 
        $xn = $x = $pdf->GetX();
        $yn = $y = $pdf->GetY();
         echo __LINE__."<br>" ;
        pre( [ $xn , $yn ] );
        $pdf->SetFont('Arial','B',9);
        $maxheight = 0;
        $width=46;
        $height=5.5;
        $cells = 7;
        
        //$width_cell=array(38,30,48,70);
        
        $page_height = 470;
        // mm 
        $pdf->SetAutoPageBreak(true);
        // echo "<pre>";
        // pre($t , 1);
        // echo "<br>here------------------<br>";
    
        $arr_professions_final = [];
        $columns= '';
        $values= '';
        $sql = 'DELETE FROM career_sui_v2 WHERE code = "'.$code.'"';
        $result  = mysqli_query(  $con ,$sql );
        $sql = 'INSERT INTO career_sui_v2 (code, profession_name , lvl_int,scr_int,lvl_apt,scr_apt ,scr_car ,car_reco ,stream, education ) VALUES ';
        // echo "Hrllo";
        // pre( $arr_edu_map , 1 );
        foreach($arr_to_print as $k=>$v)
        {
            // pre( $v );
            //Lines added on 25-04-22 - to adapt for SA   
            $cluster = $v['cluster'];
            // $reco = $v[1];
            if( $v['common_name'] == null ){
                $prof = $v['profession_name'];
            }
            else{
                $prof = $v['common_name'];
            }
            $a_match = $v['lvl_apt'];
            $i_match = $v['lvl_int'];
            $car_reco_print = $v['car_reco'];
            // pre( $car_str_cnt_map , 1 );
            
            if( $filter == 'check' ){
                // echo $k;
                // pre(  $car_str_cnt_map );
                // pre( $car_str_cnt_map); 
                // pre( $filter , 1 ); 
                if( isset( $car_str_cnt_map[$k] )){
                    if( $car_str_cnt_map[$k] > 1 ){
                        foreach ($arr_edu_map[$k] as $streamSelect) {
                            $stream = $streamSelect['11th_12th'];
                            $edu = $streamSelect['Education'];
                            $scr = round($streamSelect['scr_car']*100/0.64 , 0);
                            // $arr_professions_final[] = [ $prof, $cluster, $stream,$edu , $a_match , $i_match , $car_reco_print ];
                            if($scr > 99 ){
                                $scr = '99+';
                            }
                            $arr_professions_final[] = [ $prof, $cluster, $stream , $a_match , $i_match , $car_reco_print.'('.$scr.')' , $edu  ];
                        }
                    }
                    else{
                        $stream = $arr_edu_map[$k]['11th_12th'];
                        $edu = $arr_edu_map[$k]['Education'];
                        $scr = round($arr_edu_map[$k]['scr_car']*100/0.64 , 0);
                        // $arr_professions_final[] = [ $prof, $cluster, $stream,$edu , $a_match , $i_match , $car_reco_print ];
                        if($scr > 99 ){
                            $scr = '99+';
                        }
                        $arr_professions_final[] = [ $prof, $cluster, $stream , $a_match , $i_match , $car_reco_print.'('.$scr.')' , $edu  ];
                    }
                    extract( $v );
                    $profession_name = mysqli_real_escape_string( $con, $profession_name );
                    $edu = mysqli_real_escape_string($con, $edu);
                    $value = "('$code','$profession_name','$lvl_int','$scr_int','$lvl_apt','$scr_apt','$scr_car','$car_reco','$stream','$edu')";
                    // echo $value;
                    if( $k == 0 ){
                        $sql = $sql.$value;
                    }
                    else{
                        $sql = $sql." , ".$value;
                    }
                }
            }
            else{
                $stream = $arr_edu_map[$k]['11th_12th'];
                $edu = $arr_edu_map[$k]['Education'];
                $scr = round($arr_edu_map[$k]['scr_car']*100/0.64 , 0);
                // $arr_professions_final[] = [ $prof, $cluster, $stream,$edu , $a_match , $i_match , $car_reco_print ];
                if($scr > 99 ){
                    $scr = '99+';
                }
                $arr_professions_final[] = [ $prof, $cluster, $stream , $a_match , $i_match , $car_reco_print.'('.$scr.')' , $edu  ];
                extract( $v );
                $profession_name = mysqli_real_escape_string( $con, $profession_name );
                $edu = mysqli_real_escape_string($con, $edu);
                $value = "('$code','$profession_name','$lvl_int','$scr_int','$lvl_apt','$scr_apt','$scr_car','$car_reco','$stream','$edu')";
                // echo $value;
                if( $k == 0 ){
                    $sql = $sql.$value;
                }
                else{
                    $sql = $sql." , ".$value;
                }
            }
        }
        
        // echo $sql;
        // die;
        $result  = mysqli_query( $con ,  $sql );
        // if( $result <= 0 ){
        //     header("Location: ".$sign_url."UserController/user_buyer/failed");
        // }
        // echo __LINE__."<br>" ;
        // pre( $arr_professions_final , 1 );
        foreach($arr_professions_final as $item){
            $x = $x;
            $y = $y+$maxheight;
             echo __LINE__."<br>" ;
            pre( [ 'x' => $x , 'y' => $y ] );
            $height_of_cell = $y-$yn;
             echo __LINE__."<br>" ;
            pre( [ 'height_of_cell' => $height_of_cell ] );
            $space_left=$page_height-($y); // space left on page
             echo __LINE__."<br>" ;
            pre( [ 'space_left' => $space_left , 'page_height' => $page_height  ] ); // space left on page
            if ($height_of_cell > $space_left) 
            {
                $pdf = setSourceFile( $GLOBALS['career_listing_page'] );
                $pdf->SetFont('Arial','B',9);
                $pdf->SetXY(10,46);
                $pdf->SetTextColor(255,255,255);
                $pdf->SetFillColor(152, 72, 6);
                $pdf->Cell(40,8,'Profession Name',1,0,'L',true); // First header column 
                $pdf->Cell(65,8,'Cluster',1,0,'L',true); // First header column 
                $pdf->Cell(20,8,'11Th / 12Th',1,0,'L',true); // Second header column
                // $pdf->Cell(40,8,'Higher Education',1,0,'L',true); // Third header column 
                $pdf->Cell(15,8,'I-Match',1,0,'L',true); // Fourth header column 
                $pdf->Cell(15,8,'A-Match',1,0,'L',true); // Fourth header column 
                $pdf->Cell(30,8,'Recommendation',1,1,'L',true); // Fourth header column  
                $xn = $x = $pdf->GetX();
                $yn = $y = $pdf->GetY();
                 echo __LINE__."<br>" ;
                pre( [ 'xn' => $xn , 'yn' => $yn ] );
                $pdf->SetFont('Arial','B',9);
                $x=$xn;
                $y=$yn;
            }
            // echo __LINE__."<br>" ;
            $maxheight = 0;
            $pdf->SetTextColor(0,0,0);
            $getY = [];
            for ($i = 0; $i < $cells; $i++)
            {
                if($i==0)
                {
                    $pdf->SetXY($x + (0) , $y);
                    $pdf->MultiCell(40, $height, $item[$i],0,'L');
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $getY[] = $pdf->GetY();
                }
                else if($i==1)
                {
                    $pdf->SetXY($x + (40) , $y);
                    $pdf->MultiCell(65, $height, $item[$i],0,'L'); 
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $getY[] = $pdf->GetY();
                }
                else if($i==2)
                {
                    $pdf->SetXY($x + (105) , $y);
                    $pdf->MultiCell(20, $height, $item[$i],0,'C'); 
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $getY[] = $pdf->GetY();
                }
                else if($i==3)
                {
                    $pdf->SetXY($x + (125) , $y);
                    $pdf->MultiCell(15, $height, $item[$i],0,'C');  
                    $getY[] = $pdf->GetY();
                }
                else if($i==4)
                {
                    $pdf->SetXY($x + (140) , $y);
                    $pdf->MultiCell(15, $height, $item[$i] ,0,'C');
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $getY[] = $pdf->GetY();
                }
                elseif($i==5){
                    $pdf->SetXY($x + (155) , $y);
                    $pdf->MultiCell(30, $height, $item[$i] ,0,'C');
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $getY[] = $pdf->GetY();
                }
                elseif($i==6){
                    echo __LINE__."<br>" ;
                    pre( [ $pdf->GetY() , $item[$i] ] );
                    $maxheight = max( $getY ) - $y;
                    pre( [ 'y' => $y , 'getY' => $pdf->GetY() , 'maxheight' => $maxheight ] );
                    $lastlineY = max( $getY );
                    $pdf->SetXY( $x , $lastlineY );
                    $pdf->MultiCell(180, $height, 'Educational Roadmap : - '.$item[$i] ,0,'L');
                    $bottomLineIndex = $pdf->GetY() - $lastlineY;
                }
                if ($pdf->GetY() - $y > $maxheight)
                {
                    echo __LINE__."<br>" ;
                    pre( [ 'GetY' => $pdf->GetY() , 'y' => $y , 'maxheight' => $maxheight ] );
                    $maxheight = $pdf->GetY() - $y;
                    echo __LINE__." last line<br>" ;
                    pre( [ 'maxheight' => $maxheight , 'bottomLineIndex' => $bottomLineIndex] );
                }
            }

            // echo __LINE__."<br>" ;
            // $pdf->SetXY($x + ($width * ($i + 1)), $y);

            for ($i = 0; $i < $cells + 1; $i++) 
            {
                if($i==0)
                {
                    pre( [ $x + 30 * $i, $y, $x + 30 * $i, ( $y + $maxheight ) - $bottomLineIndex ] );
                    $pdf->Line($x + 30 * $i, $y, $x + 30 * $i, $y + $maxheight);
                }
                else if($i==1)
                {
                    pre( [ $x + 40 * $i, $y, $x + 40 * $i, $y + $maxheight] );
                    $pdf->Line($x + 40 * $i, $y, $x + 40 * $i, ( $y + $maxheight ) - $bottomLineIndex ); 
                }
                else if($i==2)
                {
                    pre( [ $x + 105, $y, $x + 105, $y + $maxheight ] );
                    $pdf->Line($x + 105, $y, $x + 105, ( $y + $maxheight ) - $bottomLineIndex ); 
                }
                else if($i==3)
                {
                    pre( [ $x + 125, $y, $x + 125, $y + $maxheight] );
                    $pdf->Line($x + 125, $y, $x + 125, ( $y + $maxheight ) - $bottomLineIndex  ); 
                }
                else if($i==4)
                {
                    pre( [ $x + 140, $y, $x + 140, $y + $maxheight ] );
                    $pdf->Line($x + 140, $y, $x + 140, ( $y + $maxheight ) - $bottomLineIndex  );
                }
                else if($i==5)
                {
                    pre( [ $x + 155, $y, $x + 155, $y + $maxheight ] );
                    $pdf->Line($x + 155, $y, $x + 155, ( $y + $maxheight ) - $bottomLineIndex  );
                }
                pre( [ $x + 184.5, $y, $x+ 184.5, $y + $maxheight ] );
                $pdf->Line($x + 184.5, $y, $x+ 184.5,  $y + $maxheight );
            }
            echo __LINE__."<br>";
            $pdf->Line($x, $y, $x + 184, $y);// top line
            echo __LINE__."<br>";
            $pdf->Line( $x, ( $y + $maxheight ) - $bottomLineIndex, $x + 184, ( $y + $maxheight ) - $bottomLineIndex);
            $pdf->Line($x, $y + $maxheight, $x + 184, $y + $maxheight);//bottom
            echo __LINE__."<br>";
            pre( [ 'x' => $x , 'y' => $y , 'maxheight' => $maxheight ] );
        }
        // die;
        // echo "hello";
        return $pdf;
    }

    function counsellor_remarks( $pageName ){
        $pdf = $GLOBALS['pdf'];
        $pdfUrl = $GLOBALS['pdfUrl'];
        $remark_date = $GLOBALS['remark_date'];
        $c_remark = $GLOBALS['c_remark'];
        $signature = $GLOBALS['signature'];
        include('v3-remark.php');
        return $pdf;
    }
    // $pdf=counsellor_remarks('counsellor-remarks');

    ob_end_clean();
    $pdf->AliasNbPages();
    $filename = $GLOBALS['code']."-".str_replace(' ', '-', $detail_row['name'] ).".pdf";
    $pdf->Output('F', '../assets/report-pdf/' .$filename, true);
    // $report_status = "SELECT report_request WHERE code='$code'";
    // $report_status = mysqli_query($con,$report_status);
    // $report_status = mysqli_fetch_array($report_status);
    $sql_for_report_status = "update report_request set status = 1 , file_name = '".$filename."' where code='$code'";
    $res_for_logo = mysqli_query($con,$sql_for_report_status);
    $pdf->Output($filename, 'I');

    // $pdf2->Output();
    ob_end_flush();

?>