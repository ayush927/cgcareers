<body class="hold-transition login-page">
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;"><?= isset($edit) ? 'Edit' : 'Add' ?> Service Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("adminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Service  Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
            <!-- Profile Image -->
            <div class="card ">
                <div class="card-header">
                <div class="row">
                  <div class="col-md-4">
                    <h3 class="card-title">Add Service  Details</h3>
                  </div>
                  <div class="col-md-8">
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/service-list' >Service Details List</a>
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
                <form action="<?= base_url() ?>adminController/submit-service-details" method="post" enctype='multipart/form-data'>
                    <?php
                        if(isset($edit))
                        {
                    ?>
                        <input type='hidden' name='id' value='<?= $edit['id']?>'>
                        <input type='hidden' name='service_id' value='<?= $edit['service_id']?>'>
                    <?php
                        }
                        if( isset( $service_id ) ){
                    ?>
                        <input type='hidden' name='service_id' value='<?=$service_id?>'>
                    <?php
                        }
                    ?>
                    
                    <div class="form-group">
                        <label for="s">Select Category</label>
                        <select required class='form-control' name='category'>
                            <option>Section</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="s">Heading Name</label>
                        <input  type="text" placeholder='Add Heading' required class="form-control" id="name" value='<?= isset($edit) ? $edit['name'] : '' ?>'  name="name">
                    </div>
                    <div class="form-group">
                        <label for="s">Service Price</label>
                        <input  type="text" placeholder='Add Price' required class="form-control" id="mrp" value='<?= isset($edit) ? $edit['mrp'] : '' ?>'  name="mrp">
                    </div>
                    <div class="form-group">
                        <label for="services">Details</label>
                        <textarea class="form-control" placeholder='Add Details' id="details" required  name="details" ><?= isset($edit) ? $edit['details'] : '' ?></textarea>
                    </div>
                    <div class="row">
                      <div class="col-8">
                      </div>
                      <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block"><?= isset($edit) ? 'Edit' : 'Add' ?> Service  </button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>