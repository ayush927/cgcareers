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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Variation Image List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php  echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Variation Image List</li>
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
                    <h3 class="card-title">Variation Image List</h3>
                  </div>
                </div>
              </div> 
              <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Variation ID</th>
                                <th>Solution Name</th>
                                <th>Variation</th>
                                <th>Image</th>
                                <th>Created</th>
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
                                <td><?php echo ucwords($row['solution_name']);?></td>
                                <td><?= $row['value_par_1'] == 'na' ? ucwords(str_replace('_' , ' ' , $row['variant_name'])) : ucwords(str_replace('_' , ' ' , $row['value_par_1'])) ?> <?= $row['par_2'] == 'stream_change' && $row['value_par_2'] == 'yes' ? 'With Stream Change' : '' ?></td>
                                <td>
                                    <?php if($row['imageName'] != '') { ?> 
                                    <img width='50px' src='<?= base_url() ?>uploads/solution-variation/<?= $row['imageName'] ?>'  class='img-responsive'>
                                    <?php }
                                    else{
                                    ?>
                                        Not Uploaded
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?php echo date( 'd-m-Y' , strtotime( $row['created_date']));?></td>
                                <td>
                                    <a class='btn btn-info' href='<?= base_url() ?>SpController/edit-variation-image/<?= $row['dataId'] ?>' >Edit</a>
                                    <a class='btn btn-info' href='<?= base_url() ?>SpController/delete-variation-image/<?= $row['id'] ?>' >Delete</a>
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