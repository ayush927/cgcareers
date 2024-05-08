<body class="hold-transition sidebar-mini">
<style>
  .pagination p, .pagination li, .pagination a{
      font-size: 1rem;
    }

    /* Hyperlinks without underline */

    /* Remove ul dots for list */
    .pagination ul{
      margin: 0;
      padding: 0;
      list-style-type: none;
    }

    /* Horizontal alignment */
    .pagination a{ 
      text-decoration: none;
      display: inline-block;
      padding: 7px 14px;
      color: #222;
    }

    /* COLORED BLOCKS */
    .blocks .control, .blocks .is-active{
      background-color: #fc9928;
      color: #fff;
      font-weight: bold;
    }
</style>
<div id="modalHomeEvents" class="modal fade" role="dialog">

    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header" style="height:50px;">
          <b>Service Details</b>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="eventId" id="eventId"/>
        	<span id="idHolder"></span>	
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="wrapper">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">

              <h1>User List</h1>

            </div>

            <div class="col-sm-6">

              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item"><a href="#">Requested User</a></li>

                <li class="breadcrumb-item active">User List</li>

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

      $email = $user['email'];

    ?>

    <div class="row">

          <div class="col-12">

            <div class="card">

              <div class="card-header">

                 <div class='row'>

                     <div class='col-md-3'>

                        <h3 class="card-title">Code List</h3> 

                     </div>

                     <div class='col-md-2 offset-md-7'>
                        <?php
                            if( !empty( $reportControl ) ){
                              // print_r( $reportControl );
                              $control =  $reportControl['status'] == 1 ? 2 : 1;
                              $text =  $reportControl['status'] == 1 ? 'Click To Automatic' : 'Click to Mannual';
                        ?>
                              <a class='btn btn-info' href="<?=base_url()?>UserController/set_report_control/<?= $control?>"><?= $text ?></a>
                        <?php
                            }
                            else{
                        ?>
                            <a class='btn btn-info float-right' href="<?= base_url() ?>UserController/set_report_control/1" >Click To Mannual</a>
                        <?php

                            }

                        ?>

                     </div>

                 </div>

              </div> 

              <!-- /.card-header -->

              

              <div class="card-body">

                <table class="table table-bordered table-striped">

                  <thead>

                    <tr>

                      <th>Email</th>

                      <th>Service Name</th>

                      <th>Code</th>

                      <th>Status</th>

                      <th>Action</th>

                    </tr>

                  </thead>

                  <tbody class='codeList' >
                            <!--<pre>-->
                          <?php
                              if( strpos( $_SERVER['SCRIPT_URI'] , '/dev/' ) ){
                                $folder = 'dev';
                              }
                                // print_r( $s->result() );
                                // die;
                              foreach( $s as $row )
                              {
                                    ?>
                                      <tr>
                                      <td><?php echo $row->user_id; ?></td>
                                      <td><?php echo $row->solution; ?></td>
                                      <td><?php echo $row->code; ?> <?= $row->nature != '' ? '(Lead Generated By Respicite)' : '' ?> </td>
                                      <?php 
                                          $status = $row->status;
                                      ?>
                                      <td>
                                        <?php
                                          if($status=='Ap')
                                          {
                                            echo "Assessment Pending";
                                          }
                                          else if($status=='Rp')
                                          {
                                              echo "Assessment Completed";
                                          }
                                          else
                                          {
                                            echo "Report Downloaded";
                                          }
                                        ?>
                                      </td>
                                      <td>
                                        <div class="row">
                                          <div class="col-sm-12">
                                            <!-- select  -->
                                            <div class="form-group">
                                            <?php 
                                              if($status=='Ap')
                                              {
                                                echo "";
                                              }
                                              else  if($status=='Rp')
                                              {
                                            ?>
                                            <?php 
                                              if($row->solution =='OCSS') {
                                            ?>
                                                  <a target='_blank' href="<?php echo 'https://faith-n-hope.com/services/reports/'.base64_encode($row->code)."/".base64_encode($email)."/".base64_encode($row->solution); ?>">View Report</a><br>
                                            <?php
                                              }
                                              else{
                                                  if( in_array( $row->solution , $allow_solution ) ){
                                                      // $this->db->where( ['status' => 0 ] );
                                                      $this->db->order_by('id' , 'DESC');
                                                      $this->db->where('code' , $row->code);
                                                      $lastReport =  $this->db->get('report_request')->row_array();
                                                      // print_r( $lastReport );
                                                      if( !empty($lastReport) ){
                                                          if( $lastReport['status'] == 0 || $lastReport['status'] == 2){
                                                              $time = strtotime(date('Y-m-d H:i:s'));
                                                              $updateTime = strtotime(date('Y-m-d H:i:s', strtotime( $lastReport['created_date']. ' +45  minutes')));
                                                              if( $time >= $updateTime ){
                                                                if( $lastReport['status'] ==  0 ){
                                                                    $this->db->where([ 'code' => $row->code ]);
                                                                    $this->db->set([ 'status' => 2 ]);
                                                                    $this->db->where([ 'id' => $lastReport['id'] ]);
                                                                    $this->db->update('report_request');
                                                                }
                                                      ?>
                                                              <a <?php if($requestParameter == null) { ?> onclick="window.open(window.location+'/request-generate','_blank');window.open(this.href,'_self');" href="<?php echo base_url().'UserController/create-request/'.$row->code; ?>/<?= $row->solution ?>/<?= $row->user_id ?><?= isset( $folder ) ? '/'.$folder : '' ?>" <?php } else{ ?> onClick="alert('<?= $reportData['report_message'] ?>')" href='#' <?php } ?> >View Report</a><br>
                                                      <?php
                                                              }
                                                              else{
                                                                  // echo "1-2";
                                                                  if( $lastReport['code'] != $row->code ){

                                                                      // echo "1-3";

                                                                      $where = "code='$row->code' AND status=1";

                                                                      $this->db->where($where);

                                                                      $reportStatus =  $this->db->get('report_request');

                                                                      if( $reportStatus->num_rows() > 0 ){

                                                                          // echo "1-4";

                                                                          // echo "1";

                                                                          $rdata = $reportStatus->row_array();

                                                                          if( $rdata['status'] == 1 ){

                                                                              // echo "1-5";

                                                                            ?>

                                                                              <a target='_blank' href="<?php echo base_url().'assets/report-pdf/'.$rdata['file_name']; ?>">View Report</a><br>

                                                                            <?php

                                                                              if( $reportControl['status'] == 1 ){

                                                                                  $control = $rdata['mannual_status'] == 1 ? 0 : 1;

                                                                                  $text =  $rdata['mannual_status'] == 1 ? 'Hide Report' : 'Allow To View';

                                                                                ?>

                                                                                  <a href="<?= base_url() ?>UserController/set_mannual_control/<?= $control ?>/<?=$row->code?>/<?= $lastReport['id'] ?>"><?= $text ?></a><br>

                                                                                <?php

                                                                              }
                                                                          }

                                                                          else{

                                                                              // echo "1-6";

                                                                            ?>

                                                                              <a target='_blank' href='#' onClick="alert('<?= $reportData['report_message'] ?>')" >View Report</a><br>

                                                                            <?php

                                                                          }

                                                                      }

                                                                      else{

                                                                          //  echo "1-7";

                                                                        ?>

                                                                          <a target='_blank' onclick="alert('<?= $reportData['report_message'] ?>')" >View Report</a><br>

                                                                        <?php

                                                                      }

                                                                  }

                                                                  else{

                                                                      //  echo "1-8";

                                                                    ?>

                                                                      <a target='_blank' href='#' onClick="alert('<?= $reportData['report_message'] ?>')" >View Report</a><br>

                                                                    <?php

                                                                  }

                                                              }

                                                          }

                                                          else{

                                                              if( !empty($lastReport) ){

                                                                  if( $lastReport['code'] == $row->code ){

                                                                      $where = "code='$row->code' AND status = 1";

                                                                      $this->db->where($where);

                                                                      $reportStatus =  $this->db->get('report_request');

                                                                      if( $reportStatus->num_rows() > 0 ){

                                                                          // echo "1";

                                                                          $rdata = $reportStatus->row_array();

                                                                          if( $rdata['status'] == 1 ){

                                                                              // echo "2-1";

                                                                            ?>

                                                                              <a target='_blank' href="<?php echo base_url().'assets/report-pdf/'.$rdata['file_name']; ?>">View Report</a><br>

                                                                            <?

                                                                                  if( $reportControl['status'] == 1 ){

                                                                                      $control = $rdata['mannual_status'] == 1 ? 0 : 1;

                                                                                      $text =  $rdata['mannual_status'] == 1 ? 'Hide Report' : 'Allow To View';

                                                                                    ?>

                                                                                      <a href="<?= base_url() ?>UserController/set_mannual_control/<?= $control ?>/<?=$row->code?>/<?= $lastReport['id'] ?>"><?= $text ?></a><br>

                                                                                    <?php

                                                                                  }

                                                                          }

                                                                          else{

                                                                              // echo "2-2";

                                                                            ?>

                                                                              <a target='_blank' href='#' onClick="alert('<?= $reportData['report_message'] ?>')" >View Report</a><br>

                                                                            <?php

                                                                          }

                                                                      }

                                                                      else{

                                                                          // echo "2-3";

                                                                        ?>

                                                                          <a  onclick="alert('<?= $reportData['report_message'] ?>')" >View Report</a><br>

                                                                        <?php

                                                                      }

                                                                  }

                                                                  else{

                                                                      // echo "2-4";

                                                                      ?>

                                                                      <a <?php if($requestParameter == null)  { ?> onclick="window.open(window.location+'/request-generate','_blank');window.open(this.href,'_self');" href="<?php echo base_url().'UserController/create-request/'.$row->code; ?>/<?= $row->solution ?>/<?= $row->user_id ?><?= isset( $folder ) ? '/'.$folder : '' ?>" <?php } else{ ?> onClick="alert('<?= $reportData['report_message'] ?>')" href='#' <?php } ?> >View Report</a><br>

                                                                    <?php

                                                                  }

                                                              }

                                                              else{

                                                              // echo "2-5";

                                                                ?>

                                                                  <a <?php if($requestParameter == null)  { ?> onclick="window.open(window.location+'/request-generate','_blank');window.open(this.href,'_self');" href="<?php echo base_url().'UserController/create-request/'.$row->code; ?>/<?= $row->solution ?>/<?= $row->user_id ?><?= isset( $folder ) ? '/'.$folder : '' ?>" <?php } else{ ?> onClick="alert('<?= $reportData['report_message'] ?>')" href='#' <?php } ?> >View Report</a><br>

                                                                <?php
                                                              }
                                                          }
                                                      }
                                                      else{
                                                          // echo "2-6";
                                                          ?>
                                                              <a <?php if($requestParameter == null)  { ?> onclick="window.open(window.location+'/request-generate','_blank');window.open(this.href,'_self');" href="<?php echo base_url().'UserController/create-request/'.$row->code; ?>/<?= $row->solution ?>/<?= $row->user_id ?><?= isset( $folder ) ? '/'.$folder : '' ?>" <?php } else{ ?> onClick="alert('<?= $reportData['report_message'] ?>')" href='#' <?php } ?> >View Report</a><br>

                                          <?php

                                                      }

                                                  }
                                                  else{

                                                      // echo "2-7";

                                      ?>

                                                      <a target='_blank' href="<?php echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">View Report</a><br>

                                      <?php

                                                  }

                                              } 

                                      ?>

                                              <a href="<?php echo base_url().'UserController/Update_Counsellor_remarks/'.base64_encode($row->code); ?>">Counsellor Remarks</a>



                                      <?php 

                                          }

                                          else

                                          {

                                            ?>

                                            <a target='_blank' href="<?php echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">View Report</a><br>

                                            <a href="<?php echo base_url().'UserController/Update_Counsellor_remarks/'.base64_encode($row->code); ?>">Counsellor Remarks</a>

                                            <?php 

                                                }



                                            ?>

                            

                                            <!-- <select class="form-control" name="act" id="act<?php echo $row->id; ?>" onchange="changeThis(<?php echo $row->id; ?>)">

                                              <option value="">Change Status</option>

                                              <option value="Ap"><?php echo "Assessment Pending"; ?></option>

                                              <option value="Rp"><?php echo "Report Pending"; ?></option>

                                              <option value="Cp"><?php echo "Counseling Pending"; ?></option>

                                              <option value="Fp"><?php echo "FeedBack Pending"; ?></option>

                                            </select> -->

                                          </div>

                                      </div>

                                    </div>

                                    </td> 

                                </tr>

                                <?php

                              }

                          ?>  

                  </tbody>        

        </table>
        <div class="pagination blocks col-md-4 offset-md-4">
          
          <!-- This will be generated by JavaScript 
            <ul>
              <a href="#"><li>Previous</li></a>
              <a href="#"><li>1</li></a>
              <a href="#"><li>2</li></a>
              <a href="#"><li>3</li></a>
              <a class="is-active" href="#"><li>4</li></a>
              <a href="#"><li>5</li></a>
              <a href="#"><li>6</li></a>
              <a href="#"><li>Next</li></a>
            </ul>
          -->
          
        </div>

    </div>

              

              

             



              <!-- /.card-body -->

            </div>

            <!-- /.card -->

          </div>

        </div>

         <!-- jQuery -->

<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

<!-- Bootstrap 4 -->

<script src="<?php echo base_url().'assets/plugins/bootstrap/js/bootstrap.bundle.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/jszip/jszip.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/pdfmake/pdfmake.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/pdfmake/vfs_fonts.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.html5.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.print.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.colVis.min.js';?>"></script>

<script>

  $(function () {

    $("#example1").DataTable({

      "responsive": true, "lengthChange": false, "autoWidth": false,

      "buttons": ["excel"]

    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({

      "paging": false,

      "lengthChange": false,

      "searching": false,

      "ordering": true,

      "info": false,

      "autoWidth": false,

      "responsive": true,

    });

  });

</script>

        <script

  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="

  crossorigin="anonymous"></script>

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  

        <script>

  $(document).on("click", ".open-homeEvents", function () {

      var eventId = $(this).data('id');

      $.ajax({

              type : 'post',

              url : '<?php echo base_url()."OtherAjax/fetch_record.php"; ?>', //Here you will fetch records 

              data :  'rowid='+ eventId, //Pass $id

              success : function(data){

              $('#idHolder').html(data);//Show fetched data from database

              }

          });
  });

  function changeThis(s) { 

    var s = s

    var a = 'act'.concat(s);

    var act = document.getElementById(a).value;

        if(s=="")

        {

          alert("Something Went Wrong");

        }

        else

        {

          $.ajax({

            url: "<?php echo base_url(); ?>UserController/update_code_status",

            type : "POST",

            dataType:"json",

            data: {

              s: s,

              act : act

            },

            success : function(data)

            {

              if(data.responce == "success")

              {

                toastr["success"](data.message)



                toastr.options = {

                "closeButton": true,

                "debug": false,

                "newestOnTop": false,

                "progressBar": true,

                "positionClass": "toast-top-right",

                "preventDuplicates": false,

                "onclick": null,

                "showDuration": "300",

                "hideDuration": "1000",

                "timeOut": "5000",

                "extendedTimeOut": "1000",

                "showEasing": "swing",

                "hideEasing": "linear",

                "showMethod": "fadeIn",

                "hideMethod": "fadeOut"

                  }

                  location.reload(true)

              }

              else

              {

                alert("something went wrong")

              }

              

            }

            

          });



        }

  };

  var totalCodes = Math.ceil( parseInt(<?=$total['codes']?>) / 10 );
    // var allData = generateTestData(parseInt(<?=$total['codes']?>) ); //[{'_id':1},{'_id':2},...{'_id':15}];
    console.log(totalCodes);
    var pagData = [];       //reduced for pagination register
    var activePageID = 1;   //current page
    var pagMaxLength = 5;
    changed = true;

    //@TODO remove if filter logic exists
    pagData = createRegisterArray(0, pagMaxLength);

    requestParameter = '<?= $requestParameter ?>'

    function getData( activePageID ){
      $.ajax({
            type : 'post',
            url : '<?php echo base_url()."UserController/filter-list/"; ?>'+activePageID+'/'+requestParameter ,
            success : function(data){
              $('.codeList').html(data);
                // if( response.status == 'success' && response.variation == 'two' ){
                    // codeElement.href = base_url+'assessment_variations/'+codeElement.getAttribute('data-part')+'/'+btoa(code);
                // }
            }
        });
    }
    //prepare data for pagination register only
    function registerData(id) {
      var pagData = [];
      if (id < 3) {
        pagData = createRegisterArray(0, pagMaxLength);
      }
      else{
          var startIndex = id - 3;
          pagData = createRegisterArray(startIndex, pagMaxLength); 
      }
      return pagData
    }

    //Markup pagination register
    function renderPagination(){
      var pagHTML = '';  
      var pagData = registerData(activePageID);
      console.log(JSON.stringify(pagData));
    
      pagHTML = pagHTML + renderPrevBtn(pagData, activePageID);
      
      
      for (i=0; i < pagData.length; i++) {
        if (pagData[i] == activePageID) {
          pagHTML = pagHTML + '<li class="page-item" ><a class="page-link is-active" href="#">' + pagData[i] + '</a></li>';
        } else if (pagData[i] <=  totalCodes) {
          pagHTML = pagHTML + '<li class="page-item" ><a class="page-link"  href="#" onclick="return gotoPage(' + pagData[i] +');">' + pagData[i] + '</a></li>';
        }
      }
    
      pagHTML = pagHTML + renderNextBtn(pagData, activePageID);
      
      $(".pagination.blocks").html("<nav aria-label='Page navigation example'><ul class='pagination'>" + pagHTML + "</ul>");
      
      if( activePageID != 1 ){
        changed = true;
        getData(activePageID);
      }
      else if( changed ){
        getData(activePageID);
      }
      // $(".content").html(allData[activePageID-1].content); 
    }

    //Markup Previous Button
    function renderPrevBtn(data, id) {
      var prevBtnHTML = '';
      if (id > 1) {
        if (typeof data != "undefined" && data.length > 0) {  
          prevBtnHTML = prevBtnHTML + '<li class="page-item" ><a aria-label="Previous" class="page-link control" href="#" onclick="return gotoPage(1);">First</a></li><li class="page-item" ><a aria-label="Previous" class="page-link control" href="#" onclick="return prevClick();">Previous</a></li>';  
        }
      }  
      
      return prevBtnHTML
    }

    //Markup Next Button
    function renderNextBtn(data, id) {
      var nextBtnHTML = '';
      
      if (id < totalCodes) {
        if (typeof data != "undefined" && data.length > 0) {  
          nextBtnHTML = nextBtnHTML + '<li class="page-item" ><a aria-label="Next" class="page-link control" href="#" onclick="return nextClick();">Next</a></li><li class="page-item" ><a aria-label="Next" class="page-link control" href="#" onclick="return gotoPage('+totalCodes+');">Last</a></li>';  
        }  
      }
    
      
      return nextBtnHTML
    }

    //previous click
    function prevClick() {
      activePageID = activePageID - 1  
      if (activePageID < 1) {
        activePageID = 1;
      }
      
      console.log("activePageID: " + activePageID);
      renderPagination();
    }

    //next click
    function nextClick() {
    
      activePageID = activePageID + 1
      
      if (activePageID > totalCodes) {
        activePageID = totalCodes;     
      }
      
      console.log("activePageID: " + activePageID);
      renderPagination();
    }

    //open page
    function gotoPage(nr) {
      activePageID = nr;
      renderPagination();
    }

    /* creates array with register numbers */
    function createRegisterArray(startIndex, maxLength) {
      var registerArray = [];
      var lastIndex = startIndex + maxLength;  
      
      console.log("startIndex: " + startIndex + ", lastIndex: " + lastIndex);
      
      var firstRegNr = startIndex + 1;
      var lastRegNr = firstRegNr + maxLength;
      
      if (lastRegNr > totalCodes) {
        lastRegNr = totalCodes + 1;
      }
      
      var diff = lastRegNr - firstRegNr;
      
      if (diff < maxLength) {
        firstRegNr = lastRegNr - (maxLength);
        if (firstRegNr < 1) {
          firstRegNr = 1;
        }
      }
      
      console.log("firstRegNr: " + firstRegNr + ", lastRegNr: " + lastRegNr + ", diff: " + diff);  
      
      for (i=firstRegNr; i < lastRegNr; i++ ) {
        registerArray.push(i); //store register number (starts with 1);
      }
      
      return registerArray;
    }

    /*generates test data array with objects */
    function generateTestData(amountOfObjects) {
      var retArray = [];
      for (i=0; i < amountOfObjects; i++ ) {
        retArray.push({_id:i, content: "Page with ID " + i});
      }
      return retArray;
      
    }

    //it is time to render, my friend
    $( document ).ready(function() {
      renderPagination();
    });

</script>

        