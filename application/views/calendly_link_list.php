<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">All Sources</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("UserController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">All Calendly Config Link</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <?php view( 'message' );   ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-3">
                    <h3 class="card-title">All Sources</h3>
                  </div>
                  <div class="col-md-9">
                    <a class="btn btn-info float-right" <?= !empty($not_show) ? 'style="pointer-events: none;" title="Request Already On Pending"' : (!empty($not_hide) ? 'style="pointer-events: none;" title="Request Already On Pending"' : '') ?>  href="<?= base_url() ?>UserController/request-change/<?= in_array("counselling", explode(',',$allowed_services)) ? 'notification_show/counselling' : 'notification_hide/counselling' ?>" > <?= in_array("counselling", explode(',',$allowed_services)) ? 'Disable' : 'Enable' ?>  Booking in User Panel</a>
                    <a class="btn btn-info float-right" <?= !empty($custom) ? 'style="margin-right:50px;pointer-events: none;" title="Request Already On Pending"' : (!empty($admin) ? 'style="pointer-events: none;" title="Request Already On Pending"' : '') ?> style="margin-right:50px;"  href="<?= base_url() ?>UserController/request-change/<?= in_array("seller_config", explode(',',$allowed_services)) ? 'admin/seller_config' : 'custom/seller_config' ?>" > <?= in_array("seller_config", explode(',',$allowed_services)) ? 'Request Admin' : 'Request Custom' ?></a>
                  </div>
                </div>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Url</th>
                      <th>Minutes</th>
                      <th>Price</th>
                      <th>Created Date</th>
                      <th>Modified Date</th>
                      <?php
                        if( in_array("seller_config", explode(',',$allowed_services)) ){
                      ?>
                          <th>Status</th>
                          <th>Action</th>
                      <?php
                        }
                      ?>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php
                        if( !empty( $list ) ){
                            foreach ( $list as $key => $row )
                            {
                                if( !empty( getQuery( [ 'where' => [ 'event_name' => $row['event_name'] , 'user_id' => $user['id'] ] , 'table' => 'calendly_event' , 'single' => true  , ] ) ) ){
                                    $data = getQuery( [ 'where' => [ 'event_name' => $row['event_name'] , 'user_id' => $user['id'] ] , 'table' => 'calendly_event' , 'single' => true  ] );
                    ?>
                                    <tr>  
                                        <td><?= $data['id'];?></td>
                                        <td><?= $data['event_name'];?></td>
                                        <td><?= $data['url'];?></td>
                                        <td><?= $data['minutes'];?></td>
                                        <td><?= $data['price'];?></td>
                                        <td><?= date('d-m-Y H:i:s' , strtotime($data['created_date']));?></td>
                                        <td><?= $data['modified_date'] != '' ? date('d-m-Y H:i:s' , strtotime($data['modified_date'])) : 'Not Updated';?></td>
                                        <?php
                                            if( in_array("seller_config", explode(',',$allowed_services)) ){
                                        ?>
                                            <td><a class='btn btn-<?= $data['status'] == 'active' ? 'success' : 'danger' ?>' href='<?= base_url() ?>userController/calendly-link-status/<?= $data['id'] ?>/<?= $data['status'] == 'active' ? 'inactive' : 'active' ?>' ><?= $data['status'] == 'active' ? 'Active' : 'Inactive' ?></a></td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="<?php echo base_url("UserController/edit-booking-link/").$data['id'];?>">Edit ( Without Link Update )</a>
                                                <a class="btn btn-sm btn-primary" href="<?php echo base_url("UserController/edit-booking-link/").$data['id'];?>/user/link">Edit( With link Update )</a>
                                            </td>
                                         <?php
                                            }
                                        ?>
                                    </tr>
                    <?php
                                }
                                else{
                    ?>
                                    <tr>  
                                        <td><?= $row['id'];?></td>
                                        <td><?= $row['event_name'];?></td>
                                        <td><?= $row['url'];?></td>
                                        <td><?= $row['minutes'];?></td>
                                        <td><?= $row['price'];?></td>
                                        <td><?= date('d-m-Y H:i:s' , strtotime($row['created_date']));?></td>
                                        <td><?= $row['modified_date'] != '' ? date('d-m-Y H:i:s' , strtotime($row['modified_date'])) : 'Not Updated';?></td>
                                         <?php
                                            if( in_array("seller_config", explode(',',$allowed_services)) ){
                                        ?>
                                        <td><a class='btn btn-success' href='<?= base_url() ?>userController/calendly-link-status/<?= $row['id'] ?>/inactive/admin' >Active</a></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url("UserController/edit-booking-link/").$row['id'];?>/admin">Edit (Without Link Update )</a>
                                            <a class="btn btn-sm btn-primary" href="<?php echo base_url("UserController/edit-booking-link/").$row['id'];?>/admin/link">Edit ( With Link Update )</a>
                                        </td>
                                         <?php
                                            }
                                        ?>
                                    </tr>
                    <?php
                                }
                            }
                        }
                        else{
                    ?>
                        <tr>
                            <td colspan='8' class='text-center'>No Record Found</td>
                        </tr>
                    <?php
                        }
                    ?>
                  </tbody>
                </table>
                
              </div>
             
              
             
            </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>