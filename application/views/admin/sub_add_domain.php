<body class="hold-transition login-page">
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;"><?= $pageTitle ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active"><?= $pageTitle ?></li>
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
                <h3 class="profile-username text-center"><?= $pageTitle ?></h3>
                <?php
                    $msg = $this->session->flashdata('msg');
                    $status = $this->session->flashdata('status');
                    if($msg !="")
                    {
                ?>     
                    <div class="alert alert-<?= $status ?>">
                        <?php echo $msg; ?>
                    </div>
                <?php
                    }
                ?>
                    <form action="<?php echo base_url('skill-development/submit-sub-domain') ?>" method="post">
                        <div class="input-group mb-3">
                             <?php
                                if( !empty( $edit ) ){
                            ?>
                                <input type='hidden' name='id' value='<?= $edit['id'] ?>' >
                            <?php
                                }
                            ?>
                            <select required class='form-control' name='domainId' >
                                <?php
                                    if( !empty( $list ) ){
                                ?>
                                    <option value=''> Select Domain </option>
                                <?php
                                        foreach( $list as $key => $value ){
                                ?>
                                    <option <?= !empty( $edit ) ?  $value['id'] == $edit['domainId'] ? 'selected' : '' : '' ; ?> value='<?= $value['id'] ?>' ><?= $value['domainName'] ?></option>
                                <?php
                                        }
                                ?>
                                <?php
                                    }
                                    else{
                                ?>
                                    <option value='' >Add Domain First</option>
                                <?php
                                    }
                                ?>  
                            </select>
                        </div>
                        <div class="input-group mb-3">      
                          <input type="text" name="subDomainName" class="form-control <?php echo (form_error('subDomainName')!="") ? 'is-invalid' : ''; ?>" placeholder="Enter Sub domain Name" value='<?= !empty( $edit ) ? $edit['subDomainName']  : "" ?>' required >
                        </div>
                        <div class="row">
                          <div class="col-8">
                          </div>
                          <!-- /.col -->
                          <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block"><?= $pageTitle ?></button>
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
  
  