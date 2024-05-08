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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Counsellor  List</h1>
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
                    <h3 class="card-title">Counsellor List</h3>
                  </div>
                  <div class="col-md-8">
                    <a class="btn btn-info float-right" href='<?= base_url() ?>adminController/add-counsellor-details' >Add Counsellor</a>
                  </div>
                  
                </div>
              </div> 
              <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Counsellor Name</th>
                                <th>Email</th>
                                <th>Image</th>
                                <th>Calenly Link</th>
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
                                <td><?php echo ucwords($row['first_name']." ".$row['last_name']);?></td>
                                <td><?= $row['email'];?></td>
                                <td>
                                    <?php if($row['profile_img'] != '') { ?> 
                                    <img width='100px' src='<?= base_url() ?>uploads/<?= $row['profile_img'] ?>'  class='img-responsive'>
                                    <?php }
                                    else{
                                    ?>
                                        Not Uploaded
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?= $row['calendly_link'] != '' ? $row['calendly_link'] : 'Not Added';?></td>  
                                <td>
                                    <a href='#' class='btn btn-info btn-view-review'  data-msg="<?php echo htmlspecialchars_decode($row['itroduction'], ENT_NOQUOTES);?>" >Intro</a>
                                    <a class='btn btn-info' href='<?= base_url() ?>adminController/edit-counsellor-details/<?= $row['id'] ?>' >Edit</a>
                                    <a class='btn btn-info' href='<?= base_url() ?>adminController/counsellor-services/<?= $row['id'] ?>' >Services</a>
                                    <a class='btn btn-<?= $row['status'] == 'active' ? 'success' : 'warning' ?>' href='<?= base_url() ?>adminController/counsellor-status-update/<?= $row['id'] ?>/<?= $row['status'] == 'active' ? 'inactive' : 'active' ?>' ><?= $row['status'] == 'active' ? 'Active' : 'Inacitve' ?></a>
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
    <div class="modal fade" id="modal_view_review" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Introduction</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class='review'></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
            <script>
                const BASE_URL = '<?php echo base_url();?>';
                $(document).on("click",".btn-view-review",function(){
                    let msg = $(this).data("msg");
                    $("#modal_view_review").modal("show");
                    $("#modal_view_review").find(".review").html(msg);
                });
            </script>