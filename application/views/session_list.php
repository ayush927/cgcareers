<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<div class="content-wrapper bg-white" style="min-height: 706px">
  <!-- Content Header (Page header) -->
  <section
    class="content-header mb-3"
    style="
      padding: 6px 0.5rem;
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    "
  >
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            Vocational Training
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/UserController/dashboard"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">Vocational Training</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>

  <section class="content ">
    <div class="container-fluid">
        <?php view( 'message' ); ?>
        <p class="float-left"><a class="btn btn-sm btn-outline-primary" href="<?= base_url();?>skill-development/live-training-list"> Training List </a></p>
        <p class="text-right"><a <?= $trainingData['sessions'] - count( $list ) <= 0 ? 'style="pointer-events: none"' : '' ?> class="btn btn-sm btn-outline-primary" href="<?php echo base_url();?>skill-development/vocational-training/add-session/<?= base64_encode($id) ?>">Add Session ( Left - <?= $trainingData['sessions'] - count( $list )  ?> )</a></p>
        <!-- main content #start -->
        <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Session ID</th>
                      <th>Sessino Name</th>
                      <th>Start Date Time</th>
                      <th>End Date Time</th>
                      <th>Status</th>
                      <th>Reshceduled</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        if(!empty($list)){
                            foreach($list as $v){
                    ?>                    
                     <tr>  
                        <td><?php echo $v['id'];?></td>
                        <td><p><?= ucwords($v['sessionName']);?></p></td>              
                        <td><p><?= date( 'd M Y H:i A' , strtotime($v['starttime']) );?></p></td>              
                        <td><p><?= date( 'd M Y H:i A' , strtotime($v['endtime']) );?></p></td>              
                        <td class="text-center">
                            <?php
                                if( $v['status'] != 'canceled' && $v['status'] != 'complete'  ){
                                  // echo "Hello";
                            ?>
                                  <a class="btn btn-sm <?= $v['status'] == 'active' ? 'btn-outline-success' : 'btn-outline-warning' ?> m-1" href="<?php echo base_url('skill-development/change-status-session/'.base64_encode($v['id']).'/'.( $v['status'] == 'active' ? 'inactive' : 'active' ));?>"><?= ucwords($v['status']) ?></a><br>
                            <?php
                                  if( $v['status'] == 'active' ){
                            ?>
                                    <a class="btn btn-sm btn-outline-info m-1" href="<?php echo base_url('skill-development/change-status-session/'.base64_encode($v['id']).'/complete');?>">Click To Complete</a><br>
                            <?php
                                  }
                            ?>
                                  <a class="btn btn-sm btn-outline-danger m-1" href="<?php echo base_url('skill-development/change-status-session/'.base64_encode($v['id']).'/canceled');?>"> Canceled </a><br>
                            <?php
                                }
                                else{
                                  // echo "Hello 1";

                            ?>
                                  <a class="btn btn-sm <?= $v['status'] == 'canceled' ? 'btn-danger' : 'btn-success' ?> m-1" href="#"> <?= ucwords($v['status']) ?> </a><br>
                            <?php
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if( $v['status'] != 'canceled' || $v['status'] != 'complete' ){
                                    
                                    if( $v['reshceduled_time'] != null ){
                            ?>
                                        Rescheduled Time : <?=  date( 'd M Y H:i A' , strtotime($v['reshceduled_time']) );  ?> <br>
                                        <a class="btn btn-sm btn-outline-danger m-1" href="<?php echo base_url('skill-development/reset-reschedule-session/'.base64_encode($v['id']));?>">Reset Reschedule</a><br>
                            <?php
                                    }
                                    else{
                            ?>
                                        <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational_training/reschedule-session/'.base64_encode($v['id']));?>">Reschedule</a><br>
                            <?php
                                    }
                                }
                                else{
                            ?>
                                    Session has been <strong><?= ucwords($v['status']) ?></strong>, We Cant Rescheduled
                            <?php
                                }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational_training/edit-session/'.base64_encode($v['id']));?>">Edit Session</a><br>
                            <a class="btn btn-sm btn-outline-info m-1" href="<?php echo base_url('skill-development/vocational_training/copy-session/'.base64_encode($v['id']));?>">copy Session</a><br>
                        </td>
                    </tr> 
                    <?php 
                          }
                        }
                        else{ 
                            echo "<tr><td colspan='7' class='text-center'>Data Not Found.</td></tr>";
                        } 
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        <!-- main content #end -->

    </div>
  </section>
</div>