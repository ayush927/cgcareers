<style>
  a{color:black}
  
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }

  .border-round{
    border:1px solid #fc9928;
  }

  .color-b{
    background-color:#fc9928;
    color:white;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Rating & Review</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><?= $for == 'counselling' ? 'Counsellor' : 'Assessment' ?>Rating & Review</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <br><br>
        <div class="row">
            <div class="col-md-3"></div>
          <div class="col-md-6">

            <!-- Profile Image -->
            <div class="bg-white rounded border-round shadow">
                <div class="card-body box-profile">
                    <p class="text-muted text-center"></p>
                        <?php 
                            $msg = $this->session->flashdata('msg');
                            $status = $this->session->flashdata('status');
                            if($msg !="")
                            {
                        ?>     
                            <div class="alert alert-<?= $status ?? 'success' ?>">
                                <?php echo $msg; ?>
                            </div>
                        <?php 
                            }
                        ?>
                  <form action="<?= base_url() ?>/baseController/update-review" method="post">
                    <?php
                        if( isset( $editData ) ){
                    ?>
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">    
                        <input type="hidden" name="reseller_id" value="<?php echo $editData['user_id']; ?>">
                        <input type="hidden" name="notificationId" value="<?php echo $editData['id']; ?>">
                    <?php
                        }
                        else{
                    ?>
                        <input type="hidden" name="reseller_id" value="<?php echo $reseller_sp['id']; ?>">
                        <input type="hidden" name="assessment_code" value="<?php echo $assessment_code; ?>">    
                    <?php
                        }
                    ?>
                    <input type="hidden" name="nature" value="<?php echo $for; ?>">
                    <div class="input-group mb-3">
                      <input type="number" class="form-control <?php echo (form_error('rating')!="") ? 'is-invalid' : ''; ?>" required name="rating"   min='1' max='5'  placeholder="Rating in number ( Between 1 - 5 )">
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-star"></span>
                        </div>
                      </div>
                      <p class="invalid-feedback"><?php echo strip_tags(form_error('rating')); ?></p>
                    </div>
                    <div class="input-group mb-3">
                      <textarea maxlength="300" class="form-control <?php echo (form_error('review')!="") ? 'is-invalid' : ''; ?>" required name="review" placeholder="Write your review"></textarea>
                      <p class="invalid-feedback"><?php echo strip_tags(form_error('review')); ?></p>
                    </div>
                    
                    <div class="row">
                      <!-- /.col -->
                      <div class="col-12">
                        <button type="submit" class="btn color-b btn-block">Submit Review</button>
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

               <br>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  