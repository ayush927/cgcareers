<?php
  class Uce_version_3 extends CI_Controller{
    function __construct(){
        parent::__construct();

        $this->part2_timer = [
            'uce_part2'   => 4,
            'uce_part2_2' => 4, 
            'uce_part2_3' => 5,
            'uce_part2_4' => 5,
            'uce_part2_5' => 3, 
            'uce_part2_6' => 4// 7 Additonal 2 minute for image loading
        ];
        // For Test
        // $this->part2_timer = [
        //     'uce_part2'   => 1,
        //     'uce_part2_2' => 1, // 8
        //     'uce_part2_3' => 1, // 9 Additonal 1 minute for image loading
        //     'uce_part2_4' => 1, // 6
        //     'uce_part2_5' => 1, // 6 
        //     'uce_part2_6' => 1// 7 Additonal 2 minute for image loading
        // ];
        
        $this->table = [
            'assessment_variation' => 'user_assessment_variation',
            'test_details' => 'ppe_part1_test_details',
            'instruction' => 'solution_instruction',
            'code_list' => 'user_code_list',
            'variation_info' => 'user_assessment_variation',
            'uce_part1' => 'uce_part1',
            'uce_part1_2' => 'uce_part1_2',
            'uce_part1_4' => 'uce_part1_4',
            'uce_part1_4_2' => 'uce_part1_4_2',
            'uce_part1_5' => 'uce_part1_5_v3',
            'rank_ordring_submit' => 'wls_part2_rank_ordring',
            'wls_detail' => 'wls_part2_1_detail',
            'option' => 'assessment_options',
            'assessment_info' => 'user_assessment_info',
            'uce_part2' => 'uce_part2',
            'uce_part2_2' => 'uce_part2_2',
            'uce_part2_5' => 'uce_part5',
            'uce_part2_7' => 'uce_part2_7',
        ];

        $this->uce_part = [
            'uce_part1' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part1'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_1',
                'option_tbl' => $this->table['option'],
                'instruction' => 'uce_part1',
                'nextPart' => 'uce_part1_2',
                'optionData' => 'text',
                'optionType' => 'radios'
            ],
            'uce_part1_2' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part1_2'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_2',
                'option_tbl' => $this->table['option'],
                'instruction' => 'uce_part1_2',
                'nextPart' => 'uce_part1_3',
                'optionData' => 'text',
                'optionType' => 'radios',
                'prePart' => 'uce_part1'
            ],
            'uce_part1_3' => [
                'submitTable' => $this->table['rank_ordring_submit'],
                'queTable' => $this->table['wls_detail'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_3',
                'instruction' => 'uce_part1_3',
                'nextPart' => 'uce_part1_4',
                'optionData' => 'text',
                'optionType' => 'checkbox',
                'prePart' => 'uce_part1_2'
            ],
            'uce_part1_4' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part1_4'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_4_1',
                'instruction' => 'uce_part1_4',
                'nextPart' => 'uce_part1_4_2',
                'optionData' => 'text',
                'optionType' => 'radios',
                'prePart' => 'uce_part1_3'
            ],
            'uce_part1_4_2' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part1_4_2'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_4_2',
                'instruction' => 'uce_part1_4_2',
                'nextPart' => 'uce_part1_5',
                'optionData' => 'text',
                'optionType' => 'radios',
                'prePart' => 'uce_part1_4'
            ],
            'uce_part1_5' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part1_5'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part1_5',
                'instruction' => 'uce_part1_5',
                'nextPart' => 'uce_part2',
                'optionData' => 'text',
                'optionType' => 'radios',
                'prePart' => 'uce_part1_4_2'
            ],
            'uce_part2' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2'],
                'part' => 'part1',
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2',
                'instruction' => 'uce_part2',
                'nextPart' => 'uce_part2_2',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'text',
                'prePart' => 'uce_part1_5'
            ],
            'uce_part2_2' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2_2'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_2',
                'instruction' => 'uce_part2_2',
                'nextPart' => 'uce_part2_3',
                'optionType' => 'radios',
                'optionData' => 'text',
                'timer' => true,
                'prePart' => 'uce_part2'
            ],
            'uce_part2_3' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2'],
                'part' => 'part3',
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_3',
                'instruction' => 'uce_part2_3',
                'nextPart' => 'uce_part2_4',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'image',
                'prePart' => 'uce_part2_2'
            ],
            'uce_part2_4' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2'],
                'part' => 'part4',
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_4',
                'instruction' => 'uce_part2_4',
                'nextPart' => 'uce_part2_5',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'text',
                'prePart' => 'uce_part2_3'
            ],
            'uce_part2_5' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2_5'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_5',
                'instruction' => 'uce_part2_5',
                'nextPart' => 'uce_part2_6',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'text',
                'prePart' => 'uce_part2_4'
            ],
            'uce_part2_6' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2'],
                'part' => 'part6',
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_6',
                'instruction' => 'uce_part2_6',
                'nextPart' => 'uce_part2_7',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'image',
                'prePart' => 'uce_part2_5'
            ],
            'uce_part2_7' => [
                'submitTable' => $this->table['test_details'],
                'queTable' => $this->table['uce_part2_7'],
                'pageName' => 'uce_part1_1',
                'solution' => 'uce_part2_7',
                'instruction' => 'uce_part2_7',
                'nextPart' => 'uce_part7',
                'optionType' => 'radios',
                'timer' => true,
                'optionData' => 'text',
                'prePart' => 'uce_part2_6'
            ],
        ];
        $this->variation = 'three';
        $this->backdoor();
    }
    function backdoor(){
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'BaseController/login');
        }
    }

    function initialize(){
        return [
            'user' =>  $this->session->userdata('user'),
            'reseller_sp' => $this->Commen_model->get_reseller_sp($this->session->userdata('user')['user_id']),
            'allowed_services' => $this->Base_model->getUserDetailsByIds($this->session->userdata('user')['user_id']),
        ];
    }

    function rand_array_prepare($code)
    {
        $codeDetail = getQuery( [ 'where' => [ 'code' => $code ]   , 'table' => $this->table['code_list'] , 'single' => true ] );
        $var_detail = getQuery( [ 'where' => [ 'code' => $code ]   , 'table' => $this->table['assessment_variation'] , 'single' => true ] );
        if( !empty($var_detail) ){
            if( $var_detail['rand_array'] == '' ){
                // pre( $codeDetail , 1 );
                $partData = $this->uce_part;
                // pre( $partData , 1 );
                $grp_arr = [];
                foreach( $partData as $key => $value){
                    $grp_arr[$key]=rand_que_group(  $codeDetail['solution'] ,  $key , ( isset( $value['part'] ) ? $value['part'] : NULL ) , $value['queTable'] );
                }
                // pre( $grp_arr , 1 );
                if( !empty( $grp_arr ) ){
                    update( [ 'where' => [ 'code' =>  $code ] , 'data' => [ 'rand_array' => json_encode( $grp_arr ) ] , 'table' => $this->table['assessment_variation'] ] );
                }
                return json_decode(json_encode($grp_arr)) ;
            }
            else{
                return json_decode( $var_detail['rand_array'] );
            }
        }
    }

    public function view_question($partName , $code){
        $grp_detail = $this->rand_array_prepare(base64_decode($code));
        // pre( $grp_detail );
        $data = $this->initialize();
        $code = base64_decode($code);
        $partData = $this->uce_part[$partName];
        $where_in['q_group'] = $grp_detail->$partName;
        // pre( $this->uce_part );
        // pre( $partData  , 1);
        // pre( $where_in , 1 );
        if( isset( $partData['part'] ) ){
            $where = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] ];
        }
        else{
            $where = [ 'asmt_variant_3' => 1 ];
        }
        if( $partName != 'uce_part1_3' ){
            $que =  getQuery( [ 'where' => $where , 'where_in' => $where_in , 'table' => $partData['queTable'] , 'num_rows' => true , 'single' => true ] );
            // lQ(1);
            $completed = getQuery( [ 'where' => [  'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ]  , 'table' => $partData['submitTable'] , 'num_rows' => true , 'single' => true ] );
        //    pre( [$que , $completed ] ,1 );
            if($que==$completed){
                if( $partName == 'uce_part1_5' || $partName == 'uce_part2_6' || $partName == 'uce_part2_7' ){
                    update( [ 'where' => [ 'user_id' =>   $data['user']['email'] , 'code' => $code , 'part' => ( $partName == 'uce_part1_5' ? 'Part 1' : ( $partName == 'uce_part2_6' ? 'Part 2' : 'Part 3' ) )] , 'data' => ['status' => 'Rp']  , 'table' => $this->table['assessment_info'] ] );
                    redirect(base_url().'baseController/view-code/'.$code);
                }
                else{
                    redirect(base_url().'assessment-variations/three/'.$partData['nextPart'].'/'.base64_encode($code));
                }
            }
        }
        elseif( $partName == 'uce_part1_3' ){
            $que =  getQuery( [ 'table' => $partData['queTable'] , 'where_in' => $where_in , 'where' => $where , 'num_rows' => true , 'single' => true ] );
            $completed = getQuery( [ 'where' => [ 'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ] , 'table' => $this->table['rank_ordring_submit' ] , 'num_rows' => true , 'single' => true ] );
            // pre( [ $que , $completed ] ,1 );
            if($que==$completed){
                redirect(base_url().'assessment-variations/three/'.$partData['nextPart'].'/'.base64_encode($code));
            }
        }

        if( isset( $partData['timer'] ) ){
            $where = "user_id='".$data['user']['email']."' and code='$code' and link='uce_part2'";
            $this->db->where( $where );
            $part2Data =  $this->db->get($this->table['assessment_info'])->row_array();
            $data['assessmentStart'] = $part2Data['start_time'];
            if(  $data['assessmentStart'] != '' ){
                $data['EndTime'] = date( 'Y-m-d H:i:s' , strtotime( "+".$part2Data['remain_time']." minutes", strtotime($data['assessmentStart']) ));
            }
            else{
                $data['EndTime'] = '';
            }
        }
        $data['code'] = $code;
        $data['partName'] = $partName;
        $data['detail'] = getQuery( [ 'where' => [ 'solution' => $partData['instruction'] ] ,  'table' => $this->table['instruction'] , 'single' => true ] );
        $data['pageName'] = 'user/v3/'.$partData['pageName'];
        // pre( $data  , 1);
        $data['nextPartName'] = $partData['nextPart'];
        view( 'user/layout' , [ 'data' => $data ] );
    }

    function get_question( $code , $partName){
        $grp_detail = $this->rand_array_prepare($code);
        $where_in['q_group'] = $grp_detail->$partName;
        $data = $this->initialize();
        $partData = $this->uce_part[$partName];
        $getCodeData = getQuery( [ 'where' => [ 'code' => $code ] , 'table' => $this->table['code_list'] , 'single' => true ] );
        if( !empty( $getCodeData ) ){
            $variation_info = getQuery( [ 'where' => [ 'code' => $code ] , 'table' => $this->table['variation_info'] , 'single' => true ] );
            if( !empty($variation_info) ){
                if( $variation_info['variation'] == 'three' ){
                    if( $partName != 'uce_part1_3' ){
                        if( isset( $partData['part'] ) ){
                            $where = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] , 'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ];
                            $queTableWhere = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] ];
                        }
                        else{
                            $where = [ 'asmt_variant_3' => 1 , 'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ];
                            $queTableWhere = [ 'asmt_variant_3' => 1 ];
                        }
                        
                        $completed = getQuery( [ 'select' => $partData['queTable'].".* , ".$partData['submitTable'].".ans" , 'where' => $where , 'table' =>  $partData['submitTable'] , 'order' => [ 'id' => 'DESC' ] , 'join' => [ [ $partData['queTable'] , $partData['queTable'].'.qno = '.$partData['submitTable'].'.qno' , 'INNER' ] ] , 'order' => [ $partData['submitTable'].'.id' => 'ASC' ] ] );

                        $queCompleted = getQuery( [ 'select' => 'GROUP_CONCAT( qno SEPARATOR "," ) as qno' , 'where' => [ 'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ]  , 'table' =>  $partData['submitTable'] , 'order' => [ 'id' , 'DESC' ] , 'single' => true ] );

                        if( isset( $partData['option_tbl'] ) ){
                            $option = getQuery( [ 'where' => [ 'assessment' => $partData['solution'] ] , 'table' => $partData['option_tbl'] ] );
                        }

                        if( $queCompleted['qno'] ==  '' ){
                            $question =  getQuery( [ 'where_in' => $where_in, 'where' => $queTableWhere , 'table' => $partData['queTable']
                            // 'order' => 'rand()' 
                            ] );
                        }
                        else{
                            $question =  getQuery( [ 'where_in' => $where_in , 'where' => $queTableWhere , 'table' => $partData['queTable'] , 
                            // 'order' => 'rand()' , 
                            'where_not_in_numeric' => [ 'qno' => $queCompleted['qno'] ] ] );
                            // lQ(1);
                        }
                        $limit = 5;
                    }
                    elseif( $partName == 'uce_part1_3' ){
                        
                        $completed = getQuery( [ 
                            'select' => $partData['queTable'].".* , ".$partData['submitTable'].".ordr" ,
                            'where' => [  'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ], 'table' =>  $partData['submitTable'],
                            'join' => [ [ $partData['queTable'] , $partData['queTable'].'.qno = '.$partData['submitTable'].'.qno AND '.$partData['queTable'].'.grp = '.$partData['submitTable'].'.grp' , 'INNER' ] ] 
                            , 'order' => [ $partData['queTable'].'.id' => 'ASC' ]
                        ] );
                        if( ! empty( $completed ) ){
                           $question =  getQuery( [ 'where_in' => $where_in , 'where' => [ 'id >' => $completed[count($completed)-1]['id'] ] , 'table' => $partData['queTable'] , 'order' => [ 'id' => 'ASC' ] ] );
                        }
                        else{
                            $question =  getQuery( [ 'where_in' => $where_in , 'table' => $partData['queTable'] , 'order' => [ 'id' => 'ASC' ] ] );
                        }
                        $limit = 25;
                    }
                    $response = [ 'status' => 'success' , 'code' => 200 , 'queCompleted' => $completed , 'left' => $question , 'redirect' => $partData['nextPart'].'/'.base64_encode($code) , 'option' => ( isset( $option ) ? $option : []) , 'optionType' => $partData['optionType'] , 'optionData' => (isset( $partData['optionData'] ) ? $partData['optionData'] : '') ,'limit' => $limit ];
                }
                else{
                    $response = [ 'status' => 'failed' , 'code' => '301' , 'msg' => 'code is not in variation three' ];
                }
            }
            else{
                $response = [ 'status' => 'failed' , 'code' => '301' , 'msg' => 'code is not in variation three' ];
            }
        }
        else{
            $response = [ 'status' => 'failed' , 'code' => '303' , 'msg' => 'Gievn Code is not avalable' ];
        }
        echo json_encode( $response );
    }

    function submit_answers($code , $partName){
        $data = $this->initialize();
        $partData = $this->uce_part[$partName];
        if( !empty( $_POST ) ){
            extract( $_POST );
            if( $partName != 'uce_part1_3' ){
                if( $partData['optionType'] == 'checkbox' ){
                    foreach( $submitArray as $itemKey => $item ){
                        $values = '';
                        foreach ($item as $key => $value) {
                            $finalArray[$itemKey] = [ 'question' => $value['question'] , 'value' => $values .=$value['value'].( $key == count($item)-1 ? '' : ',') ];
                        }
                    }

                    $submitArray = $finalArray;
                }
                foreach( $submitArray as $row ){
                    $checkQuestionExist = getQuery([ 'where' => [ 'email' => $data['user']['email'], 'qno' => $row['question'], 'solution'=>$partData['solution'],'code'=>$code ], 'table' => $partData['submitTable'] , 'single' => true ]);
                    // pre( $checkQuestionExist , 1);
                    if( !empty( $checkQuestionExist ) ){
                        $updateFormArray[] = [
                            'id' => $checkQuestionExist['id'],
                            'ans'=>$row['value']
                        ];
                    }
                    else{
                        $formArray[] = [
                            'email' => $data['user']['email'],
                            'qno' => $row['question'],
                            'solution' => $partData['solution'],
                            'code' => $code,
                            'ans'=>$row['value']
                        ];
                    }
                }
            }
            elseif( $partName == 'uce_part1_3' ){
                // pre( $submitArray , 1 );
                foreach( $submitArray as $row ){
                    if(!empty( $row ) ){
                        foreach ($row as $key => $value){
                            $checkQuestionExist = getQuery([ 'where' => [ 'email' => $data['user']['email'], 'solution' => $partData['solution'], 'code'=> $code , 'grp' => $value['grp'] , 'qno' => $value['qno'] ], 'table' => $partData['submitTable'] , 'single' => true ]);
                            if( !empty( $checkQuestionExist ) ){
                                $updateFormArray[] = [
                                    'id' => $checkQuestionExist['id'],
                                    'ordr' => $key+1
                                ];
                            }
                            else{
                                $formArray[] = [
                                    'email' => $data['user']['email'],
                                    'solution' => $partData['solution'],
                                    'code' => $code,
                                    'qno' => $value['qno'],
                                    'grp'=>$value['grp'],
                                    'ordr' => $key+1
                                ];
                            }
                        }
                    }
                }
            }
            if( $last == 'lastPage' ){
                if( $partName == 'uce_part1_5' || $partName == 'uce_part2_6' || $partName == 'uce_part2_7' ){
                    update( [ 'where' => [ 'user_id' =>   $data['user']['email'] , 'code' => $code , 'part' => ( $partName == 'uce_part1_5' ? 'Part 1' : ( $partName == 'uce_part2_6' ? 'Part 2' : 'Part 3' ) )] , 'data' => ['status' => 'Rp']  , 'table' => $this->table['assessment_info'] ] );
                    if( $partName == 'uce_part2_7' ){
                        update( [ 'where' => [ 'user_id' => $data['user']['email'] , 'code' => $code ] , 'table' => $this->table['code_list']  , 'data' => [ 'status' => 'Rp' , 'asignment_submission_date'  =>  date("d-m-Y H:i").' (GMT + 5:30)' ] ] );
                        // lQ(1);
                        $soc_code = $this->db->get('career_sui_temp_latest');
                        foreach($soc_code->result() as $soc)
                        {
                            $formArray3 = Array(
                                'Cluster'=>$soc->Cluster,
                                'Path'=>$soc->Path,
                                'G_1'=>$soc->G_1,
                                'G_2'=>$soc->G_2,
                                'profession_name'=>$soc->profession_name,
                                'code'=>$code
                            );
                            insert('career_sui_latest',$formArray3);
                        }
                    }
                }
                if( isset( $this->part2_timer[$partName] ) ){
                    update( [ 'where' => [ 'code' => $code , 'link' => 'uce_part2' ] , 'data' => [ 'start_time' => null , 'remain_time' => null ] , 'table' => $this->table['assessment_info'] ] );
                }
            }
            if( isset($updateFormArray) ){ 
                // pre( $updateFormArray );
                update_batch( [ 'data' => $updateFormArray , 'table' => $partData['submitTable'] , 'where' => 'id' ] );
            }
            if( isset( $formArray  ) ){
                // pre( $formArray , 1 );
                $this->db->insert_batch(  $partData['submitTable'] , $formArray );
            }
            $response = [ 'status' => 'success' , 'code' => '200' , 'msg' => 'answer updated' , 'nextPart' => $partData['nextPart'] ];
        }
        else{
            $response = [ 'status' => 'failed' , 'code' => '201' , 'msg' => 'invalid parameter' ];
        }
        echo json_encode( $response );
    }


    public function finish_time()
    {
        $this->load->model('User_model');
        $this->load->model('Base_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $partArray = [ 
            'uce_part2' , 
            'uce_part2_2', 
            'uce_part2_3', 
            'uce_part2_4', 
            'uce_part2_5', 
            'uce_part2_6' 
        ];
        $code = $this->input->post('code');
        $part = $this->input->post('part');
        $partData = $this->uce_part[$part];
        if( isset( $_SESSION['workingOn'][$part] ) ){
            unset( $_SESSION['workingOn'][$part] );
        }


        
        $rand_part = $this->rand_array_prepare($code);
        $where_in['q_group'] = $rand_part->$part;
        // pre( $where_in );
        // pre( $rand_part );
        // pre( $_POST , 1 );
        if( isset( $partData['part'] ) ){
            $where = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] ];
        }
        else{
            $where = [ 'asmt_variant_3' => 1 ];
        }
        $question =  getQuery( [ 'where' => $where , 'where_in' => $where_in , 'table' => $partData['queTable'] ] );

        if( isset( $partData['part'] ) ){
            $where = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] , 'email' => $email , 'code' => $code , 'solution' => $partData['solution'] ];
            $queTableWhere = [ 'asmt_variant_3' => 1 , 'part' => $partData['part'] ];
        }
        else{
            $where = [ 'asmt_variant_3' => 1 , 'email' => $email , 'code' => $code , 'solution' => $partData['solution'] ];
            $queTableWhere = [ 'asmt_variant_3' => 1 ];
        }
        $completed = getQuery( [ 'where' => [ 'code' => $code , 'solution' => $part ]  , 'table' => 'ppe_part1_test_details'] );
        // pre( [ count($question) , count($completed) ] , 1 );
        if(count($question) > count($completed) ){
            if( $completed > 0 ){
                $queCompleted = getQuery( [ 'select' => 'GROUP_CONCAT( qno SEPARATOR "," ) as qno' , 'where' => [ 'email' => $data['user']['email'] , 'code' => $code , 'solution' => $partData['solution'] ]  , 'table' =>  $partData['submitTable'] , 'order' => [ 'id' , 'DESC' ] , 'single' => true ] );
                // pre( $queCompleted );
                $question =  getQuery( [ 'where_in' => $where_in , 'where' => $queTableWhere , 'table' => $partData['queTable'] , 'where_not_in_numeric' => [ 'qno' => $queCompleted['qno'] ] ] );
                foreach($question as $q)
                {
                    $formArray = Array(
                        'email'=>$email,
                        'qno'=>$q['qno'],
                        'solution'=>$part,
                        'code'=>$code,
                        'ans'=> 0
                    );
                    // pre( $formArray );
                    $this->db->insert('ppe_part1_test_details',$formArray);
                }
            }
            else{
                
                // pre( $question , 1 );
                foreach($que as $q)
                {
                    $formArray = Array(
                        'email'=>$email,
                        'qno'=>$q['qno'],
                        'solution'=>'uce_part2',
                        'code'=>$code,
                        'ans'=> 0
                    );
                    // pre( $formArray );
                    $this->db->insert('ppe_part1_test_details',$formArray);
                }
            }
        }
        if( $part == 'uce_part2_6' ){
            $where2 = "user_id='$email' and code='$code' and link='uce_part2'";
            $this->db->where( $where2 );
            $this->db->set( 'status' , 'Rp' );
            $this->db->update( $this->table['assessment_info'] );
        }
        else{
            $where2 = "user_id='$email' and code='$code' and link='uce_part2'";
            $this->db->where( $where2 );
            $this->db->set( 'start_time' , NULl );
            $this->db->set( 'remain_time' , NUll );
            $this->db->update( $this->table['assessment_info'] );
        }
        echo json_encode(['status' => 'success' , 'msg' => 'assessment time up']);
        // foreach( $partArray as $key => $partName  ){
        // if( $key >= $onIndex ){
            // if( $onIndex == 0 ){
            //     if($qno==0)
            //     {
            //         $num = '1';
            //     }
            //     else{
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details');

            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
            // }
            // elseif( $onIndex == 1 ){
            //     if($qno == 0){
            //         $num = '1';
            //     }
            //     else{
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_2_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2_2',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
            // }
            // elseif( $onIndex == 2 ){
            //     if($qno == 0){
            //         $num = '1';
            //     }
            //     else
            //     {
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_3_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2_3',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
                
            // }
            // elseif( $onIndex == 3 ){
            //     if($qno == 0){
            //         $num = '1';
            //     }
            //     else
            //     {
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_4_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2_4',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
            // }
            // elseif( $onIndex == 4 ){
            //     if($qno == 0){
            //         $num = '1';
            //     }
            //     else
            //     {
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_5_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2_5',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
            // }
            // elseif( $onIndex == 5 ){
            //     if($qno == 0){
            //         $num = '1';
            //     }
            //     else
            //     {
            //         $this->db->where($where);
            //         $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
            //         foreach($qno->result() as $qno)
            //         {
            //             $qno = $qno->qno;
            //             $num = $qno + 1;
            //         }
            //     }
            //     $qlist['q'] = $this->Base_model->uce_part2_6_all($num);
            //     if( $qlist['q']->num_rows() > 0){
            //         foreach($qlist['q']->result() as $q)
            //         {
            //             $formArray = Array(
            //                 'email'=>$email,
            //                 'qno'=>$q->qno,
            //                 'solution'=>'uce_part2_6',
            //                 'code'=>$code,
            //                 'ans'=> 0
            //             );
            //             $this->db->insert('ppe_part1_test_details',$formArray);
            //         }
            //     }
            // }
        // }
        // redirect( base_url().'assessment_variations/'.$partArray[$onIndex+1]."/".base64_encode($code) );
        // }
       
        // echo json_encode(['status' => 'success' , 'msg' => 'assessment time up']);
    }

    function variation_time_update( $code  , $partName)
    {
        $_SESSION['timer'][$partName] = true;
        $dateTime = date('Y-m-d H:i:s');
        $this->db->where( 'code="'.$code.'" and link="uce_part2"' );
        $this->db->set( 'start_time' , $dateTime );
        $this->db->set( 'remain_time' , $this->part2_timer[$partName] );
        $this->db->update( $this->table['assessment_info']  );
        // lQ(1);
        $this->db->where( 'code="'.$code.'" and link="uce_part2"' );
        $part2Data =  $this->db->get($this->table['assessment_info'])->row_array();
        // lQ( 1 );
        $newDateTime = date( 'Y-m-d H:i:s' , strtotime( "+".$part2Data['remain_time']." minutes", strtotime($dateTime) ));
        if( affected() > 0 ){
            echo json_encode( [ 'status' => 'success' , 'data' => [ 'dateTime' => $dateTime , 'newDateTime' => $newDateTime ] ] );
        }
        else{
            echo json_encode( [ 'status' => 'failed' ] );            
        }
    }

    
    
  }
?>