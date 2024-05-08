// <?php //echo "<pre>";print_r($landingPages);
// print_r($h->result_array());die;
// ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Counsellor Services List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Counsellor Services List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
        <?php 
            $msg = $this->session->flashdata('msg');
            if($msg !="")
            {
        ?>     
            <div class="alert alert-success">
                <?php echo $msg; ?>
            </div>
        <?php 
            }
        ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-3">
                    <h3 class="card-title">Service List</h3>
                  </div>
                  <div class="col-md-8">
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/add-service' >Counsellor Services List</a>
                  </div>
                  
                </div>
              </div> 
              <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Service ID</th>
                                <th>Service Name</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="<?php echo base_url('AdminController/submit_counsellor_services') ?>" method="post"  >
                                <input type='hidden' name='counsellor_id' value='<?= $counsellor_id ?>' >                                                    
                            <?php  
                                foreach ($list as $k => $row)  
                                {
                            ?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <?php
                                            $existed = false;
                                            if( empty( $selected ) ){
                                        ?>
                                                <input type='checkbox' class='form-control form-checkbox' name='service_id[<?=$row['id']?>]' >
                                        <?php
                                            }
                                            else{
                                                foreach( $selected as $v ){
                                                    if( $v['service_id'] == $row['id'] ){
                                                        $existed = true;
                                        ?>
                                                        <input type='checkbox' checked class='form-control form-checkbox' name='service_id[<?=$row['id']?>]' >                              
                                        <?php
                                                    }
                                                }
                                                if( !$existed ){
                                        ?>
                                                    <input type='checkbox' class='form-control form-checkbox' name='service_id[<?=$row['id']?>]' >
                                        <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </td>  
                                <td><?php echo $row['id'];?></td>  
                                <td><?php echo ucwords($row['services']);?></td>
                                <td>
                                    <?php if($row['image'] != '') { ?> 
                                    <img width='50px' src='<?= base_url() ?>uploads/<?= $row['image'] ?>'  class='img-responsive'>
                                    <?php }
                                    else{
                                    ?>
                                        Not Uploaded
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>  
                            <?php 
                                }
                            ?>
                            <tr>
                                <td colspan='4'>
                                    <div class='row'>
                                        <div class='col-md-3 offset-9'>
                                            <input type='submit' class='btn btn-success' value='Add Counsellor Services' >
                                        </div>
                                    </div> 
                                </td>
                            </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>