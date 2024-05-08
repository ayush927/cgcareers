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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Service Detail List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Counsellor  List</li>
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
                    <h3 class="card-title">Service Detail List</h3>
                  </div>
                  <div class="col-md-8">
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/add_details/<?= $service_id ?>/add' >Add Service Detail</a>
                  </div>
                  
                </div>
              </div> 
              <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Detail</th>
                                <th>MRP</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if( !empty( $list ) ){
                                    foreach ($list as $row)  
                                    {                                 
                            ?>
                                    <tr>
                                        <td><?php echo $row['id'];?></td>  
                                        <td><?php echo ucwords($row['category']);?></td>
                                        <td><?php echo ucwords($row['name']);?></td>
                                        <td><?php echo substr($row['details'] , 0 , 50 );?>...</td>
                                        <td><?= $row['mrp'] == '' ? 'Not Added' : $row['mrp']."  Rs" ?></td>
                                        <td>
                                            <a class='btn btn-info' href='<?= base_url() ?>adminController/edit-details/<?= $row['id'] ?>/edit' >Edit</a>
                                            <a class='btn btn-danger' href='<?= base_url() ?>adminController/details-delete/<?= $row['id'] ?>/<?= $row['service_id'] ?>' >Delete</a>
                                        </td>
                                    </tr> 
                            <?php 
                                    }
                                }
                                else{
                            ?>
                                    <tr>
                                        <td colspan='5' class='text-center' > No Record Found </td>
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