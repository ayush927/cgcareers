<body class="hold-transition login-page">
  <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;"><?= !isset( $edit ) ? 'Add' : 'Edit' ?> Counsellor Detail</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item">
                <a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard </a>
              </li>
              <li class="breadcrumb-item active"><?= !isset( $edit ) ? 'Add' : 'Edit' ?> Counsellor Detail</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <!-- Profile Image -->
            <div class="card ">
              <div class="card-body box-profile"> <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                      $status = $this->session->flashdata('status');
                      echo "<div class='alert alert-".($status != '' ? $status : 'success')."'>$msg</div>";
                    }
                ?> 
                <form action="<?php echo base_url('AdminController/submit_counsellor_detail') ?>" method="post" enctype='multipart/form-data' >
                  <?php
                   if( isset( $edit ) ){
                  ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>" >
                  <?php
                   }
                  ?>
                  <div class="form-group">
                    <label for="cluster_name">First Name</label>
                    <input type="text" class="form-control" required  value="<?= isset( $edit ) ? $edit['first_name'] : set_value('first_name') ?>" id="first_name" name="first_name" placeholder="Enter First Name">
                  </div>
                  <div class="form-group">
                    <label for="cluster_name">Last Name</label>
                    <input type="text" class="form-control" required  value="<?= isset( $edit ) ? $edit['last_name'] : set_value('last_name') ?>" id="last_name" name="last_name" placeholder="Enter Last Name">
                  </div>
                  <div class="form-group">
                    <label for="cluster_name">Email</label>
                    <input type="text" class="form-control" required  value="<?= isset( $edit ) ? $edit['email'] : set_value('email') ?>" id="email" name="email" placeholder="Enter Email">
                  </div>
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">calendly.com/</span>
                      </div>
                      <input type="text" class="form-control" value="<?= isset( $edit ) ? $edit['calendly_link'] : set_value('calendly_link') ?>" id="calendly_link" name="calendly_link" placeholder="Enter Calendly Link" aria-label="Calendly Link" aria-describedby="basic-addon1">
                    </div>
                  <div class="form-group">
                        <label for="services">Upload Image</label>
                        <input type="file" class="form-control" id="image"  name="image" >
                    </div>
                  <div class="form-group">
                    <label for="itroduction">Itroduction</label>
                    <textarea class="form-control" value="<?= isset( $edit ) ? $edit['itroduction'] : set_value('itroduction') ?>" id="itroduction" name="itroduction" rows="3" placeholder="Type here Itroduction"><?= isset( $edit ) ? $edit['itroduction'] : set_value('itroduction') ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="expertise">Expertise</label>
                    <textarea class="form-control"    value="<?= isset( $edit ) ? $edit['expertise'] : set_value('expertise') ?>" id="expertise" name="expertise" rows="3" placeholder="Type here Expertise"><?= isset( $edit ) ? $edit['expertise'] : set_value('expertise') ?></textarea>
                  </div>
                  <div class="row">
                    <div class="col-8"></div>
                    <!-- /.col -->
                    <div class="col-4">
                      <button type="submit" class="btn btn-primary btn-block"><?= !isset( $edit ) ? 'Add' : 'Edit' ?> Counsellor Detail</button>
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
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>