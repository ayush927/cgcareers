<style>

  a{color:black}

  

  .profile-box img{

    width: 8rem;

    height: 6.5rem;

    object-fit: contain;

    margin: 10px;

  }



  .border-round{

    border:1px solid #fc9928;

  }



  .list-group-item {

    border:1px solid #fc9928;    

  }



  .list-group-unbordered>.list-group-item{

    padding-left: 7px;

    padding-right: 7px;

  }

</style>

<body class="hold-transition login-page">



    <div class="content-wrapper bg-white">

    <!-- Content Header (Page header) -->

    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <div class="row mb-2">

            

          <div class="col-sm-6">

            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Training Status</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right pt-1">

              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>

              <li class="breadcrumb-item ">Training Status</li>

            </ol>

          </div>

        </div>

      </div><!-- /.container-fluid -->

    </section>



    <!-- Main content -->

    <section class="content">
      <div class="container-fluid">
        <div class="table-responsive">

          <? view('message') ?>
          <!-- <table class="table table-bordered">

            <thead>

              <tr>

                <td>Trainer Name</td>

                <td>Training Title</td>

                <td>Apply Date</td>

                <td>Apply Status</td>

              </tr>

            </thead>

            <tbody>

              <?php if(!empty($apply_training)){ foreach($apply_training as $v){?>

              <tr>

                <td><?php echo $v["fullname"];?></td>

                <td style="width:50%"><p class="m-0 p-0"><?php echo $v["training_name"];?></p></td>

                <td><?php echo $v["apply_date"];?></td>

                <td>

                <?php 

                  if($v['training_status'] == "approval_pending"){

                    echo "<p class='text-info m-0 p-0'>Approval Pending</p>";

                  }elseif($v['training_status'] == "approved"){

                    echo "<p class='text-success m-0 p-0'>approved</p>";

                  }elseif($v['training_status'] == "completed"){

                    echo "<p class='text-success m-0 p-0'>Completed</p>";

                  }

                ?>

                </td>

              </tr>

              <?php } }else{

                echo "<tr><td colspan='4' class='text-center'>Data Not Found.</td></tr>";

              } ?>

            </tbody>

          </table> -->

          <table class="table table-bordered table-condensed table-striped">
              <thead>
                  <tr>
                    <th>View</th>
                    <th>Trainer Id</th>
                    <th>Trainer Name</th>
                    <th>Start Date</th>
                    <th>Apply Date</th>
                    <th>Apply Status</th>
                    <th>Action</th>
                  </tr>
              </thead>

              <tbody>
                <?php
                    // pre(  $trainingDetail , 1);
                    if( !empty($trainingDetail) ){
                      $index = 0;
                      foreach ($trainingDetail as $key => $training){
                ?>
                
                    <tr data-toggle="collapse" data-target="#demo<?= $index ?>" class="accordion-toggle">
                      <td><button class="btn btn-default btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></span></button></td>
                      <td><?= ++$index ?></td>
                      <td><?= substr( ucwords( $training['training_name']) , 0 , 50  ).".." ?></td>
                      <td><?= date('d M Y' ,  strtotime( $training['start_date'] ) ) ?></td>
                      <td><?= date('d M Y' ,  strtotime( $training['apply_date'] ) ) ?></td>
                      <td> <button class='btn btn-<?= $training['training_status'] == 'approval_pending' ? 'warning' : ( $training['training_status'] == 'approved'  ? 'info' : ( $training['training_status'] == 'completed' ? 'success' : ( $training['training_status'] == 'partially_complete' ? 'warning' : ( $training['training_status'] == 'incomplete' ? 'danger' : 'default' )) )) ?>' >  <?= ucwords(str_replace( '_' , ' ' , $training['training_status'])) ?> </button></td>
                      <td>
                        <?php
                          $sessionStatus = getQuery( [ 'where' => [ 'trainingId' => $training['id'] ] , 'table' => 'vocational_session_user_status' ] );
                          $sessionList = getQuery( [ 'select' => 'count(id) as total' , 'where' => [ 'trainingId' => $training['id'] , 'status !=' => 'inactive' , 'status !=' => 'canceled' ] , 'table' => 'vocational_live_session', 'single' => true ] );
                          // pre( [ $sessionStatus , $sessionList ] );
                          if( count( $sessionStatus ) == $sessionList['total'] ){
                        ?>
                          <a class='btn btn-info' href='<?= base_url() ?>baseController/update-rating-review/<?= $training['id'] ?>' >Rating and Review</a><br>
                        <?php
                          }
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <?php
                        if( !empty( $sessionDetail ) ){
                      ?>
                        <td colspan="12" class="hiddenRow">
                            <div class="accordian-body collapse" id="demo<?= $index - 1 ?>"> 
                              <table class="table table-striped">
                                <thead>
                                  <tr class="info">
                                    <th>Id</th>
                                    <th>Session Name</th>
                                    <th>Start Date</th>		
                                    <th>Status</th>	
                                    <th>Action</th>	
                                  </tr>
                                </thead>	
                                <tbody>
                                  <?php
                                      $sindex = 0;
                                      foreach( $sessionDetail as $key => $session ){
                                        if( $session['trainingId'] == $training['id'] ){
                                          $sessionStatusDetail = getQuery( [ 'where' => [ 'sessionId' => $session['id'] ] , 'table' => 'vocational_session_user_status' , 'single' => true  ] );
                                          if( !empty( $sessionStatusDetail ) ){
                                            $status = $sessionStatusDetail['status'];
                                          }
                                  ?>
                                      <tr>
                                        <td> <?= ++$sindex ?> </td>
                                        <td> <?= substr( ucwords($session['sessionName']),  0 , 30 ).".." ?> </td>
                                        <td> <?= date( 'd M Y H:i:s' , strtotime($session['starttime']) ) ?> </td>
                                        <td> 
                                          <?php
                                            if( !isset( $status ) ){
                                          ?>
                                            <button class='btn btn-<?= $session['status'] == 'active' ? 'info' : ( $session['status'] == 'complete' ? 'success' : ( $session['status'] == 'canceled' ? 'danger' : ( $session['status'] == 'inactive' ? 'warning' : 'default' ) ) ) ?>' >  <?= isset( $status ) ? ucwords( $status ) : ucwords($session['status']) ?> </button></td>
                                          <?php
                                            }
                                            else{
                                          ?>
                                            <button class='btn btn-<?= isset( $status ) ? ( $status == 'absent' ? 'warning' : ( $status == 'present' ? 'info' : ( $status == 'completed' ? 'success' : 'default' ) ) )  : '' ?>' >  <?= isset( $status ) ? ucwords( $status ) : ucwords($session['status']) ?> </button></td>
                                          <?php
                                            }
                                          ?>
                                        <td>
                                          <select data-id='<?= $session['id'] ?>' class='form-control <?= $session['status'] == 'complete' ? 'updateStatus' : '' ?>' <?= $session['status'] != 'complete' ? 'disabled' : '' ?>>
                                            <option value=''>Update Session Status</option>
                                            <option <?= isset( $status ) ? ($status == 'absent' ? 'selected' : '') : '' ?>  value='absent'>Absent</option>
                                            <option <?= isset( $status ) ? ($status == 'present' ? 'selected' : '') : '' ?> value='present' >Present ( Not Completed ) </option>
                                            <option <?= isset( $status ) ? ($status == 'completed' ? 'selected' : '') : '' ?> value='completed' >Completed</option>
                                          </select>
                                        </td>
                                      </tr>
                                  <?php
                                        if( isset( $status ) ){
                                            unset( $status );
                                        }
                                      }
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </div> 
                        </td>
                      <?php
                        }
                      ?>
                    </tr>
                <?php
                      }
                    }
                ?>
              </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- /.content -->

  </div>

  <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
  <script>
      var base_url = '<?= base_url() ?>'
      $('.updateStatus').change(function(){
          var sessionId = $(this).attr("data-id");
          var status = $(this).val();
          if( status != '' ){
            window.location = base_url+`baseController/update-session-status/${sessionId}/${status}`
          }
      });
  </script>

  

  