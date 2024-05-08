<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<div class="content-wrapper bg-white" style="min-height: 706px">
  <!-- Content Header (Page header) -->
  <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            <?= $pageTitle ?>
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url('UserController/vocational_training');?>"
                >Vocational Training</a
              >
            </li>
            <li class="breadcrumb-item active">Add New Vocational Training</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <section class="content">
        <div class="container-fluid">  
            <!-- main content #start -->
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body box-profile">  
                            <div class="">
                                <?php 
                                    if(!empty($check_inset)){
                                      if($check_inset == "OK"){
                                         echo "<div class='alert alert-success'>Successfully saved.</div>";
                                      }
                                      if($check_inset == "ERROR"){
                                         echo "<div class='alert alert-danger'>Something is wrong.</div>";
                                      }
                                    }
                                ?>
                            </div>                      
                            <form action="<?= base_url() ?>skill-development/submit-detail" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <?php
                                        if( !empty( $edit ) ){
                                    ?>
                                        <input type='hidden' value='<?= $edit['id'] ?>' name='id' >
                                    <?php
                                        }
                                    ?>
                                    <label for="vocational_name">Skill Training Name</label>
                                    <input type='text' value='<?= !empty( $edit ) ? $edit['training_name'] : '' ?>' class='form-control' required name='training_name' placeholder='Enter Skill Training Name'>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Total Price</label>
                                    <input type='text' class='form-control' value='<?= !empty( $edit ) ? $edit['price'] : '' ?>'  required name='price' placeholder='Enter Skill Training Name'>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Image</label>
                                    <input type='file' name='image' class='form-control'  >
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Session Type</label>
                                    <select class='form-control' name='sessionType' required>
                                        <option value='single' <?= !empty( $edit ) ? ( $edit['sessionType'] == 'single' ? 'selected' : '' ) : '' ?>  >Single Session</option>
                                        <option value='multiple' <?= !empty( $edit ) ? ( $edit['sessionType'] == 'multiple' ? 'selected' : '' ) : '' ?> >Multiple Sessions</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Post Type</label>
                                    <select class='form-control' name='postType' required>
                                        <option value='single'  <?= !empty( $edit ) ? ( $edit['postType'] == 'single' ? 'selected' : '' ) : '' ?> >Single Student</option>
                                        <option value='multiple' <?= !empty( $edit ) ? ( $edit['postType'] == 'multiple' ? 'selected' : '' ) : '' ?> >Multiple Students</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Number Of Session</label>
                                    <input type='number' value='<?= !empty( $edit ) ? $edit['sessions'] : '' ?>'  min='1' name='sessions' class='form-control' placeholder='Enter student Count' required value='1' >
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Not of Students</label>
                                    <input type='number' value='<?= !empty( $edit ) ? $edit['students'] : '' ?>'  min='1' name='students' class='form-control' placeholder='Enter student Count' required value='1' >
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Duration ( In Days )</label>
                                    <input type='numnber' value='<?= !empty( $edit ) ? $edit['duration'] : '' ?>' name='duration' class='form-control' placeholder='Enter Duration in days'  required >
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Start Date</label>
                                    <input type='date' value='<?= !empty( $edit ) ? $edit['start_date'] : '' ?>'  name='start_date' class='form-control'  required >
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Short Description</label>
                                    <textarea class='form-control' name='short_descriptoin' rows='5' max-length='255' placeholder='Enter Short Deacription' ><?= !empty( $edit ) ? $edit['short_descriptoin'] : '' ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6"></div>
                                    <!-- /.col -->
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Submit And Fill Meta Detail
                                        </button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </form>
                            <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- main content #end -->
        </div>
      </section>
    </div>
  </section>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
   var count_i = 2; 
 $(".btn-repeat-section").click(function(){ 
  $(".repeat-section").append(
    `<div class="m-1 mt-2 border p-1 rounded">
      <div class="text-right"><button type="button" class="btn btn-sm btn-outline-danger btn-close-section"><i class="fas fa-times"></i></button></div>
      <p class="p-0 m-0 text-bold">Training Section : ${count_i}</p>
      <div class="form-group">
        <label for="add_name_${count_i}">Name</label>
        <input required type="text" class="form-control" id="add_name_${count_i}" name="training_section_name[]" />
      </div>
      <div class="form-group">
          <label for="add_desc_${count_i}">Description</label>
          <textarea required class="form-control" id="add_desc_${count_i}" name="training_section_desc[]" rows="3"
              placeholder="Type here..."></textarea>
      </div>     
    </div>`);
    count_i++;
 })
  
  $(document).on("click",".btn-close-section",function(){
    $(this).parent().parent().remove();
  })
</script>