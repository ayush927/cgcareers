<body class="hold-transition login-page">
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;"> Service List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("adminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Service List</li>
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
                <div class="card-header">
                <div class="row">
                  <div class="col-md-3">
                    <h3 class="card-title">Add Service</h3>
                  </div>
                  <div class="col-md-8">
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/service-list' >Service List</a>
                  </div>
                  
                </div>
              </div> 
                <div class="card-body box-profile">
                <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                    
                ?>
                <form action="<?= base_url() ?>adminController/submit-service" method="post" enctype='multipart/form-data'>
                    <?php
                        if(isset($edit))
                        {
                    ?>
                        <input type='hidden' name='id' value='<?=$edit['id']?>'>
                    <?php
                        }
                    ?>
                    <div class="form-group">
                        <label for="services">Service Name</label>
                        <input type="text" class="form-control" id="services" value='<?= isset($edit) ? $edit['services'] : '' ?>'  name="services">
                    </div>
                    <div class="form-group">
                        <label for="services">Upload Image</label>
                        <input type="file" class="form-control" id="services"  name="image" >
                    </div>
                    <!--</div>-->
                    <div class="row">
                      <div class="col-8">
                      </div>
                      <!-- /.col -->
                      <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block"><?= isset($edit) ? 'Edit' : 'Add' ?> Service  </button>
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