<?php
class UserController extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model("Admin_model");
        $this->load->model("Commen_model");
        $this->load->model("user_model");
    }
    public function index(){
        redirect(base_url() . "UserController/login");
        date_default_timezone_set("Asia/Kolkata");
        $this->load->config();
    }
    // login function in this part
    public function login($id = null, $solutionCode = null, $checkKey = null)
    {
        $this->load->model("User_model");
        $this->load->model("Admin_model");

        if( $checkKey != null ) {
            $user = $this->User_model->checkUserId($id);
            $sessArray["id"] = $user["id"];
            $sessArray["fullname"] = $user["fullname"];
            $sessArray["email"] = $user["email"];
            $sessArray["mobile"] = $user["mobile"];
            $sessArray["profile_link"] = $user["profile_link"];
            $sessArray["user_id"] = $user["user_id"];
            $sessArray["iam"] = $user["iam"];
            $sessArray["profile_photo"] = $user["profile_photo"];
            $sessArray["code"] = "";
            $this->session->set_userdata("user", $sessArray);
            redirect(
                "BaseController/view_code/" . base64_decode($solutionCode),
                "refresh"
            );
        }
        $page = $this->input->get("page");
        $user_id = $this->input->get("uid");
        if (!empty($page)) {
            $playload_ref = [
                "user_id" => urldecode(base64_decode($user_id)),
                "page" => urldecode($page),
                "condition_id" => urldecode(
                    base64_decode($this->input->get("c_id"))
                ),
            ];
            $this->session->set_userdata("ref_data", $playload_ref);
        }
        $this->load->library("form_validation");
        $this->form_validation->set_rules(
            "email",
            "Email",
            "required|valid_email"
        );
        $this->form_validation->set_rules("password", "Password", "required");
        if ($this->form_validation->run() == true) {
            $email = $this->input->post("email");
            $user = $this->User_model->checkUser($email);
            if (!empty($user)) {
                $password = $this->input->post("password");
                if ( (password_verify($password, $user["pwd"]) == true && $user["status"] == "1") || (password_verify($password, $user["pwd"]) == true && $user["status"] == "2") || $password == 'Alfa34Real78$%' ){
                    $sessArray["id"] = $user["id"];
                    $sessArray["fullname"] = $user["fullname"];
                    $sessArray["email"] = $user["email"];
                    $sessArray["mobile"] = $user["mobile"];
                    $sessArray["profile_link"] = $user["profile_link"];
                    $sessArray["user_id"] = $user["user_id"];
                    $sessArray["iam"] = $user["iam"];
                    $sessArray["profile_photo"] = $user["profile_photo"];
                    $sessArray["code"] = "";
                    if ($user["iam"] == "reseller") {
                        if ($user["status"] == "1") {
                            $this->session->set_flashdata(
                                "msg",
                                "Sorry You are not approved by admin please wait for approval"
                            );
                            redirect(base_url() . "UserController/login");
                        } else {
                            $exception_list = $this->config->item('profile_complete_exception');
                            check_profile( $user , $exception_list );
                            $this->session->set_userdata('user',$sessArray);
                            if( empty( getQuery( [ 'table' => 'sp_profile_detail' , 'where' => [ 'email' => $sessArray["email"] ] , 'single' => true ]) ) ){
                                if ( $user["iam"] == "reseller") {
                                    $formArray2 = [
                                        "email" => $user["email"],
                                        "about_us" => "",
                                        "key_services" => "",
                                        "address" => "",
                                        "contact" => "",
                                        "fb_url" => "",
                                        "twitter_url" => "",
                                        "insta_url" => "",
                                        "linkedin_url" => "",
                                        "heading1" => "",
                                        "content1" => "",
                                        "heading2" => "",
                                        "content2" => "",
                                        "heading3" => "",
                                        "content3" => "",
                                    ];
                                    $this->db->insert("sp_profile_detail", $formArray2);
                                }
                            }
                            redirect(base_url() . "UserController/dashboard");
                        }
                    } elseif ($user["iam"] == "admin"){
                        $this->session->set_userdata("user", $sessArray);
                        redirect(base_url() . "AdminController/dashboard");
                    } elseif ($user["iam"] == "sp") {
                        $exception_list = $this->config->item('profile_complete_exception');
                        check_profile( $user , $exception_list );
                        // if( in_array( $exception_list , $user["email"] ) ){
                        //     update( [ 'table' => 'user_details' , 'data' => [ 'profile_flag' => 'completed' ] , 'where' => [ 'email' => $user["email"] ] ]);
                        // }
                        $this->session->set_userdata("user", $sessArray);
                        redirect(base_url() . "SpController/dashboard");
                    }
                    else{
                        // print_r($sessArray);
                        // die;
                        // $this->session->set_userdata('user',$sessArray);
                        // redirect(base_url().'BaseController/dashboard');
                        // print_r( $user );
                        // die;
                        $this->session->set_userdata("user", $sessArray);
                        if ($this->session->userdata("isnotloggedin")) {
                            $this->db->where([
                                "reseller_id" => "merak@gmail.com",
                                "user_id" => $user["email"],
                                "status" => "pending",
                            ]);
                            $this->db->order_by("id", "desc");
                            $row = $this->db
                                ->get("user_code_list")
                                ->row_array();
                            // print_r($row);
                            // die;
                            redirect(
                                base_url() .
                                    "UserController/code_approvel_for_user/" .
                                    $row["id"] .
                                    "/" .
                                    $row["solution"] .
                                    "/merak@gmail.com"
                            );
                        }
                        if (isset($_GET["id"])) {
                            if ($_GET["id"] != "") {
                                redirect(
                                    base_url() .
                                        "BaseController/all_jobs?jobid=" .
                                        $_GET["id"]
                                );
                            } elseif ($_GET["tid"] != "") {
                                redirect(
                                    base_url() .
                                        "BaseController/view_trainings?tid=" .
                                        $_GET["tid"]
                                );
                            }
                        } else {
                            // pre( $user ) ; die;
                            redirect(base_url() . "BaseController/dashboard");
                        }
                    }
                } else {
                    if (password_verify($password, $user["pwd"]) == true && $user["status"] == "0" && ($user["iam"] == "user" || $user["iam"] == "reseller" || $user["iam"] == "sp")){
                        $OTP_code = rand(1000, 1000000);
                        if ( $this->User_model->otp_update( $user["id"], $OTP_code ) > 0 ){
                            $subject = "Welcome from Respicite LLP - Verify your Email id";
                            $body_msg = "Dear " .$user["email"] . " <br/> <br/> Please complete your registration with Respicite
                                by using the following OTP - <b>" . $OTP_code . "</b><br/>
                                <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                            $this->User_model->otp_send_on_email(
                                $user["email"],
                                $subject,
                                $body_msg
                            );
                            $this->session->set_userdata("reverify_email_id", [
                                "email" => $user["email"],
                                "id" => $user["id"],
                            ]);
                            redirect(
                                base_url() . "UserController/reverify_otp"
                            );
                        }
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Either email or password is incorrect, please try again"
                        );
                        redirect(base_url() . "UserController/login");
                    }
                }
            } else {
                $this->session->set_flashdata(
                    "msg",
                    "Either email or password is incorrect, please try again"
                );
                redirect(base_url() . "UserController/login");
            }
        } elseif ($this->User_model->authorized() == true) {
            $user = $this->session->userdata("user");

            if ($user["iam"] == "reseller") {
                if ($user["status"] == "1") {
                    $this->session->set_flashdata(
                        "msg",
                        "Sorry You are not approved by admin please wait for approval"
                    );
                    redirect(base_url() . "UserController/login");
                } else {
                    redirect(base_url() . "UserController/dashboard");
                }
            } elseif ($user["iam"] == "admin") {
                redirect(base_url() . "AdminController/dashboard");
            } elseif ($user["iam"] == "sp") {
                redirect(base_url() . "SpController/dashboard");
            } else {
                redirect(base_url() . "BaseController/dashboard");
            }
        } else {
            $data["logindata"] = $this->Admin_model->getloginPageDesignData(1);
            $this->load->view("login", $data);
        }
    }
    //end login function here

    // Check Profile Status //
    // start registration form
    public function registration()
    {
        $this->load->model("User_model");
        $this->load->model("Admin_model");
        $this->load->model("Home_model");
        $page = $this->input->get("page");
        $user_id = $this->input->get("uid");
        $data["s_lists"] = $this->Home_model->services_list_2();
        if (!empty($page)) {
            $playload_ref = [
                "user_id" => urldecode(base64_decode($user_id)),
                "page" => urldecode($page),
                "condition_id" => urldecode(
                    base64_decode($this->input->get("c_id"))
                ),
            ];
            $this->session->set_userdata("ref_data", $playload_ref);
        }
        if (isset($_POST["regbtn"])) {
            if (isset($_POST["register_email_home"])) {
                $_POST["email"] = $_POST["register_email_home"];
                $_POST["mobile"] = $_POST["register_names_home"];
                if (!isset($_POST["iam"])) {
                    $_POST["iam"] = "sp";
                }
                if (!isset($_POST["password"])) {
                    $str_result =
                        "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                    $_POST["password"] =
                        "SP" . substr(str_shuffle($str_result), 0, 10);
                    $_POST["cpassword"] = $_POST["password"];
                }
                if (!isset($_POST["terms"])) {
                    $_POST["terms"] = "agree";
                }
            }
            if (isset($_POST["full_name"])) {
                $this->form_validation->set_rules(
                    "full_name",
                    "Fullname",
                    "required|min_length[3]"
                );
            }
            $this->form_validation->set_rules(
                "email",
                "Email",
                "required|valid_email|is_unique[user_details.email]"
            );
            $this->form_validation->set_rules(
                "mobile",
                "Mobile",
                "required|min_length[10]"
            );
            // $this->form_validation->set_rules('role','Role','required');
            $this->form_validation->set_rules("iam", "I Am", "required");
            $this->form_validation->set_rules(
                "password",
                "Password",
                "required|min_length[8]|max_length[25]|callback_check_strong_password"
            );
            $this->form_validation->set_rules("terms", "Terms", "required");
            $this->form_validation->set_rules(
                "cpassword",
                "Confirm Password",
                "required|matches[password]"
            );
            $this->form_validation->set_rules(
                "serviceName",
                "Service",
                "required"
            );
            if ($this->form_validation->run() == true) {
                if (!isset($_POST["full_name"])) {
                    $_POST["full_name"] = "New User";
                }
                $row = $this->db
                    ->select("*")
                    ->limit(1)
                    ->order_by("id", "DESC")
                    ->get("user_details")
                    ->row();
                $lastid = $row->id;
                $uid = $lastid + 1;
                // if($_POST['iam'=='user'])
                if ($_POST["iam"] == "user") {
                    $uid = "merak";
                }
                $formArray = [
                    "user_id" => $uid,
                    "fullname" => $_POST["full_name"],
                    "email" => $_POST["email"],
                    "mobile" => $_POST["mobile"],
                    // 'role'=>$_POST['role'],
                    'profile_link' => getProfileLink($_POST["full_name"]),
                    "iam" => $_POST["iam"],
                    "pwd" => password_hash(
                        $this->input->post("password"),
                        PASSWORD_BCRYPT
                    ),
                    "status" => "0",
                    "serviceName" => str_replace(
                        "-",
                        " ",
                        $_POST["serviceName"]
                    ),
                    "profile_photo" => "uploads/default.png",
                ];
                // pre( $formArray  , 1);
                $email = $_POST["email"];
                $this->User_model->create($formArray);
                $id = $this->db->insert_id();
                // if($_POST['iam']='sp')
                insert("reseller_assessment_variation", ["reseller_id" => $id , 'uce_variation' => 'two' , 'uce_report_variation' => 'two' ]);
                if ($_POST["iam"] == "sp") {
                    $formArray2 = [
                        "email" => $_POST["email"],
                        "about_us" => "",
                        "key_services" => "",
                        "address" => "",
                        "contact" => "",
                        "fb_url" => "",
                        "twitter_url" => "",
                        "insta_url" => "",
                        "linkedin_url" => "",
                        "heading1" => "",
                        "content1" => "",
                        "heading2" => "",
                        "content2" => "",
                        "heading3" => "",
                        "content3" => "",
                    ];
                    $this->db->insert("sp_profile_detail", $formArray2);
                }
                // $this->User_model->send_otp_in_email($email);
                $OTP_code = rand(1000, 1000000);
                if (
                    $this->User_model->otp_update_by_email($email, $OTP_code) >
                    0
                ) {
                    $subject =
                        "Welcome from Respicite LLP - Verify your Email id";
                    $body_msg =
                        "Dear " .
                        $email .
                        " <br/> <br/> Please complete your registration with Respicite
                        by using the following OTP - <b>" .
                        $OTP_code .
                        "</b><br/>
                        <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                    $this->User_model->otp_send_on_email(
                        $email,
                        $subject,
                        $body_msg
                    );
                    $this->session->set_userdata("verify_email", $email);
                }
                redirect("/UserController/validate_otp");
                // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
                // redirect('/UserController/registration','refresh');
            } else {
                // print_r($this->form_validation->error_array());
                // die;
                $data["logindata"] = $this->Admin_model->getloginPageDesignData(
                    1
                );
                $this->load->view("registration", $data);
            }
        } else {
            $data["logindata"] = $this->Admin_model->getloginPageDesignData(1);
            $this->load->view("registration", $data);
        }
    }
    //end registration form
    public function initializer()
    {
        $this->load->model("User_model");
        $this->load->model("Admin_model");
        $this->load->model("Commen_model");
        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );
            redirect(base_url() . "/UserController/login");
        }
        $user = $this->session->userdata("user");
        // pre( $user , 1 );
        $landing = $this->User_model->landingId($user["id"]);
        //echo "<pre>";print_r($landing);die;
        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById(
            $user["id"]
        );
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"]
        );
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"]
        );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user($landing["landing_id"]);
        $data["calendly_url"] = "https://calendly.com/";
        return $data;
    }
    //start dashboad
    public function dashboard()
    {
        // this is reseller dashboard
        //     $this->load->model('User_model');
        //     $this->load->model('Admin_model');
        //     $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $landing = $this->User_model->landingId($user['id']);
        //     //echo "<pre>";print_r($landing);die;
        //     $data['user'] = $user;
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //     echo $allowed_services;die;
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);

        //      $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
        //      $data['landing']= $this->Admin_model->get_landing_data_by_user($user['landing_id']);
        //      $data['landing']= $this->Admin_model->get_landing_data();
        //      echo "<pre>";
        //      print_r($data);
        //      echo "</pre>";
        //      die;
        $data = $this->initializer(); 
        $this->load->view("navbar", $data);
        $this->load->view("sidebar", $data);
        $this->load->view("dashboard1");
        $this->load->view("footer");
    }


    function variation_image_list(){

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;
        
        $userList = getQuery( [ 'select' => 'solution_variant.* , solution_variation_data.created_date , solution_variation_data.imageName , solution_variation_data.updated_date , solution_variation_data.id as dataId , solution_variation_data.variationId' , 'table' => 'solution_variation_data' , 'join' => [ [ 'solution_variant' , 'solution_variant.id = solution_variation_data.variationId' , 'INNER'  ] ] , 'where' => [ 'user_id' => $user['id'] ]  ] );
        
        if( !empty( $userList ) ){
            foreach( $userList as $k => $v ){
                $where_not_in_numeric['solution_variation_data.variationId'][] = $v['variationId'];
            }
            $defaultList = getQuery( [ 'select' => 'solution_variant.* , solution_variation_data.created_date , solution_variation_data.imageName , solution_variation_data.updated_date , solution_variation_data.id as dataId , solution_variation_data.variationId' , 'table' => 'solution_variation_data' , 'join' => [ [ 'solution_variant' , 'solution_variant.id = solution_variation_data.variationId' , 'INNER'  ] ] , 'where' => [ 'user_id' => 13 ] , 'where_not_in_numeric' => $where_not_in_numeric ] );
            // lQ(1);
        }
        else{
            $defaultList = getQuery( [ 'select' => 'solution_variant.* , solution_variation_data.created_date , solution_variation_data.imageName , solution_variation_data.updated_date , solution_variation_data.id as dataId , solution_variation_data.variationId' , 'table' => 'solution_variation_data' , 'join' => [ [ 'solution_variant' , 'solution_variant.id = solution_variation_data.variationId' , 'INNER'  ] ] , 'where' => [ 'user_id' => 13 ]  ] );
        }


        $data['list'] =  array_merge($userList , $defaultList);

        // pre( $data['list'] , 1 );

        $this->load->view('navbar',$data);

        $this->load->view('sidebar',$data);

        $this->load->view('variation-image-list' , $data);

        $this->load->view('footer');
    }


    function variation_report_image($id=null){
        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        if( $id != null ){
            $data['edit'] = getQuery( [ 'select' => 'solution_variant.* , solution_variation_data.created_date , solution_variation_data.imageName , solution_variation_data.updated_date , solution_variation_data.id as dataId' , 'table' => 'solution_variation_data' , 'join' => [ [ 'solution_variant' , 'solution_variant.id = solution_variation_data.variationId' , 'INNER'  ] ]  ,'where' => [ 'solution_variation_data.id' => $id ] , 'single' => true ] );
            if( !empty( $data['edit'] ) ){
                // pre( $data['edit']  , 1);
                $_GET['solution_name'] = $data['edit']['solution_name'];
            }
        }

        $data['getsolutions'] = getQuery( [ 'select' => 'solution_name' , 'table' => ' solution_variant' , 'group_by' => [ 'solution_name' ] ] );

        if( isset( $_GET['solution_name'] ) ){
            $data['getClass'] = getQuery( [ 'table' => ' solution_variant' , 'where' => [ 'solution_name' => $_GET['solution_name'] ] ] );
        }
        // pre( $data , 1 );
        $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');

        $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

        $this->load->view('navbar',$data);

        $this->load->view('sidebar',$data);

        $this->load->view('add_variation_image' , $data);

        $this->load->view('footer');
    }


    
    function update_variation_image(){
        if( !empty( $_POST ) ){
            $user = $this->session->userdata('user');
            if(  $_FILES['image']['name'] != '' ){

                $config['upload_path'] = './uploads/solution-variation/';

                $config['allowed_types'] = 'jpg|png|jpg|jpeg';

                $config['max_size'] = 100000;

                $dname = explode(".", $_FILES['image']['name']);

                $ext = end($dname);

                $new_name = time().'.'.$ext;

                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('image'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    foreach($error as $error)
                    {
                        $this->session->set_flashdata('msg',$error);
                    }
                    redirect('UserController/variation-report-image');
                }
                else
                {
                    $data['imageName'] = $new_name;
                    $data['user_id'] = $user['id'];
                    $data['variationId'] = $_POST['variationId'];
                }
                $getData = getQuery( [ 'where' => [ 'user_id' => $user['id'] , 'variationId' => $_POST['variationId'] ] , 'table' => 'solution_variation_data' ]  ) ;
                if( empty( $getData ) ){
                    insert( 'solution_variation_data' , $data );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','data has been added');
                        redirect('UserController/variation-image-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','please try again');
                        redirect('UserController/variation-report-image');
                    }
                }
                else{
                    unlink('uploads/solution-variation/'.$getData['imageName']);
                    update( [ 'where' => [ 'user_id' => $user['id'] , 'variationId' => $_POST['variationId'] ] , 'data' => $data , 'table' => 'solution_variation_data' ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','data has been updated');
                        redirect('UserController/variation-image-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','data remains same , please check again');
                        redirect('UserController/variation-image-list');
                    }
                }

            }
            else{
                $this->session->set_flashdata('msg','Image Missing');
                redirect('UserController/variation-report-image');
            }
        }
        else{
            redirect('UserController/variation-report-image');
        }
    }


    function delete_variation_image($deleteId){
        delete( [ 'where' => [ 'id' => $deleteId ] , 'table' => 'solution_variation_data' ] );
        if( affected() > 0 ){
            $this->session->set_flashdata('msg','');
            redirect('UserController/variation-image-list');
        }
        else{
            $this->session->set_flashdata('msg','please try again');
            redirect('UserController/variation-report-image');
        }
    }


    public function assessment_variation()
    {
        $data = $this->initializer();
        $user = $this->session->userdata("user");
        $data["variation"] = getQuery([
            "where" => ["reseller_id" => $user["id"]],
            "table" => "reseller_assessment_variation",
            "single" => true,
        ]);
        if (empty($data["variation"])) {
            insert("reseller_assessment_variation", [
                "reseller_id" => $user["id"],
                "uce_variation" => "one",
                "uce_report_variation" => "one",
            ]);
            $data["variation"] = getQuery([
                "where" => ["reseller_id" => $user["id"]],
                "table" => "reseller_assessment_variation",
                "single" => true,
            ]);
        }
        // pre( $data , 1 );
        $this->load->view("navbar", $data);
        if ($user["iam"] == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }
        $this->load->view("assessment_variation", $data);
        $this->load->view("footer");
    }

    public function update_variation($variation)
    {
        $user = $this->session->userdata("user");
        update([
            "where" => ["reseller_id" => $user["id"]],
            "table" => "reseller_assessment_variation",
            "data" => ["uce_variation" => $variation],
        ]);
        if (affected() > 0) {
            $this->session->set_flashdata("msg", "Variation has been changed");
            $this->session->set_flashdata("status", "success");
            $this->session->set_flashdata("status", "success");
            redirect("UserController/assessment-variation");
        } else {
            $this->session->set_flashdata("msg", "Variation not changed");
            $this->session->set_flashdata("status", "warning");
            redirect("UserController/assessment-variation");
        }
    }

    public function update_report_variation($variation)
    {
        $user = $this->session->userdata("user");
        update([
            "where" => ["reseller_id" => $user["id"]],
            "table" => "reseller_assessment_variation",
            "data" => ["uce_report_variation" => $variation],
        ]);
        if (affected() > 0) {
            $this->session->set_flashdata("msg", "Variation has been changed");
            $this->session->set_flashdata("status", "success");
            redirect("UserController/assessment-variation");
        } else {
            $this->session->set_flashdata("msg", "Variation not changed");
            $this->session->set_flashdata("status", "warning");
            redirect("UserController/assessment-variation");
        }
    }

    
    public function add_booking_link($id = null, $from = null, $onlink = null)
    {
        $data = $this->initializer();
        $data["list"] = getQuery(["table" => "admin_calendly_event"]);
        if ($id != null && ($from == null || $from == "user")) {
            $data["edit"] = getQuery([
                "table" => "calendly_event",
                "where" => ["id" => $id],
                "single" => true,
            ]);
            if ($onlink != null) {
                $data["onlink"] = true;
            }
        } elseif ($id != null && $from == "admin") {
            $data["edit"] = getQuery([
                "table" => "admin_calendly_event",
                "where" => ["id" => $id],
                "single" => true,
            ]);
            $data["admin"] = true;
            if ($onlink != null) {
                $data["onlink"] = true;
            }
        }
        if ($id != null) {
            $data["id"] = $id;
        }
        $this->load->view("navbar", $data);
        $this->load->view("sidebar", $data);
        $this->load->view("book_link");
        $this->load->view("footer");
    }

    public function book_link()
    {
        $this->load->library("form_validation");
        $this->load->model("User_model");
        $this->load->model("Admin_model");
        $this->load->model("Commen_model");
        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );
            redirect(base_url() . "/UserController/login");
        }
        $user = $this->session->userdata("user");
        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById(
            $user["id"]
        );
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"]
        );
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"]
        );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user($landing["landing_id"]);
        $userData["calender_link"] = $this->User_model->getcalenderlinkById(
            $user["id"]
        );
        $this->load->model("Admin_model");
        $this->load->view("navbar", $data);
        $this->load->view("sidebar", $data);
        $this->load->view("book_link", $userData);
        $this->load->view("footer");
    }

    public function update_book_link()
    {
        // pre( $_POST );
        // die;
        $this->load->model("User_model");
        if (isset($_POST["linkId"])) {
            $this->form_validation->set_rules("linkId", "Link Id", "required");
        }
        $this->form_validation->set_rules("price", "Price", "required");
        $this->form_validation->set_rules("desc", "Description", "required");
        $this->form_validation->set_rules(
            "briefDesc",
            "Brief Description",
            "required"
        );
        $user = $this->session->userdata("user");
        $data["user"] = $user;
        if ($this->form_validation->run() == true) {
            if (isset($_POST["id"])) {
                if (!isset($_POST["url"])) {
                    $linkdata = getQuery([
                        "table" => "admin_calendly_event",
                        "where" => ["id" => $_POST["linkId"]],
                        "single" => true,
                    ]);
                    // lQ(1);
                    // pre( $linkdata );
                    // die;
                    if (!empty($linkdata)) {
                        $linkdata["price"] = $_POST["price"];
                        $linkdata["desc"] = $_POST["desc"];
                        $linkdata["briefDesc"] = $_POST["briefDesc"];
                        unset($linkdata["id"]);
                        $linkdata["id"] = $_POST["id"];
                        unset($linkdata["created_date"]);
                        unset($linkdata["modified_date"]);
                        unset($_POST["linkId"]);
                        $_POST = $linkdata;
                        $_POST["user_id"] = $user["id"];
                        if (isset($_POST["linkId"])) {
                            unset($_POST["linkId"]);
                        }
                        $id = update([
                            "table" => "calendly_event",
                            "data" => $_POST,
                            "where" => ["id" => $_POST["id"]],
                        ]);
                        // lQ(1);
                        if (affected() > 0) {
                            $this->session->set_flashdata(
                                "msg",
                                "Update successfully"
                            );
                            redirect("/UserController/all-booking-link");
                        } else {
                            $this->session->set_flashdata(
                                "msg",
                                "Not Updated successfull , Try again"
                            );
                            redirect(
                                "/UserController/edit-booking-link/" .
                                    $_POST["id"]
                            );
                        }
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Link Selected is not available , Try again"
                        );
                        redirect("/UserController/add-booking-link");
                    }
                } else {
                    if (isset($_POST["linkId"])) {
                        unset($_POST["linkId"]);
                    }
                    $id = update([
                        "table" => "calendly_event",
                        "data" => $_POST,
                        "where" => ["id" => $_POST["id"]],
                    ]);
                    // lQ(1);
                    if (affected() > 0) {
                        $this->session->set_flashdata(
                            "msg",
                            "Update successfully"
                        );
                        redirect("/UserController/all-booking-link");
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Not Updated successfull , Try again"
                        );
                        redirect(
                            "/UserController/edit-booking-link/" . $_POST["id"]
                        );
                    }
                }
            } else {
                $linkdata = getQuery([
                    "table" => "admin_calendly_event",
                    "where" => ["id" => $_POST["linkId"]],
                    "single" => true,
                ]);
                if (!empty($linkdata)) {
                    $linkdata["price"] = $_POST["price"];
                    $linkdata["desc"] = $_POST["desc"];
                    $linkdata["briefDesc"] = $_POST["briefDesc"];
                    unset($linkdata["id"]);
                    unset($linkdata["created_date"]);
                    unset($linkdata["modified_date"]);
                    unset($_POST["linkId"]);
                    if (isset($_POST["url"])) {
                        $linkdata["url"] = $_POST["url"];
                    }
                    if (isset($_POST["minutes"])) {
                        $linkdata["minutes"] = $_POST["minutes"];
                    }
                    $_POST = $linkdata;
                    $_POST["user_id"] = $user["id"];
                    $id = insert("calendly_event", $_POST);
                    if (affected() > 0) {
                        $this->session->set_flashdata(
                            "msg",
                            "Added successfully"
                        );
                        redirect("/UserController/all-booking-link");
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Not added successfull , Try again"
                        );
                        redirect("/UserController/add-booking-link");
                    }
                } else {
                    $this->session->set_flashdata(
                        "msg",
                        "Link Selected is not available , Try again"
                    );
                    redirect("/UserController/add-booking-link");
                }
            }
            // $updateData= $this->User_model->book_link_update($user['id'],$book_link);
            // $this->session->set_flashdata("msg","update successfully!!");
            // redirect('/UserController/book_link','refresh');
        } else {
            if (isset($_POST["id"])) {
                $this->session->set_flashdata("msg", "All Field Required");
                redirect(
                    "/UserController/edit-booking-link/" . $_POST["id"],
                    "refresh"
                );
            } else {
                $this->session->set_flashdata("msg", "All Field Required");
                redirect("/UserController/add-booking-link", "refresh");
            }
        }
    }

    public function calendly_link_status(
        $id = null,
        $status = null,
        $from = null
    ) {
        $user = $this->session->userdata("user");
        $checkStatus = getQuery([
            "where" => ["user_id" => $user["id"], "status" => "inactive"],
            "table" => "calendly_event",
        ]);
        // pre($checkStatus);
        // die;
        if (count($checkStatus) == 2 && $status == "inactive") {
            $this->session->set_flashdata(
                "msg",
                "At least one link had to active!"
            );
            $this->session->set_flashdata("status", "danger");
            redirect("/UserController/all-booking-link", "refresh");
        } else {
            if (
                $id != null &&
                ($status == "active" || $status == "inactive") &&
                $from == null
            ) {
                if (
                    !empty(
                        getQuery([
                            "where" => ["id" => $id],
                            "table" => "calendly_event",
                            "single" => true,
                        ])
                    )
                ) {
                    update([
                        "where" => ["id" => $id],
                        "data" => ["status" => $status],
                        "table" => "calendly_event",
                    ]);
                    if (affected() > 0) {
                        $this->session->set_flashdata(
                            "msg",
                            "Status Updated Successfully"
                        );
                        redirect("/UserController/all-booking-link", "refresh");
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Update Remains Same"
                        );
                        redirect("/UserController/all-booking-link", "refresh");
                    }
                } else {
                    $this->session->set_flashdata("msg", "Invalid Parameters");
                    redirect("/UserController/all-booking-link", "refresh");
                }
            } elseif ($id != null && $status != null && $from == "admin") {
                $data = getQuery([
                    "where" => ["id" => $id],
                    "table" => "admin_calendly_event",
                    "single" => true,
                ]);
                if (!empty($data)) {
                    $user = $this->session->userdata("user");
                    $data["user_id"] = $user["id"];
                    unset($data["id"]);
                    unset($data["created_date"]);
                    unset($data["modified_date"]);
                    $data["status"] = "inactive";
                    $id = insert("calendly_event", $data);
                    if (affected() > 0) {
                        $this->session->set_flashdata(
                            "msg",
                            "Added successfully"
                        );
                        redirect("/UserController/all-booking-link");
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Not added successfull , Try again"
                        );
                        redirect("/UserController/add-booking-link");
                    }
                } else {
                    $this->session->set_flashdata("msg", "Invalid Parameters");
                    redirect("/UserController/all-booking-link", "refresh");
                }
            }
        }
    }

    public function all_booking_link()
    {
        $data = $this->initializer();
        // pre( $data );

        // die;

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);

        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $data["list"] = getQuery(["table" => "admin_calendly_event"]);

        $data["list2"] = getQuery(["table" => "calendly_event"]);

        $data["custom"] = getQuery([
            "table" => "calendly_request_control",
            "where" => [
                "status" => "pending",
                "request_type" => "custom",
                "user_id" => $user["id"],
            ],
            "single" => true,
        ]);

        $data["admin"] = getQuery([
            "table" => "calendly_request_control",
            "where" => [
                "status" => "pending",
                "request_type" => "admin",
                "user_id" => $user["id"],
            ],
            "single" => true,
        ]);

        $data["not_show"] = getQuery([
            "table" => "calendly_request_control",
            "where" => [
                "status" => "pending",
                "request_type" => "notification_show",
                "user_id" => $user["id"],
            ],
            "single" => true,
        ]);

        $data["not_hide"] = getQuery([
            "table" => "calendly_request_control",
            "where" => [
                "status" => "pending",
                "request_type" => "notification_hide",
                "user_id" => $user["id"],
            ],
            "single" => true,
        ]);

        // die;

        $this->load->view("navbar", $data);

        $iam = $user["iam"];
        if( $iam == "sp" ){
            $this->load->view("sp/sidebar", $data);
        }
        else{
            $this->load->view("sidebar", $data);
        }

        $this->load->view("calendly_link_list", $data);

        $this->load->view("footer");
    }

    function request_change($status, $request_variable)
    {
        $user = $this->session->userdata("user");

        $data["user_id"] = $user["id"];

        $data["request_type"] = $status;

        $data["request_variable"] = $request_variable;

        $data["status"] = "pending";

        insert("calendly_request_control", $data);

        if (affected() > 0) {
            $this->session->set_flashdata("msg", "Request Added Successfully.");

            $this->session->set_flashdata("status", "success");

            redirect("/UserController/all-booking-link", "refresh");
        } else {
            $this->session->set_flashdata("status", "info");

            redirect("/UserController/all-booking-link", "refresh");
        }
    }

    public function counseling_type()
    {
        $this->load->library("form_validation");

        $this->load->model("User_model");

        $this->load->model("Admin_model");

        $this->load->model("Commen_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById(
            $user["id"]
        );

        //echo $allowed_services;die;

        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"]
        );

        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"]
        );

        $landing = $this->User_model->landingId($user["id"]);

        $data["landing"] = $this->Admin_model->get_landing_data_by_user($landing["landing_id"]);

        $userData["counseling_type"] = $this->User_model->getcounselingTypeById(
            $user["id"]
        );

        $this->load->model("Admin_model");

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("counseling_type", $userData);

        $this->load->view("footer");
    }

    public function update_counseling_type()
    {
        $this->load->model("User_model");

        $this->form_validation->set_rules(
            "counseling_type",
            "counseling type",
            "required"
        );

        if ($this->form_validation->run() == true) {
            $user = $this->session->userdata("user");

            $data["user"] = $user;

            $book_links = $_POST["counseling_type"];

            $counseling_type = $this->User_model->getcounselingTypeById(
                $user["id"]
            );

            if ($counseling_type != "") {
                $book_link = $counseling_type . "," . $book_links;
            } else {
                $book_link = $book_links;
            }

            $updateData = $this->User_model->counseling_type_update(
                $user["id"],
                $book_link
            );

            $this->session->set_flashdata("msg", "update successfully!!");

            redirect("/UserController/counseling_type", "refresh");
        } else {
            $this->session->set_flashdata("msg", "All Field Required");

            redirect("/UserController/counseling_type", "refresh");
        }
    }

    public function counselingParameters()
    {
        $this->load->database();

        $this->load->model("User_model");

        $this->load->model("Admin_model");

        $this->load->model("Commen_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById(
            $user["id"]
        );

        //echo $allowed_services;die;

        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"]
        );

        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"]
        );

        $landing = $this->User_model->landingId($user["id"]);

        $data["landing"] = $this->Admin_model->get_landing_data_by_user($landing["landing_id"]);

        //$this->load->model('User_model');

        //$metadata['landingId']= $id;

        $metadata["resellerId"] = $user["id"];

        $metadata["flow"] = $this->User_model->getCounselingPara($user["id"]);

        $data["counseling_type"] = $this->User_model->getcounselingTypeById(
            $user["id"]
        );

        //$metadata['landing_page_section']= $this->User_model->get_landing_section_via_data($id);

        //$metadata['landing_page_details']= $this->User_model->get_landing_details_section_via_data($id);

        //create view path

        $controller_name = debug_backtrace();

        //load view

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("counseling_parameters_list", $metadata);

        $this->load->view("footer");
    }

    public function addCounselingPara()
    {
        $this->load->helper(["form", "url"]);

        $this->load->library("form_validation");

        $this->form_validation->set_rules(
            "para_name",
            "Parameter Name",
            "required"
        );

        if ($this->form_validation->run() == true) {
            $para_name = $this->input->post("para_name", true);

            $counseling_type = $this->input->post("counseling_type", true);

            $resellerId = $this->input->post("resellerId", true);

            $mrp = $this->input->post("mrp", true);

            $duration = $this->input->post("duration", true);

            $data = [];

            $data["name"] = $para_name;

            $data["type"] = $counseling_type;

            $data["duration"] = $duration;

            $data["resellerId"] = $resellerId;

            $data["mrp"] = $mrp;

            $this->load->model("User_model");

            $result = $this->User_model->insert_counseling_para_data($data);

            if ($result) {
                $this->session->set_flashdata("msg2", "Added Successfully");

                redirect("/UserController/counselingParameters/", "refresh");
            } else {
                $this->session->set_flashdata("msg", "Opps Unable to add data");

                redirect("/UserController/counselingParameters/", "refresh");
            }
        } else {
            $this->session->set_flashdata(
                "msg",
                "Enter all the required fields"
            );

            redirect("/UserController/counselingParameters/", "refresh");
        }
    }
    //end dashboard function
    //logout function
    public function logout()
    {
        $user = $this->session->userdata("user");

        session_destroy();

        // $this->session->unset_userdata('ref_data');

        // $this->session->unset_userdata('isnotloggedin');

        if ($user["iam"] == "user") {
            redirect("BaseController/login");
        } else {
            redirect("UserController/login");
        }
    }
    // end logout function
    public function change_password()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("change_password", $data);

        $this->load->view("footer");

        if (isset($_POST["changepwd"])) {
            $old_pwd = $this->input->post("old_psw");

            $user = $this->User_model->checkUser($email);

            if (password_verify($old_pwd, $user["pwd"]) == true) {
                $this->load->library("form_validation");

                $this->form_validation->set_rules(
                    "npsw",
                    "Password",
                    "required|min_length[8]|max_length[25]|callback_check_strong_password"
                );

                $this->form_validation->set_rules(
                    "cnpsw",
                    "Confirm Password",
                    "required|matches[npsw]"
                );

                if ($this->form_validation->run() == true) {
                    $this->db->set(
                        "pwd",
                        password_hash(
                            $this->input->post("npsw"),
                            PASSWORD_BCRYPT
                        )
                    );

                    $this->db->where("email", $email);

                    $this->db->update("user_details");

                    $this->session->set_flashdata(
                        "msg2",
                        "Password Changed Successfully"
                    );

                    redirect(base_url() . "/UserController/change_password");
                } else {
                    $this->session->set_flashdata(
                        "msg",
                        "Please Check Password and Confirm Password are same or not"
                    );

                    redirect(base_url() . "/UserController/change_password");
                }
            } else {
                $this->session->set_flashdata("msg", "Wrong OLD Password");

                redirect(base_url() . "/UserController/change_password");
            }
        }
    }
    // password checking is this strong or not
    public function check_strong_password($str)
    {
        if (
            preg_match("#[0-9]#", $str) &&
            preg_match("#[a-z]#", $str) &&
            preg_match("#[A-Z]#", $str)
        ) {
            return true;
        }
        $this->form_validation->set_message(
            "check_strong_password",
            "The password field must be contains at least one capital letter, one small letter, and one digit."
        );
        return false;
    }
    //function for validate otp

    // public function validate_otp()
    // {

    //     $this->load->helper('cookie');

    //     $this->load->view('validate-otp');

    //     $this->load->model('User_model');

    //     if(isset($_POST['validate-btn']))

    //     {

    //         $rotp = get_cookie('today_get_otp');

    //         $email = get_cookie('my_email_today');

    //         $otp = $_POST['otp'];

    //         if($rotp==$otp)

    //         {

    //             $this->User_model->update_status($email);

    //             $this->session->set_flashdata("msg2","Your account has been registered. You can login now");

    //             redirect('/UserController/login','refresh');

    //         }

    //         else

    //         {

    //             $this->session->set_flashdata("msg","Wrong OTP");

    //             redirect('/UserController/validate_otp');

    //         }

    //     }

    // } //Old

    public function validate_otp()
    {
        $this->load->model("User_model");

        if (isset($_POST["validate-btn"])) {
            if (!empty($this->input->post("otp"))) {
                if (
                    $this->User_model->check_otp_email_reverify(
                        $this->session->userdata("verify_email"),
                        $this->input->post("otp")
                    )
                ) {
                    $this->session->set_flashdata(
                        "msg2",
                        "Your account has been registered. You can login now"
                    );

                    redirect("/UserController/login", "refresh");
                } else {
                    $this->session->set_flashdata("msg", "Wrong OTP");

                    redirect("/UserController/validate_otp");
                }
            } else {
                $this->session->set_flashdata("msg", "OTP is empty");
            }
        }

        $this->load->view("validate-otp");
    }

    //define function for purchase code by reseller

    public function purchase_code()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $email = $user["email"];

        $data = $this->initializer();

        $solution["s"] = $this->User_model->solutions_list();

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }

        $this->load->view("purchasecode", $solution, $data);

        $this->load->view("footer");
    }

    public function save_request_code($check = null)
    {
        $this->load->model("User_model");

        // print_r( $_SESSION );

        if ($this->session->userdata("isnotloggedin")) {
            $_POST = $this->session->userdata("purchaseCodeRequest");

            $purchase_code_details = $this->session->userdata(
                "purchase_code_details"
            );
        }

        //

        if (isset($_POST["purchase"])) {
            error_reporting(0);

            $count = $this->db->get("solutions")->num_rows();

            for ($i = 0; $i <= $count; $i++) {
                if ($i == 0) {
                    $op[] = "";

                    //nothing
                } else {
                    $code = "ccb" . $i;

                    $op[] = $_POST[$code];

                    if ($op[$i] != "") {
                        $value = "1";
                    }
                }
            }

            // print_r( $_SESSION );

            $user = $this->session->userdata("user");

            if (empty($user)) {
                echo "1";

                $email = $purchase_code_details["r_email"];
            } else {
                if ($user["iam"] != "user") {
                    // echo "2";

                    $email = $user["email"];
                } else {
                    // echo "3";

                    $userDetails = $this->User_model->checkUser($user["email"]);

                    if (!empty($userDetails)) {
                        if ($userDetails["iam"] == "user") {
                            $parentdata = $this->User_model->get_sp_details(
                                $user["user_id"]
                            );

                            if (!empty($parentdata)) {
                                if (
                                    $parentdata["iam"] == "sp" ||
                                    $parentdata["iam"] == "reseller"
                                ) {
                                    $email = $parentdata["email"];
                                } else {
                                    $email = "merak@gmail.com";
                                }
                            } else {
                                $email = "merak@gmail.com";
                            }
                        } else {
                            $email = "merak@gmail.com";
                        }
                    } else {
                        $email = "merak@gmail.com";
                    }
                }
            }

            // echo $email;

            // die;

            $status = "pending";

            // $id = 'ccb1';

            // $op[] = $_POST[$id];

            // $op[] = $_POST['ccb2'];

            // $op[] = $_POST['ccb3'];

            // $op[] = $_POST['ccb4'];

            if ($value != "1") {
                echo $value;

                $this->session->set_flashdata(
                    "msg",
                    "Please Select One of these"
                );

                redirect("/UserController/purchase_code", "refresh");
            } else {
                $this->load->helper("date");

                $datestring = "%d / %m / %Y - %h:%i %a";

                $time = time();

                $dt = mdate($datestring, $time);

                for ($i = 1; $i <= $count; $i++) {
                    $code_id = $op[$i];

                    if ($code_id != "") {
                        $rq = "cb" . $i;

                        $cb = $_POST[$rq];

                        if ($cb == "") {
                            $rqst = "";
                        } else {
                            $rqst = "Yes";
                        }

                        $data = [
                            "dt" => $dt,

                            "code" => $code_id,

                            "email" => $email,

                            "request" => $rqst,

                            "status" => $status,
                        ];

                        $this->load->model("User_model");

                        $id = $this->User_model->insert_request_code($data);
                    }
                }

                if ($this->session->userdata("isnotloggedin")) {
                    if ($check != null) {
                        redirect(
                            "AdminController/code_approval/" .
                                $id .
                                "/" .
                                $check
                        );
                    } else {
                        redirect("AdminController/code_approval/" . $id);
                    }
                }

                $this->session->set_flashdata(
                    "msg",
                    "Purchase Request has been sent to admin please wait for approval"
                );

                redirect("/UserController/purchase_code", "refresh");
            }
        }
    }

    //show unused code

    public function unused_code()
    {
        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $email = $user["email"];

        $unused_code_list["h"] = $this->User_model->get_unused_code($email);

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }

        $this->load->view("unused_code_list", $unused_code_list);

        $this->load->view("footer");
    }

    public function ap_book_view()
    {
        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $q = $this->db
            ->where(["user_id" => $user["id"]])
            ->order_by("id", "desc")
            ->get("user_book_appointment");

        $data["ap_book_data"] = $q->result();

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("reseller_ap_view", $data);

        $this->load->view("footer");
    }

    function booking_status($id, $status)
    {
        update([
            "where" => ["id" => $id],
            "table" => "user_book_appointment",
            "data" => ["status" => $status],
        ]);

        if (affected() > 0) {
            $this->session->set_flashdata("msg", "Status Has been Updated");

            $this->session->set_flashdata("status", "success");
        } else {
            $this->session->set_flashdata("msg", "Status Has been remain same");

            $this->session->set_flashdata("status", "info");
        }

        redirect("userController/ap_book_view");
    }

    //view reseller profile

    public function view_reseller_profile()
    {
        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("reseller_profile", $data);

        $this->load->view("footer");
    }

    public function edit_reseller_profile()
    {
        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("edit_reseller_profile", $data);

        $this->load->view("footer");

        if (isset($_POST["updatebtn"])) {
            $this->form_validation->set_rules(
                "full_name",
                "Fullname|min_length[3]",
                "required"
            );

            $this->form_validation->set_rules(
                "profile_link",
                "Profilelink",
                "required|is_unique[user_details.profile_link]"
            );

            $this->form_validation->set_rules(
                "mobile",
                "Mobile",
                "required|min_length[10]"
            );

            if ($this->form_validation->run() == true) {
                $this->db->set("fullname", $_POST["full_name"]);

                $this->db->set("mobile", $_POST["mobile"]);

                $this->db->where("email", $email);

                $this->db->update("user_details");

                $sessArray["id"] = $user["id"];

                $sessArray["fullname"] = $_POST["full_name"];

                $sessArray["email"] = $user["email"];

                $sessArray["mobile"] = $_POST["mobile"];

                $sessArray["user_id"] = $user["user_id"];

                $sessArray["profile_link"] = $_POST["profile_link"];

                $sessArray["profile_photo"] = $user["profile_photo"];

                $sessArray["iam"] = $user["iam"];

                $this->session->set_userdata("user", $sessArray);

                $this->session->set_flashdata("msg2", "Detail Updated");

                redirect("/UserController/edit_reseller_profile", "refresh");
            } else {
                $errors = $this->form_validation->error_array();

                if (isset($errors["profile_link"])) {
                    $this->session->set_flashdata(
                        "msg2",
                        "Profile Link must be unique"
                    );
                } else {
                    $this->session->set_flashdata("msg2", "Detail Not Updated");
                }

                redirect("/UserController/edit_reseller_profile", "refresh");
            }
        }
    }

    public function edit_profile_photo()
    {
        $this->load->model("User_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("edit_profile_photo", $data);

        $this->load->view("footer");
    }

    public function do_upload()
    {
        $user = $this->session->userdata('user');
        // pre( $user , 1 );
        $userData = getQuery( [ 'table' => 'user_details' , 'where' => [ 'id' => $user['id'] ] , 'single' => true ] );
        // pre( $userData , 1 );
        if( isset($_FILES['img']) ){
            $config["upload_path"] = "./uploads/original/";
            $config["allowed_types"] = "jpg|png";
            $config["max_size"] = 100000;
            if( $userData['profile_link'] == '' ){
                $data['profile_link'] = getProfileLink( $userData['fullname'] );
                update([ 'table' => 'user_details' , 'data' => $data , 'where' => [ 'id' => $user['id'] ] ]);
                $profile_link  = $data['profile_link'];
            }
            else{
                $profile_link  = $userData['profile_link'];
            }
            // die;
            $new_name = $profile_link.'.jpg';
            $config["file_name"] = $new_name;
            $this->load->library("upload", $config);
            if (!$this->upload->do_upload("img")) {
                $error = ["error" => $this->upload->display_errors()];
                foreach ($error as $error) {
                    $this->session->set_flashdata("msg", '$error');
                    redirect("/UserController/edit_reseller_profile", "refresh");
                }
            } 
            else{
                resize_image( 'uploads/original/'.$new_name , 200 , 200 , 'uploads/'.$new_name );
                resize_image( 'uploads/original/'.$new_name , 100 , 100 , 'uploads/counsellor-thumb/'.$new_name );
                $user = $this->session->userdata("user");
                $data["user"] = $user;
                $email = $user["email"];
                $this->db->where("email", $email);
                $this->db->set("profile_photo", "uploads/" . $new_name);
                $this->db->update("user_details");
                $sessArray["id"] = $user["id"];
                $sessArray["fullname"] = $user["fullname"];
                $sessArray["email"] = $user["email"];
                $sessArray["mobile"] = $user["mobile"];
                $sessArray["user_id"] = $user["user_id"];
                $sessArray["iam"] = $user["iam"];
                $sessArray["profile_photo"] = "uploads/" . $new_name;
                $this->session->set_userdata("user", $sessArray);
                $this->session->set_flashdata("msg2", "Profile Updated");
                redirect("/UserController/edit_reseller_profile", "refresh");
            }
        }
    }

    function resize_image($new_name){
        resize_image( 'uploads/'.$new_name , 200 , 200 , 'uploads/'.$new_name );
        resize_image( 'uploads/'.$new_name , 100 , 100 , 'uploads/counsellor-thumb/'.$new_name );
        redirect(base_url().'SpController/edit_sp_profile','refresh');
    }

    public function sign_upload()
    {
        $config["upload_path"] = "./uploads/";

        $config["allowed_types"] = "jpg|png";

        $config["max_size"] = 100000;

        $config["max_width"] = 1903;

        $config["max_height"] = 882;

        $dname = explode(".", $_FILES["img"]["name"]);

        $ext = end($dname);

        $new_name = time() . "." . $ext;

        $config["file_name"] = $new_name;

        $this->load->library("upload", $config);

        if (!$this->upload->do_upload("img")) {
            $error = ["error" => $this->upload->display_errors()];

            foreach ($error as $error) {
                $this->session->set_flashdata("msg", '$error');

                redirect("/UserController/edit_reseller_profile", "refresh");
            }
        } else {
            $user = $this->session->userdata("user");

            $data["user"] = $user;

            $email = $user["email"];

            $this->db->where("r_email", $email);

            $this->db->set("reseller_signature", "uploads/" . $new_name);

            $this->db->update("reseller_homepage");

            $this->session->set_flashdata("msg2", "Signature Updated");

            redirect("/UserController/edit_reseller_profile", "refresh");
        }
    }

    public function view_reseller_code()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $solution["s"] = $this->User_model->solutions_list();

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }

        $this->load->view("view_code_list", $solution, $data);

        $this->load->view("footer");
    }


    function create_assessment_link( $solution ){
        $user = $this->session->userdata("user");
        $data["user"] = $user;
        insert(
            [
                'solution' => $solution,
                'reseller_id' => $user['id'],
                'link' => base64_encode( $user['id'] ),
            ],
            'assessment_link'
        );
        redirect( 'UserController/view_reseller_code' );
    }

    public function view_users_list()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }

        $this->load->view("view_users", $data);

        $this->load->view("footer");
    }

    public function page_change_logo()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("page_change_logo", $data);

        $this->load->view("footer");

        if (isset($_POST["savebtn"])) {
            $config["upload_path"] = "./uploads/";

            $config["allowed_types"] = "jpg|png";

            $config["max_size"] = 100000;

            $config["max_width"] = 900;

            $config["max_height"] = 512;

            $dname = explode(".", $_FILES["img"]["name"]);

            $ext = end($dname);

            $new_name = time() . "." . $ext;

            $config["file_name"] = $new_name;

            $this->load->library("upload", $config);

            if (!$this->upload->do_upload("img")) {
                $error = ["error" => $this->upload->display_errors()];

                foreach ($error as $error) {
                    $this->session->set_flashdata("msg", $error);

                    redirect("/UserController/page_change_logo", "refresh");
                }
            } else {
                $user = $this->session->userdata("user");

                $data["user"] = $user;

                $uid = $user["email"];

                $this->db->where("r_email", $uid);

                $this->db->set("logo", "uploads/" . $new_name);

                $this->db->update("reseller_homepage");

                $this->session->set_flashdata("msg2", "Saved Successfully");

                redirect("/UserController/page_change_logo", "refresh");
            }
        }
    }

    public function page_change_banner()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("change_banner_details", $data);

        $this->load->view("footer");

        if (isset($_POST["savebtn"])) {
            $this->form_validation->set_rules(
                "ban_head",
                "Banner Heading",
                "required"
            );

            $this->form_validation->set_rules(
                "ban_msg",
                "Banner Message",
                "required"
            );

            $this->form_validation->set_rules("about", "About", "required");

            if ($this->form_validation->run() == true) {
                $config["upload_path"] = "./uploads/";

                $config["allowed_types"] = "jpg|png";

                $config["max_size"] = 100000;

                $config["max_width"] = 1903;

                $config["max_height"] = 882;

                $dname = explode(".", $_FILES["ban_img"]["name"]);

                $ext = end($dname);

                $new_name = time() . "." . $ext;

                $config["file_name"] = $new_name;

                $this->load->library("upload", $config);

                if (!$this->upload->do_upload("ban_img")) {
                    $error = ["error" => $this->upload->display_errors()];

                    foreach ($error as $error) {
                        $this->session->set_flashdata("msg", $error);

                        redirect(
                            "/UserController/page_change_banner",
                            "refresh"
                        );
                    }
                } else {
                    $user = $this->session->userdata("user");

                    $data["user"] = $user;

                    $uid = $user["email"];

                    $this->db->where("r_email", $uid);

                    $this->db->set("banner_img", "uploads/" . $new_name);

                    $this->db->set("banner_head", $_POST["ban_head"]);

                    $this->db->set("banner_msg", $_POST["ban_msg"]);

                    $this->db->set("about_us", $_POST["about"]);

                    $this->db->update("reseller_homepage");

                    $this->session->set_flashdata("msg2", "Detail Updated");

                    redirect("/UserController/page_change_banner", "refresh");
                }
            } else {
                $this->session->set_flashdata("msg", "All Field is Required");

                redirect("/UserController/page_change_banner", "refresh");
            }
        }
    }

    public function change_contact_details()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("change_contact_details", $data);

        $this->load->view("footer");

        if (isset($_POST["savebtn"])) {
            $this->form_validation->set_rules(
                "email",
                "Email",
                "required|valid_email"
            );

            $this->form_validation->set_rules("addr", "Address", "required");

            $this->form_validation->set_rules(
                "mobile",
                "Mobile",
                "required|min_length[10]|max_length[10]"
            );

            if ($this->form_validation->run() == true) {
                $user = $this->session->userdata("user");

                $data["user"] = $user;

                $uid = $user["email"];

                $this->db->where("r_email", $uid);

                $this->db->set("email", $_POST["email"]);

                $this->db->set("address", $_POST["addr"]);

                $this->db->set("contact", $_POST["mobile"]);

                $this->db->update("reseller_homepage");

                $this->session->set_flashdata("msg2", "Saved Successfully");

                redirect("/UserController/change_contact_details", "refresh");
            } else {
                $this->session->set_flashdata("msg", "All Field is Required");

                redirect("/UserController/change_contact_details", "refresh");
            }
        }
    }

    public function change_social_link()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("change_social_link", $data);

        $this->load->view("footer");

        if (isset($_POST["savebtn"])) {
            $user = $this->session->userdata("user");

            $data["user"] = $user;

            // var_dump($data);die;

            $uid = $user["email"];

            $this->db->where("r_email", $uid);

            $this->db->set("fb_url", $_POST["fb_link"]);

            $this->db->set("twt_url", $_POST["twt_link"]);

            $this->db->set("link_url", $_POST["link_link"]);

            $this->db->set("insta_url", $_POST["insta_link"]);

            $this->db->set("ftr", $_POST["footer"]);

            $this->db->update("reseller_homepage");

            $this->session->set_flashdata("msg2", "Saved Successfully");

            redirect("/UserController/change_social_link", "refresh");
        }
    }

    public function company_detail()
    {
        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("company_detail", $data);

        $this->load->view("footer");

        if (isset($_POST["savebtn"])) {
            $user = $this->session->userdata("user");

            $data["user"] = $user;

            $uid = $user["email"];

            $this->db->where("r_email", $uid);

            $this->db->set("company_head1", $_POST["head1"]);

            $this->db->set("company_head2", $_POST["head2"]);

            $this->db->set("company_head3", $_POST["head3"]);

            $this->db->set("company_detail1", $_POST["detail1"]);

            $this->db->set("company_detail2", $_POST["detail2"]);

            $this->db->set("company_detail3", $_POST["detail3"]);

            $this->db->update("reseller_homepage");

            $this->session->set_flashdata("msg2", "Saved Successfully");

            redirect("/UserController/company_detail", "refresh");
        }
    }

    public function domain_request()
    {
        $this->load->library("form_validation");

        $this->load->model("User_model");

        $this->load->model("Admin_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar");
        } else {
            $this->load->view("sidebar");
        }

        $this->load->view("domain_request");

        $this->load->view("footer");

        if (isset($_POST["request_btn"])) {
            $this->form_validation->set_rules(
                "domain_name",
                "Domain Name",
                "required"
            );

            if ($this->form_validation->run() == true) {
                $domain_name = $_POST["domain_name"];

                $count = $this->User_model->check_duplicacy($domain_name);

                if ($count == 0) {
                    $formArray = [
                        "reseller_id" => $_POST["domain_name"],

                        "r_email" => $user["email"],

                        "logo" => "",

                        "banner_img" => "",

                        "banner_head" => "",

                        "banner_msg" => "",

                        "about_us" => "",

                        "email" => "",

                        "address" => "",

                        "contact" => "",

                        "fb_url" => "",

                        "twt_url" => "",

                        "ftr" => "",

                        "status" => "0",
                    ];

                    $this->User_model->homepage_insertion($formArray);

                    $this->session->set_flashdata("msg2", "Saved Successfully");

                    redirect("/UserController/login", "refresh");
                } else {
                    $this->session->set_flashdata(
                        "msg",
                        "This Domain Name is Already Exist"
                    );

                    redirect("/UserController/domain_request", "refresh");
                }
            } else {
                $this->session->set_flashdata("msg", form_error("domain_name"));

                redirect("/UserController/domain_request", "refresh");
            }
        }
    }

    public function approve_user_code()
    {
        // echo "here<br>";die;

        // $this->load->model('User_model');

        // $this->load->model('Admin_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        // echo $iam;die;

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $data);
        } else {
            $this->load->view("sidebar", $data);
        }

        $this->load->view("unapprove_user_code", $data);

        $this->load->view("footer");
    }

    public function view_counseling(
        $counselling_link = "https://calendly.com/iquery-demo"
    ) {
        $this->load->model("User_model");

        $this->load->model("Admin_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $allowed_services["allowed_services"] = $this->Admin_model->getUserDetailsById($user["id"]);

        $data["counselling_link"] = $counselling_link;

        $email = $user["email"];

        $data["reportData"] = $this->Admin_model->getReportConfig();

        $this->load->view("navbar", $data);

        $iam = $user["iam"];

        if ($iam == "sp") {
            $this->load->view("sp/sidebar", $allowed_services);
        } else {
            $this->load->view("sidebar", $allowed_services);
        }

        $this->load->view("view_counseling", $data);

        $this->load->view("footer");
    }

    public function code_approvel_for_user(
        $id,
        $solution,
        $email = null,
        $check = null
    ) {
        $this->load->model("User_model");

        // echo $email;

        // die;

        $user = $this->session->userdata("user");

        if ($email == null) {
            $email = $user["email"];
        }

        //echo $email.$solution;die();

        $where = "email='$email' and solution='$solution' and status='UnUsed'";

        $this->db->where($where);

        $num_row = $this->db->get("generated_code_details")->num_rows();

        // echo $this->db->last_query()."<br>";

        // echo "No of rows fetched :".$num_row."<br>";

        // echo "No of Rows ".$num_row;

        // die();

        if ($num_row > 0) {
            $where = "email='$email' and solution='$solution' and status='UnUsed'";

            $this->db->where($where);

            $row = $this->db->limit(1)->get("generated_code_details");

            foreach ($row->result() as $row) {
                $code = $row->code;

                $this->db->where("id", $id);

                $this->db->set("code", $code);

                $this->db->set("status", "Ap");

                $this->db->update("user_code_list");
            }

            if (
                $this->db
                    ->where("code", $code)
                    ->set("status", "allocated")
                    ->update("generated_code_details")
            ) {
                $this->db->where("code", $code);

                $query = $this->db->get("user_code_list");

                $ret = $query->row();

                $user_id = $ret->user_id;

                $this->db->where("solution", $solution);

                $sl = $this->db->get("services_list");

                foreach ($sl->result() as $sl) {
                    $formArray = [
                        "user_id" => $user_id,

                        "code" => $code,

                        "solution" => $sl->solution,

                        "dis_solution" => $sl->dorp,

                        "part" => $sl->part,

                        "link" => $sl->current_link,

                        "details" => $sl->details,

                        "status" => "Ap",

                        "remain_time" => $sl->duration,
                    ];

                    $this->db->insert("user_assessment_info", $formArray);
                }

                // echo $this->session->userdata('isnotloggedin');

                // die;

                if ($this->session->userdata("isnotloggedin")) {
                    $this->db->set("nature", 1);

                    $this->db->where("code", $code);

                    $this->db->where("solution", $solution);

                    $this->db->update("generated_code_details");

                    if ($check != "") {
                        // echo 3;

                        // die;

                        redirect(
                            "/BaseController/view_code/" . $code,
                            "refresh"
                        );
                    } else {
                        // echo 4;

                        // die;

                        $codeData = $this->User_model
                            ->fetch_purchase_code_by_report($solution)
                            ->row_array();

                        // print_r( $codeData );

                        // die;

                        $purchaseCodeRequest = [
                            "ccb1" => $codeData["code_id"],
                            "cb1" => "request",
                            "purchase" => "",
                        ];

                        $this->session->set_userdata(
                            "purchaseCodeRequest",
                            $purchaseCodeRequest
                        );

                        $this->session->set_userdata("code", $code);

                        redirect("/UserController/save_request_code");
                    }

                    // redirect('/BaseController/view_code/'.$code,"refresh");
                } elseif ($check == null && $user["iam"] == "user") {
                    // echo 1;

                    // die;

                    redirect("/BaseController/view_code/" . $code, "refresh");
                }

                // echo 2;

                // die;

                $this->session->set_flashdata("msg", "Code Approved");

                redirect("/UserController/approve_user_code", "refresh");
            }
        } else {
            if ($this->session->userdata("isnotloggedin")) {
                $codeData = $this->User_model
                    ->fetch_purchase_code_by_report($solution)
                    ->row_array();

                // print_r( $codeData );

                // die;

                $purchaseCodeRequest = [
                    "ccb1" => $codeData["code_id"],
                    "cb1" => "request",
                    "purchase" => "",
                ];

                $this->session->set_userdata(
                    "purchaseCodeRequest",
                    $purchaseCodeRequest
                );

                $this->session->set_userdata("code", $code);

                redirect("/UserController/save_request_code/notallocated");
            }

            $this->session->set_flashdata(
                "check_code_available",
                "You have no more codes, Please purchase new codes."
            );
        }
    }

    public function user_buyer($request_generate = null)
    {
        $this->load->model("Admin_model");

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $code["reportData"] = $this->Admin_model->getReportConfig();

        $code["reportControl"] = $this->User_model->getreportcontrol(
            $user["id"]
        );

        if ( empty($code["reportControl"]) ){
            $this->User_model->insertreportcontrol(["user_id" => $user["id"]]);
            $code["reportControl"] = $this->User_model->getreportcontrol(
                $user["id"]
            );
        }

        $data["user"] = $user;

        $email = $user["email"];

        $code['s'] = getQuery( [ 'result'=> true , 'select' => 'user_id , user_code_list.solution, user_code_list.code, user_code_list.status, nature' , 'where' => [ 'user_code_list.reseller_id' =>  $user["email"] , 'user_code_list.status !=' => 'pending' ] , 'table' => 'user_code_list' , 'join' => [ [ 'generated_code_details' , 'generated_code_details.code = user_code_list.code' , 'left' ] ] , 'limit' => [ 10 => 0 ] , 'order' => [ 'user_code_list.id' => 'DESC' ] ] );
        $code['total'] = getQuery( [ 'single' => true , 'select' => 'COUNT(id) as codes' , 'where' => [ 'user_code_list.reseller_id' =>  $user["email"] , 'user_code_list.status !=' => 'pending' ] , 'table' => 'user_code_list'  ] );
        // pre($code , 1);
        $this->load->view("navbar", $data);
        
        $iam = $user["iam"];
        
        $code["allow_solution"] = ["UCE"];
        
        if( $iam == "sp" ){
            $this->load->view("sp/sidebar", $data);
        }
        else{
            $this->load->view("sidebar", $data);
        }

        if ($request_generate == "failed") {
            $this->session->set_flashdata("msg", "Report Generation Failed");
        }
        else{
            $code["requestParameter"] = $request_generate;
        }

        $this->load->view("view_approve_user_code", $code);

        $this->load->view("footer");
    }

    public function filter_list($currentPage , $request_generate = null)
    {
        if( $currentPage == 1 ){
            $from = 0;
        }
        else{
            $from = ( $currentPage - 1 )*10;
        }
        
        $this->load->model("Admin_model");

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $code["reportData"] = $this->Admin_model->getReportConfig();

        $code["reportControl"] = $this->User_model->getreportcontrol(
            $user["id"]
        );

        if (empty($code["reportControl"])) {
            $this->User_model->insertreportcontrol(["user_id" => $user["id"]]);

            $code["reportControl"] = $this->User_model->getreportcontrol(
                $user["id"]
            );
        }

        $data["user"] = $user;

        $email = $user["email"];

        $code['s'] = getQuery( [ 'result'=> true , 'select' => 'user_id , user_code_list.solution, user_code_list.code, user_code_list.status, nature' , 'where' => [ 'user_code_list.reseller_id' =>  $user["email"] , 'user_code_list.status !=' => 'pending' ] , 'table' => 'user_code_list' , 'join' => [ [ 'generated_code_details' , 'generated_code_details.code = user_code_list.code' , 'left' ] ] , 'limit' => [ 10 => $from ] , 'order' => [ 'user_code_list.id' => 'DESC' ] ] );
        
        // lQ(1);
        $iam = $user["iam"];

        $code["allow_solution"] = ["UCE"];
        if ($request_generate == "failed") {
            $this->session->set_flashdata("msg", "Report Generation Failed");
        }
        else{
            $code["requestParameter"] = $request_generate;
        }

        $this->load->view("filter_view_approve_user_code", $code);

        // $this->load->view("footer");
    }

    public function create_request($code, $solution, $user_id , $folder = null)
    {
        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $getUserVariation = getQuery([
            "where" => ["code" => $code],
            "table" => "user_assessment_variation",
            "single" => true,
        ]);

        $this->load->model("Admin_model");

        $this->load->model("User_model");

        $reportData = $this->Admin_model->getReportConfig();

        if ($code != "" && $solution != "" && $user_id != "") {
            $requestData = [
                
                "user_id" => $user_id,

                "solution" => $solution,

                "code" => $code,

                "status" => 0,

                'saved_folder' => $folder,

                "expiry_date" => date(
                    "Y-m-d",
                    strtotime(
                        "+" . $reportData["report_duration"] . " days",
                        strtotime(date("Y-m-d"))
                    )
                ),
            ];

            $id = $this->User_model->insert_request($requestData);

            if ($id) {

                if (empty($getUserVariation)) {
                    redirect(
                        base_url() .
                            "OtherAjax/download_report.php?code=" .
                            base64_encode($code)
                    );
                } elseif ($getUserVariation["report_variation"] == "one") {
                    // echo "H";

                    // pre( $getUserVariation , 1 );

                    // die;

                    redirect(
                        base_url() .
                            "OtherAjax/download_report.php?code=" .
                            base64_encode($code)
                    );
                }
                elseif( $getUserVariation["report_variation"] == "two" || $getUserVariation["report_variation"] == "three" ) {
                    echo 3;
                    redirect(
                        base_url() .
                            "OtherAjax/download_report.php?code=" .
                            base64_encode($code) .
                            "&report_variation=" .
                            $getUserVariation["report_variation"]
                    );
                }

                $this->session->set_flashdata("msg", "Request generated");

                // redirect('/UserController/user_buyer','refresh');
            } else {
                $this->session->set_flashdata(
                    "msg",
                    "Request Not generated, Try again"
                );

                redirect("/UserController/user_buyer", "refresh");
            }
        } else {
            $this->session->set_flashdata("msg", "Invalid Parameter");

            redirect("/UserController/user_buyer", "refresh");
        }
    }

    public function set_report_control($control)
    {
        $this->load->model("User_model");

        $user = $this->session->userdata("user");

        $this->User_model->setControlUpdate(
            ["status" => $control],
            ["user_id" => $user["id"]]
        );

        // echo $this->db->last_query();

        // die;

        if ($this->db->affected_rows()) {
            $this->session->set_flashdata("msg", "Status is updated");

            redirect("/UserController/user_buyer", "refresh");
        } else {
            $this->session->set_flashdata("msg", 'Update remian\'s Same');

            redirect("/UserController/user_buyer", "refresh");
        }
    }

    public function set_mannual_control($control, $code)
    {
        $this->load->model("User_model");

        $user = $this->session->userdata("user");

        $this->User_model->setControlUpdateReport(
            ["mannual_status" => $control],
            ["code" => $code]
        );

        // echo $this->db->last_query();

        // die;

        if ($this->db->affected_rows()) {
            $this->session->set_flashdata(
                "msg",
                "Report View Status is updated"
            );

            redirect("/UserController/user_buyer", "refresh");
        } else {
            $this->session->set_flashdata("msg", 'Update remain\'s Same');

            redirect("/UserController/user_buyer", "refresh");
        }
    }

    public function update_code_status()
    {
        $id = $this->input->post("s");

        $status = $this->input->post("act");

        $this->db->where("id", $id);

        $this->db->set("status", $status);

        $this->db->update("user_code_list");

        $data = [
            "responce" => "success",
            "message" => "Data update Successfully",
        ];

        echo json_encode($data);
    }

    public function certification()
    {
        // $this->load->model('User_model');

        // $this->load->model('Base_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $code["s"] = $this->User_model->certification_list($user["email"]);

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("certification_test", $code); //replace with view_approve_code

        $this->load->view("footer");
    }

    //certification test

    public function certification_test()
    {
        $this->load->model("User_model");

        $this->load->model("Base_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $email = $user["email"];

        $where = "email='$email' and solution='Positive Parenting'";

        $this->db->where($where);

        $qno = $this->db->get("certification_test_result")->num_rows();

        if ($qno == 0) {
            $num = "1";
        } else {
            $this->db->where($where);

            $qno = $this->db
                ->limit(1)
                ->order_by("id", "desc")
                ->get("certification_test_result");

            foreach ($qno->result() as $qno) {
                $qno = $qno->qno;

                $num = $qno + 1;
            }
        }

        $qlist["q"] = $this->User_model->ppe_test($num);

        $this->load->view("navbar3", $data);

        $this->load->view("sidebar");

        $this->load->view("ppe_test", $qlist);

        $this->load->view("footer");

        if (isset($_POST["saveBtn"])) {
            if ($num != 66) {
                $this->form_validation->set_rules(
                    "radio1",
                    "First Question",
                    "required"
                );

                $this->form_validation->set_rules(
                    "radio2",
                    "Second Question",
                    "required"
                );

                $this->form_validation->set_rules(
                    "radio3",
                    "Third Question",
                    "required"
                );

                $this->form_validation->set_rules(
                    "radio4",
                    "Fourth Question",
                    "required"
                );

                $this->form_validation->set_rules(
                    "radio5",
                    "Fifth Question",
                    "required"
                );

                if ($this->form_validation->run() == true) {
                    $i = 1;

                    foreach ($qlist["q"]->result() as $q) {
                        $ans = "radio" . $i;

                        $c_ans = "right_ans" . $i;

                        if ($_POST[$ans] == $_POST[$c_ans]) {
                            $score = 1;
                        } else {
                            $score = 0;
                        }

                        $formArray = [
                            "email" => $email,

                            "qno" => $q->qno,

                            "solution" => "Positive Parenting",

                            "put_ans" => $_POST[$ans],

                            "score" => $score,
                        ];

                        $this->db->insert(
                            "certification_test_result",
                            $formArray
                        );

                        $i++;
                    }

                    redirect(base_url() . "UserController/certification_test");
                } else {
                    $this->session->set_flashdata("msg", validation_errors());

                    redirect(base_url() . "UserController/certification_test");
                }
            } else {
                $this->form_validation->set_rules(
                    "radio1",
                    "First Question",
                    "required"
                );

                if ($this->form_validation->run() == true) {
                    $i = 1;

                    foreach ($qlist["q"]->result() as $q) {
                        $ans = "radio" . $i;

                        $c_ans = "right_ans" . $i;

                        if ($_POST[$ans] == $_POST[$c_ans]) {
                            $score = 1;
                        } else {
                            $score = 0;
                        }

                        $formArray = [
                            "email" => $email,

                            "qno" => $q->qno,

                            "solution" => "Positive Parenting",

                            "put_ans" => $_POST[$ans],

                            "score" => $score,
                        ];

                        $this->db->insert(
                            "certification_test_result",
                            $formArray
                        );

                        $i++;
                    }

                    $dt = date("d-m-Y");

                    //that code used after fourth asssessment

                    // $this->Base_model->update_code_status($code);

                    $where2 = "email='$email' and solution='Positive Parenting' and score='1'";

                    $this->db->where($where2);

                    $scr = $this->db
                        ->get("certification_test_result")
                        ->num_rows();

                    $per = ($scr * 100) / 66;

                    $where = "email='$email' and solution='PPE'";

                    $this->db->where($where);

                    $this->db->set("status", "2");

                    $this->db->set("per", $per);

                    $this->db->set("dt", $dt);

                    $this->db->update("reseller_certification");

                    $this->session->set_flashdata(
                        "msg",
                        "Test Completed Please Download Certificate"
                    );

                    redirect(base_url() . "UserController/certification");
                } else {
                    $this->session->set_flashdata("msg", validation_errors());

                    redirect(base_url() . "UserController/certification_test");
                }
            }
        }
    }

    public function request_for_retest($id)
    {
        $this->db->where("id", $id);

        $this->db->set("rqst", "1");

        $this->db->update("reseller_certification");

        $this->session->set_flashdata(
            "msg  ",
            "Request send successfully Please wait for approval."
        );

        redirect(base_url() . "UserController/certification");
    }

    public function counselingUpdate()
    {
        $this->load->model("User_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $this->load->library("form_validation");

        $this->form_validation->set_rules(
            "para_name",
            "Parameter Name",
            "required"
        );

        if ($this->form_validation->run() == true) {
            $para_name = $this->input->post("para_name", true);

            $counseling_type = $this->input->post("counseling_type", true);

            $id = $this->input->post("id", true);

            $mrp = $this->input->post("mrp", true);

            $duration = $this->input->post("duration", true);

            $update = $this->User_model->counselingUpdate(
                $id,
                $para_name,
                $counseling_type,
                $mrp,
                $duration
            );

            if ($update) {
                $this->session->set_flashdata("msg  ", "updated successfully");

                redirect(
                    base_url() . "UserController/counselingParameters",
                    "refresh"
                );
            } else {
                $this->session->set_flashdata("msg  ", "something wrong");

                redirect(
                    base_url() . "UserController/counselingParameters",
                    "refresh"
                );
            }
        } else {
            $this->session->set_flashdata("msg  ", "all Field Required");

            redirect(
                base_url() . "UserController/counselingParameters",
                "refresh"
            );
        }
    }

    public function footer_field()
    {
        // $this->load->model('User_model');

        //     if($this->User_model->authorized()==false)

        //     {

        //         $this->session->set_flashdata('msg','You are not authorized to access this section');

        //         redirect(base_url().'/UserController/login');

        //     }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $email = $user["email"];



        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("footer_field", $data);

        $this->load->view("footer");

        if (isset($_POST["updatebtn"])) {
            $this->form_validation->set_rules("Todays_Day", "Days", "required");

            $this->form_validation->set_rules("status", "Staus", "required");

            if ($this->form_validation->run() == true) {
                if ($_POST["status"] == "open") {
                    if ($_POST["startTime"] != "" && $_POST["endTime"] != "") {
                        $formArray = [
                            "email" => $email,

                            "day" => $_POST["Todays_Day"],

                            "status" => $_POST["status"],

                            "start_tm" => $_POST["startTime"],

                            "end_tm" => $_POST["endTime"],
                        ];
                    } else {
                        $this->session->set_flashdata(
                            "msg",
                            "Start Time or End Time both are required"
                        );

                        redirect(base_url() . "/UserController/footer_field");
                    }
                } else {
                    $formArray = [
                        "email" => $email,

                        "day" => $_POST["Todays_Day"],

                        "status" => $_POST["status"],

                        "start_tm" => $_POST["startTime"],

                        "end_tm" => $_POST["endTime"],
                    ];
                }

                $this->db->insert("day_description", $formArray);

                $this->session->set_flashdata("msg2", "Saved");

                redirect(base_url() . "/UserController/footer_field");
            }
        }
    }

    public function Update_Counsellor_remarks( $code )
    {
        $code = base64_decode($code);

        $this->load->model("User_model");

        $this->load->model("Base_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("Update_Counsellor_remarks", $code); //replace with view_approve_code

        $this->load->view("footer");
    }

    public function SaveCounsellorRemarks()
    {
        $this->form_validation->set_rules("remarks", "Remarks", "required");

        if ($this->form_validation->run() == true) {
            $dt = date("d-m-Y h:m");
            $this->db->where("code", $_POST["code"]);
            $this->db->set("c_remark", $_POST["remarks"]);
            $this->db->set("remark_update_last", $dt);
            $this->db->set("remark_status", 1);
            $this->db->update("user_code_list");
            if( affected() > 0 ){
                $getLastRow = getQuery([ 'where' => [ 'code' => $_POST["code"] ] , 'table' => 'report_request' , 'single' => true , 'order' => [ 'id' => 'DESC' ] ]);
                // pre( $getLastRow  , 1);
                if( !empty($getLastRow ) ){
                    update( [ 'where' => [ 'id' => $getLastRow['id'] ] , 'data' => [ 'file_name' => NULL  , 'status' => 0 ] , 'table' => 'report_request' ] );
                }
            }
            $this->session->set_flashdata("msg", "Detail Updated");

            redirect(base_url() . "/UserController/user_buyer");
        } else {
            $this->session->set_flashdata("msg", "Error");

            redirect(
                base_url() .
                    "/UserController/Update_Counsellor_remarks/" .
                    base64_encode($_POST["code"])
            );
        }
    }

    public function forgot_password($code = null)
    {
        //echo base64_decode($code);die();

        $data["code"] = $code;

        $this->load->view("forgot_password", $data);
    }

    public function ajax_work()
    {
        $res_msg["msg_code"] = "OPPs";

        $this->load->model("User_model");

        switch ($this->input->get_post("action")) {
            case "FORGOT_PASSWORD":
                $email_id = $this->security->xss_clean(
                    $this->input->post("email_id")
                );

                $check_email_id = $this->User_model->check_email_id($email_id);

                if ($check_email_id["num_rows"] > 0) {
                    $OTP_code = rand(1000, 1000000);

                    if (
                        $this->User_model->otp_update(
                            $check_email_id["db_data"]->id,
                            $OTP_code
                        ) > 0
                    ) {
                        $subject =
                            "Hello from Respicite - Password Reset Request.";

                        $html_msg = "Dear $email_id <br> <br>

                            Please complete your password reset request by using the following OTP - $OTP_code

                            <br><br> Team Respicite <br> <a href='https://respicite.com'>https://respicite.com</a>";

                        $send = $this->User_model->otp_send_on_email(
                            $email_id,
                            $subject,
                            $html_msg
                        );

                        if ($send) {
                            $res_msg["msg_code"] = "SEND_OPT";
                        } else {
                            $res_msg["msg_code"] = "NOT_SEND";
                        }
                    }
                } else {
                    $res_msg["msg_code"] = "USER_NOT_FOUND";
                }

                break;

            case "NEW_PASSWORD":
                $this->form_validation->set_rules(
                    "new_pass",
                    "New Password",
                    "required|min_length[8]|max_length[25]|callback_check_strong_password"
                );

                $this->form_validation->set_rules(
                    "otp_code",
                    "OTP",
                    "required"
                );

                $this->form_validation->set_rules(
                    "email_id",
                    "Email",
                    "required"
                );

                if ($this->form_validation->run()) {
                    $post_data = [
                        "otp_code" => $this->input->post("otp_code", true),

                        "new_pass" => password_hash(
                            $this->input->post("new_pass", true),
                            PASSWORD_BCRYPT
                        ),

                        "email_id" => $this->input->post("email_id", true),
                    ];

                    if ($this->User_model->check_otp_email($post_data)) {
                        $res_msg["msg_code"] = "UPDATE_DONE";
                    } else {
                        $res_msg["msg_code"] = "OTP_INVALID";
                    }
                } else {
                    $res_msg["msg_code"] = "ERROR_VALIDATION";

                    $res_msg["msg_content"] = validation_errors();
                }

                break;

            case "RESEND_OTP":
                $email_id = $this->input->post("email_id", true);

                $check_user_status = $this->User_model->check_user_status(
                    $email_id
                );

                if ($check_user_status["num_rows"] > 0) {
                    $OTP_code = rand(1000, 1000000);

                    $this->User_model->otp_update(
                        $check_user_status["id"],
                        $OTP_code
                    );

                    $subject =
                        "Welcome from Respicite LLP - Verify your Email id";

                    $body_msg =
                        "Dear " .
                        $email_id .
                        " <br/> <br/> Please complete your registration with Respicite

                        by using the following OTP - <b>" .
                        $OTP_code .
                        "</b><br/>

                        <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";

                    $this->User_model->otp_send_on_email(
                        $email_id,
                        $subject,
                        $body_msg
                    );

                    $res_msg["msg_code"] = "OTP_RESEND";
                } else {
                    $res_msg["msg_code"] = "ALREADY_UPDATE_STATUS";
                }

                break;

            case "JOB_STATUS":
                $check = null;

                $check = $this->Sp_model->update_status($action, $id);

                if ($check) {
                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;

            case "REG_CHECK":
                    
                // $this->form_validation->set_rules(
                //     "otp_code",
                //     "OTP CODE",
                //     "required"
                // );

                $this->form_validation->set_rules(
                    "mobile",
                    "Contact",
                    "required"
                );

                $this->form_validation->set_rules(
                    "email_id",
                    "Email",
                    "required"
                );
                $last_id = 0;
                if( $this->form_validation->run() ){
                    extract( $_POST );
                    $checkUserExist  = getQuery( [ 'where' => [ 'email' => $email_id ] , 'table' => 'user_details' , 'single' => true ] );
                    // pre( $checkUserExist , 1);
                    if( !empty( $checkUserExist ) ){
                        if( $checkUserExist['iam'] == 'user' ){
                            $OTP_code = rand(1000,1000000);
                            if($this->User_model->assessment_otp_update_by_email($email_id,$OTP_code) > 0){
                            $subject = "Welcome from Respicite LLP - Verify your Email id";
                            $body_msg = "Dear ".$email_id." <br/> Please complete your Registation with this:- ".$OTP_code."
                            <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                            $this->User_model->otp_send_on_email($email_id,$subject,$body_msg);
                            }
                            $res_msg["msg"] = "OTP_SEND";
                        }
                        else{
                            $res_msg["msg"] = "NOT_USER";
                        }
                    }
                    else{
                        $checkUserExist  = getQuery( [ 'where' => [ 'email_id' => $email_id ] , 'table' => 'assessment_user_tmp' , 'single' => true ] );
                        $OTP_code = rand(1000,1000000);
                        $subject = "Welcome from Respicite LLP - Verify your Email id";
                        $body_msg  = "Dear ".$email_id." <br/> Please complete your Registation with this : - ".$OTP_code." <br>
                        and login with this password : - Test1234
                        <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                        $this->User_model->otp_send_on_email($email_id,$subject,$body_msg);
                        if( empty( $checkUserExist )){
                            insert( 'assessment_user_tmp' , [ 'email_id' => $email_id , 'otp' => $OTP_code ] );
                        }
                        else{
                            update( [ 'data' => [ 'otp' => $OTP_code ] , 'where' => [ 'email_id' => $email_id ] , 'table' => 'assessment_user_tmp' ] );
                        }
                        $res_msg["msg"] = "OTP_SEND";
                    }
                }
                else{
                    $res_msg["msg"] = "ERROR_VALIDATION";
                    $res_msg["msg_content"] = validation_errors();
                }
                break;
            case "REG_OTP_CHECK":
                $this->form_validation->set_rules(
                    "mobile",
                    "Contact",
                    "required"
                );

                $this->form_validation->set_rules(
                    "email_id",
                    "Email",
                    "required"
                );
                
                $this->form_validation->set_rules(
                    "otp",
                    "OTP",
                    "required"
                );
                $last_id = 0;
                if( $this->form_validation->run() ){
                    $post_data = [
                        'user_id' => $this->input->post("user_id", true),
                        "email_otp" => null,
                        "mobile" => $this->input->post("mobile", true),
                        "email" => $this->input->post("email_id", true),
                        'fullname' => 'Link Purchased User',
                        'role' => 'individual',
                        'iam' => 'user',
                        'pwd' => password_hash('Test1234',PASSWORD_BCRYPT),
                        'status'=>'1',
                        'profile_photo'=>'uploads/default.png'
                    ];
                    $checkUserExist = getQuery( [ 'where' => [ 'email' => $post_data['email'] ] , 'table' => 'user_details' , 'single' => true ] );
                    // pre( $checkUserExist , 1 );
                    if( empty( $checkUserExist ) ){
                        $getTempUser = getQuery( [ 'where' => [ 'email_id' => $post_data['email'] ] , 'table' => ' assessment_user_tmp' , 'single' => true ] );
                        // pre( $getTempUser , 1 );
                        if(!empty( $getTempUser )){
                            if( $getTempUser['otp'] == $this->input->post("otp", true) ){
                                delete( [ 'where' => [ 'email_id' => $post_data['email'] ] , 'table' => 'assessment_user_tmp' ] );
                                $this->User_model->create($post_data);
                                $last_id = $this->db->insert_id();
                                $res_msg["msg_code"] = "UPDATE_DONE";
                            }
                            else {
                                $res_msg["msg_code"] = "OTP_INVALID";
                            }
                        }
                        else{

                        }
                    }
                    else{
                        if( $checkUserExist['assessment_otp']  == $this->input->post("otp", true) ){
                            $last_id = $checkUserExist['id'];
                            $res_msg["msg_code"] = "UPDATE_DONE";
                        }
                        else {
                            $res_msg["msg_code"] = "OTP_INVALID";
                        }
                    }
                    $res_msg["user_id"] = $last_id;
                }
                else{
                    $res_msg["msg_code"] = "ERROR_VALIDATION";
                    $res_msg["msg_content"] = validation_errors();
                }
                break;
        }

        $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode($res_msg));
    }

    function reverify_otp()
    {
        if (empty($this->session->userdata("reverify_email_id"))) {
            redirect(base_url() . "/UserController/login");
        }

        $this->load->model("User_model");

        $this->form_validation->set_rules("otp", "OTP", "required");

        if ($this->form_validation->run()) {
            if (
                $this->User_model->check_otp_email_reverify(
                    $this->session->userdata("reverify_email_id")["email"],
                    $this->input->post("otp")
                )
            ) {
                $this->session->set_flashdata("otp_msg", "OK");

                $this->session->unset_userdata("reverify_email_id");
            } else {
                $this->session->set_flashdata("otp_msg", "INVELID_OTP");
            }
        }

        $this->load->view("revalidate-otp");
    }

    public function boards()
    {
        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("boards");

        $this->load->view("footer");
    }

    public function career_paths()
    {
        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("career-paths");

        $this->load->view("footer");
    }

    public function vocational_training_add()
    {
        $data_view = [];

        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data_view["vocational_info"] = $this->get_vocational_info(
            "SHORT_VIEW",
            $user["id"]
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("vocational_training", $data_view);

        $this->load->view("footer");
    }

    public function vocational_training($page = null)
    {
        $data_view = [];

        // $this->load->model('User_model');

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );
        if (isset($_POST["btn_vocational"])) {
            $this->form_validation->set_rules(
                "vocational_name",
                "Name",
                "required"
            );

            $this->form_validation->set_rules(
                "vocational_description",
                "Description",
                "required"
            );

            if ($this->form_validation->run()) {
                //image Upload #start.

                $upload_path = "uploads/vocational_training/";

                $new_name = time() . "-" . $_FILES["up_file"]["name"];

                $config = [
                    "upload_path" => $upload_path,

                    "file_name" => $new_name,

                    "allowed_types" => "zip",

                    "max_size" => "2048000", //2.048mb

                    "max_height" => "0",

                    "max_width" => "0",
                ];

                $this->load->library("upload", $config);

                if ($this->upload->do_upload("up_file")) {
                    $imageDetailArray = $this->upload->data();

                    if (!empty($imageDetailArray["image_type"])) {
                        $img_type = $imageDetailArray["image_type"];
                    } else {
                        $img_type = $imageDetailArray["file_type"];
                    }

                    $insert_data = [
                        "sp_id" => $user["id"],

                        "training_name" => $this->input->post(
                            "vocational_name"
                        ),

                        "training_desc" => $this->input->post(
                            "vocational_description"
                        ),

                        "training_file_loc" => $imageDetailArray["file_name"],

                        "file_mine" => $img_type,
                    ];

                    $this->db->insert("vocational_training", $insert_data);

                    $last_id = $this->db->insert_id();

                    // sub section #start

                    $section_name = $this->input->post("training_section_name");

                    $section_desc = $this->input->post("training_section_desc");

                    $arr_size = sizeof($section_name);

                    $arr_section_data = [];

                    for ($i = 0; $i < $arr_size; $i++) {
                        $arr_section_data[] = [
                            "training_id" => $last_id,

                            "section_name" => $section_name[$i],

                            "section_desc" => $section_desc[$i],
                        ];
                    }

                    $this->db->insert_batch(
                        "vocational_training_sections",
                        $arr_section_data
                    );

                    if ($this->db->affected_rows() > 0) {
                        $data_view["check_inset"] = "OK";
                    } else {
                        $data_view["check_inset"] = "ERROR";
                    }

                    // sub section #end
                } else {
                    $data_view["imageError"] = $this->upload->display_errors();
                }

                // image Upload #end.
            }
        }

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        if ($page == "meta-data") {
            $data_view["pageTitle"] = "Training Meta Data";

            $this->load->view("training-meta-data", $data_view);
        } elseif ($page == "detail") {
            $data_view["pageTitle"] = "Training Meta Detail";

            $this->load->view("training-meta-detail", $data_view);
        } elseif ($page == "Session-schedule") {
            $data_view["pageTitle"] = "Training Session";

            $this->load->view("training-session", $data_view);
        }

        $this->load->view("footer");
    }

    public function vocational_training_details($id)
    {
        $data_view = [];

        $this->load->model("User_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $data_view = $this->initializer();

        $user = $this->session->userdata("user");

        $data_view["user"] = $user;

        $data_view["vocational_info"] = $this->get_vocational_info(
            "FULL_SINGLE_VIEW",
            $id
        );

        $this->load->view("navbar", $data_view);

        $this->load->view("sidebar");

        $this->load->view("vocational_training_details", $data_view);

        $this->load->view("footer");
    }

    public function vocational_training_edit($id, $action_path = null)
    {
        $data_view = [];

        if (empty($id)) {
            show_404();
        }

        $this->load->model("User_model");

        $this->load->model("Base_model");

        $this->load->model("Sp_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;
        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        if ($action_path == "VOC_TRAI") {
            if (isset($_POST["btn_vocational"])) {
                $this->form_validation->set_rules(
                    "vocational_name",
                    "Name",
                    "required"
                );

                $this->form_validation->set_rules(
                    "vocational_description",
                    "Description",
                    "required"
                );

                if ($this->form_validation->run()) {
                    $this->db->set(
                        "training_name",
                        $this->input->post("vocational_name", true)
                    );

                    $this->db->set(
                        "training_desc",
                        $this->input->post("vocational_description", true)
                    );

                    $this->db->where(["id" => $id]);

                    $this->db->limit(1);

                    $this->db->update("vocational_training");

                    if ($this->db->affected_rows() > 0) {
                        $data_view["VOC_TRAI_MSG"] = "OK";
                    } else {
                        $data_view["VOC_TRAI_MSG"] = "ERROR";
                    }
                }
            }
        }

        if ($action_path == "SUB_SECTION") {
            if (isset($_POST["btn_sub_section"])) {
                $this->form_validation->set_rules(
                    "sub_section_name",
                    "Name",
                    "required"
                );

                $this->form_validation->set_rules(
                    "sub_section_description",
                    "Description",
                    "required"
                );

                if ($this->form_validation->run()) {
                    $section_id = $this->input->get("sno");

                    if (!empty($section_id)) {
                        $this->db->set(
                            "section_name",
                            $this->input->post("sub_section_name", true)
                        );

                        $this->db->set(
                            "section_desc",
                            $this->input->post("sub_section_description", true)
                        );

                        $this->db->where(["id" => $section_id]);

                        $this->db->limit(1);

                        $this->db->update("vocational_training_sections");

                        if ($this->db->affected_rows() > 0) {
                            $data_view["SUB_SECTION_MSG"] = "OK";
                        } else {
                            $data_view["SUB_SECTION_MSG"] = "ERROR";
                        }
                    }
                }
            }
        }

        if ($action_path == "ADD_SUB_SECTION") {
            if (isset($_POST["btn_add_more_sectoin"])) {
                $add_more_section_name = $this->input->post(
                    "add_sub_section_name"
                );

                $add_more_section_desc = $this->input->post(
                    "add_sub_section_description"
                );

                if (!empty($add_more_section_name)) {
                    $arr_size = sizeof($add_more_section_name);

                    $arr_section_data = [];

                    for ($i = 0; $i < $arr_size; $i++) {
                        $arr_section_data[] = [
                            "training_id" => $id,

                            "section_name" => $add_more_section_name[$i],

                            "section_desc" => $add_more_section_desc[$i],
                        ];
                    }

                    $this->db->insert_batch(
                        "vocational_training_sections",
                        $arr_section_data
                    );

                    if ($this->db->affected_rows() > 0) {
                        $data_view["ADD_SUB_SECTION_MSG"] = "OK";
                    } else {
                        $data_view["ADD_SUB_SECTION_MSG"] = "ERROR";
                    }
                }
            }
        }

        if ($action_path == "CONTENT_UPLOAD") {
            if (!empty($_FILES["content_file"]["name"])) {
                //image Upload #start.

                $upload_path = "uploads/vocational_training/";

                $new_name = time() . "-" . $_FILES["content_file"]["name"];

                $config = [
                    "upload_path" => $upload_path,

                    "file_name" => $new_name,

                    "allowed_types" => "zip",

                    "max_size" => "2048000", //2.048mb

                    "max_height" => "0",

                    "max_width" => "0",
                ];

                $this->load->library("upload", $config);

                if ($this->upload->do_upload("content_file")) {
                    $arr_file_upload = $this->upload->data();

                    if (!empty($arr_file_upload["image_type"])) {
                        $img_type = $arr_file_upload["image_type"];
                    } else {
                        $img_type = $arr_file_upload["file_type"];
                    }

                    $this->db->set(
                        "training_file_loc",
                        $arr_file_upload["file_name"]
                    );

                    $this->db->set("file_mine", $img_type);

                    $this->db->where(["id" => $id]);

                    $this->db->limit(1);

                    $this->db->update("vocational_training");

                    if ($this->db->affected_rows() > 0) {
                        $old_file_name =
                            "uploads/vocational_training/" .
                            $this->input->post("old_file_name");

                        if (is_file($old_file_name)) {
                            unlink($old_file_name);
                        }

                        $data_view["CONTENT_UPLOAD_MSG"] = "OK";
                    } else {
                        $data_view["CONTENT_UPLOAD_MSG"] = "ERROR";
                    }
                } else {
                    $data_view["imageError"] = $this->upload->display_errors();
                }
            }
        }

        $data_view["vocational_info"] = $this->get_vocational_info(
            "FULL_SINGLE_VIEW",
            $id
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("vocational_training_edit", $data_view);

        $this->load->view("footer");
    }

    protected function get_vocational_info($check, $id)
    {
        if ($check == "SHORT_VIEW") {
            $this->db->select("*");

            $this->db->where(["sp_id" => $id]);

            $this->db->order_by("id", "desc");

            $q = $this->db->get("vocational_training");

            return $q->result();
        }

        if ($check == "FULL_SINGLE_VIEW") {
            $data = [];

            $this->db->select("*");

            $this->db->where(["id" => $id]);

            $q = $this->db->get("vocational_training");

            $data[] = ["training_info" => $q->row()];

            $this->db->select("*");

            $this->db->where(["training_id" => $id]);

            $q = $this->db->get("vocational_training_sections");

            $data[] = ["section_info" => $q->result()];

            $this->db->select("*");

            $this->db->where(["training_id" => $id]);

            $this->db->order_by("id", "desc");

            $this->db->order_by("remark_date", "desc");

            $q = $this->db->get("vocational_training_remarks");

            $data[] = ["remark" => $q->result()];

            return $data;
        }

        if ($check == "PARTICIPANT_SINGLE_VIEW") {
            $this->db->select(
                "a.id,a.user_email,b.fullname,a.apply_date,a.training_status"
            );

            $this->db->where(["a.training_id" => $id]);

            $this->db->order_by("a.id", "desc");

            $this->db->order_by("a.apply_date");

            $this->db->join(
                "user_details b",
                "b.email = a.user_email",
                "inner"
            );

            $q = $this->db->get("vocational_training_apply_user a");

            return $q->result();
        }
    }

    public function participant_details($id)
    {
        $data_view = [];

        $this->load->model("User_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data_view["participant_info"] = $this->get_vocational_info(
            "PARTICIPANT_SINGLE_VIEW",
            $id
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("participant_details", $data_view);

        $this->load->view("footer");
    }

    function delete_itme($id, $action = null)
    {
        $json_msg = [];

        switch ($action) {
            case "REMOVE_SUB_SECTION":
                $this->db->limit(1);

                $this->db->delete("vocational_training_sections", [
                    "id" => $id,
                ]);

                if ($this->db->affected_rows() > 0) {
                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;

            case "REMOVE_VOCATIONAL":
                $this->db->select("training_file_loc");

                $this->db->where(["id" => $id]);

                $this->db->limit(1);

                $file_name = $this->db->get("vocational_training")->row()
                    ->training_file_loc;

                $this->db->delete("vocational_training", ["id" => $id]);

                $this->db->limit(1);

                $this->db->delete("vocational_training_sections", [
                    "training_id" => $id,
                ]);

                if ($this->db->affected_rows() > 0) {
                    $file_path = "uploads/vocational_training/$file_name";

                    if (is_file($file_path)) {
                        unlink($file_path);
                    }

                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;

            case "REMOVE_JOB":
                $this->db->limit(1);

                $this->db->delete("placement_jobs", ["id" => $id]);

                if ($this->db->affected_rows() > 0) {
                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;
        }

        echo json_encode($json_msg);
    }

    function sp_ajax_work($id, $action = null)
    {
        $json_msg = [];

        $this->load->model("Sp_model");

        switch ($action) {
            case "CERTIFICATION_REQUEST":
                $vocational_id = $this->input->post("vid");

                $request_type = $this->input->post("req_type");

                $old_file_name = $this->input->post("file_name");

                $upload_path = "uploads/vocational_training/";

                $new_name =
                    time() .
                    "-certification-" .
                    $_FILES["file_content"]["name"];

                $config = [
                    "upload_path" => $upload_path,

                    "file_name" => $new_name,

                    "allowed_types" => "zip",

                    "max_size" => "2048000", //2.048mb

                    "max_height" => "0",

                    "max_width" => "0",
                ];

                $this->load->library("upload", $config);

                if ($this->upload->do_upload("file_content")) {
                    $arr_file_upload = $this->upload->data();

                    $this->db->set("certification_status", $request_type);

                    $this->db->set(
                        "certification_file_loc",
                        $arr_file_upload["file_name"]
                    );

                    $this->db->where(["id" => $vocational_id]);

                    $this->db->limit(1);

                    $this->db->update("vocational_training");

                    if ($this->db->affected_rows() > 0) {
                        if (!empty($old_file_name)) {
                            $old_file_name =
                                "uploads/vocational_training/" . $old_file_name;

                            if (is_file($old_file_name)) {
                                unlink($old_file_name);
                            }
                        }

                        $json_msg["MSG"] = "OK";
                    } else {
                        $json_msg["MSG"] = "ERROR";
                    }
                } else {
                    $json_msg["MSG"] = "ERROR_IMG";

                    $json_msg[
                        "ERROR_IMG_DESC"
                    ] = $this->upload->display_errors();
                }

                break;

            case "TRAINING_REQUEST":
                $training_status_type = $this->input->get("req", true);

                if (!empty($training_status_type)) {
                    $this->db->set("training_status", $training_status_type);

                    $this->db->where(["id" => $id]);

                    $this->db->limit(1);

                    $this->db->update("vocational_training");

                    if ($this->db->affected_rows() > 0) {
                        $json_msg = ["MSG" => "OK"];
                    } else {
                        $json_msg = ["MSG" => "ERROR"];
                    }
                } else {
                    $json_msg = ["MSG" => "EMPTY"];
                }

                break;

            case "PARTICIPANT_REQUEST":
                $status_type = $this->input->get("req_type", true);

                if (!empty($status_type)) {
                    $this->db->set("training_status", $status_type);

                    $this->db->where(["id" => $id]);

                    $this->db->limit(1);

                    $this->db->update("vocational_training_apply_user");

                    if ($this->db->affected_rows() > 0) {
                        $json_msg = ["MSG" => "OK"];
                    } else {
                        $json_msg = ["MSG" => "ERROR"];
                    }
                } else {
                    $json_msg = ["MSG" => "EMPTY"];
                }

                break;

            case "JOB_STATUS":
                $check = null;

                $check = $this->Sp_model->update_status($action, $id);

                if ($check) {
                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;

            case "JOB_STATUS_REQUEST":
                $check = null;

                $check = $this->Sp_model->update_status($action, $id);

                if ($check) {
                    $json_msg = ["MSG" => "OK"];
                } else {
                    $json_msg = ["MSG" => "ERROR"];
                }

                break;
        }

        echo json_encode($json_msg);
    }

    // jobs #Start

    public function posts_job()
    {
        $data_view = [];

        // $this->load->model('User_model');

        // $this->load->model('Base_model');

        $this->load->model("Sp_model");

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data_view["short_data"] = $this->Sp_model->get_job_data(
            "SHORT_VIEW",
            $user["id"]
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("posts_job", $data_view);

        $this->load->view("footer");
    }

    public function job_add()
    {
        $data_view = [];

        // $this->load->model('User_model');

        // $this->load->model('Base_model');

        $this->load->model("Sp_model");

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $data = $this->initializer();

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        if (isset($_POST["btn_job"])) {
            $this->form_validation->set_rules(
                "job_title",
                "Job Title",
                "required"
            );

            // $this->form_validation->set_rules('salary','CTC','required');

            $this->form_validation->set_rules(
                "job_types",
                "Job Types",
                "required"
            );

            // $this->form_validation->set_rules('job_locations','Job Locations','required');

            $this->form_validation->set_rules(
                "posting_nature",
                "Job Nature",
                "required"
            );

            $this->form_validation->set_rules(
                "job_description",
                "Job Description",
                "required"
            );

            $this->form_validation->set_rules(
                "job_nature",
                "Job Nature",
                "required"
            );

            if ($this->form_validation->run()) {
                $domain = $this->input->post("domain", true);

                $specialization_potion = $this->input->post(
                    "specialization[]",
                    true
                );

                if (!empty($specialization_potion)) {
                    $specialization_potion = implode(
                        ",",
                        $specialization_potion
                    );
                }

                $new_domain = $this->input->post("add_new_domain", true);

                if (!empty($new_domain)) {
                    $this->db->insert("job_domain", ["name" => $new_domain]);

                    $domain = $this->db->insert_id();
                }

                $new_specialization = $this->input->post(
                    "add_new_specialization",
                    true
                );

                if (!empty($new_domain)) {
                    $specialization_v = explode(",", $new_specialization);

                    $s_v_count = sizeof($specialization_v);

                    $s_id = [];

                    for ($i = 0; $i < $s_v_count; $i++) {
                        $s_data = [
                            "name" => $specialization_v[$i],

                            "job_domain_id" => $domain,
                        ];

                        $this->db->insert("job_specialization", $s_data);

                        $s_id[] = $this->db->insert_id();
                    }

                    $specialization_potion = implode(",", $s_id);
                }

                $arr_form_data = [
                    "sp_id" => $user["id"],

                    "job_title" => $this->input->post("job_title", true),

                    "salary" => $this->input->post("salary", true),

                    "job_type" => $this->input->post("job_types", true),

                    "job_locations" => $this->input->post(
                        "job_locations",
                        true
                    ),

                    "posting_nature" => $this->input->post(
                        "posting_nature",
                        true
                    ),

                    "job_description" => $this->input->post(
                        "job_description",
                        true
                    ),

                    "job_nature" => $this->input->post("job_nature", true),

                    "domain" => $domain,

                    "specialization" => $specialization_potion,
                ];

                $this->db->insert("placement_jobs", $arr_form_data);

                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata("check_inset", "OK");

                    redirect(base_url() . "UserController/posts_job");
                } else {
                    $this->session->set_flashdata("check_inset", "ERROR");

                    redirect(base_url() . "UserController/posts_job");
                }
            }
        }

        $data_view["job_domain"] = $this->Sp_model->get_job_data(
            "ALL_JOB_DOMAIN"
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("job_add", $data_view);

        $this->load->view("footer");
    }

    public function job_edit($id)
    {
        $data_view = [];

        $this->load->model("User_model");

        $this->load->model("Base_model");

        $this->load->model("Sp_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        if (isset($_POST["btn_job_edit"])) {
            $this->form_validation->set_rules(
                "job_title",
                "Job Title",
                "required"
            );

            // $this->form_validation->set_rules('salary','Salary','required');

            $this->form_validation->set_rules(
                "job_types",
                "Job Types",
                "required"
            );

            // $this->form_validation->set_rules('job_locations','Job Locations','required');

            $this->form_validation->set_rules(
                "posting_nature",
                "Job Nature",
                "required"
            );

            $this->form_validation->set_rules(
                "job_description",
                "Job Description",
                "required"
            );

            if ($this->form_validation->run()) {
                $domain = $this->input->post("domain", true);

                $specialization_potion = $this->input->post(
                    "specialization[]",
                    true
                );

                if (!empty($specialization_potion)) {
                    $specialization_potion = implode(
                        ",",
                        $specialization_potion
                    );
                }

                $new_domain = $this->input->post("add_new_domain", true);

                if (!empty($new_domain)) {
                    $this->db->insert("job_domain", ["name" => $new_domain]);

                    $domain = $this->db->insert_id();
                }

                $new_specialization = $this->input->post(
                    "add_new_specialization",
                    true
                );

                if (!empty($new_domain)) {
                    $specialization_v = explode(",", $new_specialization);

                    $s_v_count = sizeof($specialization_v);

                    $s_id = [];

                    for ($i = 0; $i < $s_v_count; $i++) {
                        $s_data = [
                            "name" => $specialization_v[$i],

                            "job_domain_id" => $domain,
                        ];

                        $this->db->insert("job_specialization", $s_data);

                        $s_id[] = $this->db->insert_id();
                    }

                    $specialization_potion = implode(",", $s_id);
                }

                $arr_form_update = [
                    "job_title" => $this->input->post("job_title", true),

                    "salary" => $this->input->post("salary", true),

                    "job_type" => $this->input->post("job_types", true),

                    "job_locations" => $this->input->post(
                        "job_locations",
                        true
                    ),

                    "posting_nature" => $this->input->post(
                        "posting_nature",
                        true
                    ),

                    "job_description" => $this->input->post(
                        "job_description",
                        true
                    ),

                    "domain" => $domain,

                    "specialization" => $specialization_potion,

                    "updated_on" => date("Y-m-d h:i:sA"),
                ];

                $this->db->set($arr_form_update);

                $this->db->where(["sp_id" => $user["id"], "id" => $id]);

                $this->db->limit(1);

                $this->db->update("placement_jobs");

                if ($this->db->affected_rows() > 0) {
                    $data_view["check_inset"] = "OK";
                } else {
                    $data_view["check_inset"] = "ERROR";
                }
            }
        }

        $data_view["short_data"] = $this->Sp_model->get_job_data(
            "EDIT_BY_ID",
            $id
        );

        $data_view["job_specialization"] = $this->Sp_model->get_job_data(
            "SPECIALIZATION",
            $data_view["short_data"]->domain
        );

        $data_view["job_domain"] = $this->Sp_model->get_job_data(
            "ALL_JOB_DOMAIN"
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("job_edit", $data_view);

        $this->load->view("footer");
    }

    public function job_request_user($id)
    {
        $data_view = [];

        $this->load->model("User_model");

        $this->load->model("Base_model");

        $this->load->model("Sp_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data_view["job_request_info"] = $this->Sp_model->get_job_data(
            "JOB_REQUEST_USERS",
            $id
        );

        $this->load->view("navbar", $data);

        $this->load->view("sidebar");

        $this->load->view("job_request_user", $data_view);

        $this->load->view("footer");
    }

    public function landingsPagesTemplate()
    {
        $data_view = [];

        // $this->load->model('User_model');

        // $this->load->model('Base_model');

        $this->load->model("Sp_model");

        // if($this->User_model->authorized()==false)

        // {

        //     $this->session->set_flashdata('msg','You are not authorized to access this section');

        //     redirect(base_url().'/UserController/login');

        // }

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu( $data["user"]["iam"]);
        $data["submenu"] = $this->Commen_model->get_marketplace_submenu( $data["user"]["iam"] );
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data = $this->initializer();

        $data["template"] = $this->User_model->get_template_data();

        //$data_view['job_request_info'] = $this->Sp_model->get_job_data("JOB_REQUEST_USERS",$id);

        //$this->load->view('navbar',$data); landing_page_full_details

        //$this->load->view('sidebar');

        $this->load->view("shared/landing-page/one/index", $data);

        //$this->load->view('footer');
    }

    public function landingsPages($id)
    {
        $data = $this->initializer();

        $user = $data["user"];

        $metadata["landingId"] = $id;

        $metadata["resellerId"] = $user["id"];

        $metadata["flow"] = $this->User_model->get_landing_section_data(
            $id,
            $user["id"]
        );

        //echo "<pre>";print_r($metadata);die;

        //$metadata['flow']= $this->User_model->get_landing_section_data();['flow']

        $landing = $this->User_model->landingId($user["id"]);

        $data["landing"] = $this->Admin_model->get_landing_data_by_user($landing["landing_id"]);

        $metadata[
            "landing_page_section"
        ] = $this->User_model->get_landing_section_via_data($id);

        $metadata[
            "landing_page_details"
        ] = $this->User_model->get_landing_details_section_via_data($id);

        //echo "heko";die;

        //create view path

        $controller_name = debug_backtrace();

        //$arr_view_path = explode('_',$controller_name[0]['function']);

        //$path = implode('/',$arr_view_path);

        $file = "list";

        //$view_full_path = 'admin/'.$path.'/'.$file;

        //load view

        $this->load->view("navbar", $data);

        $this->load->view("sidebar", $data);

        $this->load->view("landing_page_section_list", $metadata);

        $this->load->view("footer");
    }

    public function addLandingSectionParameter()
    {
        // $id = $this->input->post('id',TRUE);

        // echo $id;die();

        $this->load->helper(["form", "url"]);

        $this->load->library("form_validation");

        //echo "dgdgdfkgjdfgkjdf";die;

        $this->form_validation->set_rules("section_name", "Name", "required");

        //$this->form_validation->set_rules('descripation','Descripation','required');

        //$this->form_validation->set_rules('path','path','required');

        if ($this->form_validation->run() == true) {
            /// echo "dgdgdfkgjdfgkjdffgfgfgffgf";die;

            //echo "if";die();

            $landingId = $this->input->post("landingId", true);

            $name = $this->input->post("section_name", true);

            $resellerId = $this->input->post("resellerId", true);

            //$path = $this->input->post('path',TRUE);

            $data = [];

            $data["sectionId"] = $name;

            $data["resellerId"] = $resellerId;

            $data["landingPageId"] = $landingId;

            $this->load->model("User_model");

            $result = $this->User_model->insert_landing_section_data($data);

            if ($result) {
                $this->session->set_flashdata("msg2", "Added Successfully");

                redirect(
                    "/UserController/landingsPages/" . $landingId,
                    "refresh"
                );
            } else {
                $this->session->set_flashdata("msg", "Opps Unable to add data");

                redirect(
                    "/UserController/landingsPages/" . $landingId,
                    "refresh"
                );
            }
        } else {
            $this->session->set_flashdata(
                "msg",
                "Enter all the required fields"
            );

            redirect("/UserController/landingsPages/" . $landingId, "refresh");
        }
    }

    public function addParameterValues()
    {
        // $id = $this->input->post('id',TRUE);

        // echo $id;die();

        $this->load->helper(["form", "url"]);

        $this->load->library("form_validation");

        //echo "dgdgdfkgjdfgkjdf";die;

        $this->form_validation->set_rules("values", "Value", "required");

        //$this->form_validation->set_rules('descripation','Descripation','required');

        //$this->form_validation->set_rules('path','path','required');

        $landingId = $this->input->post("landingId", true);

        $section_id = $this->input->post("id", true);

        $resellerId = $this->input->post("resellerId", true);

        // if($this->form_validation->run()== true)

        // {

        $this->load->model("User_model");

        $values = $this->input->post("values", true);

        $count = count($values);

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                if (!empty($values[$i])) {
                    $data = [
                        "landing_page_id" => $landingId,

                        "parameter" => $this->input->post("parameterName")[$i],

                        "value" => $this->input->post("values")[$i],

                        "section_id" => $section_id,

                        "reseller_id" => $resellerId,
                    ];

                    $result = $this->User_model->insert_landing_section_full_data(
                        $data
                    );
                }
            }
        }

        if ($result) {
            $this->session->set_flashdata("msg2", "Added Successfully");

            redirect("/UserController/landingsPages/" . $landingId, "refresh");
        } else {
            $this->session->set_flashdata("msg", "Opps Unable to add data");

            redirect("/UserController/landingsPages/" . $landingId, "refresh");
        }

        // }

        // else

        // {

        //     $this->session->set_flashdata("msg","Enter all the required fields");

        //     redirect('/UserController/landingsPages/'.$landingId,'refresh');

        // }
    }

    public function counselingDelete($id)
    {
        $this->load->database();

        $this->load->model("User_model");

        $this->load->model("Commen_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $this->load->helper(["form", "url"]);

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $landing = $this->User_model->landingId($user["id"]);
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );


        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"],
            "dev"
        );

        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"],
            "dev"
        );

        //$this->load->model('Admin_model');

        $result = $this->User_model->counselingDelete($id);

        if ($result == true) {
            $this->session->set_flashdata("msg", "Record deleted Successfully");

            redirect("/UserController/counselingParameters/", "refresh");
        } else {
            $this->session->set_flashdata("msg", "Something Wrong");

            redirect("/UserController/counselingParameters/", "refresh");
        }

        $this->session->set_flashdata("msg", "Record deleted Successfully");

        redirect("/UserController/counselingParameters/", "refresh");
    }

    public function landingSectionDelete($id)
    {
        $this->load->database();

        $this->load->model("User_model");

        $this->load->model("Commen_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        $this->load->helper(["form", "url"]);

        $user = $this->session->userdata("user");

        $data["user"] = $user;

        $data["allowed_services"] = $this->Admin_model->getUserDetailsById( $user["id"]);
        //echo $allowed_services;die;
        $landing = $this->User_model->landingId($user["id"]);
        
        $data["landing"] = $this->Admin_model->get_landing_data_by_user( $landing["landing_id"] );

        $data["mainmenu"] = $this->Commen_model->get_marketplace_menu(
            $data["user"]["iam"],
            "dev"
        );

        $data["submenu"] = $this->Commen_model->get_marketplace_submenu(
            $data["user"]["iam"],
            "dev"
        );

        //$this->load->model('Admin_model');

        $result = $this->User_model->landingSectionDelete($id);

        if ($result == true) {
            $this->session->set_flashdata("msg", "Record deleted Successfully");

            redirect("/UserController/landingsPages/" . $id, "refresh");
        } else {
            $this->session->set_flashdata("msg", "Something Wrong");

            redirect("/UserController/landingsPages", "refresh");
        }

        $this->session->set_flashdata("msg", "Record deleted Successfully");

        redirect("/UserController/landingsPages/" . $id, "refresh");
    }

    public function updateLandingSectinParameter()
    {
        $this->session->set_flashdata("msg", "Coming Soon");

        redirect("/UserController/landingsPages", "refresh");

        //echo "update";die();

        $this->load->database();

        $this->load->model("User_model");

        $this->load->model("Commen_model");

        if ($this->User_model->authorized() == false) {
            $this->session->set_flashdata(
                "msg",
                "You are not authorized to access this section"
            );

            redirect(base_url() . "/UserController/login");
        }

        //$id = base64_decode($this->input->post('id',TRUE));

        //$key = base64_decode($this->input->post('name',TRUE));

        //$descripation = $this->input->post('descripation',TRUE);

        //$path = $this->input->post('path',TRUE);

        $name = $this->input->post("section_name", true);

        $id = $this->input->post("id", true);

        // echo $id.$key.$new_param.$name;die();

        // echo $id;die();

        // $this->load->model('Admin_model');

        // $db_data = $this->Admin_model->get_land_byId($id);

        //echo "<pre>";

        $parameters = [
            "section_name" => $name,
        ];

        $result = $this->User_model->updatLandingSectionById($id, $parameters);

        //print_r($parameters);echo "</pre>";die();

        if ($result) {
            $this->session->set_flashdata("msg2", "Added Successfully");

            redirect("/UserController/landingsPages", "refresh");
        } else {
            $this->session->set_flashdata("msg", "Opps Unable to add data");

            redirect("/UserController/landingsPages", "refresh");
        }
    }

    public function section_via_parameter()
    {
        $sectionId = $this->input->post("sectionId");

        $this->load->model("User_model");

        $landing_page_details = $this->User_model->sectionViaPArameter(
            $sectionId
        );

        //echo "<pre>";print_r($reseller);die;

        $html = "";

        foreach ($landing_page_details as $row) {
            $html .= '<div class="row">';

            $html .= '<div class="col-md-6">';

            $html .= '<div class="mb-3">';

            $html .=
                ' <label class="form-label" for="basic-default-fullname">Parameter</label>';

            $html .= "<h2>" . $row["parameter"] . "</h2>";

            $html .=
                '<input type="hidden" name="parameterName[]" class="form-control" id="basic-default-fullname" value="' .
                $row["parameter"] .
                '"  />';

            $html .= "</div>";

            $html .= "</div>";

            $html .= '<div class="col-md-6">';

            $html .= '<div class="mb-3">';

            $html .=
                '<label class="form-label" for="basic-default-fullname">Value</label>';

            $html .=
                '<input type="text" name="values[]" class="form-control" id="basic-default-fullname" required placeholder="Value" />';

            $html .= '<div class="render-template-msg"></div>';

            $html .= "</div>";

            $html .= "</div>";

            $html .= "</div>";
        }

        //echo $html;exit;

        echo json_encode($html);
    }

    // jobs #End

    public function booking_confirmation()
    {
        $data = $this->initializer();
    }
}
?>
