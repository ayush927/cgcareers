<?php
  class Common_action extends CI_Controller
  {
      public function index()
      {
          redirect(base_url() . "UserController/login");
          date_default_timezone_set("Asia/Kolkata");
      }

      function create_assessment_link( $code ){
        $user = $this->session->userdata("user");
        $id = update( 
          [ 
            'table' => 'generated_code_details' ,
            'data' => [
              'link_status' => 'enabled',
            ],
            'where' => [
              'code' => $code,
              'email' => $user['email'],
            ]
          ]
        );
        if( $id ){
          setFlashData( [ 'status' => 'success' , 'msg' => 'Link has been created' ] );
        }
        else{
          setFlashData( [ 'status' => 'info' , 'msg' => 'Please try again for link creation' ] );
        }

        redirect( ( $user['iam'] == 'reseller' ? 'UserController' : 'SpController' ).'/unused_code' );
      }



    function send_email( $link , $email ){
      $link = base64_decode($link);
      $linkArr = explode( '/' , $link );
      $email = base64_decode($email);
      $getSolution = getQuery( [ 'where' => [ 'link' => $linkArr[count($linkArr) - 1]] , 'table' => 'assessment_link' , 'single' => true ] );
      $checkUserExist  = getQuery( [ 'where' => [ 'email' => $email ] , 'table' => 'user_details' , 'single' => true ] );
      // pre( $checkUserExist , 1);
      if( !empty( $checkUserExist ) ){
        if( $checkUserExist['iam'] == 'user' ){
            $subject = "Welcome from Respicite LLP - Verify your Email id";
            $body_msg  = "Dear ".$email." <br/> <br/> Please complete your assessment
            by using the Take Assessment through this link <a href='".$link."'>Take Assessment</a>
            <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
            $this->User_model->otp_send_on_email($email,$subject,$body_msg);
            $json_msg["msg"] = "OTP SEND";
        }
        else{
            $json_msg["msg"] = "NOT USER";
        }
      }
      else{
          $checkUserExist  = getQuery( [ 'where' => [ 'email_id' => $email ] , 'table' => 'assessment_user_tmp' , 'single' => true ] );
          // if($this->User_model->assessment_otp_update_by_email($email,$OTP_code) > 0){
          $subject = "Welcome from Respicite LLP - Verify your Email id";
          $body_msg  = "Dear ".$email." <br/> <br/> Please complete your Registration with Respicite
          by Taking Assessment through this link <a href='".$link."'>Take Assessment</a>
          <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
          $this->User_model->otp_send_on_email($email,$subject,$body_msg);
          
          // if( empty( $checkUserExist )){
          //   insert( 'assessment_user_tmp' , [ 'email_id' => $email , 'otp' => $OTP_code ] );
          // }
          // else{
          //   update( [ 'data' => [ 'otp' => $OTP_code ] , 'where' => [ 'email_id' => $email ] , 'table' => 'assessment_user_tmp' ] );
          // }
          $json_msg["msg"] = "OTP SEND";
      }
      $this->output
      ->set_content_type("application/json")
      ->set_output(json_encode($json_msg));
    }

    public function initializer()
    {
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            if( $this->User_model->authorized() == false )
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
            $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            $this->db->select('iam');
            $this->db->where('id', $user['id']);
            $qry = $this->db->get('user_details');
            $data['user']['iam'] = $qry->row_array()['iam'];
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $data['mot_menu'] = $this->Commen_model->get_mot_menu('sp','mot_sidebar');
            $data['mot_navbar'] = $this->Commen_model->get_mot_menu('sp','mot_navbar');
            $data['booking_url'] = 'https://calendly.com/';
                return $data;
    }
    
    
    function share_codes( $solution = null ){
      $data =$this->initializer();

      $user = $this->session->userdata('user');
      $data['solution'] = $solution;
      $data['user'] = $user; 

      $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);

      $email = $user['email'];

      $data['getCodeCount'] = getQuery( [ 'select' => 'COUNT(id) as totalCode', 'where' => [ 'email' => $user['email'] , 'solution' => $solution , 'status' => 'UnUsed' ] , 'table' => 'generated_code_details' , 'single' => true ] );

      $data['getAssociateList'] = getQuery( [ 'select' => 'id , fullname, email' , 'where' => [ 'user_id' => $user['user_id'] , 'status' => 'active' ] , 'table' => 'user_associate' ] );
      // lQ(1);
      // pre( $data , 1 );

      $this->load->view('navbar3',$data);

      if( $user['iam'] == 'reseller' ){
        $this->load->view('sidebar', $data);
      }
      else{
        $this->load->view('sp/sidebar',$data);
      }

      $this->load->view('share_code',$solution,$data);

      $this->load->view('footer');
      
    }

    function submit_share_code(){
      $user = $this->session->userdata('user');
      if( !empty( $_POST ) ){
        extract( $_POST );
        $getCodeCount = getQuery( [ 'select' => 'COUNT(id) as totalCode', 'where' => [ 'email' => $user['email'] , 'solution' => $solution , 'status' => 'UnUsed' ] , 'table' => 'generated_code_details' , 'single' => true ] );
        if( $getCodeCount['totalCode'] >= $share_code ){
          $getCodes = getQuery( [ 'select'=> 'code', 'where' => [ 'email' => $user['email'] , 'solution' => $solution , 'status' => 'UnUsed' ] , 'table' => 'generated_code_details' , 'limit' => [ $share_code => 0 ] ] );
          // pre( $getCodes , 1 );
          if( !empty($getCodes) ){
            foreach ($getCodes as $key => $value){
              update( [ 'where' => [ 'email' => $user['email'] , 'code' => $value['code'] ] , 'table' => 'generated_code_details' , 'data' => [ 'status' => 'allocated' , 'link_status' => 'shared' , 'associate_id' => $associate_id ] ] );
              // lQ(1);
            }
            setFlashData( [ 'status' => 'danger' , 'msg' => 'Codes has been share to associate, please check' ] );
          }
          else{
            setFlashData( [ 'status' => 'danger' , 'msg' => 'Codes not found check please' ] );
          }
        }
        else{
          setFlashData( [ 'status' => 'danger' , 'msg' => 'Share code request is not valid' ] );
        }
      }
      else{
        setFlashData( [ 'status' => 'danger' , 'msg' => 'Invalid Request' ] );
      }
      if( $user['iam'] == 'sp' ){
        redirect( 'SpController/view_reseller_code' ); 
      }
      else{
        redirect( 'UserController/view_reseller_code' ); 
      }
    }

    function create_link(){
      $data =$this->initializer();

      $user = $this->session->userdata('user');
      // $data['solution'] = $solution;
      $data['user'] = $user; 

      $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);

      $email = $user['email'];

      $data['getSolution'] = getQuery( [ 'select' => 'solution', 'where' => [ 'email' => $user['email'] , 'status' => 'UnUsed' ] , 'table' => 'generated_code_details' , 'group_by' => [ 'solution' ] ] );

      $this->load->view('navbar3',$data);

      if( $user['iam'] == 'reseller' ){
        $this->load->view('sidebar', $data);
      }
      else{
        $this->load->view('sp/sidebar',$data);
      }

      $this->load->view('create_link', $data);

      $this->load->view('footer');
    }

    function generate_link(){
      $user = $this->session->userdata('user');
      if( !empty( $_POST ) ){
        // pre( $_POST , 1 );
        extract( $_POST );
        $getcode = getQuery( [ 'where' => [ 'email' => $user['email'] , 'status' => 'UnUsed' , 'link_status' => 'disabled' , 'solution' => $solution ] , 'select' => 'code' , 'table' => 'generated_code_details' , 'single' => true ] );
        // pre( $getcode , 1 );
        if( !empty( $getcode ) ){
          update(
            [ 
              'table' => 'generated_code_details' ,
              'data' => [
                'link_status' => 'enabled',
              ],
              'where' => [
                'code' => $getcode['code'],
                'email' => $user['email'],
              ]
            ]
          );
          if( affected() ){
            setFlashData( [ 'status' => 'success' , 'msg' => 'Link has been created' ] );
          }
          else{
            setFlashData( [ 'status' => 'info' , 'msg' => 'Please try again for link creation' ] );
          }
        }
        else{
          setFlashData( [ 'status' => 'danger' , 'msg' => 'Code Not Found Check Again' ] );
        }
      }
      else{
        setFlashData( [ 'status' => 'danger' , 'msg' => 'Invalid Request' ] );
      }
      if( $user['iam'] == 'sp' ){
        redirect( 'SpController/unused-code' ); 
      }
      else{
        redirect( 'UserController/unused-code' ); 
      }
    }

    
  }
?>