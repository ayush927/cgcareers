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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Reseller Service List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php  echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Reseller Service List</li>
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
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/add-service' >Add Service</a>
                  </div>
                  
                </div>
              </div> 
              <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Service ID</th>
                                <th>Service Name</th>
                                <th>Image</th>
                                <!--<th>Modified date</th>-->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                foreach ($list as $row)  
                                {
                            ?>
                            <tr>
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
                                <!--<td><?php echo date( 'd-m-Y' , strtotime( $row['create_date']));?></td>-->
                                <!--<td><? $row['modified_date'] != '' ? date( 'd-m-Y' , strtotime( $row['modified_date']) ) : 'Not Updated' ;?></td>-->
                                <td>
                                    <a class='btn btn-info' href='<?= base_url() ?>adminController/edit-service/<?= $row['id'] ?>' >Edit</a>
                                    <a class='btn btn-info' href='<?= base_url() ?>adminController/service-detail-list/<?= $row['id'] ?>' >Details</a>
                                    <a class='btn btn-<?= $row['status'] == 'active' ? 'success' : 'warning' ?>' href='<?= base_url() ?>adminController/service-status-update/<?= $row['id'] ?>/<?= $row['status'] == 'active' ? 'inactive' : 'active' ?>' ><?= $row['status'] == 'active' ? 'Active' : 'Inacitve' ?></a>
                                </td>
                            </tr>  
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script></script>