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

    <!-- approval certification modal #start.-->

    <div class="modal fade" id="modal-approval-certification" aria-hidden="true">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header">

            <h4 class="modal-title">Upload Certification Content</h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">×</span>

            </button>

          </div>      

          <div class="modal-body">

          <div class="file-error p-2 text-danger"></div>

            <div class="form-group">

              <div class="input-group">

                <div class="custom-file">

                  <form action="" method="post" id="form_cerfifcation_requrest" enctype="multipart/form-data">

                    <label for="content_file">Upload Training Content, The only zip file, and file size should be 2MB.</label>

                    <input type="file" name="file_content" class="custom-file-input" id="content_file">

                    <label class="custom-file-label" for="content_file">Choose file </label>

                    <div class="set-sp-info"></div>

                  </form>

                </div>

              </div>

            </div>

          </div>

          <div class="modal-footer justify-content-between">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <button type="submit" form="form_cerfifcation_requrest" class="btn btn-primary btn-disable-req">Upload</button>

          </div>

        </div>

      </div>

    </div>

    <!-- approval certification modal #end.-->



  <section class="content ">

    <div class="container-fluid"> 

        <?php view( 'message' ); ?>

        <p class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?php echo base_url();?>skill-development/vocational_training/detail">Add New Live Training</a></p>

        <!-- main content #start -->

        <div class="card">

              

              <div class="card-body table-responsive p-0">

                <table class="table table-bordered text-nowrap">

                  <thead>

                    <tr>

                      <th>Training ID</th>

                      <th>Training Name</th>

                      <th>Price/Discount</th>

                      <th>Session Detail</th>

                      <th>Student Detail</th>

                      <th class="text-center">Edit Action</th>

                      <th class="text-center">Training Approval Status</th>

                      <th class="text-center">Participants Details</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if(!empty($list)){ 

                        // pre( $list , 1 );

                        foreach($list as $v){

                    ?>                    

                 <tr>  

                    <td><?php echo $v['id'];?></td>

                    <td style="width:25%;white-space: break-spaces;"><p class="m-0 p-0"><?= ucwords($v['training_name']);?></p></td>              

                    <td><p class="m-0 p-0"> Price <?= $v['discount'] != '' ? '<strike>'.$v['price'].'</strike><br> Discounted price : '.($v['price'] - round($v['price']*$v['discount']/100)) : $v['price'] ?>  Rs</p></td>              

                    <td>

                        Session Type : <?= $v['sessionType'] ?><br>

                        No of Session : <?= $v['sessions'] ?>

                    </td>

                    <td>

                        Post Type : <?= $v['postType'] ?><br>

                        No of Session : <?= $v['sessions'] ?>

                    </td>

                    <td class="text-center">

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/detail/'.base64_encode($v['id']));?>">Edit Details</a>

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/meta-data/'.base64_encode($v['id']));?>">Meta Data</a><br>

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/session-schedule/'.base64_encode($v['id']));?>">Session</a>

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/curriculum-list/'.base64_encode($v['id']));?>">Curriculam</a>

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/faq-list/'.base64_encode($v['id']));?>"> FAQ </a><br>

                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('skill-development/vocational-training/recommended-training/'.base64_encode($v['id']));?>"> Recommend Training </a>

                    </td>

                    <td class="text-center">

                      <?php

                            if( $v['status'] != 'canceled' || $v['status'] != 'complete'  ){
                              $sessions = getQuery( [ 'where' => [ 'trainingId' => $v['id'] , 'status' => 'active' ], 'or_where' => [  'status' => 'completed' ] , 'table' => 'vocational_live_session' ] );
                              $curriculum = getQuery( [ 'where' => [ 'trainingId' => $v['id'] ] , 'table' => 'sd_curriculum' ] );
                              if( count( $sessions ) == $v['sessions'] &&  count($curriculum) > 0 ){
                      ?>

                                <a class="btn btn-sm <?= $v['status'] == 'active' ? 'btn-outline-info' : 'btn-outline-warning' ?> m-1" href="<?php echo base_url('skill-development/change-status-training/'.base64_encode($v['id']).'/'.( $v['status'] == 'active' ? 'inactive' : 'active' ));?>">Click To <?= $v['status'] == 'active' ? 'Inactive' : 'Active' ?></a><br>
                                <a class="btn btn-sm btn-outline-success m-1" href="<?php echo base_url('skill-development/change-status-training/'.base64_encode($v['id']).'/completed');?>"> Click To Complete </a><br>
                      <?php
                              }
                      ?>

                                <a class="btn btn-sm btn-outline-danger m-1" href="<?php echo base_url('skill-development/change-status-training/'.base64_encode($v['id']).'/canceled');?>"> Click to Cancel </a><br>


                            <?php

                                }

                                else{

                            ?>

                             <a class="btn btn-sm <?= $v['status'] == 'canceled' ? 'btn-danger' : 'btn-success' ?> m-1" href="#"> <?= $v['status'] ?> </a><br>

                        <?php

                            }

                        ?>

                    </td>

                    <td>

                      <a href="<?php echo base_url('skill-development/participant_details/'.$v['id'])?>" class='btn btn-sm btn-outline-info'>Click Here</a> 

                    </td>

                </tr> 

                <?php 

                        } 

                    }

                    else { 

                        echo "<tr><td colspan='6' class='text-center'>Data Not Found.</td></tr>";

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