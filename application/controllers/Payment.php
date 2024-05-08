<?php
require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;
class Payment extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model("Sp_model");
        $this->load->model("User_model");
        $this->load->model("Base_model");
        $this->load->model("Commen_model");
        $this->load->model("Admin_model");
    }
    
    function checkout($variation = null){
            //echo "checkout";die();
        $data = [];
        // print_r( $data );die;
        if($this->User_model->authorized()==false)
        {
                if( !$this->session->userdata('isnotloggedin') ){
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
        }
        //get all details in data DB.
        $purchase_code_details = $this->session->userdata("purchase_code_details");
        if(empty($purchase_code_details)){
                redirect(base_url().'BaseController/request_code');
        }
        $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);
        $pay_data = $this->db->get_where("user_payment_history", ["transaction_id"=>$purchase_code_details['transaction_id']])->row();
        // pre( $pay_data , 1 );
        if( $sp_details->email == "merak@gmail.com" ){
                $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }
        else{
                $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
                // $paymentKeys = $this->Sp_model->get_payment_detail( $sp_details->id , 1 );
        }
        // lQ(1);
        // print_r( $paymentKeys );
        // die;

        //find log, if logo not exist then use respicite log by default.
        if(!empty($sp_details->logo)){
                if(file_exists("./$sp_details->logo")){
                    $sp_logo = base_url($sp_details->logo);
            }else{
                    $sp_logo = base_url("uploads/1631091187.png"); 
            }
        }else{
                $sp_logo = base_url("uploads/1631091187.png");
        }

        $user = $this->session->userdata('user');
        // print_r($data['user']  );
        // die;
        if( empty( $user ) ){
                $data['user'] = $user_details;
        }
        else{
                $data['user'] = $user;
        }
        // print_r( $data );
        // die;
        $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
        if( !$this->session->userdata('isnotloggedin') ){
                $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        }

        //send checkout detail in view page. 
        $order_json = [
            "key"               => $paymentKeys['api_key'],
            "amount"            => $pay_data->amount_paise,
            "name"              => ( $sp_details->user_id != "merak" ? $sp_details->fullname : 'Respicite'),
            "description"       => $solution_details['display_solution_name'],
            "image"             => $sp_logo,
            "prefill"           => [
                    "name"              => $user_details['fullname'],
                "email"             => $user_details['email'],
                "contact"           => $user_details['mobile'],
            ],
            "notes"             => [
                    "address"           => "Respicite"
            ],
            "theme"             => [
                    "color"             => "#fc9928"
            ],
            "order_id"          => $pay_data->transaction_id,
            "callback_url"      => base_url("payment/payment_status/".$variation)
        ];        

        //sp detail
        /* $data["sp"] = [
                "fullname"  =>  $sp_details->fullname,
            "email"     =>  $sp_details->email,
            "logo"      =>  $sp_logo
        ]; */
        $data["user_detail"] = $user_details;
        $data["solution"] = $solution_details;
        $data["api_pay"] = json_encode($order_json);

        $data["variation"] = $variation;
        // pre( $data );
        // die;
        $this->load->view('navbar',$data);
        if( !$this->session->userdata('isnotloggedin') ){
            $this->load->view('user/sidebar');
            $this->load->view("user/payment_ui",$data);
        }
        else{
                $this->load->view("user/payment_ui",$data);
        }
        $this->load->view('footer');
    }
    function payment_status($variation = null){
        $purchase_code_details = $this->session->userdata("purchase_code_details");
        $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $parentData = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        if( $purchase_code_details['r_email'] == "merak@gmail.com" ){
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }
        else{
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }

        $user = $this->session->userdata('user');
        if( empty( $user ) ){
                $data_view['user'] = $user_details;
        }
        else{
                $data_view['user'] = $user;
        }
        $data_view['parentData'] =  $parentData;
        // $msg = 
        $api = new Api( $paymentKeys['api_key'], $paymentKeys['secret_key'] );  
        $success = true;
        // $error ;
        $razorpay_payment_id = $this->input->post("razorpay_payment_id");
        // echo $razorpay_payment_id."hello";
        // die;
        $razorpay_order_id = $this->input->post("razorpay_order_id");
        $razorpay_signature = $this->input->post("razorpay_signature");

        if (!empty($razorpay_payment_id)){
                try
            {
                    $attributes = array(
                        'razorpay_payment_id' =>  $razorpay_payment_id,
                    'razorpay_order_id' => $razorpay_order_id,
                    'razorpay_signature' => $razorpay_signature
                );
                $api->utility->verifyPaymentSignature($attributes);
                // pre( $attributes , 1 );
                $success = true;
            }
            catch(SignatureVerificationError $e)
            {
                    $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }


            $check_payment_id = $this->db->get_where("razorpay_transaction_history",["razorpay_payment_id"=>$razorpay_payment_id]);
            if($check_payment_id->num_rows() <= 0){
                    $this->db->trans_start();
                if ($success === true)
                {
                        $res_data = array(
                            'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "success"
                    );

                    $this->db->insert("razorpay_transaction_history",$res_data);
                    if($this->db->affected_rows() > 0){
                        $purchase_details = array(
                            'user_id' => $purchase_code_details['u_email'],
                            'reseller_id' => $purchase_code_details['r_email'],
                            'solution' => $solution_details['solution'],
                            'display_solution_name' => $solution_details['display_solution_name'],
                            'status' => 'pending',
                            'payment_mode' => 'online'
                        );
                        //insert code in user_code_list.
                        $this->db->insert('user_code_list',$purchase_details);  
                        if($this->db->affected_rows() > 0){
                                //update transaction status
                            $insert_id = $this->db->insert_id();
                            if( $variation != null ){
                                $_SESSION['variant_form_data']['requestId'] = $insert_id;
                                $this->db->insert('user_solution_variation_data',$_SESSION['variant_form_data']);
                            }
                            $this->db->set("code_purchase_id",$insert_id);
                            $this->db->set("transaction_status","success");
                            $this->db->where([
                                    "transaction_id" => $this->input->post("razorpay_order_id"),
                                "u_email"        => $purchase_code_details['u_email'],
                                "r_email"        => $purchase_code_details['r_email']
                            ]);
                            $this->db->limit(1);
                            $this->db->update("user_payment_history");

                            //update user_code_list table in db
                            $this->db->set("payment_status","success");
                            $this->db->where("id",$insert_id);
                            $this->db->limit(1);
                            $this->db->update("user_code_list");
                            $data_view["roder_id"] = $razorpay_order_id;
                            // print_r( $data_view );
                            // die;
                            $this->load->view("user/payment_success", $data_view); 
                            $this->session->unset_userdata('purchase_code_details');                     
                        }else{
                                $this->db->trans_rollback();
                        }                        
                    }else{
                            $this->db->trans_rollback();
                    }
                }
                else
                {
                        $res_data = array(
                            'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "failed"
                    );
                    $this->db->insert("razorpay_transaction_history",$res_data);
                    $data_view["roder_id"] = $razorpay_order_id;
                    $this->load->view("user/payment_failed",$data_view); 
                    $this->session->unset_userdata('purchase_code_details'); 
                } 
                $this->db->trans_complete();
            }else{

                    echo "<div style='text-align:center;padding:10px'>
                <a href='".base_url("BaseController/purchase_code_history")."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back to Purchase Code History</a></div>";
                show_404();
            }            
        }else{
                echo "<div style='text-align:center;padding:10px'>
            <a href='".base_url("BaseController/purchase_code_history")."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back to Purchase Code History</a></div>";
            show_404();
        }
    }


    function user_request_training_online( $email, $r_email, $training_id, $status = null){

        if( $status != null ){

            $this->session->set_userdata("isnotloggedin",true);

        }

        //  echo "solution_id".$solution_id;die();

        $this->load->model("Sp_model");

        $this->load->model("User_model");

        $purchase_code_details = [

            "u_email"           => urldecode($email),

            "r_email"           => urldecode($r_email),

            "training_id"       => urldecode(base64_decode($training_id))

        ];

        $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);

        // print_r(  );

        // echo $this->db->last_query();

        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);

        $training_details = $this->User_model->get_user_details("TRAINING_BY_NAME",$purchase_code_details['training_id']);
        // pre( $training_details , 1 );

        // die;

        if( $status != null ){

            $this->session->set_userdata("isnotloggedin",true);

            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );

        }

        else{

            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );

            if( $sp_details->email != "merak@gmail.com" ){

                $data = $this->Sp_model->get_payment_detail( $sp_details->id , 1 );

                if( !empty($data) ){

                    $paymentKeys = $data;    

                }

            }

        }

        // print_r( $paymentKeys );

        // die;

        $user = $this->session->userdata('user');

        if( empty( $user ) ){

            $data['user'] = $user_details;

        }

        else{

            if(  $status != null ){

                $this->session->unset_userdata('user');   

                $data['user'] = $user_details;

            }

            else{

                $data['user'] = $user;

            }                

        }

        

        //echo $reseller_id; die;

        // $this->db->select('user_id, content, date');

        // $query = $this->db->get('user_details');

        //initialize payment api by razarpay

        $order_amount = $training_details['price']*100;
        if( $training_details['discount'] != '' ){
            $order_amount = ($training_details['price'] - ($training_details['price']*$training_details['discount']/100))*100;
        }

        // echo "<pre>";

        // print_r( $order_amount );

        // die;

        $payment_api = new Api( $paymentKeys['api_key'], $paymentKeys['secret_key'] );

        

        $order_data = [

            'receipt'         => "rcptid_".$user_details["id"]."_".rand(1000,100000),

            'amount'          => $order_amount, // 39900 rupees in paise

            'currency'        => 'INR'

        ];

        

        // create payment 

        

        $create_order = $payment_api->order->create($order_data);

        // if( )



        //insert all required info in DB.

        $save_data = [

            "r_email"                   => $sp_details->email,

            "u_email"                   => $user_details['email'],

            "solution_id"               => $training_details['id'],

            "solution_name"             => $training_details['training_name'],

            "solution_price"            => ($order_amount/100),

            "transaction_id"            => $create_order['id'],

            "amount_paise"              => $create_order['amount'],

            "currency"                  => $create_order['currency'],

            "receipt"                   => $create_order['receipt'],

            "transaction_status"        => $create_order['status'],

            "transaction_created_at"    => $create_order['created_at'],
            
            "purchase_type"             => 'training'

        ];



        $this->db->insert("user_payment_history",$save_data);

        

        $purchase_code_details = [

            "transaction_id"    => $create_order['id'],

            "u_email"           => urldecode($email),

            "r_email"           => urldecode($r_email),

            "training_id"       => urldecode(base64_decode($training_id))

        ];



        $this->session->set_userdata("purchase_code_details",$purchase_code_details);

        redirect(base_url().'payment/training_checkout');

    }

    function training_checkout(){
            //echo "checkout";die();
        $data = [];
        // print_r( $data );die;
        //get all details in data DB.
        $purchase_code_details = $this->session->userdata("purchase_code_details");
        // pre( $purchase_code_details , 1 );
        if(empty($purchase_code_details)){
            redirect(base_url().'BaseController/request_code');
        }
        $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $training_details = $this->User_model->get_user_details( "TRAINING_BY_NAME", $purchase_code_details['training_id']);
        // pre( $training_details , 1 );
        
        $pay_data = $this->db->get_where("user_payment_history",["transaction_id"=>$purchase_code_details['transaction_id']])->row();
        if( $sp_details->email == "merak@gmail.com" ){
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }
        else{
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }
        // print_r( $paymentKeys );
        // die;

        //find log, if logo not exist then use respicite log by default.
        if(!empty($sp_details->logo)){
                if(file_exists("./$sp_details->logo")){
                    $sp_logo = base_url($sp_details->logo);
            }else{
                    $sp_logo = base_url("uploads/1631091187.png"); 
            }
        }else{
                $sp_logo = base_url("uploads/1631091187.png");
        }

        $user = $this->session->userdata('user');
        // print_r($data['user']  );
        // die;
        if( empty( $user ) ){
                $data['user'] = $user_details;
        }
        else{
                $data['user'] = $user;
        }
        // print_r( $data );
        // die;
        $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
        if( ! $this->session->userdata('isnotloggedin') ){
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        }

        //send checkout detail in view page. 
        $order_json = [
            "key"               => $paymentKeys['api_key'],
            "amount"            => $pay_data->amount_paise,
            "name"              => ( $sp_details->user_id != "merak" ? $sp_details->fullname : 'Respicite'),
            "description"       => $training_details['training_name'],
            "image"             => $sp_logo,
            "prefill"           => [
                "name"              => $user_details['fullname'],
                "email"             => $user_details['email'],
                "contact"           => $user_details['mobile'],
            ],
            "notes"             => [
                    "address"           => "Respicite"
            ],
            "theme"             => [
                    "color"             => "#fc9928"
            ],
            "order_id"          => $pay_data->transaction_id,
            "callback_url"      => base_url("payment/training_payment_status")
        ];
        
       

        //sp detail
        /* $data["sp"] = [
                "fullname"  =>  $sp_details->fullname,
            "email"     =>  $sp_details->email,
            "logo"      =>  $sp_logo
        ]; */
        $data["user_detail"] = $user_details;
        $data["solution"] = [ 'display_solution_name' => $training_details['training_name'  ] , 'price' => ($order_json['amount']/100) , 'paymentFor' => 'training'];
        $data["api_pay"] = json_encode($order_json);
        // print_r( $data );
        // die;
        $this->load->view('navbar',$data);
        if( !$this->session->userdata('isnotloggedin') ){
            $this->load->view('user/sidebar');
            $this->load->view("user/payment_ui",$data);
        }
        else{
                $this->load->view("user/payment_ui",$data);
        }
        $this->load->view('footer');
    }
    function training_payment_status(){
        $purchase_code_details = $this->session->userdata("purchase_code_details");
        $training_details = $this->User_model->get_user_details("TRAINING_BY_NAME",$purchase_code_details['training_id']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $parentData = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        if( $purchase_code_details['r_email'] == "merak@gmail.com" ){
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }
        else{
            $paymentKeys = $this->Sp_model->get_payment_detail( RESP_DEFAULT_PAYMENT_ID , 1 );
        }

        $user = $this->session->userdata('user');
        if( empty( $user ) ){
                $data_view['user'] = $user_details;
        }
        else{
                $data_view['user'] = $user;
        }
        $data_view['parentData'] =  $parentData;
        // $msg = 
        $api = new Api( $paymentKeys['api_key'], $paymentKeys['secret_key'] );  
        $success = true;
        // $error ;
        $razorpay_payment_id = $this->input->post("razorpay_payment_id");
        // echo $razorpay_payment_id."hello";
        // die;
        $razorpay_order_id = $this->input->post("razorpay_order_id");
        $razorpay_signature = $this->input->post("razorpay_signature");

        if (!empty($razorpay_payment_id)){
                try
            {
                $attributes = array(
                    'razorpay_payment_id' =>  $razorpay_payment_id,
                    'razorpay_order_id' => $razorpay_order_id,
                    'razorpay_signature' => $razorpay_signature
                );
                $api->utility->verifyPaymentSignature($attributes);
                // pre( $attributes , 1 );
                $success = true;
            }
            catch(SignatureVerificationError $e)
            {
                    $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }


            $check_payment_id = $this->db->get_where("razorpay_transaction_history",["razorpay_payment_id"=>$razorpay_payment_id]);
            if($check_payment_id->num_rows() <= 0){
                    $this->db->trans_start();
                if ($success === true)
                {
                    $res_data = array(
                        'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "success"
                    );
                    $this->db->insert("razorpay_transaction_history",$res_data);
                    if($this->db->affected_rows() > 0){ 
                        if($this->db->affected_rows() > 0){
                            // redirect( RESPICITE_URL."skill-development-details?id=".base64_encode( $training_details['id'] ) );
                            $subject = "Login Instruction";
                            $body_msg = email_template( [
                                'heading' => 'Heading One',
                                'heading2' => 'Heading Two',
                                'para1' => "Dear ".$user_details['fullname']." <br/> <br/> Please Follow the instruction to take skill development classes"
                            ]);
                            $this->User_model->otp_send_on_email($email,$subject,$body_msg);
                            $form_data = [
                                "training_id" => $training_details['id'],
                                "user_email" => $user_details['email'],
                                "taining_status" => 'approved',
                                "trainingType" => 'live',
                            ];
                            $this->db->insert( "vocational_training_apply_user" , $form_data);
                            redirect( RESPICITE_URL."skill-development-thank-you" );
                            $this->session->unset_userdata('purchase_code_details');               
                        }
                        else{
                            $this->db->trans_rollback();
                        }
                    }else{
                            $this->db->trans_rollback();
                    }
                }
                else
                {
                    $res_data = array(
                        'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "failed"
                    );
                    $this->db->insert("razorpay_transaction_history",$res_data);
                    $data_view["roder_id"] = $razorpay_order_id;
                    $this->load->view("user/payment_failed",$data_view); 
                    $this->session->unset_userdata('purchase_code_details'); 
                }
                $this->db->trans_complete();
            }
            else{

                echo "<div style='text-align:center;padding:10px'>
                <a href='".redirect( RESPICITE_URL."skill-development-details?id=".base64_encode( $training_details['id'] ) )."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back To Training Detail</a></div>";
                show_404();
            }            
        }
        else{
            echo "<div style='text-align:center;padding:10px'>
            <a href='".redirect( RESPICITE_URL."skill-development-details?id=".base64_encode( $training_details['id'] ) )."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back To Training Detail</a></div>";
            show_404();
        }
    }



    /* function test(){
            $this->load->view("user/payment_failed");
    } */
}
?>