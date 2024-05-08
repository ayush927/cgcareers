<style>

a{color:black;}



.btn-my{

  color: #fc9928;

  border-color: #fc9928;

}



.btn-my:hover {

    color: #fff;

    background-color: #fc9928;

    border-color: #fc9928;

}

.table td{

  border-top: 0.5px solid #dee2e6;

}



.my-width{

  width:57%;

}



@media (max-width: 767.98px) { 

  .table tr {

    display: flex;

    flex-direction: column;

    width: 100%;

    text-align: justify;

    border: 1px solid #e2e5e7;

    border-radius: 7px;

    margin-bottom: 12px;

  }

  .my-width{

    width:100%;

  }

}

.translationcontainer{
    height: 200px;
    display: block;
  }
  .translationbox{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 50%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox1{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 43%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox2{
    width: 100px;
    height: 100px;
    position: absolute;
    left: 57%;
    top: 50%;
    translate: -50px -50px;
    background-color: orange;
    color: black;
    z-index: -1;
    text-align: center;
    border-radius: 50%;
    opacity: 1;
  }
  .translationbox, .translationbox1, .translationbox2{
    animation-name: transitionkey;
    animation-duration: 2s;
    animation-fill-mode:forwards;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
    animation-direction: reverse;
  }
  .loading-overlay {
    background: rgba(255, 255, 255, 0.7);
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    top: 0;
    z-index: 999999;
    align-items: center;
    justify-content: center;
  }
  .loading-overlay.is-active {
      display: flex;
  }
  @keyframes transitionkey{
    0% {transform: translate(0px,0px); opacity: 1;}
    25% {transform: translate(150px,0px);opacity: 0.5;}
    50% {transform: translate(0px,0px);opacity: 1;}
    75% {transform: translate(-150px,0px);opacity: 0.5;} 
    100% {transform: translate(0px,0px);opacity: 1;}
  }
</style>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

<div class="content-wrapper bg-white">
    <div class="loading-overlay">
      <div class="translationcontainer">
        <div class="translationbox">
        </div>
        <div class="translationbox1">
        </div>
        <div class="translationbox2">
        </div>
      </div>
    </div>
    <!-- Content Header (Page header) -->

    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Code List</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right pt-1">

            <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>

              <li class="breadcrumb-item">Purchase code</li>

            </ol>

          </div>

        </div>

      </div><!-- /.container-fluid -->

    </section>

    <section class="content">

      <div class="container-fluid">

        <?php 

        $url_msg = $this->session->flashdata("url_msg");

        if(!empty($url_msg)){

          if($url_msg == "request_code"){

            echo '<div class="alert alert-info">

            Purchase new assessments.

          </div>';

          }

        }?>

      <?php 

        $msg = $this->session->flashdata('msg');

        if($msg !="")

        {

  ?>     

    <div class="alert alert-success">

            <?php echo $msg; ?>

    </div>

    <?php 

}

$email = $user['email'];

//echo "email".$email;die();

// $user = $this->session->userdata('user');

//             echo "<pre>";print_r($user);die;

            

//             $this->db->where('user_id',$user['id']);

//             $this->db->where('payment_type','razorypay');

//             $this->db->select('*');

//             $reseller_details = $this->db->get('paymentcrd');



//             if(isset($reseller_details)){

//                  foreach($reseller_details->result() as $reseller_row)

//                 {

//                     $status = $reseller_row->status;

//                     $id = $reseller_row->id;

//                 }   

//             }

//           $status=isset($status)?$status:'';

           

//           $this->db->where('user_id',$user['id']);

//             $this->db->where('payment_type','stripe');

//             $this->db->select('*');

//             $reseller_detailsstripe = $this->db->get('paymentcrd');



//             if(isset($reseller_detailsstripe)){

//                  foreach($reseller_detailsstripe->result() as $reseller_row1)

//                 {

//                     $statuss = $reseller_row1->status;

//                     $ids = $reseller_row1->id;

//                 }   

//             }

//           $statuss=isset($statuss)?$status:'';

?>

  <div class="row">

          <div class="col-12">

            <!-- <div class="card"> -->

              <!-- <div class="card-header">

                <h3 class="card-title">Code List</h3>

              </div>  -->

              <!-- /.card-header -->

              

              <!-- <div class="card-body">  -->

                          <?php

                            $this->db->where('email',$email);

                            $row = $this->db->get('user_details');

                            // echo "<pre>";

                            // print_r($row->result());die();

                            foreach($row->result() as $row)
                            {
                              $code = $row->user_id;
                            }

                            // $where = "user_id='$code' and email!='$email' and iam='reseller'";

                            $where = "user_id='$code' and email!='$email' and (iam='reseller' or iam='sp')";

                            $this->db->where($where);

                            $row2 = $this->db->get('user_details');

                            // echo "<pre>gbcg";

                            // print_r($row2->result());die();

                            foreach($row2->result() as $row2)
                            {
                              $r_email = $row2->email;
                            }

                            $sl_get = $this->db->get('solutions');
                            //echo $r_email;die();
                            // print_r($sl_get->result());die();
                          ?>

                            <div class="card card-default collapsed-card">

                              <div class="card-header">

                                <h3 class="card-title">

                                  Solution for School students

                                </h3>



                                <div class="card-tools">

                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-plus"></i>

                                  </button>

                                </div>

                              </div>

                              <!-- /.card-header -->



                              <div class="card-body">

                                <div class="table-responsive">

                                  <table class="table">

                                  <?php

                                    foreach($sl_get->result() as $sl)

                                    {                                      

                                        $solution = $sl->solution;

                                        $dsn = $sl->display_solution_name;

                                        $where = "email='$r_email' and solution='$solution'";

                                        $this->db->where($where);

                                        $list_no = $this->db->get('generated_code_details')->num_rows();

                                        

                                        if($list_no>0)

                                        {                                  

                                            if($sl->c_group == "Career Explorer")

                                            {                                

                                            ?>

                                            <tr>

                                              <td>                                               

                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>

                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>

                                              </td>

                                              <td>
                                                <?php $rr = explode(",",$allowed_services);?>
                                                <?php
                                                    // pre( $resellerVariation );
                                                    if( $solution == 'UCE' && !empty( $resellerVariation ) ){
                                                      if( $resellerVariation['uce_variation'] == 'three' && $resellerVariation['uce_report_variation'] == 'three' ){
                                                        ?>
                                                          <a data-toggle="modal" data-remail='<?= $r_email; ?>'  data-solution='<?= $solution; ?>' data-email='<?= $email ?>' data-dsn='<?= $dsn ?>' data-slId='<?= $sl->id ?>' data-offline='user_request_code' data-online='user_request_code_online' id='variantChoose' onclick='variantChoose()' data-target="#exampleModal"  class="btn btn-sm btn-outline-secondary btn-my mb-2" >Choose Variant</a><br>

                                                        <?
                                                      }
                                                      else{
                                                        ?>
                                                          <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                        <?php
                                                            // }
                                                        if(in_array("payment_gateways", $rr)){

                                                              if(!empty($paymentGateway)){ 

                                                                  if(count($paymentGateway) > 0){

                                                                      if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){
                                                          ?>

                                                          <br>

                                                        

                                                          <a  class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                          <?php        

                                                                      }

                                                                  }

                                                              }

                                                        }
                                                      }
                                                    }
                                                    else{
                                                      ?>

                                                        <a style='pointer-events: none;' class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                      <?php    
                                                        // }
                                                        if(in_array("payment_gateways", $rr)){

                                                            if(!empty($paymentGateway)){ 

                                                                if(count($paymentGateway) > 0){

                                                                    if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){

                                                        ?>

                                                        <br>
                                                        <a style='pointer-events: none;' class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                        <?php        

                                                                    }

                                                                }

                                                            }

                                                        }

                                                        ?>
                                                      <?php
                                                    }
                                                ?>
                                               <!--<a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>shared/payments/StripController/stripView">Pay online </a>-->

                                              

                                              </td>

                                              <td class="my-width">                                               

                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>

                                              </td>

                                              

                                            </tr>

                                            <?php                                   

                                            }

                                        }

                                    }

                                  ?>

                                  </table>

                                  </div>

                                <!-- /.card-body -->

                              </div>

                            </div>



                            <div class="card card-default collapsed-card">

                              <div class="card-header">

                                <h3 class="card-title">

                                Solution for College students 

                                </h3>



                                <div class="card-tools">

                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-plus"></i>

                                  </button>

                                </div>

                              </div>

                              <!-- /.card-header -->



                              <div class="card-body">

                              <div class="table-responsive">

                                  <table class="table">

                                  <?php

                                    foreach($sl_get->result() as $sl)

                                    {                                      

                                        $solution = $sl->solution;

                                        $dsn = $sl->display_solution_name;                                

                                        $where = "email='$r_email' and solution='$solution'";

                                        $this->db->where($where);

                                        $list_no = $this->db->get('generated_code_details')->num_rows();

                                        

                                        if($list_no>0)

                                        {                                  

                                            if($sl->c_group == "Career Builder"){                                

                                            ?>

                                            <tr>

                                              <td>                                               

                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>

                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>

                                              </td>

                                              <td>

                                               <?php $rr = explode(",",$allowed_services);?>

                                                <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                <?php    

                                                    // }

                                                    if(in_array("payment_gateways", $rr)){

                                                        if(!empty($paymentGateway)){ 

                                                            if(count($paymentGateway) > 0){

                                                                if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){

                                                    ?>

                                                    <br>

                                                   

                                                    <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                    <?php        

                                                                }

                                                            }

                                                        }

                                                    }

                                                ?>

                                              </td>

                                              <td class="my-width">                                               

                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>

                                              </td>

                                              

                                            </tr>

                                            <?php                                   

                                            }

                                        }

                                    }

                                  ?>

                                  </table>

                                  </div>

                                <!-- /.card-body -->

                              </div>

                            </div>



                            <div class="card card-default collapsed-card">

                              <div class="card-header">

                                <h3 class="card-title">

                                Solution for Professionals

                                </h3>



                                <div class="card-tools">

                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-plus"></i>

                                  </button>

                                </div>

                              </div>

                              <!-- /.card-header -->



                              <div class="card-body">

                              <div class="table-responsive">

                                  <table class="table">

                                  <?php

                                    foreach($sl_get->result() as $sl)

                                    {                                      

                                        $solution = $sl->solution;

                                        $dsn = $sl->display_solution_name;                                

                                        $where = "email='$r_email' and solution='$solution'";

                                        $this->db->where($where);

                                        $list_no = $this->db->get('generated_code_details')->num_rows();

                                        

                                        if($list_no>0)

                                        {                                  

                                            if($sl->c_group == "Career Excellence"){                                

                                            ?>

                                            <tr>

                                              <td>                                               

                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>

                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>

                                              </td>

                                              <td>

                                               <?php $rr = explode(",",$allowed_services);?>

                                                <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                <?php    

                                                    // }

                                                    if(in_array("payment_gateways", $rr)){

                                                        if(!empty($paymentGateway)){ 

                                                            if(count($paymentGateway) > 0){

                                                                if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){

                                                    ?>

                                                    <br>

                                                   

                                                    <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                    <?php        

                                                                }

                                                            }

                                                        }

                                                    }

                                                ?>

                                              </td>

                                              <td class="my-width">                                               

                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>

                                              </td>

                                              

                                            </tr>

                                            <?php                                   

                                            }

                                        }

                                    }

                                  ?>

                                  </table>

                                  </div>

                                <!-- /.card-body -->

                              </div>

                            </div>



                            <div class="card card-default collapsed-card">

                              <div class="card-header">

                                <h3 class="card-title">

                                  Solution for Parents

                                </h3>



                                <div class="card-tools">

                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-plus"></i>

                                  </button>

                                </div>

                              </div>

                              <!-- /.card-header -->



                              <div class="card-body">

                              <div class="table-responsive">

                                  <table class="table">

                                  <?php

                                    foreach($sl_get->result() as $sl)

                                    {                                      

                                        $solution = $sl->solution;

                                        $dsn = $sl->display_solution_name;                                

                                        $where = "email='$r_email' and solution='$solution'";

                                        $this->db->where($where);

                                        $list_no = $this->db->get('generated_code_details')->num_rows();

                                        

                                        if($list_no>0)

                                        {                                  

                                            if($sl->c_group == "Positive Parenting"){                                

                                            ?>

                                            <tr>

                                              <td>                                               

                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>

                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>

                                              </td>

                                              <td>
                                              
                                               <?php $rr = explode(",",$allowed_services);?>

                                                <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                <?php    

                                                    // }

                                                    if(in_array("payment_gateways", $rr)){

                                                        if(!empty($paymentGateway)){ 

                                                            if(count($paymentGateway) > 0){

                                                                if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){

                                                    ?>

                                                    <br>

                                                   

                                                    <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                    <?php        

                                                                }

                                                            }

                                                        }

                                                    }

                                                ?>

                                              </td>

                                              <td class="my-width">                                               

                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>

                                              </td>                                              

                                            </tr>

                                            <?php                                   

                                            }

                                        }

                                    }

                                  ?>

                                  </table>

                                  </div>

                                <!-- /.card-body -->

                              </div>

                            </div>



                            <div class="card card-default collapsed-card">

                              <div class="card-header">

                                <h3 class="card-title">

                                  Overseas Services

                                </h3>



                                <div class="card-tools">

                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-plus"></i>

                                  </button>

                                </div>

                              </div>

                              <!-- /.card-header -->



                              <div class="card-body">

                              <div class="table-responsive">

                                  <table class="table">

                                  <?php

                                //   echo "<pre>";

                                //   print_r($sl_get->result());die();

                                    foreach($sl_get->result() as $sl)

                                    {   //echo "email=".$r_email."sol=".$solution;                                 

                                        $solution = $sl->solution;

                                        $dsn = $sl->display_solution_name;                                

                                        $where = "email='$r_email' and solution='$solution'";

                                        $this->db->where($where);

                                        $list_no = $this->db->get('generated_code_details')->num_rows();

                                        

                                        //echo "list no".$list_no."<br>";

                                        

                                        if($list_no>0)

                                        {                                  

                                            if($sl->c_group == "Overseas Companion"){  //echo "<br>".$sl->c_group."<br>";                              

                                            ?>

                                            <tr>

                                              <td>                                               

                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>

                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>

                                              </td>

                                              <td>

                                               <?php $rr = explode(",",$allowed_services);?>

                                                <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>

                                                <?php    

                                                    // }

                                                    if(in_array("payment_gateways", $rr)){

                                                        if(!empty($paymentGateway)){ 

                                                            if(count($paymentGateway) > 0){

                                                                if($paymentGateway['crd_status'] == 1 && $paymentGateway['payment_type'] == 1){

                                                    ?>

                                                    <br>

                                                   

                                                    <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>

                                                    <?php        

                                                                }

                                                            }

                                                        }

                                                    }

                                                ?>

                                             

                                               <!--<a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>shared/payments/StripController/stripView">Pay online </a>-->

                                              

                                              </td>

                                              <td class="my-width">                                               

                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>

                                              </td>                                              

                                            </tr>

                                            <?php                                   

                                            }

                                        }

                                    } 

                                  ?>

                                  </table>

                                  </div>

                                <!-- /.card-body -->

                              </div>

                            </div>



                           

              <!-- </div> -->

              

              

             



              <!-- /.card-body -->

            <!-- </div> -->

            <!-- /.card -->

          </div>

        </div>

<?php
  
  echo submitForm( 'Select Variant' , $segment , $cluster , $stream );
  echo reportNature( 'Read Information About Report Nature' );
  echo reportType( 'Read Information About Report Type');
  echo clusterDetail();
?>

<script>
    //  $('.loading-overlay').addClass('d-none');
    //  $('.loading-overlay').removeClass('d-none');
    buttonText = '';
    modalfooter = '';
    checkCluster = false
    checkStream = false
    function variantChoose(){
      vChoose = $('#variantChoose')
      modalfooter = $('.modal-footer')
      buttonText = `<a class="btn btn-sm btn-outline-secondary btn-my col-md-5 mr-3" onclick='submitForm("offline")' id='offline' data-url="${vChoose.data('offline')}" data-email="${vChoose.data('email')}" data-remail="${vChoose.data('remail')}"  data-solution="${vChoose.data('solution')}" data-dsn="${vChoose.data('dsn')}">Request Code</a>`
      // <a id='online' onclick='submitForm("online")' class="btn btn-sm btn-outline-success col-md-5 mr-3" data-url="${vChoose.data('online')}" data-email="${vChoose.data('email')}" data-remail="${vChoose.data('remail')}" data-solution="${vChoose.data('solution')}" data-id="${vChoose.data('slid')}">Pay online</a>
    }

    function submitForm(buttonId){
      buttonData = $('#'+buttonId);
      console.log( buttonData );
      $.ajax({
        type : 'POST',
        url : base_url+'baseController/variant_form_submit',
        data : $('#variantForm').serializeArray(),
        dataType : "text",
        success : function(data){
          var response = JSON.parse(data);
          if( response.status == 'success' ){
            if( buttonId == 'offline' ){
              window.location = base_url+'BaseController/'+buttonData.data('url')+'/'+buttonData.data('email')+'/'+buttonData.data('remail')+'/'+buttonData.data('solution')+'/'+buttonData.data('dsn')+'/variation'
            }
            else if( buttonId == 'online' ){
              window.location = base_url+'BaseController/'+buttonData.data('url')+'/'+buttonData.data('email')+'/'+buttonData.data('remail')+'/'+buttonData.data('id')+'/null/variation'
            }
          }    
        }
      });
    }
    function getFilter( searchColumn , selectColumn ){
      var className = $('#class') , report_nature = $('#report_nature') , stream_action = $('#stream_action'), report_type = $('#report_type') , price = $('#price') , reset  = '<option value="">Select</option>';
      if( searchColumn == 'mkt_sgmt' ){
        checkCluster = false
        checkStream = false
        $('.reportNature').addClass('d-none');
        $('.reportType').addClass('d-none');
        className.html(reset);report_nature.html(reset);stream_action.html(reset);report_type.html(reset);price.val(0);
      }
      if( searchColumn == 'class' ){
        checkCluster = false
        checkStream = false
        $('.reportNature').removeClass('d-none');
        $('.reportType').addClass('d-none');
        report_nature.html(reset);stream_action.html(reset);report_type.html(reset);price.val(0);
      }
      if( searchColumn == 'report_nature' ){
        checkCluster = false;
        checkStream = false;
        $('.reportType').addClass('d-none');
        stream_action.html(reset);report_type.html(reset);price.val(0);
      }
      if( searchColumn == 'stream_action' ){
        checkStream = false
        $('.reportType').removeClass('d-none');
        report_type.html(reset);price.val(0);
      }
      if( searchColumn == 'report_type' ){
        price.val(0);
      }
      value = $('#'+searchColumn).val();
      $.ajax({
        type : 'POST',
        url : base_url+'baseController/variant_filter',
        data : { 'searchColumn' : searchColumn  , 'value' : value , 'selectColumn' : selectColumn },
        dataType: "text",
        success : function(data){
          modalfooter.html('');
          var response = JSON.parse(data);
          $('#'+selectColumn).html(response.data);
          if( ( selectColumn == 'stream_action' && response.list == 'no list' && value != 'awareness' ) || ( selectColumn == 'stream_action' && response.list != 'no list' && value == 'suitability') ){
            checkCluster = true
            console.log( 2  , selectColumn , );
            // $('.stream_action').addClass('d-none');
            $('.cluster').removeClass('d-none');
            $('.profession').removeClass('d-none');
          }
          else if( checkCluster === false ){
            console.log(1  , selectColumn);
            $('.cluster').addClass('d-none');
            $('#cluster').val('');
            $('.profession').addClass('d-none');
            $('#profession').html('<option value="">Select</option>');
          }
          if( searchColumn == 'stream_action' && value == 'Open to change existing streams' ||  searchColumn == 'stream_action' && value == 'Continue with existing streams' ){
            checkStream = true;
            $('.currentStream').removeClass('d-none');
            if( searchColumn == 'stream_action' && value == 'Open to change existing streams' ){
              $('.changeStream').removeClass('d-none');
            }
            else{
              $('.changeStream').addClass('d-none');
            }
          }
          else if( checkStream == false ){
            $('.currentStream').addClass('d-none');
            $('#currentStream').val('');
            $('.changeStream').addClass('d-none');
            $('#changeStream').val('');
          }
          if( selectColumn == 'report_nature' || selectColumn == 'report_type' ){
            $('#report_nature_div').html( response.reportNature )
            $('#report_type_div').html( response.reportType )
            // if( selectColumn == 'report_type' && value == 'Open to change existing streams' ){
              // $('#'+selectColumn).html('<option value="">Select</option>');
            // }
          }
          if( selectColumn == 'price' ){
            $('#price').val( response.data.price )
            $('#variantId').val( response.data.variantId )
            modalfooter.html(buttonText)
          }
        }
      });
    }

    function getProfession(  ){
      var cluster = $('#cluster').val();
      $.ajax({
        type : 'POST',
        url : base_url+'baseController/get_profession',
        data : { 'cluster' : cluster },
        dataType: "text",
        success : function(data){
          var response = JSON.parse(data);
          if( response.status == 'success' ){
            $('#profession').html(response.data);
          }
          else{
            $('#profession').html('<select value=""> Select </option>');
          }
        }
      });
    }

    function checkChangeStream(){
      var selectedStream = $("#currentStream").val();
      var changeStreamList = $("#changeStream")[0];
      console.log(changeStreamList.options );
      for( var i=0; i<changeStreamList.options.length; i++ ) {
        if (changeStreamList.options[i].value == selectedStream){
          changeStreamList.remove(i);
        }
      }
      $("#changeStream")[0].options = changeStreamList
      selectMultiple()
    }
    // $('.select2-mutilple').select2();
</script>



        

       

