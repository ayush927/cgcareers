<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<div class="content-wrapper bg-white" style="min-height: 706px">
  <!-- Content Header (Page header) -->
  <section
    class="content-header mb-3"
    style="
      padding: 6px 0.5rem;
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    "
    >
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            Vocational Training
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/UserController/dashboard">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Training curriculum</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content ">
    <div class="container-fluid">
        <?php view( 'message' ); ?>
        <p class="float-left"><a class="btn btn-sm btn-outline-primary" href="<?= base_url();?>skill-development/live-training-list"> Training List </a></p>
        <p class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?php echo base_url();?>skill-development/vocational-training/add-faq/<?= base64_encode($id) ?>">Add FAQ</a></p>
        <!-- main content #start -->
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Question</th>
                      <th>Answer</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      <th>Action</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        if(!empty($list)){
                            foreach($list as $v){
                    ?>
                     <tr>  
                        <td><?php echo $v['id'];?></td>
                        <td><p><?= ucwords($v['question']);?></p></td>              
                        <td><p class='word-wrap: break-word; width:25%'><?= substr( ucfirst($v['answer']), 0 , 50 );?>...</p></td>              
                        <td><p><?= date( 'd M Y H:i A' , strtotime($v['created_date']) );?></p></td>              
                        <td><p><?= $v['modified_date'] != '' ? date( 'd M Y H:i A' , strtotime($v['modified_date']) ) : 'Not Updated';?></p></td>              
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-info m-1" href="<?= base_url('skill-development/vocational_training/edit-faq/'.base64_encode($v['id'])) ?>">Edit</a><br>
                            <a class="btn btn-sm btn-outline-danger m-1" href="<?= base_url('skill-development/delete-faq/'.base64_encode($v['id']));?>"> Delete </a><br>
                        </td>
                    </tr> 
                    <?php 
                            }
                        }
                        else{
                            echo "<tr><td colspan='7' class='text-center'>Data Not Found.</td></tr>";
                        } 
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- main content #end -->
    </div>
  </section>
</div>