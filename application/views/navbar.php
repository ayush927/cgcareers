<?php 



$webpage_title='Respicite';



// $a = $this->config->item('wl_list');



$is_active['mot_navbar'] = false;

if($user['user_id'] == 506){

  $is_active['mot_navbar'] = true;

  $webpage_title = "Marketing Optimization";

}//MOT user

$menu['mot_navbar']='';

if($is_active['mot_navbar']){$menu['mot_navbar'] = $mot_navbar;}



// echo "<pre>";

// print_r($menu);

// echo "</pre>";

// die;



?>



<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo $webpage_title;?></title>

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

	<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> -->

  <!-- Google Font: Source Sans Pro -->
  <link href="<?= base_url() ?>assets/logo_36.png" rel="shortcut icon" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">

  <!-- Ionicons -->

  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->

  <!-- Tempusdominus Bootstrap 4 -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'; ?>">

  <!-- iCheck -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">

  <!-- JQVMap -->

  <!-- <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/jqvmap/jqvmap.min.css'; ?>"> -->

  <!-- Theme style -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/dist/css/adminlte.min.css'; ?>">

  <!-- overlayScrollbars -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'; ?>">

  <!-- Daterange picker -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker.css'; ?>">

  <!-- summernote -->

  <!-- <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/summernote/summernote-bs4.min.css'; ?>"> -->

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/fontawesome-free/css/all.min.css'; ?>">

  <!-- icheck bootstrap -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">

  <!-- Theme style -->

  <link rel="stylesheet" href="<?php echo base_url().'assets/dist/css/adminlte.min.css'; ?>">

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'; ?>">

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'; ?>">

  <link rel="stylesheet" href="<?php echo base_url().'assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'; ?>">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  

</head>

<body class="hold-transition <?=  $this->User_model->authorized()==true ? 'sidebar-mini' : 'sidebar-collapse' ?> layout-fixed">

<div class="wrapper">



  <!-- Preloader -->

  <!--<div class="preloader flex-column justify-content-center align-items-center">-->

  <!--  <img class="animation__shake" src="<?php echo base_url().'assets/dist/img/loader.gif';?>" alt="AdminLTELogo" height="60">-->

  <!--</div>-->



  <!-- Navbar -->

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->

    <ul class="navbar-nav">

      <li class="nav-item">

        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>

      </li>

      <li class="nav-item d-none d-sm-inline-block">

      <div class="nav-link">Welcome &nbsp;<?php echo $user['fullname']; ?>

      </li>

      <?php

      if($is_active['mot_navbar'])

      {

        if(!empty($menu['mot_navbar']))

        {

          foreach($menu['mot_navbar'] as $v)

          {

            if($v->parent_id == 0)

            {

              ?>

              <li class="nav-item d-none d-sm-inline-block">

              <a href="<?php echo base_url().'BaseController/dashboard'; ?>" class="nav-link"><?php echo $v->name;?></a>

              </li>

              

              <?php

            }

  

          }



        }



      }



      ?>

     <?php if(!empty($this->session->user['code'])) { ?>  

           <li class="nav-item d-none d-sm-inline-block">

          <a href="<?php echo base_url().'BaseController/logout'; ?>" class="nav-link">Logout</a>

          </li>

      <?php }else{ ?>

          <li class="nav-item d-none d-sm-inline-block">

          <a href="<?php echo base_url().'UserController/logout'; ?>" class="nav-link">Logout</a>

          </li>

      <?php } ?>

    </ul>



    <!-- Right navbar links -->

    <ul class="navbar-nav ml-auto">

      <!-- Navbar Search -->

      <li class="nav-item">

        <!-- <a class="nav-link" href="#" role="button">

        <b>CG Career</b>

        </a> -->

        <!-- <div class="navbar-search-block">

          <form class="form-inline">

            <div class="input-group input-group-sm">

              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">

              <div class="input-group-append">

                <button class="btn btn-navbar" type="submit">

                  Welcome

                </button>

                <button class="btn btn-navbar" type="button" data-widget="navbar-search">

                  <i class="fas fa-times"></i>

                </button>

              </div>

            </div>

          </form>

        </div> -->

      </li>



      <!-- Messages Dropdown Menu -->

      

       <?php if(!empty($this->session->user['code'])) { ?>  

            <li class="nav-item">

        <a class="nav-link" href="<?php echo base_url().'BaseController/logout'; ?>" role="button">

          <i class="fas fa-sign-out-alt"></i>

        </a>

      </li>

      <?php }else{ ?>

           <li class="nav-item">

        <a class="nav-link" href="<?php echo base_url().'UserController/logout'; ?>" role="button">

          <i class="fas fa-sign-out-alt"></i>

        </a>

      </li>

      <?php } ?>
    </ul>
  </nav>
  <?php
    if( isset( $_SESSION['profileError'] ) ){
      // pre( $_SESSION['profileError'] );
  ?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <div class="card w-100">
        <div class="card-header" id="headingOne">
          <h4 class="mb-0">
            <button id='messageModalbutton' style='font-size:1.5rem;' class="btn text-danger btn-link btn-block text-left font-30" type="button" >
              <b> Profile incomplete - Please click to know details. </b>
            </button>
          </h4>
        </div>
      </div>
    </nav>
    <div class="modal fade" id="messageModal" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Profile Incomplete</h4>
                <button type="button" class="close messageclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
              <?php
                  foreach ($_SESSION['profileError'] as $key => $value) {
              ?>
                  <div class="alert alert-danger" role="alert">
                    <?= $key  ?> : <?= $value  ?>
                  </div>
              <?php
                  }
              ?>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default messageclose" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
<?php
    }
  ?>

  <!-- /.navbar -->



  