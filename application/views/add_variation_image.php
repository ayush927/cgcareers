<body class="hold-transition login-page">
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Variation Image</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("UserController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Variation Image</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
              <!-- Profile Image -->
              <div class="card ">
                <div class="card-body box-profile">
                <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                    // pre( $_GET , 1 );
                    if(isset( $_GET['solution_name' ])){
                      $url = base_url().'UserController/update_variation_image';
                      $method = 'post';
                    }
                    else{
                      $url = '';
                      $method = 'get';
                    }
                ?>
                <form action="<?= $url ?>" method="<?= $method ?>" enctype='multipart/form-data'>
                    <?php
                        if(isset($edit)){
                    ?>
                        <input type='hidden' name='id' value='<?= $edit['id'] ?>' >
                    <?php
                        }
                    ?>
                    <div class="form-group">
                        <label for="cluster_path">Select Solution</label>
                        <select class="form-control" name="solution_name" required autocomplete="off" readonly>
                            <option value=''>Select Solution</option>
                            <?php
                                if( !empty($getsolutions) ){
                                    foreach( $getsolutions as $key => $value ){
                            ?>
                                    <option value='<?= $value['solution_name']?>' <?= isset($edit) ? ( $edit['solution_name'] == $value['solution_name'] ? 'selected' : '' ) : ( isset( $_GET['solution_name' ] ) ? ( $_GET['solution_name' ] == $value['solution_name'] ? 'selected' : '' ) : '' ) ?> > <?= ucwords($value['solution_name']) ?> </option>
                            <?php
                                    }                                    
                                }
                            ?>
                        </select>
                        <?php
                          if( !isset( $_GET['solution_name'] ) ){
                        ?>
                          <div class="row mt-4">
                            <div class="col-8">
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                              <button type="submit" class="btn btn-primary btn-block"> Get Class </button>
                            </div>
                            <!-- /.col -->
                          </div>
                        <?php
                          }
                          else{
                        ?>
                            <div class="form-group mt-4">
                              <label for="cluster_path">Select Class Variant</label>
                              <select class="form-control" name="variationId" required autocomplete="off" readonly>
                                <option value=''>Select Class</option>
                                <?php
                                    if( !empty($getClass) ){
                                        foreach( $getClass as $key => $value ){
                                ?>
                                        <option value='<?= $value['id']?>' <?= isset($edit) ? ( $edit['id'] == $value['id'] ? 'selected' : '' ) : '' ?> > 
                                          <?= $value['value_par_1'] == 'na' ? ucwords(str_replace('_' , ' ' , $value['variant_name'])) : ucwords(str_replace('_' , ' ' , $value['value_par_1'])) ?> <?= $value['par_2'] == 'stream_change' && $value['value_par_2'] == 'yes' ? 'With Stream Change' : '' ?> </option>
                                <?php
                                        }                         
                                    }
                                ?>
                              </select>
                            </div>

                            <div class="form-group mt-4">
                              <label for="career_path">Upload Image</label>
                              <input type="file" class="form-control"  name="image">
                            </div>
                        <?php
                            if( !isset( $_GET['class'] ) ){
                              ?>
                                <div class="row mt-4">
                                  <div class="col-8">
                                  </div>
                                  <!-- /.col -->
                                  <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Submit Image</button>
                                  </div>
                                  <!-- /.col -->
                                </div>
                              <?php
                            }
                          }
                        ?>
                    </div>
                    <!-- <div class="form-group">
                        <label for="career_path">Career Path</label>
                        <input type="text" class="form-control" id="career_path" value='<?= isset($edit) ? $edit['career_path'] : '' ?>'  name="career_path">
                    </div> -->
    
                    <!--<div class="form-group">-->
                    <!--    <label for="cluster_description">Cluster Description</label>-->
                    <!--    <textarea class="form-control" id="cluster_description" name="cluster_description" rows="3" placeholder="Type here..."></textarea>-->
                    <!--</div>-->
    
                    <!--<div class="form-group">-->
                    <!--    <label for="sourceName">Source Name</label>-->
                    <!--    <input type="text" class="form-control" id="sourceName" value='<?= isset($edit) ? $edit['sourceName'] : '' ?>' name="sourceName">-->
                    <!--</div>-->
                </form>
                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
           
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  