<?php //echo "<pre>";print_r($paymentGateway);die;//echo "fgfgfgfgfgff";die; ?>

<body class="hold-transition sidebar-mini">

<style>

  .hover-effect:hover{

    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;

  }

  .book-call,.book-msg,.book-app{

    display: inline-block;

    background: #5cb85c;

    color: white;

    padding: 0px 10px;

    border-radius: 18px;

  }

  .book-msg{

    background: #2e6da4;

  }

  .book-app{

    background: #e4bd23;

  }

</style>

<div class="wrapper">

<div class="content-wrapper">



    <!-- Content Header (Page header) -->

    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Notifications</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right pt-1">

              <li class="breadcrumb-item"><a href="<?php echo base_url("BaseController/dashboard");?>">Dashboard</a></li>

              <li class="breadcrumb-item active">Notifications</li>

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

                <h3 class="card-title">Notifications</h3>

              </div> 

              <!-- /.card-header -->

              

              <div class="card-body table-responsive p-0">

                <table class="table table-bordered text-nowrap">

                    <thead>

                    <tr>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Phone Number</th>

                        <!-- <th>City</th> -->

                        <th>Nature</th>

                        <th>Status</th>

                        <th>AP Request Date</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if(!empty($ap_book_data)){ 

                        // pre( $ap_book_data );

                        

                        foreach($ap_book_data as $v){ 

                    ?>                    

                        <tr>                            

                            <td><?php echo $v['name'];?></td>

                            <td><?php echo $v['email'];?></td>

                            <td><?php echo $v['phone_no'];?></td>

                           

                            <!--<td>-->

                                <!--<button type="button" class="btn btn-sm btn-primary btn-view-msg" data-msg="<?php echo htmlspecialchars_decode($v['message'],ENT_NOQUOTES);?>">View</button>-->

                            <!--</td>-->

                            <td>

                            <?php 

                                if($v['appointment_type'] == "interested_call_back"){

                                        echo "<p class='book-call'>Interested for Call Back</p>";

                                    }elseif($v['appointment_type'] == "message"){

                                        echo "<p class='book-msg'>Message</p>";

                                    }elseif($v['appointment_type'] == "book_appointment"){

                                      echo "<p class='book-app'>Counselling</p><br>";

                                    }elseif($v['appointment_type'] == "assessment_review"){

                                      echo "<p class='book-app'>Services</p><br>";

                                    }?>

                            </td>

                            <td><?= $v['status'] != '' ? ($v['status'] == 'pending' ? 'Booked' : ucfirst($v['status']) ) : 'Not Applccable' ;?>

                                <?php

                                if($v['status'] != '') {

                                         if( $v['status'] == 'completed' ){

                                             if( empty( getQuery( [ 'where' => [ 'notificationId' => $v['id'] ] , 'table' => 'review_rating'  , 'single' => true ] ) ) ){

                                ?>

                                                <a class="btn btn-sm btn-primary" href='<?= base_url() ?>baseController/rating-review/<?= $v['id'] ?>/counselling/' >Rating & Review</a>

                                <?php

                                             }

                                             else{

                                ?>

                                                <i class="fa fa-check-circle text-green"  area-hidden='true' data-toggle="tooltip" data-placement="top" title="Review Completed"></i>

                                <?php

                                                 

                                             }

                                        }

                                    }

                                ?>

                            </td>

                          </td>

                            <td><?php echo date('d-m-Y h:i a', strtotime($v['created_at']));?></td>

                        </tr>

                    <?php } }else{ echo "<tr><td colspan='8' class='text-center'>Data Not Found.</td></tr>";} ?>

                  </tbody>

                </table>

                

              </div>

            </form>

              <!-- /.card-body -->

            </div>

            <!-- /.card -->

          </div>

        </div>

        <!--Add Parameter details Start -->

            <div class="modal fade" id="AddOMRTemplate12" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

              <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                  <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Add Counseling Parameters</h5>

                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">

                      <span aria-hidden="true">&times;</span>

                    </button>

                  </div>

                  <div class="modal-body">

                    <form action="<?php echo base_url("UserController/addParameterValues");?>" method="post" enctype="multipart/form-data">

                        <div id="paramDataSection"></div>

                        

                        <input type="hidden" name="id" id="id" value="">

                        <input type="hidden" name="landingId" id="landingId" value="">

                        <input type="hidden" name="resellerId" id="resellerId" value="">

                              

                  </div>

                  <div class="modal-footer">

                    <button type="button" onclick="addFlowClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <input type="submit" class="btn btn-primary btn-submit"/>

                  </div>

                </form>

                </div>

              </div>

            </div>

             <!-- Add Parameter Closed -->    

         <!--Add Parameter Start -->

            <div class="modal fade" id="AddOMRTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

              <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                  <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Add Counseling Parameters</h5>

                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">

                      <span aria-hidden="true">&times;</span>

                    </button>

                  </div>

                  <div class="modal-body">

                    <form action="<?php echo base_url("UserController/addCounselingPara");?>" method="post" enctype="multipart/form-data">

                        <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Parameters Name</label>

                              <input type="text" name="para_name" class="form-control" id="basic-default-fullname" required placeholder="Counseling Parameters Name" />

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Type</label>

                              <select class="form-control" name="counseling_type" id="" placeholder="Counseling Type">

                                    <option value="">Select Section</option>

                                    <option value="Career Counseling">Career Counseling</option>

                                    <option value="Overseas consultation">Overseas consultation</option>

                               </select>

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling MRP</label>

                              <input type="text" name="mrp" class="form-control" id="basic-default-fullname" required placeholder="Counseling MRP" />

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Duration</label>

                              <select class="form-control" name="duration" id="" placeholder="Counseling Duration">

                                    <option value="">Select Section</option>

                                    <option value="30">30</option>

                                    <option value="60">60</option>

                                    <option value="90">90</option>

                               </select>

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                        <input type="hidden" name="resellerId" value="<?php  echo $resellerId?>">

                      </div>

                      

                    

                  </div>

                  <div class="modal-footer">

                    <button type="button" onclick="addFlowClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <input type="submit" class="btn btn-primary btn-submit"/>

                  </div>

                </form>

                </div>

              </div>

            </div>

             <!-- Add Parameter Closed -->

             

              <!--Edit Parameter Start -->

            <div class="modal fade" id="editParameter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

              <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">

                  <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Edit Counseling Parameters</h5>

                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">

                      <span aria-hidden="true">&times;</span>

                    </button>

                  </div>

                  <div class="modal-body">

                    <form action="<?php echo base_url("UserController/counselingUpdate");?>" method="post" enctype="multipart/form-data">

                    <div id="paramData"></div>

                     

                  </div>

                  <div class="modal-footer">

                    <button type="button" onclick="addFlowClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <input type="submit" class="btn btn-primary btn-submit"/>

                  </div>

                </form>

                </div>

              </div>

            </div>

             <!-- Edit Parameter Closed -->

            <script src="<?php echo base_url().'assets/custom.js/list_flow.js'; ?>"></script>

            

            <script>

                function editParameter(id,name,type,duration,mrp) {

                    

                    let editParam = "";

                    editParam += ` <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Parameters Name</label>

                              <input type="text" name="para_name" class="form-control" id="basic-default-fullname" required placeholder="Counseling Parameters Name" value="${name}" />

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Type</label>

                              <select class="form-control" name="counseling_type" id="" placeholder="Counseling Type">

                                    <option value="">Select Section</option>

                                    <option value="Career Counseling">Career Counseling</option>

                                    <option value="Overseas consultation" >Overseas consultation</option>

                               </select>

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling MRP</label>

                              <input type="text" name="mrp" class="form-control" id="basic-default-fullname" required placeholder="Counseling MRP" value="${mrp}"/>

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                      </div>

                      

                      <div class="row">

                          <div class="col-md-12">

                            <div class="mb-3">

                              <label class="form-label" for="basic-default-fullname">Counseling Duration</label>

                              <select class="form-control" name="duration" id="" placeholder="Counseling Duration">

                                    <option value="">Select Section</option>

                                    <option value="30">30</option>

                                    <option value="60">60</option>

                                    <option value="90">90</option>

                               </select>

                              <div class="render-template-msg"></div>

                          </div>

                        </div>

                        <input type="hidden" name="id" value="${id}">

                      </div>`;

                        

                       

                      

                      $("#paramData").append(editParam);

                      $("#paramData").empty().append(editParam);

                    $("#editParameter").modal("show");

                }

                    

            </script>

        <script>

 

          function addFlow($id){  

            $("#AddOMRTemplate").modal("show");

          }

        function addFlows($id ,$landingId,$resellerId){ 

            $("#id").val($id);

            $("#landingId").val($landingId);

            $("#resellerId").val($resellerId);

            $.ajax({

                  url: "<?php echo base_url(); ?>UserController/section_via_parameter",

                  type : "POST",

                  dataType:"json",

                  data: {

                    sectionId: $id

                  },

                  success : function(data)

                  {

                    //console.log('dffdgdfgdfgd'+data);

                      $("#paramDataSection").append(data);

                      $("#AddOMRTemplate12").modal("show");

                      //location.reload(true);

                    

                  }

                });

            

          }

           function addFlowClose($id){  

            $("#AddOMRTemplate").modal("hide");

          }

        </script>