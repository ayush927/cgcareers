<?php
    class Skill_development extends CI_Controller
    {
        function __construct() {

            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            $this->load->model('Admin_model');
            
            $this->table = [
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
            ];

        }

        function backdoor()
        {
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
        }
        
        public function initializer()
        {
            $this->backdoor();
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            if( $this->User_model->authorized() == false )
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $landing = $this->User_model->landingId($user['id']);
            //echo "<pre>";print_r($landing);die;
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
            //echo $allowed_services;die;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data['calendly_url']='https://calendly.com/';
            return $data;

        }
        
        // Domain
        function domain_list(){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => 'Domains List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['domain'] ])
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/domain_list');
            $this->load->view('footer');
        }
        
        function add_domain($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add domain': 'Edit Domain'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery([ 'table' => $this->table['domain'] , 'where' => [ 'id' => $edit ], 'single'=> true ]) : [] )
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_domain');
            $this->load->view('footer');
        }
        
        function submit_domain(){
            if( !empty( $_POST ) ){
                if( isset($_POST['id']) ){
                    update( [ 'table' => $this->table['domain'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Domain Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/domain-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Domain Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-domain/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['domain'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Domain Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/domain-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Domain Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/domain-list');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/add-domain');
            }
        }
        
        function delete_domain( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['domain'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Domain Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/domain-list');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/domain-list');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/domain-list');
            }
        }
        
        // Domain
        function sub_domain_list(){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => 'Sub Domain List',
                'user' => $user,
                'list' => getQuery(['select' => $this->table['sub_domain'].".* ,".$this->table['domain'].'.domainName' ,  'table' => $this->table['sub_domain'] , 'join' => [ [ $this->table['domain'] , $this->table['domain'].'.id = '.$this->table['sub_domain'].'.domainId' , 'INNER' ] ] ])
            ];
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sub_domain_list');
            $this->load->view('footer');
        }
        
        function add_sub_domain($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'list' => getQuery( [ 'table' => $this->table['domain'] ] ),
                'pageTitle' => ( $edit == null ? 'Add Sub domain': 'Edit Sub Domain'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery(['select' => $this->table['sub_domain'].".* ,".$this->table['domain'].'.domainName' ,  'table' => $this->table['sub_domain'] , 'where' => [ $this->table['sub_domain'].'.id' => $edit ], 'join' => [ [ $this->table['domain'] , $this->table['domain'].'.id = '.$this->table['sub_domain'].'.domainId' , 'INNER' ] ] , 'single' => true] ) : [] )
            ];
            // pre(  $data , 1 ); 
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sub_add_domain');
            $this->load->view('footer');
        }
        
        function submit_sub_domain(){
            if( !empty( $_POST ) ){
                if( isset($_POST['id']) ){
                    update( [ 'table' => $this->table['sub_domain'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Domain Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/sub-domain-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Domain Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-sub-domain/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['sub_domain'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Domain Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/sub-domain-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Domain Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/sub-domain-list');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/sub-add-domain');
            }
        }
        
        function delete_sub_domain( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['sub_domain'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Domain Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/sub-domain-list');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/sub-domain-list');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/sub-domain-list');
            }
        }
        
        // Level
        function level_list(){
            $user = $this->session->userdata('user');
            
            $data = [
                'pageTitle' => 'Level List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['level'] ]),
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/level_list');
            $this->load->view('footer');
        }
        
        function add_level($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add Level': 'Edit Level'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery( [ 'table' => $this->table['level'] , 'where' => [ 'id' => $edit ]  , 'single' => true ] ) : [] )
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level');
            $this->load->view('footer');
        }
        
        function submit_level(){
            if( !empty( $_POST ) ){
                if( isset($_POST['id']) ){
                    update( [ 'table' => $this->table['level'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Level Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/level-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Level Not Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-level/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['level'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Level Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/level-list');
                    }
                    else{
                        $this->session->set_flashdata('msg','Level Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/level-list');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/level-list');
            }
        }
        
        function delete_level( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['level'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Domain Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/level-list');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/level-list');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/level-list');
            }
        }
        
        // Nature
        function nature(){
            $user = $this->session->userdata('user');
            
            $data = [
                'pageTitle' => 'Nature List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['nature'] ]),
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/nature_list');
            $this->load->view('footer');
        }
        
        function add_nature($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add Nature': 'Edit Nature'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery( [ 'table' => $this->table['nature'] , 'where' => [ 'id' => $edit ]  , 'single' => true ] ) : [] )
            ];
            // pre( $data , 1 );
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_nature');
            $this->load->view('footer');
        }
        
        function submit_nature(){
            if( !empty( $_POST ) ){
                if( isset($_POST['id']) ){
                    update( [ 'table' => $this->table['nature'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Nature Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/nature');
                    }
                    else{
                        $this->session->set_flashdata('msg','Nature Not Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-nature/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['nature'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Nature Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/nature');
                    }
                    else{
                        $this->session->set_flashdata('msg','Nature Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/nature');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/nature');
            }
        }
        
        function delete_nature( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['nature'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Nature Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/nature');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/nature');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/nature');
            }
        }
        
        // Certification Type
        function certification(){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => 'Certification List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['certification'] ]),
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/certification_list');
            $this->load->view('footer');
        }
        
        function add_certification($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add Certification': 'Edit Certification'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery( [ 'table' => $this->table['certification'] , 'where' => [ 'id' => $edit ]  , 'single' => true ] ) : [] )
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_certification');
            $this->load->view('footer');
        }
        
        function submit_certification(){
            // pre($_POST , 1);
            if( !empty( $_POST ) ){
                if( isset( $_POST['id'] ) ){
                    update( [ 'table' => $this->table['certification'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Certification Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/certification');
                    }
                    else{
                        $this->session->set_flashdata('msg','Certification Not Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-certification/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['certification'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Certification Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/certification');
                    }
                    else{
                        $this->session->set_flashdata('msg','Certification Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/certification');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/certification');
            }
        }
        
        function delete_certification( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['certification'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Nature Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/certification');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/certification');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/certification');
            }
        }
        
        // Discount
        function discount(){
            $user = $this->session->userdata('user');
            
            $data = [
                'pageTitle' => 'Discount List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['discount'] ]),
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/discount_list');
            $this->load->view('footer');
        }
        
        function add_discount($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add Discount': 'Edit Discount'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery( [ 'table' => $this->table['discount'] , 'where' => [ 'id' => $edit ]  , 'single' => true ] ) : [] )
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_discount');
            $this->load->view('footer');
        }
        
        function submit_discount(){
            // pre($_POST , 1);
            if( !empty( $_POST ) ){
                if( isset( $_POST['id'] ) ){
                    update( [ 'table' => $this->table['discount'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Discount Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/discount');
                    }
                    else{
                        $this->session->set_flashdata('msg','Discount Not Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-discount/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['discount'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','discount Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/discount');
                    }
                    else{
                        $this->session->set_flashdata('msg','discount Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/discount');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/discount');
            }
        }
        
        function delete_discount( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['discount'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Discount Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/discount');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/discount');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/discount');
            }
        }
        
        // Language
        function language(){
            $user = $this->session->userdata('user');
            
            $data = [
                'pageTitle' => 'Language List',
                'user' => $user,
                'list' => getQuery([ 'table' => $this->table['language'] ]),
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/language_list');
            $this->load->view('footer');
        }
        
        function add_language($edit=null){
            $user = $this->session->userdata('user');
            $data = [
                'pageTitle' => ( $edit == null ? 'Add Language': 'Edit Language'), 
                'user' => $user,
                'edit' => ( $edit !=null ? getQuery( [ 'table' => $this->table['language'] , 'where' => [ 'id' => $edit ]  , 'single' => true ] ) : [] )
            ];
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data , 1 );
            $this->load->view( 'navbar' , $data );
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_language');
            $this->load->view('footer');
        }
        
        function submit_language(){
            // pre($_POST , 1);
            if( !empty( $_POST ) ){
                if( isset( $_POST['id'] ) ){
                    update( [ 'table' => $this->table['language'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Language Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/language');
                    }
                    else{
                        $this->session->set_flashdata('msg','Language Not Update');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/edit-language/'.$_POST['id'] );
                    }
                }
                else{
                    insert( $this->table['language'] , $_POST  );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg','Language Added');
                        $this->session->set_flashdata('status','success');
                        redirect( base_url().'skill-development/language');
                    }
                    else{
                        $this->session->set_flashdata('msg','discount Not Added');
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/language');
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/language');
            }
        }
        
        function delete_language( $id ){
            if( $id != null  ){
                delete( [ 'table' => $this->table['language'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Language Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/language');
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/language');
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/language');
            }
        }
        
        public function get_sub_domain($id = null){
            if( $id != null ){
                $result = getQuery([ 'table' => $this->table['sub_domain'] , 'where' => [ 'domainId' => base64_decode($id) ] ]);
                // lQ(1);
                if( !empty( $result ) ){
                    $html = '<option> Select Sub Domain </option>';
                    foreach( $result as $key => $value ){
                        $html .='<option value="'.$value['id'].'" >'.ucwords($value['subDomainName']).'</option>';
                    }
                    $data = [ 'result' => $html , 'code' =>'success' , 'message' => 'sub domain found' ];
                }
                else{
                    $html = '<option> Select Sub Domain </option>';
                    $data = [ 'result' => $html , 'message' => 'sub domain not found' ];
                }
            }
            else{
                $data = [ 'result' => [] , 'message' => 'domain id not found' ];
            }
            echo json_encode( $data );
        }
        
        public function get_what_you_skill($id = null){
            if( $id != null ){
                $result = getQuery([ 'table' => $this->table['whatyoulearn'] , 'where' => [ 'id' => base64_decode($id) ] ]);
                // lQ(1);
                if( !empty( $result ) ){
                    $html = '<option> Select Sub Domain </option>';
                    foreach( $result as $key => $value ){
                        $html = ucfirst($value['whatyoulearn']);
                    }
                    $data = [ 'result' => $html , 'code' =>'success' , 'message' => 'sub domain found' ];
                }
                else{
                    $html = '<option> Select Sub Domain </option>';
                    $data = [ 'result' => $html , 'message' => 'sub domain not found' ];
                }
            }
            else{
                $data = [ 'result' => [] , 'message' => 'domain id not found' ];
            }
            echo json_encode( $data );
        }

        public function get_skill($id = null){
            if( $id != null ){
                $result = getQuery([ 'table' => $this->table['training_skill'] , 'where' => [ 'id' => base64_decode($id) ] ]);
                // lQ(1);
                if( !empty( $result ) ){
                    $html = '<option> Select Sub Domain </option>';
                    foreach( $result as $key => $value ){
                        $html = ucfirst($value['skillName']);
                    }
                    $data = [ 'result' => $html , 'code' =>'success' , 'message' => 'sub domain found' ];
                }
                else{
                    $html = '<option> Select Sub Domain </option>';
                    $data = [ 'result' => $html , 'message' => 'sub domain not found' ];
                }
            }
            else{
                $data = [ 'result' => [] , 'message' => 'domain id not found' ];
            }
            echo json_encode( $data );
        }
        
        public function live_training_list( ){
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data_view = [
                'pageTitle' => 'Live Training List',
                'list' => getQuery( [ 'table' => $this->table['vocational_live'] ] ),
            ];
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // pre( $data['user'] , 1 );
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view( 'training_list', $data_view );
            $this->load->view('footer' , $data_view);
        }
        
        public function vocational_training( $page = null , $id = null  ){
            $data_view = [];
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            // pre( $data['user'] , 1 );
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu( $data['user']['iam'], 'dev' );
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            if( $page == 'meta-data' ){
                $edit = ( $id != null ? getQuery( [ 'table' => $this->table['vocational_live'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ) : [] );
                $data_view = [
                    'edit' => $edit,
                    'pageTitle' => 'Training Meta Data',
                    'domain' => getQuery([ 'table' => $this->table['domain'] ]),
                    'subDomain' => ( $id != null ? getQuery([ 'table' => $this->table['sub_domain'] , 'where' => [ 'domainId' => $edit['domainId'] ] ]) : [] ) ,
                    'nature' => getQuery( [ 'table' => $this->table['nature'] ] ),
                    'certification' => getQuery( [ 'table' => $this->table['certification'] ] ),
                    'level' => getQuery( [ 'table' => $this->table['level'] ] ),
                    'language' => getQuery( [ 'table' => $this->table['language'] ] ),
                    'discount' => getQuery( [ 'table' => $this->table['discount'] ] ),
                    'user' => $user,
                    'id' => base64_decode( $id ),
                ];
                // pre( $data_view  , 1);
                $pageName = 'training-meta-data';
                
            }
            elseif( $page == 'detail' ){
                $data_view = [
                    'pageTitle' => 'Training Detail',
                    'edit' => ( $id != null ? getQuery( [ 'table' => $this->table['vocational_live'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ) : [] )
                ];
                // lQ(1);
                $pageName = 'training-detail' ;
            }
            elseif( $page == 'session-schedule' ){
                $data_view = [ 
                    'pageTitle' => 'Training Session',
                    'trainingData' => getQuery( [ 'where' => [ 'id' => base64_decode($id) ] , 'table' => $this->table['vocational_live'] , 'single' => true ] ),
                    'list' => getQuery( [ 'where' => [ 'trainingId' => base64_decode($id) ] , 'table' => $this->table['live_session'] ] ),
                    'id' => base64_decode( $id )
                ];
                $pageName = 'session_list' ;
            }
            elseif( $page == 'add-session' || $page == 'edit-session' || $page == 'copy-session' ){
                $data_view = [ 
                    'pageTitle' => ( $page == 'edit-session' ? 'Edit' : 'Add' ).' Training Session',
                    'trainingData' => getQuery( [ 'where' => [ 'id' => base64_decode($id) ] , 'table' => $this->table['vocational_live'] , 'single' => true ] ),
                    'id' => base64_decode( $id ),
                    'edit' => ( $page == 'edit-session' || $page == 'copy-session' ? getQuery( [ 'table' => $this->table['live_session'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ) : [] ),
                    'whatyoulearnList' => getQuery( [ 'table' => $this->table['whatyoulearn'] ] ),
                    'skillyougetList' => getQuery( [ 'table' => $this->table['training_skill'] ] ),
                    'copy' => ( $page == 'copy-session' ? true : false )
                ];
                // pre( $data_view , 1 );
                $pageName = 'add_session';
            }
            elseif( $page == 'reschedule-session' ){
                $data_view = [
                    'pageTitle' => 'Reschedule Training Session',
                    'id' => base64_decode( $id ),
                    'edit' => getQuery( [ 'table' => $this->table['live_session'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ),
                ];
                $pageName = 'recheduled';
                // pre( $data_view , 1 );
            }
            elseif( $page == 'curriculum-list' ){
                
                $data_view = [
                    'pageTitle' => 'Training Curriculum List',
                    'list' => getQuery( [ 'where' => [ 'trainingId' => base64_decode($id) ] , 'table' => $this->table['curriculum'] ] ),
                    'id' => base64_decode( $id )
                ];
                $pageName = 'curriculum_list' ;
            }
            elseif( $page == 'add-curriculum' || $page == 'edit-curriculum' ){
                
                $data_view = [
                    'pageTitle' => ( $page == 'edit-curriculum' ? 'Edit' : 'Add' ).' Curriculum',
                    'id' => base64_decode( $id ),
                    'edit' => ( $page == 'edit-curriculum' ? getQuery( [ 'table' => $this->table['curriculum'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ) : [] )
                ];
                $pageName = 'add_curriculum';
                
            // pre( $data_view , 1 );
            }
            elseif( $page == 'faq-list' ){
                
                $data_view = [
                    'pageTitle' => 'Frequently Ask Questions',
                    'list' => getQuery( [ 'where' => [ 'trainingId' => base64_decode($id) ] , 'table' => $this->table['faq'] ] ),
                    'id' => base64_decode( $id )
                ];
                $pageName = 'faq_list' ;
            }
            elseif( $page == 'add-faq' || $page == 'edit-faq' ){
                
                $data_view = [
                    'pageTitle' => ( $page == 'edit-faq' ? 'Edit' : 'Add' ).' FAQ',
                    'id' => base64_decode( $id ),
                    'edit' => ( $page == 'edit-faq' ? getQuery( [ 'table' => $this->table['faq'] , 'where' => [ 'id' => base64_decode( $id ) ] , 'single' => true ] ) : [] )
                ];
                $pageName = 'add_faq';
                
            // pre( $data_view , 1 );
            }
            $this->load->view( $pageName , $data_view );
            $this->load->view('footer' , $data_view);
        }
        
        function submit_detail(){
            // pre( $_POST );
            // pre( $_FILES , 1 );
            if(!empty($_POST))
            {
                $user = $this->session->userdata('user');
                $_POST['sp_id'] = $user['id'];
                //image Upload #start.
                if( $_FILES["image"]['name'] != '' ){
                    $upload_path="uploads/vocational_training/"; 
                    $new_name = time().'-'.$_FILES["image"]['name'];
                    $config = array(
                        'upload_path' => $upload_path,
                        'file_name'=>$new_name,
                        'allowed_types' => "jpg|jpeg|png|gif",
                        'max_size' => "2048000", //2.048mb
                        'max_height' => "0",
                        'max_width' => "0"
                    );
                    $this->load->library('upload', $config);                    
                    if($this->upload->do_upload('image'))
                    {
                        $imageDetailArray = $this->upload->data();
                        $_POST['image'] = $imageDetailArray['file_name'];
                    }
                    else
                    {
                        $data_view['imageError'] =  $this->upload->display_errors();
                        $this->session->set_flashdata('msg',$data_view['imageError'][0]);
                        $this->session->set_flashdata('status','danger');
                        if( isset( $_POST['id'] ) ){
                            redirect(base_url().'skill-development/vocational-training/detail/'.base64_encode( $_POST['id'] ));
                        }
                        else{
                            redirect(base_url().'skill-development/vocational-training/detail');
                        }
                    }
                }
                
                if( isset( $_POST['id'] ) ){
                    // pre( $_POST , 1 );
                    update( [ 'where' => [ 'id' => $_POST['id'] ] , 'table' => $this->table['vocational_live'] , 'data' => $_POST ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'training Updated successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/live-training-list');
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Update Remian\'s Same' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/live-training-list');
                    }
                }
                else{
                    // pre( $_POST , 1 );
                    $id = insert( $this->table['vocational_live'] , $_POST );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'training added successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/meta-data/'.base64_encode($id));
                    }
                    else{
                        $this->session->set_flashdata('msg', 'training not added , try again' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/detail');
                    }
                }
                
                // image Upload #end.
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/vocational-training/detail');
            }
        }
        
        function meta_detail_submit(){
            if( !empty($_POST) )
            {
                update( ['table' => $this->table['vocational_live'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg', 'Meta data successfully updated' );
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['id']));
                }
                else{
                    $this->session->set_flashdata('msg', 'training not added , try again' );
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/vocational-training/meta-data/'.base64_encode($_POST['id']));
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function session_submit(){
            if(!empty($_POST))
            {
                // pre( $_POST , 1 );
                extract($_POST);
                if( !empty( $_POST['whatyoulearnselected'] ) ){
                    foreach (explode(';' , $_POST['whatyoulearnselected']) as $key => $value){
                        if( $value != '' ){
                            $check_whatyoulearn = getQuery( [ 'where' => [ 'whatyoulearn' => $value ] , 'table' => $this->table['whatyoulearn'] , 'single' => true ] );
                            if( empty( $check_whatyoulearn ) ){
                                $whatyoulearn[] = insert( $this->table['whatyoulearn'] , [ 'whatyoulearn' => $value ] );
                            }
                            else{
                                $whatyoulearn[] = $check_whatyoulearn['id'];
                            }
                        }
                    }
                }
                if( !empty( $_POST['skillyougetselected'] ) ){
                    foreach (explode(';', $_POST['skillyougetselected']) as $key => $value){
                        if( $value != '' ){
                            $check_training_skill = getQuery( [ 'where' => [ 'skillName' => $value ] , 'table' => $this->table['training_skill'] , 'single' => true ] );
                            if( empty( $check_training_skill ) ){
                                $skillyouget[] = insert( $this->table['training_skill'] , [ 'skillName' => $value ] );
                            }
                            else{
                                $skillyouget[] = $check_training_skill['id'];
                            }
                        }
                    }
                }
                // pre( $whatyoulearn , 1);
                $_POST['whatyoulearn'] = json_encode(array_unique($whatyoulearn));
                $_POST['skillyouget'] = json_encode(array_unique($skillyouget));
                $_POST['starttime'] = str_replace('T', ' ' , $_POST['starttime']).':00';
                if( $_POST['duration'] != '' ){
                    $_POST['endtime'] = date("Y-m-d H:i:s", strtotime('+'.$_POST['duration'].' minutes', strtotime($_POST['starttime'])));
                }
                // $_POST['endtime'] = str_replace('T', ' ' , $_POST['endtime']).':00';
                unset( $_POST['duration'] );
                unset( $_POST['skillyougetselected'] );
                unset( $_POST['whatyoulearnselected'] );
                // pre( $_POST , 1 );
                if( isset( $_POST['id']  ) ){
                    // pre( $_POST , 1 );
                    update( ['table' => $this->table['live_session'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'Sessions successfully updated' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['trainingId']));
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Update Remains same' );
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['trainingId']) );
                    }
                }
                else{
                    insert( $this->table['live_session']  , $_POST );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'Session add successfully updated' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['trainingId']));
                    }
                    else{
                        $this->session->set_flashdata('msg', 'session not added , try again' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/add-session/'.base64_encode($_POST['trainingId']));
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function schedulling_submit(){
            if( !empty( $_POST ) ){
                update( ['table' => $this->table['live_session'] , 'data' => $_POST , 'where' => [ 'id' => $_POST['id'] ] ] );
                if( affected() > 0 ){
                    $this->session->set_flashdata( 'msg', 'Sessions successfully updated' );
                    $this->session->set_flashdata( 'status','success' );
                    redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['trainingId']) );
                }
                else{
                    $this->session->set_flashdata('msg', 'Update Remains same' );
                    $this->session->set_flashdata('status','info');
                    redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($_POST['trainingId']) );
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function reset_reschedule_session($id = null){
            if( $id != null ){
                $sessionData = getQuery( [ 'where' => [ 'id' => base64_decode($id) ] , 'table' => $this->table['live_session'] , 'single' => true ] );
                if( !empty( $sessionData ) ){
                    $data = [
                        'reshceduled_time' => NULL,
                        'reschedule_remark' => NULL,
                    ];
                    update( ['table' => $this->table['live_session'] , 'data' => $data , 'where' => [ 'id' => base64_decode($id) ] ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata( 'msg', 'Reset Reschedule successfully updated' );
                        $this->session->set_flashdata( 'status','success' );
                        redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($sessionData['trainingId']) );
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Update Remains same' );
                        $this->session->set_flashdata('status','info');
                        redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($sessionData['trainingId']) );
                    }
                }
                else{
                    $this->session->set_flashdata('msg', 'Invalid Parameter' );
                    $this->session->set_flashdata('status','danger');
                    redirect(base_url().'skill-development/live-training-list');
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function change_status_session( $sessionId = null , $status = null ){
            if( $sessionId != null && $status != null ){
                if( $status == 'active' || $status == 'inactive' || $status == 'canceled' || $status == 'complete' ){
                    $sessionData = getQuery( [ 'where' => [ 'id' => base64_decode( $sessionId ) ] , 'table' => $this->table['live_session'] , 'single' => true ] );
                    if( !empty( $sessionData ) ){
                        update( ['table' => $this->table['live_session'] , 'data' => ['status' => $status ] , 'where' => [ 'id' => base64_decode($sessionId) ] ] );
                        if( affected() > 0 ){
                            $this->session->set_flashdata( 'msg', 'Status Has been updated' );
                            $this->session->set_flashdata( 'status','success' );
                            redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($sessionData['trainingId']) );
                        }
                        else{
                            $this->session->set_flashdata('msg', 'Update Remains same' );
                            $this->session->set_flashdata('status','info');
                            redirect( base_url().'skill-development/vocational-training/session-schedule/'.base64_encode($sessionData['trainingId']) );
                        }
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Invalid Session Parameter' );
                        $this->session->set_flashdata('status','danger');
                        redirect(base_url().'skill-development/live-training-list');
                    }
                }
                else{
                    $this->session->set_flashdata('msg', 'Parameter are wrong' );
                    $this->session->set_flashdata('status','danger');
                    redirect(base_url().'skill-development/live-training-list');
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function change_status_training( $trainingId = null , $status = null ){
            if( $trainingId != null && $status != null ){
                if( $status == 'active' || $status == 'inactive' || $status == 'canceled' || $status == 'completed' ){
                    $sessionData = getQuery( [ 'where' => [ 'id' => base64_decode( $trainingId ) ] , 'table' => $this->table['vocational_live'] , 'single' => true ] );
                    if( !empty( $sessionData ) ){
                        update( ['table' => $this->table['vocational_live'] , 'data' => ['status' => $status ] , 'where' => [ 'id' => base64_decode($trainingId) ] ] );
                        if( affected() > 0 ){
                            $this->session->set_flashdata( 'msg', 'Status Change Successfully' );
                            $this->session->set_flashdata( 'status','success' );
                            redirect( base_url().'skill-development/live-training-list/' );
                        }
                        else{
                            $this->session->set_flashdata('msg', 'Update Remains same' );
                            $this->session->set_flashdata('status','info');
                            redirect( base_url().'skill-development/live-training-list/' );
                        }
                    }
                    else{
                        $this->session->set_flashdata('msg', 'Invalid Session Parameter' );
                        $this->session->set_flashdata('status','danger');
                        redirect(base_url().'skill-development/live-training-list');
                    }
                }
                else{
                    $this->session->set_flashdata('msg', 'Parameter are wrong' );
                    $this->session->set_flashdata('status','danger');
                    redirect(base_url().'skill-development/live-training-list');
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function curriculum_submit(){
            // pre( $_POST );
            // pre( $_FILES , 1 );
            if(!empty($_POST))
            {
                $user = $this->session->userdata('user');
                // $_POST['sp_id'] = $user['id'];
                //image Upload #start.
                if( $_FILES["image"]['name'] != '' ){
                    $upload_path="uploads/vocational_training/"; 
                    $new_name = time().'-'.$_FILES["image"]['name'];
                    $config = array(
                        'upload_path' => $upload_path,
                        'file_name'=>$new_name,
                        'allowed_types' => '*',                        
                        'max_size' => "2048000", //2.048mb
                        'max_height' => "0",
                        'max_width' => "0"
                    );
                    $this->load->library('upload', $config);              
                    if($this->upload->do_upload('image'))
                    {
                        $imageDetailArray = $this->upload->data();
                        $_POST['filename'] = $imageDetailArray['file_name'];
                    }
                    else
                    {
                        $data_view['imageError'] =  $this->upload->display_errors();
                        $this->session->set_flashdata('msg', $data_view['imageError'][0]);
                        $this->session->set_flashdata('status','danger');
                        if( isset( $_POST['id'] ) ){
                            redirect(base_url().'skill-development/vocational-training/edit-curriculum/'.base64_encode( $_POST['id'] ));
                        }
                        else{
                            redirect(base_url().'skill-development/vocational-training/add-curriculum/'.base64_encode( $_POST['trainingId'] ));
                        }
                    }
                }
                
                if( isset( $_POST['id'] ) ){
                    // pre( $_POST , 1 );
                    update( [ 'where' => [ 'id' => $_POST['id'] ] , 'table' => $this->table['curriculum'] , 'data' => $_POST ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'Curriculum Updated successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode( $_POST['trainingId'] ) );
                    } 
                    else{
                        $this->session->set_flashdata('msg', 'Update Remian\'s Same' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode( $_POST['trainingId'] ));
                    }
                }
                else{
                    // echo "hello";
                    // pre( $_POST , 1 );
                    $id = insert( $this->table['curriculum'] , $_POST );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'Curriculum updated successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode($_POST['trainingId']));
                    }
                    else{
                        $this->session->set_flashdata('msg', 'training not added , try again' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode($_POST['trainingId']));
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/vocational-training/detail');
            }
        }
        
        function delete_curriculum( $id ){
            if( $id != null  ){
                $data = getQuery( [ 'table' => $this->table['curriculum'] , 'where' => [ 'id' => base64_decode($id) ] , 'single' => true ] );
                delete( [ 'table' => $this->table['curriculum'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','Curriculum Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode($data['trainingId']));
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/vocational-training/curriculum-list/'.base64_encode($data['trainingId']));
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
        
        function faq_submit(){
            // pre( $_POST );
            // pre( $_FILES , 1 );
            if(!empty($_POST))
            {
                if( isset( $_POST['id'] ) ){
                    // pre( $_POST , 1 );
                    update( [ 'where' => [ 'id' => $_POST['id'] ] , 'table' => $this->table['faq'] , 'data' => $_POST ] );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'FAQ Updated successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode( $_POST['trainingId'] ) );
                    } 
                    else{
                        $this->session->set_flashdata('msg', 'Update Remian\'s Same' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode( $_POST['trainingId'] ));
                    }
                }
                else{
                    // echo "hello";
                    // pre( $_POST , 1 );
                    $id = insert( $this->table['faq'] , $_POST );
                    if( affected() > 0 ){
                        $this->session->set_flashdata('msg', 'FAQ Added successfully' );
                        $this->session->set_flashdata('status','success');
                        redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode($_POST['trainingId']));
                    }
                    else{
                        $this->session->set_flashdata('msg', 'training not added , try again' );
                        $this->session->set_flashdata('status','info');
                        redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode($_POST['trainingId']));
                    }
                }
            }
            else{
                $this->session->set_flashdata('msg', 'Invalid Request' );
                $this->session->set_flashdata('status','danger');
                redirect(base_url().'skill-development/vocational-training/detail');
            }
        }
        
        function delete_faq( $id ){
            if( $id != null  ){
                $data = getQuery( [ 'table' => $this->table['faq'] , 'where' => [ 'id' => base64_decode($id) ] , 'single' => true ] );
                delete( [ 'table' => $this->table['faq'] , 'where' => [ 'id' => $id]] );
                if( affected() > 0 ){
                    $this->session->set_flashdata('msg','FAQ Deleted');
                    $this->session->set_flashdata('status','success');
                    redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode($data['trainingId']));
                }
                else{
                    $this->session->set_flashdata('msg','Delete Not Done , Try Again');
                    $this->session->set_flashdata('status','info');
                    redirect(base_url().'skill-development/vocational-training/faq-list/'.base64_encode($data['trainingId']));
                }
            }
            else{
                $this->session->set_flashdata('msg','Invalid Request');
                $this->session->set_flashdata('status','info');
                redirect(base_url().'skill-development/live-training-list');
            }
        }
    }
?>