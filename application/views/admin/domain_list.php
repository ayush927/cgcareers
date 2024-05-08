<body class="hold-transition sidebar-mini">
    <div class="wrapper">
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
            <section class="content">
                <div class="container-fluid">
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
                    <div class="row">
                        <div class='col-3 offset-9 mb-2'>
                            <a class='btn btn-info btn-block' href='<?= base_url() ?>skill-development/add-domain' >Add Domain</a>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?= $pageTitle ?></h3>
                                </div> 
                          <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-bordered text-nowrap">
                                      <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Domain Name</th>
                                            <th>Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                            if( !empty($list) ){
                                                foreach ($list as $key => $data)  
                                                {  
                                                    extract( $data );
                                            ?>
                                                    <tr>
                                                        <td><?= $key+1 ?></td>
                                                        <td><?= ucwords($data['domainName']) ;?></td>  
                                                        <td>
                                                            <a class="btn btn-primary" href="<?php echo base_url().'skill-development/edit-domain/'.$data['id'];?>" id="edit" value="<?php echo $data['id'];?>" data-material="">Modify</a>
                                                            <a class="btn btn-danger" href="<?php echo base_url().'skill-development/delete-domain/'.$data['id'];?>" >Delete</a>
                                                        </td>
                                                    </tr>  
                                            <?php
                                                }
                                            }
                                            else{
                                        ?>
                                                <tr>
                                                    <td> No Record Found </td>
                                                </tr>
                                        <?php
                                            }
                                        ?>  
                                        </tbody>
                                    </table>
                                </div>      
                            </form>
                        </div>
                          <!-- /.card -->
                    </div>
                </div>