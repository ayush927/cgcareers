<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;"><?= isset($edit) ? 'Edit' : 'Add' ?> Links</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url(
                  "UserController/dashboard"
              ); ?>">Dashboard</a></li>
              <li class="breadcrumb-item active"><?= isset($edit) ? 'Edit' : 'Add' ?> Links</li>
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
                        
                            <?php
                            $msg = $this->session->flashdata("msg");
                            if ($msg != "") {
                                echo "<div class='alert alert-success'>$msg</div>";
                            }
                            ?>
                            <form action="<?php echo base_url("UserController/update_book_link"); ?>" method="post">
                                <?php
                                    if( isset( $edit ) && !isset( $admin ) ){
                                ?>
                                    <input type='hidden'  name='id' value='<?= $edit['id'] ?>' >
                                <?php
                                    }
                                ?>
                                <?php
                                    if( isset( $onlink )){
                                ?>
                                    <label class='label'>Add Link<span class='text-red'>*</span></label>
                                    <input class='form-control' type='url'  name='url' value='<?= $edit['url'] ?>' >
                                    
                                    <label class='label'>Add Minutes<span class='text-red'>*</span></label>
                                    <input class='form-control' type='number'  name='minutes' value='<?= $edit['minutes'] ?>' >
                                <?php
                                    }
                                ?>
                                <?php
                                    if( !isset( $onlink )){
                                ?>
                                <label class='label'>Select Time<span class='text-red'>*</span></label>
                                <select <?= isset( $edit ) ? 'disabled' : ''  ?> class='form-control' <?= isset($id) ? '' : "name='linkId'" ?>  required >
                                    <?php
                                        if( !empty( $list ) ){
                                    ?>
                                        <option value=''>Select Name</option>
                                            
                                    <?php
                                            foreach( $list as $key => $value ){
                                    ?>
                                                <option value='<?= $value['id'] ?>' <?= isset( $edit ) ?  ( $edit['event_name'] == $value['event_name'] ? 'selected' : '' )  : '' ?> <?php isset( $edit ) ?  ( $edit['event_name'] == $value['event_name'] ? $linkId = $value['id'] : '' )  : '' ?> ><?= ucwords( $value['event_name'] )  ?></option>
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                        <option value=''>Add Event Name</option>
                                        
                                    <?php
                                        }
                                    ?>
                                </select>
                                <?php
                                    }
                                ?> 
                                
                                <?php
                                    if( isset($id) ){
                                        if( isset( $linkId ) ){
                                            
                                ?>
                                    <input type='hidden'  name='linkId' value='<?= $linkId ?>' >
                                <?php
                                        }
                                        else{
                                ?>
                                            <input type='hidden'  name='linkId' value='<?= $edit['id'] ?>' >
                                <?php
                                        }
                                    }
                                ?> 
                                <!--<label class='label'>Add Minutes<span class='text-red'>*</span></label>-->
                                <!--<div class="input-group mb-3">-->
                                <!--    <input type="number" name="minutes" value="<?php echo isset($edit) ? $edit['minutes'] : ""; ?>" class="form-control <?php echo form_error("book_link") != "" ? "is-invalid" : ""; ?>" placeholder="Add Minutes">-->
                                <!--    <p class="invalid-feedback"><?php echo strip_tags(form_error("minutes")); ?></p>-->
                                <!--</div>-->
                                <label class='label'>Add Price<span class='text-red'>*</span></label>
                                <div class="input-group mb-3">
                                    <input type="number" name="price" value="<?php echo isset($edit) ? $edit['price'] : ""; ?>" class="form-control <?php echo form_error("price") != "" ? "is-invalid" : ""; ?>" placeholder="Add price">
                                    <p class="invalid-feedback"><?php echo strip_tags(form_error("price")); ?></p>
                                </div>
                                <!--<label class='label'>Add Url<span class='text-red'>*</span></label>-->
                                <!--<div class="input-group mb-3">-->
                                <!--    <input type="url" name="url" value="<?php echo isset($edit) ? $edit['url'] : ""; ?>" class="form-control <?php echo form_error("url") != "" ? "is-invalid" : ""; ?>" placeholder="Add url">-->
                                <!--    <p class="invalid-feedback"><?php echo strip_tags(form_error("url")); ?></p>-->
                                <!--</div>-->
                                <!--<label class='label'>Add Event Name<span class='text-red'>*</span></label>-->
                                <!--<div class="input-group mb-3">-->
                                <!--    <input type="text" name="event_name" value="<?php echo isset($edit) ? $edit['event_name'] : ""; ?>" class="form-control <?php echo form_error("event_name") != "" ? "is-invalid" : ""; ?>" placeholder="Add Event Name">-->
                                <!--    <p class="invalid-feedback"><?php echo strip_tags(form_error("event_name")); ?></p>-->
                                <!--</div>-->
                                <label class='label'>Add Description<span class='text-red'>*</span></label>
                                <div class="input-group mb-3">
                                    <input type="text" name="desc" value="<?php echo isset($edit) ? $edit['desc'] : ""; ?>" class="form-control <?php echo form_error("desc") != "" ? "is-invalid" : ""; ?>" placeholder="Add Description">
                                    <p class="invalid-feedback"><?php echo strip_tags(form_error("desc")); ?></p>
                                </div>
                                <label class='label'>Brief Description<span class='text-red'>*</span></label>
                                <div class="input-group mb-3">
                                    <textarea  name="briefDesc" class="form-control <?php echo form_error("briefDesc") != "" ? "is-invalid" : ""; ?>" placeholder="Add Brief Description" ><?php echo isset($edit) ? $edit['briefDesc'] : ""; ?></textarea>
                                    <p class="invalid-feedback"><?php echo strip_tags(form_error("breifDesc")); ?></p>
                                </div>
                                <div class="row">
                                  <div class="col-8">
                                  </div>
                                  <!-- /.col -->
                                  <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block"><?= isset($edit) ? 'Edit' : 'Add' ?> Links</button>
                                  </div>
                                  <!-- /.col -->
                                </div>
                            </form>
                        </div>
                  <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  