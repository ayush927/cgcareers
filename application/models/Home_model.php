<?php 
class Home_model extends CI_Model{

    function services_list(){
        $this->db->select("c_group");
        $this->db->distinct();
        $q = $this->db->get("solutions");
        return $q->result();
    }

    function services_list_2(){
        // $this->db->where("status" , 'active');
        $this->db->select("alternate_name");
        $this->db->distinct("alternate_name");
        $this->db->where("alternate_name !=" , '');
        $this->db->where("alternate_name !=" , 'Test');
        $this->db->where("alternate_name !=" , 'test');
        $this->db->order_by("alternate_name" , 'ASC');
        $q = $this->db->get("provider_level_three");
        return $q->result_array();
    }

    function services_list_3(){
        // $arr = [ 
        //         'Career Counselling' => 'Career Builder',
        //          'Career Counselling' =>  'Career Explorer',
        //         'Corporate Coaching' => 'Career Excellence',
        //         'Parenting Counselling'=> 'Positive Parenting',
        //         'Overseas Services'  =>  'Overseas Companion' ,
        //         'Overseas Counselling'  =>  'Overseas Companion' 
        //     ];
        $arr = [ 
            'Career Builder'=> 'Career Counselling',
            'Career Explorer' => 'Career Counselling' ,
            'Career Excellence' => 'Corporate Coaching',
            'Positive Parenting' => 'Parenting Counselling', 
            'Overseas Companion' => 'Overseas Services' ,
            'Overseas Counselling' => 'Overseas Services' 
        ];
        $result = $this->db->select('service_filter as services')
                        ->where( 'status' , 'active' )
                        ->get('user_details_seo');
        $services_arr = [];
        $data = $result->result_array();
        $service = '';
        if( !empty( $data ) ){
            foreach( $data as $k => $v ){
                $service = $v['services'].",".$service;
            }
        }
        $services_arr = array_map('trim' , explode( ',' , $service));
        foreach( $services_arr as $k => $v ){
            if( in_array( $v , array_keys( $arr ) ) ){
                $services_arr[$k] = $arr[$v];
            }
        }
        // pre(array_unique($services_arr), 1);
        return array_unique([ 
                'Career Counselling',
                'Overseas Services',
                'Parenting Counselling'
            ]);
        }

    function cities_list(){
        $this->db->distinct();
        $this->db->select("name");
        $this->db->order_by("name","asc");
        $q = $this->db->get("user_cities_all");
        return $q->result();
    }

    function getResellerServiceDetail( $where ){
        $this->db->select("*");
        $this->db->where( $where );
        $q = $this->db->get( "reseller_services" );
        return $q->row_array();
    }

    function getResellerSeo($where){
        $this->db->select("*");
        $this->db->where( $where );
        $q = $this->db->get( "counsellor_seo" );
        return $q->result_array();
    }

    function days(){
        $this->db->select("*");
        $q = $this->db->get("days");
        return $q->result();        
    }

    function u_seo_channels_lists(){
        $this->db->select("*");
        $q = $this->db->get("user_details_seo_channels");
        return $q->result();
    }

    function top_skills($ids){
        $this->db->select("skill");
        $this->db->where_in("id",$ids);
        $q = $this->db->get("user_details_seo_top_skills");
        $output = [];
        foreach($q->result_array() as $v){
            $output[] = $v["skill"];
        }
        return $output;
    }

    function user_seo_lists($page, $service_name,  $city , $search ){ 
        // $this->db->where('iam IN ('sp' , 'reseller')  `b`.`status` != 0 ]);
        $this->db->where_in('iam', [ 'sp', 'reseller' ]);
        $this->db->where('c.role =', 1);
        $this->db->where('b.acc_status', 'active');
        $this->db->where('a.status', 'active');
        // $this->db->where('b.profile_flag !=', 'incomplete-incomplete');
        if( !is_array($service_name) ){
            if( $service_name == 'Other Services' ){
                $this->db->where('a.services', '');
            }
        }

        $this->db->select("b.user_id as domain_id, b.profile_link , b.fullname,b.email,b.mobile,b.profile_photo,a.*,b.id as user_details_id");
        $this->db->order_by("b.fullname");

        $w_service_name = [];
        $w_city = [];
        $w_days = [];
        $w_channel = [];
        $like = [];
        // echo $service_name;
            // print_r($service_name );
            // die;
        if( !is_array( $service_name ) ){
            if($service_name != "null" &&  $service_name  != '' ){
                if( $service_name == 'Career Counselling' ){
                    $this->db->like("a.services" , 'Career' , 'both');
                }
                elseif( $service_name == 'Parenting Counselling' ){
                    $this->db->like("a.services" , 'Parent' , 'both');
                    // $this->db->like("a.services" , 'Positive Parenting');
                }
                elseif( $service_name == 'Overseas Services' ){
                    $this->db->like("a.services" , 'Overseas' , 'both');
                }
                elseif( $service_name == 'Skill Development' ){
                    $this->db->like("a.services" , 'skill' , 'both');
                }
                elseif( $service_name == 'Corporate Coaching' ){
                    $this->db->like("a.services" , 'Corporate' , 'both');
                }
                elseif( $service_name == 'Psychological Counselling' ){
                    $this->db->like("a.services" , 'Psycho' , 'both');
                }
                elseif( $service_name == 'Life Counselling' ){
                    $this->db->like("a.services" , 'Life' , 'both');
                }
                elseif($service_name == 'Other Services'){
                    $this->db->like("a.services" , '');
                }
            }
        }
        else{
            foreach( $service_name as $key=>$value ){
                if( $value == 'Career Counselling' ){
                    $this->db->like("a.services" , 'Career' , 'both');
                }
                elseif( $value == 'Parenting Counselling' ){
                    $this->db->like("a.services" , 'Parent' , 'both');
                    // $this->db->like("a.services" , 'Positive Parenting');
                }
                elseif( $value == 'Overseas Services' ){
                    $this->db->like("a.services" , 'Overseas' , 'both');
                }
                elseif( $value == 'Skill Development' ){
                    $this->db->like("a.services" , 'skill' , 'both');
                }
                elseif( $value == 'Corporate Coaching' ){
                    $this->db->like("a.services" , 'Corporate' , 'both');
                }
                elseif( $value == 'Psychological Counselling' ){
                    $this->db->like("a.services" , 'Psycho' , 'both');
                }
                elseif( $value == 'Life Counselling' ){
                    $this->db->like("a.services" , 'Life' , 'both');
                }
                elseif($value == 'Other Services'){
                    $this->db->like("a.services" , '');
                }
            }
        }

        if($city != null &&  $city  != ''  ){                
            $this->db->like('a.locations', $city , 'both' );
            // $this->db->like("a.locations" , );
        }
        if($search != null  ){
            if( $search['search_name'] != null ){
                $this->db->like('b.fullname' , $search['search_name'] , 'both');
            }
            if( $search['search_locations'] != null ){
                $this->db->or_like('a.locations' , $search['search_locations'] , 'both');
            }
            if( $search['search_service'] != null ){
                $this->db->or_like('a.services' , $search['search_service'] , 'both');
            }
        }
        // if($days != "null" &&  $days  != ''  ){
        //     $this->db->like("a.available_days", $days);
        // }
        // if($channel != "null" &&  $channel  != ''  ){
        //     $this->db->like("a.channels" , $channel);
        // }

        $this->db->join("user_details b","b.id = a.user_id" , 'right');
        
        $this->db->join("counsellor_list_status c","c.user_id = b.id" , 'right');
        if( !empty( $like ) ){
            // print_r( $like );
            $this->db->like($like);
        }
        if( $page != null ){
            $this->db->limit(10, ($page-1)*10);
        }
        $q = $this->db->get("user_details_seo a");
        // lQ(1);
        return $q->result_array();

    }
}
?>