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
              <a href="<?php echo base_url( 'UserController/dashboard' );?>"
                >Dashboard</a
              >
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
                            <form action="<?= base_url() ?>skill-development/meta-detail-submit" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type='hidden' name='id'  value='<?= $id ?>'>
                                    <label for="vocational_name">Domain</label>
                                    <select class='form-control' name='domainId' required id='domainId' > 
                                        <?php
                                            if( !empty( $domain ) ){
                                        ?>
                                                <option> Select Domain First </option>
                                        <?php
                                                foreach( $domain as $k => $v ){
                                        ?>
                                                    <option value='<?= $v['id'] ?>' <?= !empty( $edit ) ? ($edit['domainId'] == $v['id'] ? 'selected' : '') : ''  ?>  > <?= ucwords($v['domainName']) ?> </option>
                                        <?php
                                                }
                                            }
                                            else{
                                        ?>
                                                <option> Add Domain First </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Sub Domain</label>
                                    <select class='form-control' name='subDomainId' required id='subDomainData' > 
                                        <option> Select Sub Domain </option>
                                        <?php
                                            if( !empty( $subDomain ) ){
                                                foreach( $subDomain as $k => $v ){
                                        ?>
                                                <option <?= !empty( $edit ) ? ( $edit['subDomainId'] == $v['id'] ? 'selected' : '' ) : ''  ?> value='<?= $v['id'] ?>' > <?= $v['subDomainName'] ?> </option>
                                        <?php
                                                }
                                            }
                                        ?> 
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Nature</label>
                                    <select class='form-control' name='nature' required id='nature' > 
                                        <?php
                                            if( !empty( $nature ) ){
                                        ?>
                                                <option> Select Nature First </option>
                                        <?php
                                                foreach( $nature as $k => $v ){
                                        ?>
                                                    <option <?= !empty( $edit ) ? ( $edit['nature'] == $v['id'] ? 'selected' : '' ) : ''  ?> value='<?= $v['id'] ?>'> <?= ucwords($v['nature']) ?> </option>
                                        <?php
                                                }
                                            }
                                            else{
                                        ?>
                                                <option> Add Nature First </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Certification Type</label>
                                     <select class='form-control' name='certification' required id='certification' >
                                        <?php
                                                if( !empty( $certification ) ){
                                        ?>
                                                    <option> Select Certification First </option>
                                        <?php
                                                foreach( $certification as $k => $v ){
                                        ?>
                                                    <option <?= !empty( $edit ) ? ( $edit['certification'] == $v['id'] ? 'selected' : '' ) : ''  ?> value='<?= $v['id'] ?>'> <?= ucwords($v['certificationName']) ?> </option>
                                        <?php
                                                }
                                            }
                                            else{
                                        ?>
                                                <option> Add Certification First </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Level Type</label>
                                    <select class='form-control' name='level' required id='level' >
                                    <?php
                                            if( !empty( $level ) ){
                                    ?>
                                                <option> Select Level First </option>
                                    <?php
                                            foreach( $level as $k => $v ){
                                    ?>
                                                <option <?= !empty( $edit ) ? ( $edit['level'] == $v['id'] ? 'selected' : '' ) : ''  ?> value='<?= $v['id'] ?>'> <?= ucwords($v['level']) ?> </option>
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                            <option> Add Level First </option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Language Type</label>
                                    <select class='form-control' name='language' required id='language' >
                                    <?php
                                            if( !empty( $language ) ){
                                    ?>
                                                <option> Select Language First </option>
                                    <?php
                                            foreach( $language as $k => $v ){
                                    ?>
                                                <option <?= !empty( $edit ) ? ( $edit['language'] == $v['id'] ? 'selected' : '' ) : ''  ?> value='<?= $v['id'] ?>'> <?= ucwords($v['language']) ?> </option>
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                            <option> Add Language First </option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vocational_name">Discount ( In Percentage )</label>
                                    <select class='form-control' name='discount' id='discount' >
                                        <?php
                                                if( !empty( $discount ) ){
                                        ?>
                                                    <option> Select Discount First </option>
                                        <?php
                                                foreach( $discount as $k => $v ){
                                        ?>
                                                    <option <?= !empty( $edit ) ? ( $edit['discount'] == $v['discount'] ? 'selected' : '' ) : ''  ?> value='<?= $v['discount'] ?>'> <?= ucwords($v['discount']) ?> % </option>
                                        <?php
                                                }
                                            }
                                            else{
                                        ?>
                                                <option> Add Discount First </option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                               
    
                                <div class="row">
                                    <div class="col-8"></div>
                                    <!-- /.col -->
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Next To Session Schedule
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