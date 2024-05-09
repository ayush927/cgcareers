<?php

  extract( $config );

  foreach($s->result() as $gmail)

  {

      $email = $gmail->email;

      $p_photo = $gmail->profile_photo;

  }

  $this->db->where('email',$email);

  $record = $this->db->get('sp_profile_detail');

  if( $record->num_rows() > 0 ){

    foreach($record->result() as $record)

    {

      $about_us = $record->about_us;

      $key_services = $record->key_services;

      $address = $record->address;

      $mobo = $record->contact;

      $fb = $record->fb_url;

      $twt = $record->twitter_url;

      $insta = $record->insta_url;

      $linke = $record->linkedin_url;

      $heading1 = $record->heading1;

      $content1 = $record->content1;

      $heading2 = $record->heading2;

      $content2 = $record->content2;

      $heading3 = $record->heading3;

      $content3 = $record->content3;

      $showdata = true;

    }

  }

  else{

      $showdata = false;

      $about_us = "Not Added";

      $key_services = "Not Added";

      $address = "Not Added";

      $mobo = "Not Added";

      $fb = "";

      $twt = "";

      $insta = "";

      $linke = "";

      $heading1 = "";

      $content1 = "";

      $heading2 = "";

      $content2 = "";

      $heading3 = "";

      $content3 = "";

  }

?>

<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SP Page</title>

     <!--Google Font: Source Sans Pro -->

    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->

     <!--Font Awesome -->

    <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">

     <!--icheck bootstrap -->

    <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">

     <!--Theme style -->

    <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>">

    <style>

        .basic-drop-shadow {

            box-shadow: 0 0 10px #e9e3e3;

        }

        .margin-0-auto{

            margin: 0 auto;

        }

        .review-list ul li .left span {

             width: 32px;

             height: 32px;

             display: inline-block;

        }

         .review-list ul li .left {

             flex: none;

             max-width: none;

             margin: 0 10px 0 0;

        }

         .review-list ul li .left span img {

             border-radius: 50%;

        }

         .review-list ul li .right h4 {

             font-size: 16px;

             margin: 0;

             display: flex;

        }

         .review-list ul li .right h4 .gig-rating {

             display: flex;

             align-items: center;

             margin-left: 10px;

             color: #ffbf00;

        }

         .review-list ul li .right h4 .gig-rating svg {

             margin: 0 4px 0 0px;

        }

         .country .country-flag {

             width: 16px;

             height: 16px;

             vertical-align: text-bottom;

             margin: 0 7px 0 0px;

             border: 1px solid #fff;

             border-radius: 50px;

             box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);

        }

         .country .country-name {

             color: #95979d;

             font-size: 13px;

             font-weight: 600;

        }

         .review-list ul li {

             border-bottom: 1px solid #dadbdd;

             padding: 5 10px;

             /*margin: 0 0 30px;*/

            width: 80%;

            margin: 15px auto 10px;

        }

         .review-list ul li .right {

             flex: auto;

        }

         .review-list ul li .review-description {

             margin: 20px 0 0;

             text-align: left;

        }

         .review-list ul li .review-description p {

             font-size: 14px;

             margin: 0;

        }

        .review-list ul li .publish {

            font-size: 13px;

            color: #95979d;

            text-align: right;

        }

        

        .review-section h4 {

             font-size: 20px;

             color: #222325;

             font-weight: 700;

        }

         .review-section .stars-counters tr .stars-filter.fit-button {

             padding: 6px;

             border: none;

             color: #4a73e8;

             text-align: left;

        }

         .review-section .fit-progressbar-bar .fit-progressbar-background {

             position: relative;

             height: 8px;

             background: #efeff0;

             -webkit-box-flex: 1;

             -ms-flex-positive: 1;

             flex-grow: 1;

             box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);

             background-color: #ffffff;

            ;

             border-radius: 999px;

        }

         .review-section .stars-counters tr .star-progress-bar .progress-fill {

             background-color: #ffb33e;

        }

         .review-section .fit-progressbar-bar .progress-fill {

             background: #2cdd9b;

             background-color: rgb(29, 191, 115);

             height: 100%;

             position: absolute;

             left: 0;

             z-index: 1;

             border-radius: 999px;

        }

         .review-section .fit-progressbar-bar {

             display: flex;

             align-items: center;

        }

         .review-section .stars-counters td {

             white-space: nowrap;

        }

         .review-section .stars-counters tr .progress-bar-container {

             width: 100%;

             padding: 0 10px 0 6px;

             margin: auto;

        }

         .ranking h6 {

             font-weight: 600;

             padding-bottom: 16px;

        }

         .ranking li {

             display: flex;

             justify-content: space-between;

             color: #95979d;

             padding-bottom: 8px;

        }

         .review-section .stars-counters td.star-num {

             color: #4a73e8;

        }

         .ranking li>span {

             color: #62646a;

             white-space: nowrap;

             margin-left: 12px;

        }

         .review-section {

             border-bottom: 1px solid #dadbdd;

             padding-bottom: 24px;

             margin-bottom: 34px;

             padding-top: 64px;

        }

         .review-section select, .review-section .select2-container {

             width: 188px !important;

             border-radius: 3px;

        }

        ul, ul li {

            list-style: none;

            margin: 0px;

        }

        .helpful-thumbs, .helpful-thumb {

            display: flex;

            align-items: center;

            font-weight: 700;

        }

        .form-group{
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .viewMore {
            position: absolute;
            right: 0;
            bottom: 0;
            color: #fff;
            background : #fc9928;
            border-color: #fc9928;
        }

        .viewMore:hover{
            color: #fff;
            background : #fc9928;
            border-color: #fc9928;
        }
        .btn-theme {
            position: absolute;
            bottom: 0;
            color: #fff;
            background : #fc9928;
            border-color: #fc9928;
        }

        .btn-theme:hover{
            color: #fff;
            background : #fc9928;
            border-color: #fc9928;
        }

        #product-rating-tab{
            font-size:18px;
        }

        .aboutUs{
            min-height : 100px;
            height: auto;
        }

        .services{
            min-height : 100px;
            height: auto;
        }
    </style>

  </head>

  <body>
        <div class="modal fade" id="afterAboutUs" aria-hidden="true" >
            <div class="modal-dialog" style='max-width:65rem;'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">View Profile Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class='row'>
                            <div class='col-md-12'>
                                <h3>About</h3>
                                <div class="form-group text-justify"> 
                                    <?= $about_us ?>
                                </div>
                            </div> 
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <h4>Key Services</h4>
                                <div class="form-group"> <?php echo $key_services; ?> </div>
                                <div class="mt-2 border-1">
                                </div>
                                <div class="mt-2 product-share">
                                    <?php 
                                        if(!empty($fb)){
                                    ?> 
                                        <a href="<?php echo $fb; ?>" class="text-gray" style="cursor: pointer;">
                                            <i class="fab fa-facebook-square fa-2x"></i>
                                        </a> 
                                    <?php
                                        } 
                                        if(!empty($twt)){
                                    ?> 
                                        <a href="<?php echo $twt; ?>" class="text-gray" style="cursor: pointer;">
                                            <i class="fab fa-twitter-square fa-2x"></i>
                                        </a>
                                    <?php       
                                        }
                                    ?> 
                                    <?php 
                                        if(!empty($insta)){
                                    ?> 
                                        <a href="<?php echo $insta; ?>" class="text-gray" style="cursor: pointer;">
                                            <i class="fab fa-instagram-square fa-2x"></i>
                                        </a>
                                    <?php
                                        } 
                                        if(!empty($linke)){
                                    ?>
                                        <a href="<?php echo $linke; ?>" class="text-gray" style="cursor: pointer;">
                                            <i class="fab fa-linkedin fa-2x"></i>
                                        </a>
                                    <?php       
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <h4>More Detail</h4>
                            </div>
                            <div class='col-md-12 mb-2'>
                                <!-- <div class="form-group">
                                    <b> <?php // echo $heading1; ?> </b>
                                </div> -->
                                <div class="form-group"> <?php echo $content1; ?> </div>
                            </div>
                            <div class='col-md-12 mb-2'>
                                <!-- <div class="form-group">
                                    <b> <?php // echo $heading2; ?> </b>
                                </div> -->
                                <div class="form-group"> <?php echo $content2; ?> </div>
                            </div>
                            <div class='col-md-12 mb-2'>
                                <!-- <div class="form-group">
                                    <b> <?php // echo $heading3; ?> </b>
                                </div> -->
                                <div class="form-group"> <?php echo $content3; ?> </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <div id="modalHomeEvents" class="modal fade" role="dialog">

      <div class="modal-dialog">

        <!-- Modal content-->

        <div class="modal-content">

          <div class="modal-header" style="height:50px;">

            <label>Contact Details</label>

            <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>

          <form action="<?php echo base_url('SpController/sp_detail'); ?>" method="post">

          <div class="modal-body">

            <div class="input-group mb-3">

              <a href="mailto:

                <?php echo $email; ?>" class="btn btn-sm btn-primary">

                <span class="fas fa-envelope"></span>

              </a>

              <b>Email :-</b> <?php echo $email; ?> <br>

            </div>

            <div class="input-group mb-3">

              <a href="tel:

															<?php echo $mobo; ?>" class="btn btn-sm btn-primary">

                <span class="fas fa-phone"></span>

              </a>

              <b>Mobile :- </b> <?php echo $mobo; ?> <br>

            </div>

            <div class="form-group">

              <span class="fas fa-home">

                <b>Address :- </b>

              </span> <?php echo $address; ?>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

          </div>

          </form>

        </div>

      </div>

    </div>

    <!-- Modal content-->

    <div class="content">

        <header id="header" class="header">

            <div class="header-top bg-theme-colored2 sm-text-center">

                <div class="container">

                    <div class="row">

                        <div class="col-md-3 margin-0-auto">

                            <a class="navbar-brand p-0" href="https://respicite.com">

                               <img src="<?= base_url()?>/assets/b-logo.png" alt='Career Counselling'>

                            </a>

                        </div>

                    </div>

                </div>

            </div>            

        </header>

      <!-- Content Header (Page header) -->

      <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">

        <div class="container">

          <div class="row mb-2">

            <div class="col-sm-6">

              <h1 class="m-0 pt-2 font-36" style="font-size: 1.2em;color: #000;"><?= ucwords($fullname) ?></h1>

              <ol class="breadcrumb pt-1">

                <li class="breadcrumb-item">

                  <a href="https://respicite.com">Home</a>

                </li>

                <li class="breadcrumb-item active">Counsellors Profile</li>

              </ol>

            </div>

          </div> 

            <?php 

                $msg = $this->session->flashdata('msg');

                if($msg !="")

                {

                ?> <div class="alert alert-danger"> <?php echo $msg; ?> </div> <?php 

                }

                 $msg2 = $this->session->flashdata('msg2');

                if($msg2 !="")

                {

                ?> <div class="alert alert-success"> <?php echo $msg2; ?> </div> <?php 

                }

            ?>

        </div>

        <!-- /.container-fluid -->

      </section>

      <!-- Main content -->

      <div class="container mt-5">

        <section class="content">

          <!-- Default box -->

            <div class="card card-solid pb-0">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-5 offset-md-1">
                        <!-- <h3 class="d-inline-block d-sm-none">bbcbcxbcbcxbcxbx</h3> -->
                        <div class="col-12">
                            <?php
                                // pre( $profileViewData );
                                if(  isset($profileViewData["fullImage"]) ){
                                    if( $profileViewData["fullImage"] == 0 ){
                                        $img_arr = explode('/' , $p_photo );
                                        // pre( $img_arr );
                                        if( $img_arr[count($img_arr) - 1] == 'default.png' ){
                                            $image = base_url().'uploads/avatar-100-100.jpg';
                                        }
                                        else{
                                            if( file_exists( './uploads/'.$img_arr[count($img_arr) - 1] ) ){
                                                $image = base_url().'uploads/'.$img_arr[count($img_arr) - 1];
                                            }
                                            else{
                                                // echo 2;
                                                $image = LIVE_URL.'uploads/'.$img_arr[count($img_arr) - 1];
                                            // }
                                            // else{
                                            //     $image = base_url().'uploads/counsellor-thumb/avatar-100-100.jpg';
                                            }
                                        }
                                        // $image = base_url().'uploads/avatar.jpg';
                                    }
                                    else{
                                        // echo 2;
                                        $img_arr = explode('/' , $p_photo );
                                        // pre( $img_arr );
                                        if( $img_arr[count($img_arr) - 1] == 'default.png' ){
                                            $image = base_url().'uploads/avatar-100-100.jpg';
                                        }
                                        else{
                                            if( file_exists( './uploads/'.$img_arr[count($img_arr) - 1] ) ){
                                                $image = base_url().'uploads/'.$img_arr[count($img_arr) - 1];
                                            }
                                            else{
                                                // echo 2;
                                                $image = LIVE_URL.'uploads/'.$img_arr[count($img_arr) - 1];
                                            // }
                                            // else{
                                            //     $image = base_url().'uploads/counsellor-thumb/avatar-100-100.jpg';
                                            }
                                        }
                                    }
                                }
                                else{
                                    $img_arr = explode('/' , $p_photo );
                                    // pre( $img_arr );
                                    if( $img_arr[count($img_arr) - 1] == 'default.png' ){
                                        $image = base_url().'uploads/avatar-100-100.jpg';
                                    }
                                    else{
                                        if( file_exists( './uploads/'.$img_arr[count($img_arr) - 1] ) ){
                                            $image = base_url().'uploads/'.$img_arr[count($img_arr) - 1];
                                        }
                                        else{
                                            // echo 2;
                                            $image = LIVE_URL.'uploads/'.$img_arr[count($img_arr) - 1];
                                        // }
                                        // else{
                                        //     $image = base_url().'uploads/avatar-100-100.jpg';
                                        }
                                    }
                                    // $image = 'uploads/avatar.jpg';
                                }
                            ?>
                            <img src="<?php echo $image; ?>" class="product-image mb-1" alt="Profile Image">
                        </div>

                        </div>

                        <div class="col-12 col-sm-6 text-left">
                            <div class="row">
                                <div class="col-md-2"></div>

                                <div class="col-md-2"></div>

                                <div class="col-md-8">
                                    <div class="Stars" style="--rating: 2.3;" aria-label="Rating of this product is 2.3 out of 5."></div>
                                </div>
                            </div>
                            <?php
                                if( str_word_count( $about_us )  > 100){
                                    // echo 1;
                            ?>
                            <div class='row aboutus'>
                                <div class='col-md-12'>
                                    <h3>About</h3>
                                    <div class="form-group text-justify"> 
                                        <?php 
                                            $word_arr = explode( ' ', $about_us );
                                            $str = '';
                                            foreach( $word_arr as $key => $value ){
                                                if( $key <= 99 ){
                                                    if( $key == 0 ){
                                                        $str = $value;
                                                    }
                                                    else{
                                                        $str = $str." ".$value;
                                                    }
                                                }
                                            }
                                            echo $str."..."; 
                                        ?>
                                        <a class="btn viewMore" data-toggle="modal" data-target="#afterAboutUs">View More</a>
                                    </div>
                                </div> 
                            </div>
                            <?php
                                }
                                elseif( str_word_count( $about_us ) + str_word_count( $key_services ) < 100 ){
                            ?>
                                <div class='row aboutus'>
                                    <div class='col-md-12'>
                                        <h3>About</h3>
                                        <div class="form-group"> <?php echo $about_us; ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class='col-md-12 services'>
                                        <h4>Key Services</h4>
                                        <div class="form-group"> <?php echo $key_services; ?> </div>
                                        <div class="mt-2 border-1">
                                        </div>
                                        <div class="mt-2 product-share">
                                            <?php 
                                                if(!empty($fb)){
                                            ?> 
                                                <a href="<?php echo $fb; ?>" class="text-gray" style="cursor: pointer;">
                                                    <i class="fab fa-facebook-square fa-2x"></i>
                                                </a> 
                                            <?php
                                                } 
                                                if(!empty($twt)){
                                            ?> 
                                                <a href="<?php echo $twt; ?>" class="text-gray" style="cursor: pointer;">
                                                    <i class="fab fa-twitter-square fa-2x"></i>
                                                </a>
                                            <?php       
                                                }
                                            ?> 
                                            <?php 
                                                if(!empty($insta)){
                                            ?> 
                                                <a href="<?php echo $insta; ?>" class="text-gray" style="cursor: pointer;">
                                                    <i class="fab fa-instagram-square fa-2x"></i>
                                                </a>
                                            <?php
                                                } 
                                                if(!empty($linke)){
                                            ?>
                                                <a href="<?php echo $linke; ?>" class="text-gray" style="cursor: pointer;">
                                                    <i class="fab fa-linkedin fa-2x"></i>
                                                </a>
                                            <?php       
                                                }
                                            ?><br>
                                            <a class="btn viewMore" data-toggle="modal" data-target="#afterAboutUs" aria-selected="false">View More</a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                                elseif( str_word_count( $about_us ) + str_word_count( $key_services ) > 100 ){
                            ?>
                                <div class='row h-100'>
                                    <div class='col-md-12 h-50'>
                                        <h3>About</h3>
                                        <div class="form-group"> <?php echo $about_us; ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class='col-md-12 h-50'>
                                        <h4>Key Services</h4>
                                        <div class="form-group"> 
                                            <?php substr($key_services , 0 , (100 - str_word_count( $about_us ))); ?> </div>
                                        <a class="btn viewMore" data-toggle="modal" data-target="#afterAboutUs" aria-selected="false">View More</a>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                            
                        </div>
                    </div>
                

                    <div class="row mt-4">

                        <nav class="w-100">

                        <div class="nav nav-tabs" id="product-tab" role="tablist">

                            <!-- <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Services</a> -->

                            <!-- <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Reviews</a> -->

                            <a class="nav-item nav-link active" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false"><b>Respicite Career & Parenting Platform</b></a>

                        </div>

                        </nav>

                        <div class="tab-content w-100 p-0" id="nav-tabContent">

                            <div class="tab-pane show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab"> 

                                    <?php 
                                        if($showdata != true ){
                                        $i = 1;
                                        foreach($l->result() as $row)  
                                        {
                                    ?> 
                                            <div class="col-sm-12">
                                                <div class="card card-solid">
                                                    <div class="card-body pb-0">
                                                        <div class="form-group">
                                                            <?php
                                                                $l1 = $row->l1;

                                                                $this->db->where('id',$l1);

                                                                $l1_d = $this->db->get('provider_level_one');

                                                                foreach($l1_d->result() as $l1_d)

                                                                {

                                                                echo $l1_d->l1;

                                                                echo "->"; 

                                                                }

                                                                $l2 = $row->l2;

                                                            

                                                                $this->db->where('id',$l2);

                                                                $l2_d = $this->db->get('provider_level_two');

                                                                foreach($l2_d->result() as $l2_d)

                                                                {

                                                                    echo $l2_d->l2;

                                                                    echo "->"; 

                                                                }

                                                                $l3 = $row->l3_id;

                                                                $this->db->where('id',$l3);

                                                                $l3_d = $this->db->get('provider_level_three');

                                                                foreach($l3_d->result() as $l3_d)

                                                                {

                                                                    echo $l3_d->l3;

                                                                }

                                                                $where = "l1='$l1' and l2='$l2' and l3_id='$l3'";

                                                                $this->db->where($where);

                                                                $para = $this->db->get('provider_level_four');

                                                                foreach($para->result() as $para)

                                                                {

                                                                    $para1 = $para->param_one;

                                                                    $para2 = $para->param_two;

                                                                    $para3 = $para->param_three;

                                                                    $para4 = $para->param_four;

                                                                }

                                                                echo "<br>";

                                                                echo $para1;

                                                                echo "(".$row->p1.")";

                                                                echo ", ";

                                                                echo $para2;

                                                                echo " (".$row->p2.")";

                                                                echo ", ";

                                                                echo $para3;

                                                                echo " (".$row->p3.")";

                                                                echo ", ";

                                                                echo $para4;

                                                                echo " (".$row->p4.")";

                                                                echo "<br>";

                                                                echo "<div align='right' class='text-primary'><b>Price:- $row->price</b></div>";

                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                    <?php
                                            $i++;
                                            }
                                        }
                                    ?> 
                            </div>

                            <div class="tab-pane show active text-left" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <div class='row p-3'>
                                    <?php
                                        foreach ($dataPreview as $key => $value) {
                                    ?>
                                        <div class='col-md-4 mt-2 p-2 basic-drop-shadow border-radius'>
                                            <div class="form-group text-center">
                                                <b > <?= ucfirst($value['heading']) ?>  </b>
                                            </div>
                                            <div class="form-group text-justify"> <?= ucfirst($value['p']); ?> </div>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>

                        <div class="container">
                            <div id="reviews" class="review-section pl-5">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h4 class="m-0"><?= $total_rating['total'] ?> Reviews</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="stars-counters">
                                            <tbody>
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <button class="fit-button fit-button-color-blue fit-button-fill-ghost fit-button-size-medium stars-filter">5 Stars</button>
                                                        </span>
                                                    </td>
                                                    <td class="progress-bar-container">
                                                        <div class="fit-progressbar fit-progressbar-bar star-progress-bar">
                                                            <div class="fit-progressbar-background">
                                                                <span class="progress-fill" style="width: 97.2973%;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="star-num">(<?= $ratingData[0]['total_review'] ?>)</td>
                                                </tr>
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <button class="fit-button fit-button-color-blue fit-button-fill-ghost fit-button-size-medium stars-filter">4 Stars</button>
                                                        </span>
                                                    </td>
                                                    <td class="progress-bar-container">
                                                        <div class="fit-progressbar fit-progressbar-bar star-progress-bar">
                                                            <div class="fit-progressbar-background">
                                                                <span class="progress-fill" style="width: 2.2973%;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="star-num">(<?= $ratingData[1]['total_review'] ?>)</td>
                                                </tr>
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <button class="fit-button fit-button-color-blue fit-button-fill-ghost fit-button-size-medium stars-filter">3 Stars</button>
                                                        </span>
                                                    </td>
                                                    <td class="progress-bar-container">
                                                        <div class="fit-progressbar fit-progressbar-bar star-progress-bar">
                                                            <div class="fit-progressbar-background">
                                                                <span class="progress-fill" style="width: 0;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="star-num">(<?= $ratingData[2]['total_review'] ?>)</td>
                                                </tr>
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <button class="fit-button fit-button-color-blue fit-button-fill-ghost fit-button-size-medium stars-filter">2 Stars</button>
                                                        </span>
                                                    </td>
                                                    <td class="progress-bar-container">
                                                        <div class="fit-progressbar fit-progressbar-bar star-progress-bar">
                                                            <div class="fit-progressbar-background">
                                                                <span class="progress-fill" style="width: 0;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="star-num">(<?= $ratingData[3]['total_review'] ?>)</td>
                                                </tr>
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <button class="fit-button fit-button-color-blue fit-button-fill-ghost fit-button-size-medium stars-filter">1 Stars</button>
                                                        </span>
                                                    </td>
                                                    <td class="progress-bar-container">
                                                        <div class="fit-progressbar fit-progressbar-bar star-progress-bar">
                                                            <div class="fit-progressbar-background">
                                                                <span class="progress-fill" style="width: 0;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="star-num">(<?= $ratingData[4]['total_review'] ?>)</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="review-list">
                                <ul>
                                    <?php
                                        if( !empty( $reviewData ) ){
                                        foreach( $reviewData as $key => $value ){
                                    ?>        
                                    <li>
                                        <div class="d-flex">
                                            <div class="left">
                                                <span>
                                                    <img src="<?= base_url() ?><?= $value['profile_photo'] ?>" class="profile-pict-img img-fluid" alt="" />
                                                </span>
                                            </div>
                                            <div class="right">
                                                <h4>
                                                    <?= ucwords( $value['fullname'] ) ?>
                                                    <span class="gig-rating text-body-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1792 1792" width="15" height="15">
                                                            <path
                                                                fill="currentColor"
                                                                d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"
                                                            ></path>
                                                        </svg>
                                                        <?= $value['rating'] ?>
                                                    </span>
                                                </h4>
                                                <div class="review-description">
                                                    <p><?= ucfirst( $value['review'] ) ?></p>
                                                </div>
                                                <span class="publish py-3 d-inline-block w-100"><?= date('d M Y' , strtotime($value['created_date'])) ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
          <!-- /.card -->
        </section>
        <!-- /.content -->
      </div>
    </div>
    <!-- jQuery -->

    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>">

    </script>

    <!-- Bootstrap 4 -->

    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>">

    </script>

    <!-- AdminLTE App -->

    <script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>">

    </script>

  </body>

<!--</html>-->