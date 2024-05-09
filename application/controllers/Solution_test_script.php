<?php
  class Solution_test_script extends CI_Controller
  {
    protected $uce_part_2_time = 0;
    public function __construct(){
      parent::__construct();
      date_default_timezone_set('Asia/Kolkata');
      
      // $this->arr_codes = [
        // 'Aminzor558642' => '', 'Aminzor654958' => '', 'Aminzor605193' => '', 'Aminzor236202' => '', 'Aminzor317109' => '', 'Aminzor896725' => '', 'Aminzor902408' => '', 'Aminzor105388' => '', 'Aminzor560568' => '', 'Aminzor353026' => '', 'Aminzor308241' => '', 'Aminzor353668' => '', 'Aminzor719079' => '', 'Aminzor236788' => '', 'Aminzor751272' => '', 'Aminzor970296' => '', 'Aminzor756850' => '', 'Aminzor865190' => '', 'Aminzor538412' => '', 'Aminzor375118' => '', 'Aminzor929469' => '', 'Aminzor547891' => '', 'Aminzor114424' => '', 'Aminzor562602' => '', 'Aminzor851031' => '', 'Aminzor601065' => ''
      // ];
      $this->arr_codes = [
        'Aminzor799420' => '', 'Aminzor525298' => '', 'Aminzor966927' => '', 'Aminzor660680' => '', 'Aminzor636015' => '', 'Aminzor480097' => '', 'Aminzor562582' => '', 'Aminzor212832' => '', 'Aminzor989378' => '', 'Aminzor402111' => '', 'Aminzor489453' => '', 'Aminzor348727' => '', 'Aminzor504165' => '', 'Aminzor822618' => '', 'Aminzor557764' => '', 'Aminzor581930' => '', 'Aminzor213930' => '', 'Aminzor305588' => '', 'Aminzor826436' => '', 'Aminzor239923' => '', 'Aminzor111477' => '', 'Aminzor772913' => '', 'Aminzor660409' => '', 'Aminzor561890' => '', 'Aminzor912199' => '', 'Aminzor770081' => ''
      ];
      $this->percentage = [
          'uce_part2'   => 'per20',
          'uce_part2_2' => 'per20',
          'uce_part2_3' => 'per100',
          'uce_part2_4' => 'per20',
          'uce_part2_5' => 'per100',
          'uce_part2_6' => 'per100', 
      ];
      $this->table = [
        'ppe_table' => 'ppe_part1_test_details',
        'wls_detail' => 'wls_part2_rank_ordring',
        'code_list' => 'user_code_list',
        'assessment_info' => ' user_assessment_info',
        'user_details' => 'user_details',
        'user_code_va' => 'user_assessment_variation',
        'top_value_db' => 'top_value_db'
      ];
      $this->solution = [
            'UCE' => [
                'uce_part1_1' => [
                  'submitTable' => $this->table['ppe_table'],
                  'optionValue' => [1,2,3,4],
                  'optionPoints' => [ ], 
                ], 
                    'uce_part1_2' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1,2,3,4],
                    'optionPoints' => [ ], 
                ], 
                'uce_part1_3' => [ 
                    'submitTable' => $this->table['wls_detail'],
                    'questionId' => [1 , 2 , 3 , 4 , 5],
                    'optionValue' => [1 , 2 , 3 , 4 , 5],
                    'optionPoints' => [ ], 
                ],
                'uce_part1_4_1' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1,2],
                    'optionPoints' => [ ],
                ], 
                'uce_part1_4_2' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1,2],
                    'optionPoints' => [ ], 
                  ], 
                'uce_part1_5' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1,2,3],
                    'optionPoints' => [ ],
                    'update' => true,
                    'part' => 'Part 1'
                ],
                'uce_part2'   => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2 , 3 , 4],
                    'optionPoints' => [ ],
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [5, 3, 4, 5, 1, 2, 3, 1, 3, 2] , 'q_id' => [ 1, 2, 4, 6, 7, 9, 11, 14, 16, 17 ] ],
                      'per80' => [ 'r_ans' => [5, 3, 4, 5, 1, 2, 3, 1, 1, 3] , 'q_id' => [ 1, 2, 4, 6, 7, 9, 11, 14, 16, 17 ] ],
                      'per60' => [ 'r_ans' => [5, 3, 4, 5, 1, 2, 2, 2, 1, 3] , 'q_id' => [ 1, 2, 4, 6, 7, 9, 11, 14, 16, 17 ] ],
                      'per40' => [ 'r_ans' => [5, 3, 4, 5, 3, 1, 1, 2, 1, 3] , 'q_id' => [ 1, 2, 4, 6, 7, 9, 11, 14, 16, 17 ] ],
                      'per20' => [ 'r_ans' => [5, 3, 2, 4, 3, 1, 1, 2, 1, 3] , 'q_id' => [ 1, 2, 4, 6, 7, 9, 11, 14, 16, 17 ] ],
                    ]
                ],
                'uce_part2_2' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2 , 3 , 4 , 5 , 6],
                    'optionPoints' => [ ],
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [ 5, 2, 3, 2, 2, 4, 4, 5, 5 ] , 'q_id' => [ 1, 2, 3, 4, 6, 7, 9, 14, 16 ] ],
                      'per80' => [ 'r_ans' => [ 5, 2, 3, 2, 2, 4, 4, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 6, 7, 9, 14, 16 ] ],
                      'per60' => [ 'r_ans' => [ 5, 2, 3, 2, 2, 3, 3, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 6, 7, 9, 14, 16 ] ],
                      'per40' => [ 'r_ans' => [ 5, 2, 3, 2, 5, 3, 3, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 6, 7, 9, 14, 16 ] ],
                      'per20' => [ 'r_ans' => [ 5, 2, 1, 2, 5, 3, 3, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 6, 7, 9, 14, 16 ] ],
                    ]
                ], 
                'uce_part2_3' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2 , 3 , 4],
                    'optionPoints' => [ ], 
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [ 3, 1, 4, 4, 2, 1, 3, 4, 1, 3, 2 ] , 'q_id' => [ 1, 2, 4, 7, 9, 11, 13, 15, 17, 18, 19 ] ],
                      'per80' => [ 'r_ans' => [ 3, 1, 4, 4, 2, 1, 3, 4, 2, 2, 1 ] , 'q_id' => [ 1, 2, 4, 7, 9, 11, 13, 15, 17, 18, 19 ] ],
                      'per60' => [ 'r_ans' => [ 3, 1, 4, 4, 2, 1, 1, 2, 3, 1, 3 ] , 'q_id' => [ 1, 2, 4, 7, 9, 11, 13, 15, 17, 18, 19 ] ],
                      'per40' => [ 'r_ans' => [ 3, 1, 4, 4, 2, 2, 1, 1, 2, 2, 3 ] , 'q_id' => [ 1, 2, 4, 7, 9, 11, 13, 15, 17, 18, 19 ] ],
                      'per20' => [ 'r_ans' => [ 3, 1, 4, 3, 1, 4, 4, 1, 4, 1, 4 ] , 'q_id' => [ 1, 2, 4, 7, 9, 11, 13, 15, 17, 18, 19 ] ],
                    ]
                ],
                'uce_part2_4' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2 , 3 , 4 , 5],
                    'optionPoints' => [ ], 
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [ 3, 1, 3, 2, 3, 1, 1, 5, 1, 3, 3, 5, 1, 4, 2, 4, 1, 4, 5, 4, 4, 2 ] , 'q_id' => [ 1, 2, 4, 5, 8, 10, 14, 16, 17, 19, 23, 24, 26, 28, 29, 31, 34, 35, 37, 38, 39, 40 ] ],
                      'per80' => [ 'r_ans' => [ 3, 1, 3, 2, 3, 1, 1, 5, 1, 3, 3, 5, 1, 4, 2, 2, 4, 1, 4, 3, 3, 1 ] , 'q_id' => [ 1, 2, 4, 5, 8, 10, 14, 16, 17, 19, 23, 24, 26, 28, 29, 31, 34, 35, 37, 38, 39, 40 ] ],
                      'per60' => [ 'r_ans' => [ 3, 1, 3, 2, 3, 1, 1, 5, 1, 3, 3, 5, 1, 3, 1, 2, 4, 1, 4, 3, 3, 1 ] , 'q_id' => [ 1, 2, 4, 5, 8, 10, 14, 16, 17, 19, 23, 24, 26, 28, 29, 31, 34, 35, 37, 38, 39, 40 ] ],
                      'per40' => [ 'r_ans' => [ 3, 1, 3, 2, 3, 1, 1, 5, 1, 1, 2, 4, 1, 3, 1, 2, 4, 1, 4, 3, 3, 1 ] , 'q_id' => [ 1, 2, 4, 5, 8, 10, 14, 16, 17, 19, 23, 24, 26, 28, 29, 31, 34, 35, 37, 38, 39, 40 ] ],
                      'per20' => [ 'r_ans' => [ 3, 1, 1, 2, 1, 2, 2, 4, 2, 1, 2, 4, 1, 3, 1, 2, 4, 1, 4, 3, 3, 1 ] , 'q_id' => [ 1, 2, 4, 5, 8, 10, 14, 16, 17, 19, 23, 24, 26, 28, 29, 31, 34, 35, 37, 38, 39, 40 ] ],
                    ]
                ],
                'uce_part2_5' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2],
                    'optionPoints' => [ ],
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [ 1, 2, 1, 1, 2, 1, 1, 1, 1, 1, 2, 2, 2, 2, 1, 2, 2, 1, 1, 1, 2, 2, 2, 1, 2, 1, 1, 1, 2, 2 ] , 'q_id' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 17, 18, 21, 26, 31, 34, 35, 38, 45, 46, 47, 48, 55, 56, 58, 65, 71, 79, 80, 89, 90 ] ],
                      'per80' => [ 'r_ans' => [ 1, 2, 1, 1, 2, 1, 1, 1, 1, 1, 2, 2, 2, 2, 1, 2, 2, 1, 1, 1, 2, 2, 2, 2, 1, 2, 2, 2, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 17, 18, 21, 26, 31, 34, 35, 38, 45, 46, 47, 48, 55, 56, 58, 65, 71, 79, 80, 89, 90 ] ],
                      'per60' => [ 'r_ans' => [ 1, 2, 1, 1, 2, 1, 1, 1, 1, 1, 2, 2, 2, 2, 1, 2, 2, 1, 2, 2, 1, 1, 2, 2, 1, 2, 2, 2, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 17, 18, 21, 26, 31, 34, 35, 38, 45, 46, 47, 48, 55, 56, 58, 65, 71, 79, 80, 89, 90 ] ],
                      'per40' => [ 'r_ans' => [ 1, 2, 1, 1, 2, 1, 1, 1, 1, 1, 2, 2, 2, 1, 2, 1, 1, 2, 2, 2, 1, 1, 2, 2, 1, 2, 2, 2, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 17, 18, 21, 26, 31, 34, 35, 38, 45, 46, 47, 48, 55, 56, 58, 65, 71, 79, 80, 89, 90 ] ],
                      'per20' => [ 'r_ans' => [ 1, 2, 1, 1, 2, 1, 1, 1, 1, 2, 1, 1, 1, 1, 2, 1, 1, 2, 2, 2, 1, 1, 2, 2, 1, 2, 2, 2, 1, 1 ] , 'q_id' => [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 17, 18, 21, 26, 31, 34, 35, 38, 45, 46, 47, 48, 55, 56, 58, 65, 71, 79, 80, 89, 90 ] ],
                    ]
                ],
                'uce_part2_6' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2 , 3 , 4],
                    'optionPoints' => [ ],
                    'update' => true,
                    'part' => 'Part 2',
                    'correctAns' => [
                      'per100' => [ 'r_ans' => [ 4, 1, 4, 2, 2, 4, 2, 2, 2, 2, 1 ] , 'q_id' => [ 1, 6, 9, 12, 18, 25, 26, 27, 28, 31, 34 ] ],
                      'per80' => [ 'r_ans' => [ 4, 1, 4, 2, 2, 4, 2, 2, 2, 1, 3 ] , 'q_id' => [ 1, 6, 9, 12, 18, 25, 26, 27, 28, 31, 34 ] ],
                      'per60' => [ 'r_ans' => [ 4, 1, 4, 2, 2, 4, 2, 4, 1, 3, 2 ] , 'q_id' => [ 1, 6, 9, 12, 18, 25, 26, 27, 28, 31, 34 ] ],
                      'per40' => [ 'r_ans' => [ 4, 1, 4, 2, 2, 1, 4, 1, 3, 1, 3 ] , 'q_id' => [ 1, 6, 9, 12, 18, 25, 26, 27, 28, 31, 34 ] ],
                      'per20' => [ 'r_ans' => [ 4, 1, 4, 3, 1, 3, 1, 4, 3, 4, 2 ] , 'q_id' => [ 1, 6, 9, 12, 18, 25, 26, 27, 28, 31, 34 ] ],
                    ]
                ],
                'uce_part2_7' => [ 
                    'submitTable' => $this->table['ppe_table'],
                    'optionValue' => [1 , 2],
                    'optionPoints' => [ ], 
                    'update' => true,
                    'part' => 'Part 3'
                ]
            ]
        ];

        $this->submit_solution_code = [
            'UCE' => 'Aminzor509640',
        ];
    }

    function apt_rand(  ){
        $uce_part = [
          'uce_part2'   => 'per20',
          'uce_part2_2' => 'per20',
          'uce_part2_3' => 'per20',
          'uce_part2_4' => 'per20',
          'uce_part2_5' => 'per20',
          'uce_part2_6' => 'per20',
        ];
        $percentage = [ 'per20' ,'per40', 'per60' , 'per80' ];
        
        foreach ($uce_part as $key => $value){
          $pKey = rand( 0 , 3 );
          $uce_part[$key] =  $percentage[$pKey];
        }
        return $uce_part;
    }

    function code_render(){
      $codes = $this->arr_codes;
      foreach ($codes as $key => $value){
        $codes[$key] = $this->apt_rand();
      }
      return $codes;
    }


    function index(){
      $combination = $this->code_render();
      // pre( $combination , 1 );
      foreach ($combination as $key => $value) {
        $this->uce_answer_submittion(  $key  , 'UCE' , $value );
      }

    }

    function uce_answer_submittion( $code , $solution , $codes_per = null){
      $start = microtime(TRUE);
      if( $codes_per != null ){
        $this->percentage  = $codes_per;
      }
      // pre(  $this->percentage , 1 );
      $codeData = getQuery( [ 'where' => [ 'code' => $code ] , 'table' => $this->table['code_list'] , 'single' => true ] );
      $userDetail = getQuery( [ 'where' => [ 'email' => $codeData['user_id'] ] , 'table' => $this->table[ 'user_details' ] , 'single' => true ] );
      $getCodeVariation = getQuery( [ 'where' => [ 'code' => $code ] , 'table' => $this->table['user_code_va'] , 'single' => true ] );
      if( empty( $getCodeVariation ) ){
          insert( $this->table['user_code_va'] , [ 'user_id' => $userDetail['id'] , 'code' => $code , 'variation' => 'three' , 'report_variation' => 'three' ] );
      }
      else{
        update( [ 'table' => $this->table['user_code_va'] , 'data' => [ 'variation' => 'three' , 'report_variation' => 'three' ] , 'where' => [ 'code' => $code ] ] );
      }
      $parts = $this->solution[$solution];
      // lQ(1);
      delete( [ 'table' => $this->table['wls_detail'] , 'where' => [ 'code' => $code ] ] );
      delete( [ 'table' => $this->table['top_value_db'] , 'where' => [ 'code' => $code ] ] );
      delete( [ 'table' => $this->table['ppe_table'] , 'where' => [ 'code' => $code ] ] );
      foreach ($parts as $key => $value) {
        $getQuestion = getQuery( [ 'select' => 'email, solution, code, qno, '.( $key == 'uce_part1_3' ? 'grp , ordr' : 'ans'),  'where' => [ 'solution' => $key , 'code' => $this->submit_solution_code[$solution] ] , 'table' => $value['submitTable'] ] );
        // pre( $getQuestion , 1);
        if( !empty( $getQuestion ) ){
          foreach ($getQuestion as $k => $v){
            $v['code'] = $code;
            if( $key == 'uce_part1_3' ){
              if( empty($value['optionValue']) ){
                $value['optionValue'] = $parts[$key]['optionValue'];
              }
              if( empty($value['questionId']) ){
                // pre( $key ) ; pre($parts[$key]['questionId'] );
                $value['questionId'] = $parts[$key]['questionId'];
              }
              // Random Rank Order //
              $randomKey = array_rand( $value['optionValue'], 1 );
              $number = $value['optionValue'][$randomKey];
              unset($value['optionValue'][$randomKey]);
              // End Random Rank Order //
              
              // Random Rank Question Order //
              $randquestionIdKey = array_rand( $value['questionId'], 1 );
              $questionId = $value['questionId'][$randquestionIdKey];
              unset($value['questionId'][$randquestionIdKey]);
              // End Random Rank Question Order
            }
            else{
              if( isset( $value['correctAns'] ) ){
                $anskey = array_search($v['qno'], $value['correctAns'][$this->percentage[$key]]['q_id']);
                $v['ans'] = $value['correctAns'][$this->percentage[$key]]['r_ans'][$anskey];
              }
              else{
                $v['ans'] = $value['optionValue'][array_rand($value['optionValue'],1)];
              }
            }
            $getQuestion[$k] = $v;
            insert( $value['submitTable'] , $v );
          }
        }
        if( isset( $value['update'] ) ){
          if( $codeData['status'] != 'Rp' ){
            update([ 'where' => [ 'code'=> $code , 'part' => $value['part']] , 'table' => $this->table['assessment_info'], 'data' => [ 'status' => 'Rp' ] ]);
            if( $key == 'uce_part2_7' ){
              $class = rand( 8 , 12 );
              update([ 
                'where' => [ 'code'=> $code] , 
                'table' => $this->table['code_list'], 
                'data' => [ 
                  'status' => 'Rp' , 
                  'dob' => date('Y-m-d'),
                  'gender' => 'Male' , 
                  'cls' => $class , 
                  'mobile' => rand(6666666666 , 9999999999) , 
                  'email' => 'test'.rand(00,999).'@gmail.com' , 
                  'address' => 'Script run for test',
                  'aadhar' => '',
                  'associate_code' => '',
                  'language' => 'English'
                ] 
              ]);
            }
          }
        }
      }
      $end = microtime(TRUE);
      pre( [ 'status' => 'success' , 'code' => 200 , 'submitted all question with answers' , 'code' => $code , 'start' => $start , 'end' => $end, 'total' => 'Execution took '.( $end - $start ).' seconds.' , 'reportUrl' => 'https://users.respicite.com/dev/OtherAjax/uce_report_three.php?code='.base64_encode($code) ] );
    }

    function solution_careers( $provided ){
      $getCode = getQuery( [ 'where' => [ 'code' => $provided ] , 'table' => 'user_code_list' , 'single' => true ] );
      if( !empty( $getCode ) ){
          redirect( base_url().'OtherAjax/uce_report_two_dev.php?code='.base64_encode($provided) );
      }
      else{
        $getCodes = getQuery( [ 'where' => [ 'email' => $provided , 'solution' => 'UCE' ] , 'table' => 'user_code_list' , 'order' => [ 'id' => 'DESC' ] ] );
        if( !empty($getCodes) ){
          foreach ($getCodes as $key => $value){
            echo "<a target='_blank' href='".base_url().'OtherAjax/uce_report_two_dev.php?code='.base64_encode($value['code'])."'>".$value['code']."</a><br><br>";
          }
        }
        else{
          echo 'No code found';
        }
      }
    }
  }
?>