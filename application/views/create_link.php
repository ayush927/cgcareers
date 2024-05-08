<body class="hold-transition login-page">



    <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <div class="row mb-2">

            

          <div class="col-sm-6">

            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Share Code</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right pt-1">

              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>

              <li class="breadcrumb-item active">Share Code</li>

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

            <div class="card card-primary card-outline">

                <div class="card-body box-profile">


                    <h3 class="profile-username text-center"></h3>



                    <p class="text-muted text-center">Share Code</p>



              

                    <?php 

                    $msg = $this->session->flashdata('msg');

                    if($msg !="")

                    {

                    ?>     

                    <div class="alert alert-danger">

                        <?php echo $msg; ?>

                    </div>

                    <?php 

                    }

                     $msg2 = $this->session->flashdata('msg2');

                    if($msg2 !="")

                    {

                    ?>

                    <div class="alert alert-success">

                    <?php echo $msg2; ?>

                    </div>

                    <?php 

                    }

                    ?>       

                    <form action="<?= base_url() ?>common-action/generate-link" enctype="multipart/form-data" method="post">

                        

                        <div class="form-group">

                            <label for="exampleInputFile"> Solution</label>

                            <select class='form-control' name='solution' required>
                              <?php
                                if( !empty( $getSolution ) ){
                              ?>
                                <option value='' >Select Solution</option>
                              <?php
                                  foreach ($getSolution as $key => $value) {
                                    ?>
                                      <option value="<?= $value['solution'] ?>"><?= $value['solution'] ?></option>
                                    <?php
                                  }
                                }
                                else{
                              ?>
                                  <option value=''>Purchase code first</option>
                              <?php
                                }
                              ?>
                            </select>

                        </div>
                        <div class="row">
                            <div class="col-8">
                            <!-- blank portion -->
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Save</button>
                            </div>
                            <!-- /.col -->
                        </div>
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