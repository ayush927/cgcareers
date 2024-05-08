<?php
date_default_timezone_set("Asia/Kolkata");
class Home extends Ci_controller{
    function __construct(){
            parent::__construct();
            $this->load->model("Home_model");
    }
    function service_provider( $service_name = null,$city = null,$days = null,$channel = null){
        
        $service_name = urldecode($service_name);
        // echo $service_name;die;
        // echo $service_name;die;
        $city = urldecode($city);
        $days = urldecode($days);
        $channel = urldecode($channel);
        $data = [];
        $data["s_lists"] = $this->Home_model->services_list(); //table='solutions'
        $data["c_lists"] = $this->Home_model->cities_list(); //table='user_cities_all'
        $data["channels_lists"] = $this->Home_model->u_seo_channels_lists(); //table='user_details_seo_channels'
        $data["days"] = $this->Home_model->days();//days
        $seo_lists = $this->filter_data($service_name,$city,$days,$channel); //table='user_details_seo'
        $data["seo_lists"] = $seo_lists["seo_lists"];
        $data["seo_filter"] = $seo_lists["seo_filter"];
        $this->load->view("home/sp_pages/all_sp",$data);

    }

    function counselors_view($profile_link){
        $this->db->where('profile_link',$profile_link);
        $level1['s']=$this->db->get('user_details');
        if( $level1['s']->num_rows() > 0 ){
            $level1['rData']  = $level1['s']->row_array();
            $email = $level1['s']->row_array()['email'];
            $id = $level1['s']->row_array()['id'];
            $level1['profileViewData'] = getQuery( [ 'where' => [ 'r_email' => $email ] , 'table' => 'counsellor_list_status' , 'single'  => true ] );
            // pre( $level1['profileViewData'] , 1 );
            $level1['fullname'] = $level1['s']->row_array()['fullname'];
            $user_id = $level1['s']->row_array()['id'];
            $level1['ratingData'][] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'rating' => 5 ] , 'select' => 'count(id) as total_review' , 'table' => 'review_rating' , 'single' => true ] );
            $level1['ratingData'][] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'rating' => 4 ] , 'select' => 'count(id) as total_review' , 'table' => 'review_rating' , 'single' => true ] );
            $level1['ratingData'][] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'rating' => 3 ] , 'select' => 'count(id) as total_review' , 'table' => 'review_rating' , 'single' => true ] );
            $level1['ratingData'][] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'rating' => 2 ] , 'select' => 'count(id) as total_review' , 'table' => 'review_rating' , 'single' => true ] );
            $level1['ratingData'][] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'rating' => 1 ] , 'select' => 'count(id) as total_review' , 'table' => 'review_rating' , 'single' => true ] );

            $level1['total_rating'] = getQuery( [ 'where' => [ 'reseller_id' => $user_id ], 'select' => 'count(id) as total' , 'table' => 'review_rating' , 'single' => true ] );
            $level1['reviewData'] = getQuery( [ 'where' => [ 'reseller_id' => $user_id , 'review_rating.status' => 'active' ], 'select' => 'review_rating.* , user_details.fullname, user_details.profile_photo' , 'table' => 'review_rating' ,'join' => [ [ 'user_details' , 'user_details.id = review_rating.user_id' , 'INNER' ] ]  ] );
            // pre( $level1 );
            // die;
            $this->db->where('email',$email);
            $data['config'] = [
                'title' => 'Best Career Counsellors | Parenting Coach in India - Respicite',
                // 'keywords' => 'parenting counselor, career counselling services,education,school,university,educational,learn,learning,teaching,workshop',
                // 'keywords' => 'best career aptitude test, psychometric test for career counselling, career assessment test india',
                'keywords' => 'best career counsellors in india, parenting coach in india, overseas consultancy in india, career counselling, parenting counselling, corporate coachiing, life coach',
                'description' => 'Respicite provides the best overseas consultancy services in India. We also offer parenting coaching and career counselling services. Book your service now!',
                'contact_no' => '9584675111',
                'email' => 'sales@respicite.com',
                'msg' => [
                    'register'=>'Register as a User',
                    // 'banner-1'=> $title,
                    'banner-2'=>'Browse, Choose, Get Counselling'
                ],
                'img' => [
                    'bg'=>'https://respicite.com/images/1920x820-2.jpg',
                    'data-bg'=>'https://respicite.com/images/1920x820-2.jpg'
                ],
                'dataPreview' => [
                    [
                        'heading' => 'Assessments',
                        'p' => 'Respicite provides best career tests & best parenting assessments for students and their families. Each assessment is designed to address a specific issue - including stream selection, post-college direction, overseas readiness, job readiness as well as positive parenting. Solutions are relevant, to-the-point and rigorously designed.Visit our career & parenting assessment page. Choose the most suitable solution.
                        <br>
                        <br>
                        <a target="_blank" class="btn col-md-4 offset-md-4 btn-theme mb-1 mb-1" href="'.RESPICITE_URL.'career-assessment-test-india" > Know More </a>'
                    ],
                    [
                        'heading' => 'Counsellors',
                        'p' => 'Respicite onboards best career counsellors, parenting counsellors, overseas consultants, skill trainers as well as career coaches on its platform. There are more than 300 partners on our platform and this number is rapidly growing. You can get best career and parenting counselling on our platform. Visit our counsellor listing page to view and connect with them.
                        <br>
                        <br>
                        <a  class="btn col-md-4 offset-md-4 btn-theme mb-1" href="'.base_url().'best-counsellors-india" > Know More </a>'
                    ],
                    [
                        'heading' => 'Career Counselling Certification',
                        'p' => 'Respicite enables people in counselling, teaching and related domains to build their capacity as career counsellors and parenting counsellors. They can build their own counselling business using our certifications as well as our assessment tools. Visit our Career Counsellor Certification or Parenting Counsellor Certificate page to choose a plan that best suits your need.
                        <br>
                        <br>
                        <a  target="_blank" class="btn col-md-4 offset-md-4 btn-theme mb-1" href="'.RESPICITE_URL.'partners/career-counselling-certification" > Know More </a>'
                    ]
                ]
            ];
            $level1['l']=$this->db->get('provider_detail_four');
            // pre( $level1 , 1 );
            $this->load->view("home/counsellors/header" , $data );
            $this->load->view('home/counsellors/profile-view',$level1);
            $this->load->view("home/counsellors/footer");
        }
        else{
            $this->session->set_flashdata('msg','No Counsellor Found');
            redirect(base_url().'best-counsellors-india'); 
        }
    }
    function partner_counsellors($service_name = null,$city = null,$days = null,$channel = null){
            $service_name = urldecode($service_name);
            $city = urldecode($city);
            $days = urldecode($days);
            $channel = urldecode($channel);
            $data = [];
            $data["s_lists"] = $this->Home_model->services_list();//table='solutions'
            $data["c_lists"] = $this->Home_model->cities_list(); //table='user_cities_all'
            $data["channels_lists"] = $this->Home_model->u_seo_channels_lists();//table='user_details_seo_channels'
            $data["days"] = $this->Home_model->days();//days
            $seo_lists = $this->filter_data($service_name,$city,$days,$channel);//table='user_details_seo'
            $data["seo_lists"] = $seo_lists["seo_lists"];
            $data["seo_filter"] = $seo_lists["seo_filter"];
            $this->load->view("home/sp_pages/all_sp",$data);
    }
    function counsellors( $service_name = null, $city = null){
        
            // $data = [];
            if( $service_name == 'all-services' ){
                $service_name = null;
            }
            else{
                $data["faq_lists"] = getQuery( [ 'where' => [ 'page_url' => $service_name ] , 'table' => 'seo_faq'  ] );
            }
            if( $city == 'all' ){
                $city = null;
            }
            $serviceDetail = [];
            $filter_service = '0';
            $filter_location = '0';

            // echo "<pre>";
            // print_r( $serviceDetail );
            // print_r( $resellerSeo );
            // die;
            $this->load->library("pagination");
            $service_name = urldecode($service_name);
            if( $service_name == "career-counselling-all" ){
                $service_name = [ 'Career Counselling', 'College', 'Corporate Coaching', 'Education Counselling', 'Overseas Services','Placement Services', 'School' , 'Skill Development' , 'Other services' ];
            }
            if( $service_name == "parenting-counselling-all" ){
                $service_name = [ 'Dmit Practitioner' , 'Life Counselling' , 'Parenting Counselling' , 'Psychological Counselling' , 'Other services' ];
            }
            $city = urldecode($city);
            // $days = urldecode($days);
            // $channel = urldecode($channel); 
            $data["s_lists"] = $this->Home_model->services_list_3(); //table='solutions'
            // print_r( $data );
            $data["c_lists"] = $this->Home_model->cities_list(); //table='user_cities_all'
            $data["channels_lists"] = $this->Home_model->u_seo_channels_lists();//table='user_details_seo_channels'
            $data["days"] = $this->Home_model->days();//days
            if( $this->session->userdata('current_page')){
                $page_number = $this->session->userdata('current_page');
                $seo_lists = $this->filter_data($page_number,$service_name,$city);
            }
            else{
                $seo_lists = $this->filter_data(null,$service_name,$city);
            }
            if( $seo_lists['all_count'] == 0 ){
                $filter_service = '0';
                $filter_location = '0';
                $searched_service_name = $service_name;
                $service_name = 'all-services';
                $city = null;
            }
            if( $service_name != null && $service_name != 'all-services' && $service_name != 'other-services' ){
                $filter_service = '1';
                if( !is_array( $service_name ) ){
                    $serviceDetail = $this->Home_model->getResellerServiceDetail( [ 'serviceName' => ucwords( str_replace('-' , ' ' , $this->uri->segment(2)))] );
                }
                elseif( $service_name = 'career-counselling-all' ){
                    $serviceDetail = $this->Home_model->getResellerServiceDetail( [ 'serviceName' => 'career counselling'] );
                }
                elseif( $service_name = 'parenting-counselling-all' ){
                    $serviceDetail = $this->Home_model->getResellerServiceDetail( [ 'serviceName' => 'parenting counselling'] );
                }
                if( $serviceDetail['serviceType'] == '' ){
                    // echo "heelo";
                    // die;
                    $filter_service = '0';
                    $filter_location = '0';
                    $serviceDetail = [];
                    $data['serviceFirst'] = $this->Home_model->getResellerServiceDetail( [ 'serviceType' => 1 ] );
                    // echo $this->db->last_query();
                    // echo "<br>";
                    $data['serviceSecond'] = $this->Home_model->getResellerServiceDetail( [ 'serviceType' => 2 ] );                
                    // echo $this->db->last_query();
                    // echo "<br>";
                }
            }
            else{
                $data['serviceFirst'] = $this->Home_model->getResellerServiceDetail( [ 'serviceType' => 1 ] );
                $data['serviceSecond'] = $this->Home_model->getResellerServiceDetail( [ 'serviceType' => 2 ] );
            }
            if( $city != null ){
                $filter_location = '1';
            }

            $resellerSeo = $this->Home_model->getResellerSeo( [ 'filter_service' => $filter_service , 'filter_location' => $filter_location ] );
            $data['resellerSeo'] = $resellerSeo; 
            $data['serviceDetail'] = $serviceDetail;
            $data["seo_lists"] = $seo_lists["paginate_list"];
            $data["c_lists"] = $seo_lists["cities"];
            
            $config["total_rows"] = $seo_lists["all_count"];
            $config["base_url"] = base_url() . "best-counsellors-india";
            $config["per_page"] = 10;
            $config['cur_page'] = 1;
            if( $this->session->userdata('current_page')){
                $config['cur_page'] = $this->session->userdata('current_page');
                $this->session->unset_userdata('current_page');
            }
            // $config["uri_segment"] = 2;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config);
            $data["seo_filter"] = $seo_lists["seo_filter"];
            $data["links"] = $this->pagination->create_links();
            $title = 'Best Career Aptitude and Assessment test in India - Respicite';
            if( $this->uri->segment(2) != '' ){
                $title.= ' of '.(ucwords( str_replace('-' , ' ' , $this->uri->segment(2))) );
            }
            if( $this->uri->segment(3) != '' ){
                $title.= '<br> in location '.ucwords($city);
            }
            $data['config'] = [
                'title' => 'Best Career Counsellors | Parenting Coach in India - Respicite',
                // 'keywords' => 'parenting counselor, career counselling services,education,school,university,educational,learn,learning,teaching,workshop',
                // 'keywords' => 'best career aptitude test, psychometric test for career counselling, career assessment test india',
                'keywords' => 'best career counsellors in india, parenting coach in india, overseas consultancy in india, career counselling, parenting counselling, corporate coachiing, life coach',
                'description' => 'Respicite provides the best overseas consultancy services in India. We also offer parenting coaching and career counselling services. Book your service now!',
                'contact_no' => '9584675111',
                'email' => 'sales@respicite.com',
                'msg' => [
                    'register'=>'Register as a User',
                    'banner-1'=> $title,
                    'banner-2'=>'Browse, Choose, Get Counselling'
                ],
                'img' => [
                    'bg'=>'https://respicite.com/images/1920x820-2.jpg',
                    'data-bg'=>'https://respicite.com/images/1920x820-2.jpg'
                ],
            ];
            if( ( $city != null || $city == '' ) && $service_name != 'all-services' ){
                // echo "Hello";
                $prepare_seo = prepare_seo( $service_name , $city );
            }
            if( $city == null && $service_name == null  ){
                // echo 2;
                $prepare_seo = prepare_seo();
            }
            // if( $city )
            if( !isset( $prepare_seo ) && $seo_lists['all_count'] == 0 ){
                // echo 1;
                $prepare_seo = prepare_seo( $searched_service_name , $city );
            }
            // pre( [ $service_name , $city ] );
            // pre( $prepare_seo  , 1 );
            if( isset( $prepare_seo ) ){
                
                $data['config']['title'] = $prepare_seo['title'];
                $data['config']['keywords'] = $prepare_seo['keywords'];
                $data['config']['description'] = $prepare_seo['description'];
                $data['config']['googleTag'] = $prepare_seo['google-code'];
            }
            $data['search'] = isset( $_SESSION['search'] ) ? $_SESSION['search'] : [];
            $data['service_name'] = $service_name;
            $data['city'] = $city;
            // echo "<pre>";
            // pre($data , 1);
            // die;
            $this->load->view("home/counsellors/header",$data);
            $this->load->view("home/counsellors/list",$data);
            $this->load->view("home/counsellors/footer",$data);
    }

    function filter_data( $page = null, $service_name, $city ){
            if( !isset( $_SESSION['search']['search_name'] ) ){
                $_SESSION['search']['search_name'] = null;
            }
            if( !isset( $_SESSION['search']['search_service'] ) ){
                $_SESSION['search']['search_service'] = null;
            }
            if( !isset( $_SESSION['search']['search_locations'] ) ){
                $_SESSION['search']['search_locations'] = null;
            }
            if( !is_array($service_name)){
                $service_name = ( $service_name != null ? ucwords(str_replace( '-' , ' ' , $service_name )) : null );
            }

            extract( $_SESSION );
            // pre($_SESSION , 1);
            // die;
            $total_lists = $this->Home_model->user_seo_lists( null, $service_name, null , null );
            $seo_lists = $this->Home_model->user_seo_lists( null, $service_name, $city , $search );
            // echo $this->db->last_query();
            // die;
            // pre( $seo_lists , 1 );
            $paginate_list = $this->Home_model->user_seo_lists( ($page != null ? $page : 1), $service_name, $city , $search);
            // echo $this->db->last_query();
            // die;
            $_count = sizeof($paginate_list);
            $seo_filter = false;
            // if($_count == 0){
            //     $seo_lists =   $this->Home_model->user_seo_lists(($page != null ? $page : 1),null,null);
            //     $_count = sizeof($seo_lists);
            // }
            $cities = '';
            foreach( $total_lists as $k => $value ){
                // echo $value['locations']."<br>";
                $x = $value['locations'];
                if( $x == '0' || $x == '' ){
                    $x = 'All';
                }
                $cities = $x.", ".$cities;
                // echo $cities."<br>";
            }
            $cities_arr = array_filter( array_unique(array_map('ucwords' , array_map( 'trim' ,  explode(',', $cities)) )) , 'strlen' );
            sort( $cities_arr );
            // pre( $cities_arr ,  1 );
            $seo_filter = true;
            if(!empty( $paginate_list ) ){
                for($i = 0; $i < $_count;$i++){
                    $paginate_list[$i]["services"] = array_filter(explode(',' , $paginate_list[$i]["services"]));
                    $paginate_list[$i]["profile_photo"] = base_url(($paginate_list[$i]["profile_photo"] != '' ? $paginate_list[$i]["profile_photo"] : 'uploads/default.png' ));
                    $paginate_list[$i]["locations"] = explode(",",$paginate_list[$i]["locations"]);
                    $paginate_list[$i]["most_relevant_education"] = explode(",",$paginate_list[$i]["most_relevant_education"]);
                    $paginate_list[$i]["top_skills"] = $this->Home_model->top_skills(explode(",",$paginate_list[$i]["top_skills"]));
                }   
            }
            return ["all_count" => count($seo_lists) , "paginate_list" => $paginate_list, "seo_filter" => $seo_filter , 'cities' => $cities_arr ];
        }
        function set_page_number($page_number){
            if( is_numeric( $page_number ) ){
                $this->session->set_userdata( 'current_page' , $page_number );
                echo json_encode( [ 'status' => true ] );
            }
        }
        function set_search_string($string1 = null , $string2 = null, $string3 = null){
            $_SESSION['search']['search_name'] = trim($string1);
            $_SESSION['search']['search_service'] = trim($string2);
            $_SESSION['search']['search_locations'] = trim($string3);
            echo json_encode( [ 'status' => true ] );
        }
        function unset_search_string(){
            unset( $_SESSION['search'] );
            echo json_encode( [ 'status' => true ] );
        }
        function work_ajax(){
             $this->load->model('User_model');
             $this->load->model('Sp_model');
            $json_msg = [];
            $form_data = [
                "appointment_type"=>$this->input->post("action_name"),
                "name"=>$this->input->post("name"),    
                "email"=>$this->input->post("email"),
                "phone_no"=>$this->input->post("phone_no"),    
                "location"=>$this->input->post("location"),
                "message"=>htmlspecialchars($this->input->post("message")),
                "user_id"=>$this->input->post("user_id"),
                "created_at"  => date('Y-m-d H:i:s'),
            ];
            // echo 1;
            $sp_detail = $this->Sp_model->get_sp_details('DETAILS_BY_EMAIL', null , $form_data['user_id']);
            $this->db->insert("user_book_appointment",$form_data);
            if($this->db->affected_rows() > 0){
                $subject = ucwords(str_replace('_', ' ', $form_data['appointment_type']));
                $body_msg = "Dear ".$form_data['name']."<br/><br/> Appointment requested by a parent or student for a call back, providing the following information.
                <br>
                <strong> Name : ".$form_data['name']."</strong> <br>
                <strong> Email : ".$form_data['email']."</strong> <br>
                <strong> Contact Number : ".$form_data['phone_no']."</strong><br>
                <strong> Booking Datetime : ".date( 'd m Y h:i A' )."</strong><br>
                <strong> Booking Messgae : ".$form_data['message']."</strong><br>
                <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a>";
                $this->User_model->otp_send_on_email( $sp_detail->email  , $subject , $body_msg );
                $json_msg["message_type"] = "book_succ";
                $json_msg["message"] = "Your Request has been forwarded to the counsellor.The team shall connect with you.";
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($json_msg));
            
        }
        
        function check_status(){
            if( !isset($_GET['email_id']) ){
                $data['code'] = null;
                $this->load->view("check_status" , $data);
            }
            else{
                $data['code'] = null;
                $checkUserExist = getQuery( [ 'where' => [ 'email' => $_GET['email_id'] ] , 'where_in' => [ 'iam' => [ 'reseller' , 'sp' ] ] , 'table' => 'user_details' , 'single' => true ] );
                // lQ(1);
                // pre(  $checkUserExist , 1);
                if( !empty(  $checkUserExist ) ){
                    $detail_seo = getQuery( [ 'where' => [ 'user_id' => $checkUserExist['id'] ] , 'table' => 'user_details_seo' , 'single' => true ] );
                    
                    $data['status']['Registration'] = 'Partner Counsellor';
                    $data['status']['iam'] = $checkUserExist['iam'];
                    
                    $data['status']['Your Primary Service'] = ( $checkUserExist['serviceName'] != '' ?  $checkUserExist['serviceName'] : ( !empty( $detail_seo ) ? $detail_seo['services']  : 'No Services') );
                    
                    $data['status']['Your onboarding document'] = 'https://drive.google.com/drive/folders/1nra0lpKbgUsOMk732ObhQBjlOOg-bIa9?usp=sharing';
                    
                    $data['status']['Counsellor Dashboard Login'] = base_url().'userController/login/'.( !is_numeric($checkUserExist['user_id']) ? base64_encode( $checkUserExist['user_id'] ) : '' );
                    
                    $data['status']['Your Public Profile'] = ( $checkUserExist['profile_link'] != '' ? base_url().'counselors-view/'.$checkUserExist['profile_link'] : '' );
                    
                    $serviceArr = explode( ',' , $data['status']['Your Primary Service'] );
                    // pre( $serviceArr , 1 );
                    if( !empty( $serviceArr ) ){
                        if( in_array( 'Career Counselling' , $serviceArr ) || in_array( 'Career Explorer' , $serviceArr ) || in_array( 'Career Builder' , $serviceArr ) || in_array( 'Positive Parenting' , $serviceArr ) || in_array( 'Parenting Counselling' , $serviceArr ) ){
                            $data['status']['Your Training Resources'] = 'https://drive.google.com/drive/folders/11XlZdJ3MoozcCAsFKRbIEfIL35mwGUaa?usp=drive_link';
                        }
                        elseif( in_array( 'Overseas Services' , $serviceArr ) || in_array( 'Overseas Counselling' , $serviceArr ) || in_array( 'Overseas Companion' , $serviceArr ) ){
                            $data['status']['Your Training Resources'] = 'https://drive.google.com/drive/folders/1O9QyLeZILAsYprDFAfo0KZ_-3UlG7MlD?usp=drive_link';
                        }
                        else{
                            $data['status']['Your Training Resources'] = '';
                        }
                    }
                    else{
                        $data['status']['Your Training Resources'] = '';
                    }
                    
                    $data['status']['New Student Registration'] = base_url().'BaseController/registration/'.base64_encode($checkUserExist['user_id']);
                    
                    $data['status']['Existing Student Login'] = base_url().'BaseController/login/'.base64_encode($checkUserExist['user_id']);
                    

                }
                else{
                    $checkUser = getQuery( [ 'where' => [ 'email' => $_GET['email_id'] , 'iam' => 'user' ] , 'single' => true  , 'table' => 'user_details' ] );
                    if( !empty( $checkUser ) ){
                        $data['status']['Registration'] = 'Student User';
                        $data['status']['iam'] = 'user';
                        $data['status']['Dashboard Login'] = base_url().'baseController/login/'.(base64_encode( $checkUser['user_id'] ));
                    }
                    else{
                        $data['status']['iam'] = '';
                        $data['status']['Registration'] = 'Not Registered';
                        $data['status']['Register as Partner Counsellor'] = base_url().'UserController/registration/';
                        $data['status']['Register as Student User'] = base_url().'BaseController/registration/';
                    }
                }
                $this->load->view("check_status" , $data);
            }
        }
    }
?>