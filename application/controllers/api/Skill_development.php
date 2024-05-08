<?php
    class Skill_development extends CI_Controller
    {
        function __construct() {
            parent::__construct();
            $this->table = [
                'users' => 'user_details',
                'users_seo' => 'user_details_seo',
                'domain' => 'sd_domain',
                'sub_domain' => 'sd_sub_domain',
                'level' => 'sd_level',
                'nature' => 'sd_nature',
                'certification' => 'sd_certification',
                'discount' => 'sd_discount',
                'language' => 'sd_language',
                'vocational_live' => 'vocational_live_training',
                'live_session' => 'vocational_live_session' ,
                'curriculum' => 'sd_curriculum' ,
                'faq' => 'sd_faq',
                'recommendation' => 'sd_recommendation',
                'whatyoulearn' => 'sd_whatyoulearn',
                'training_skill' => 'sd_training_skill',
                'ratingReview' => 'review_rating',
            ];
        }

			  public function getSKillDetails( $id ){

					$skillId = base64_decode($id);
          // pre( $skillId , 1 );
					if( $skillId != null ){
						$skillData = getQuery(
              [
                'select' => "vocational_live_training.* ,{$this->table['nature']}.nature as natureName, domainName , subDomainName, certificationName, discount, {$this->table['language']}.language, {$this->table['level']}.level",
                'table' => "{$this->table['vocational_live']}",
                'join' => [
                  [ "{$this->table['domain']}" , "{$this->table['domain']}.id = {$this->table['vocational_live']}.domainId" , 'LEFT' ],
                  [ "{$this->table['sub_domain']}" , "{$this->table['sub_domain']}.id = {$this->table['vocational_live']}.subDomainId" , 'LEFT' ],
                  [ "{$this->table['level']}" , "{$this->table['level']}.id = {$this->table['vocational_live']}.nature" , 'LEFT' ],
                  [ "{$this->table['certification']}" , "{$this->table['certification']}.id = {$this->table['vocational_live']}.certification" , 'LEFT' ],
                  [ "{$this->table['nature']}" , "{$this->table['nature']}.id = {$this->table['vocational_live']}.nature" , 'LEFT' ],
                  [ "{$this->table['language']}" , "{$this->table['language']}.id = {$this->table['vocational_live']}.language" , 'LEFT' ],
                ],
                'where' => [ "{$this->table['vocational_live']}.id" => $skillId ],
                'single' => true
						  ] 
            );
            //  lQ(1);
            
						if( !empty( $skillData ) ){

              $result['skill_info'] = $skillData;

              $result['trainerDetail'] = getQuery( [ 'where' => [ "{$this->table['users']}.id" => $skillData['sp_id'] ] , 'table' => "{$this->table['users']}" , 'single' => true , 'join' => [ [ "{$this->table['users_seo']}" , "{$this->table['users']}.id = {$this->table['users_seo']}.user_id" , 'INNER' ] ] ]);

              $session = getQuery( [ 'where' => [ 'trainingId' => $skillId , 'status' => 'active' ] , 'table' => "{$this->table['live_session']}" , 'order' => [ 'reshceduled_time' => 'asc' ] ]);
              
              if( !empty($session) ){
                
                foreach($session as $key => $value){
                  $whatyoulearnArray = [];
                  $skillyougetArray = [];

                    if( $value['whatyoulearn'] != '' ){
                      $whatyoulearnData = json_decode( $value['whatyoulearn'] );
                      // pre( [ $value , $whatyoulearnData ] );
                      foreach( $whatyoulearnData as $key => $whatyoulearn ){
                        if( is_numeric( $whatyoulearn ) ){
                          $data = getQuery([ 'where' => [ 'id' => $whatyoulearn ] , 'table' => $this->table['whatyoulearn'] , 'single' => true ]);
                          if( !empty($data)){
                            $whatyoulearnArray[] = ucfirst(trim($data['whatyoulearn']));
                          }
                        }
                        else{
                          $whatyoulearnArray[] = ucfirst(trim($whatyoulearn));
                        }
                      }
                    } 
                    // pre( $whatyoulearnArray );
                    if( $value['skillyouget'] != '' ){
                      $skillyougetData = json_decode( $value['skillyouget'] );
                      if( !is_array( $skillyougetData ) ){
                          $skillyougetData = explode( ';'  , $value['skillyouget'] ); 
                      }
                      // pre( [ $value , $skillyougetData ] );
                      foreach ( $skillyougetData as $key => $skillyouget ){
                          if( is_numeric( $skillyouget ) ){
                            $data = getQuery([ 'where' => [ 'id' => $skillyouget ] , 'table' => $this->table['training_skill'] , 'single' => true ]);
                            if( !empty($data)){
                              $skillyougetArray[] = ucfirst(trim($data['skillName']));
                            }
                          }
                          else{
                            $skillyougetArray[] = ucfirst(trim($skillyouget));
                          }
                      }
                    }
                    $value['whatyoulearn'] = array_filter($whatyoulearnArray);
                    $value['skillyouget'] = array_filter($skillyougetArray);
                    // pre( $value );
                    $result['session'][] = $value;
                }
              }

              $result['rating'] = getQuery( [ 'select' =>  'COUNT(id) as total_rows, SUM(rating) as rating' ,  'where' => [ 'trainingId' => $skillId ] , 'table' => $this->table['ratingReview'] , 'order' => [ 'id' => 'asc'  ] , 'single' => true ]);

              $result['review'] = getQuery( [ 'where' => [ 'trainingId' => $skillId , $this->table['ratingReview'].'.status' => 'active' ], 'join' => [ [ $this->table['users'] , $this->table['users'].'.id = '.$this->table['ratingReview'].'.user_id' , 'INNER' ] ] , 'table' => $this->table['ratingReview'] , 'order' => [ $this->table['ratingReview'].'.id' => 'asc' ] , 'limt' => [ 10 ] ]);
              
              $result['curriculum'] = getQuery( [ 'where' => [ 'trainingId' => $skillId ] , 'table' => "{$this->table['curriculum']}" , 'order' => [ 'id' => 'asc'  ] ]);
              
              $result['faq'] = getQuery( [ 'where' => [ 'trainingId' => $skillId ] , 'table' => "{$this->table['faq']}" , 'order' => [ 'id' => 'asc' ] ]);
              $response = [ 'code' => '200' , 'status' => 'success' , 'message' => 'training details' , 'result' => $result ];
            }
            else{
              $response = [ 'code' => '200' , 'status' => 'success' , 'message' => 'No training details' , 'result' => [] ];
            }
					}
					else{
						$response = [ 'code' => '300' , 'status' => 'failed' , 'message' => 'Parameter not correct' , 'result' => []  ];
					}
          echo json_encode( $response );
				}
    }