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
                    <?= $pageTitle ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right pt-1">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url( 'UserController/dashboard' );?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active"><?= ucwords( $pageTitle  ) ?></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <?php view( 'message' ); ?>
      <section class="content">
        <div class="container-fluid">  
            <!-- main content #start -->
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body box-profile">
                            <form action="<?= base_url() ?>skill-development/faq-submit" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <?php
                                        if( !empty( $edit ) ){
                                    ?>
                                        <input type='hidden' name='id'  value='<?= $edit['id'] ?>'>
                                        <input type='hidden' name='trainingId'  value='<?= $edit['trainingId'] ?>'>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <input type='hidden' name='trainingId'  value='<?= $id ?>'>
                                    <?php
                                        }
                                    ?> 
                                    <label for="question">Question<span class='text-red' > * </span> </label>
                                    <input type='text' name='question' value='<?= !empty( $edit ) ? $edit['question'] : '' ?>'  class='form-control' required  placeholder='Enter Questions' >
                                </div>
                                <div class="form-group">
                                    <label for="answer">Answer<span class='text-red' > * </span></label>
                                    <textarea name='answer' class='form-control' required  placeholder='Enter Short Answer' rows='5' ><?= !empty( $edit ) ? $edit['answer'] : '' ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-primary btn-block"><?= $pageTitle ?></button>
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