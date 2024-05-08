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

  .ui-tooltip {

        /* tooltip container box */

        white-space: pre-line;

    }

    

    .ui-tooltip-content {

        /* tooltip content */

        white-space: pre-line;

    }

</style>

<div class="wrapper">

<div class="content-wrapper">



    <!-- Content Header (Page header) -->

    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Counseling Parameters</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right pt-1">

              <li class="breadcrumb-item"><a href="<?php echo base_url("BaseController/dashboard");?>">Dashboard</a></li>

              <li class="breadcrumb-item active">Counseling Parameters View</li>

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

            <div class="alert alert-<?= $status != '' ? $status : 'info' ?>">

                    <?php echo $msg; ?>

            </div>

            <?php 

        }

        ?>

  <div class="row">

          <div class="col-12">

            <div class="card">

              <div class="card-header">

                <h3 class="card-title">Counseling Parameters View</h3>

              </div> 

              <!-- /.card-header -->

              

              <div class="card-body table-responsive p-0">

                <table class="table table-bordered text-nowrap">

                  <thead>

                    <tr>

                        <th>Reseller Name</th>

                        <th>Category</th>

                        <th>Nature</th>

                        <th>Name</th>

                        <!--<th>Email</th>-->

                        <!--<th>Phone Number</th>-->

                        <!--<th>City</th>-->

                        <!--<th>Message</th>-->

                        <th>AP Request Date</th>

                        <th>Status</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if(!empty($ap_book_data)){ 

                        // pre( $ap_book_data );

                        foreach($ap_book_data as $v){ 

                    ?>

                        <tr>                            

                            <td><?php echo $v['resellerName'];?></td>

                            <td>Lead</td>

                            <td>

                                <?php 

                                    if($v['appointment_type'] == "interested_call_back"){

                                        echo "<p class='book-call'>Call Back</p>";

                                    }

                                    elseif($v['appointment_type'] == "message"){

                                        echo "<p class='book-msg'>Message</p>";

                                    }

                                    elseif($v['appointment_type'] == "book_appointment"){

                                        echo "<p class='book-app'>Counselling</p>";

                                    }

                                    elseif($v['appointment_type'] == "assessment_review"){

                                      echo "<p class='book-app'>Services</p><br>";

                                    }

                                ?>

                            </td>

                            <td>

                                <?php echo ucwords($v['name']);?><i class="fa fa-info-circle" data-html="true" data-toggle="tooltip" data-placement="top" title="Email - <?php echo $v['email'];?>  &#013;Phone - <?php echo $v['phone_no'];?>  &#013;City - <?php echo $v['location'] != '' ? $v['location'] : 'Not Added';?>" aria-hidden="true"></i>

                            </td>

                            <!--<td><?php echo $v->email;?></td>-->

                            <!--<td><?php echo $v->phone_no;?></td>-->

                            <!--<td><?php echo $v->location;?></td>-->

                            <!--<td>-->

                                <!--<button type="button" class="btn btn-sm btn-primary btn-view-msg" data-msg="<?php echo htmlspecialchars_decode($v['message'],ENT_NOQUOTES);?>">View</button>-->

                            <!--</td>-->

                            <td><?php echo date('d-m-Y h:i a', strtotime($v['created_at']));?></td>

                            <td>

                                <?php

                                    if( $v['status'] != '' ){

                                        $data = getQuery( [ 'where' => [ 'notificationId' => $v['id']  ] , 'table' => 'review_rating'  , 'single' => true ] );

                                        if( empty( $data ) ){

                                ?>

                                    <a class="btn btn-sm btn-<?= $v['status'] == 'pending' ? 'warning' : 'primary' ?>" href="#"  >

                                        <?= $v['status'] == 'pending' ? 'Booked' : 'Rating & Review' ?>

                                    </a>

                                <?php

                                        }

                                        else{

                                            

                                ?>

                                        <a type="button" class="btn btn-sm btn-primary btn-view-review" data-review="<?php echo htmlspecialchars_decode($data['rating'], ENT_NOQUOTES);?>"  data-msg="<?php echo htmlspecialchars_decode($data['review'], ENT_NOQUOTES);?>">View Review</a>

                                        

                                        <a data-toggle="tooltip" data-placement="top" title="<?= $data['status'] == 'active' ? 'Click To inactive' : 'Click To active' ?>" class="btn btn-sm btn-<?= $data['status'] == 'active' ? 'success' : 'danger' ?>" href="<?= base_url() ?>/adminController/review-status/<?= $data['id'] ?>/<?= $data['status'] == 'active' ? 'inactive' : 'active' ?>"  >

                                            <?= $data['status'] == 'active' ? 'Active' : 'Inactive' ?>

                                        </a>

                                            <!--<i class="fa fa-check-circle text-green"  area-hidden='true' data-toggle="tooltip" data-placement="top" title="Review Activated"></i>-->

                                <?php

                                        }

                                    }

                                ?> 

                            </td>

                            <!--<td><?= $v['status'] != '' ? ($v['status'] == 'pending' ? 'Booked' : 'Rating & Review' ) : '' ;?>-->

                            <!--</td>-->

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

            <div class="modal fade" id="modal_view_review" aria-hidden="true">

                <div class="modal-dialog modal-md">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h4 class="modal-title">Message</h4>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">×</span>

                            </button>

                        </div>

                        <div class="modal-body">

                            <p class='rating'></p>

                            <p class='review'></p>

                        </div>

                        <div class="modal-footer justify-content-between">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        </div>

                    </div>

            

                </div>

            

            </div>

             <!-- Edit Parameter Closed -->

            <script src="<?php echo base_url().'assets/custom.js/list_flow.js'; ?>"></script>

            <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

            <script>

                const BASE_URL = '<?php echo base_url();?>';

                $(document).on("click",".btn-view-review",function(){

                    let msg = $(this).data("msg");

                    let review = parseInt($(this).data("review"));

                    $("#modal_view_review").modal("show");

                    var rating = '';

                        rating = `<div class="mb-2 w-100 d-flex">
                                    <div class="d-flex" id="theStars">`
                                    for( i = 1; i <= 5; i++ ){
                                      if( i <= review ){
                                          rating+=`<div style="width: .5rem !important; overflow-x: clip;">
                                                <i class="bi bi-star-fill starIcon text-warning" ></i>
                                              </div><div style="width: .5rem !important; overflow-x: clip;">
                                                <i class="bi bi-star-fill starIcon text-warning" style="margin-left:-.5rem" ></i>
                                                </div>`
                                      }
                                      else{
                                          rating+=`<div style="width: .5rem !important; overflow-x: clip;">
                                                    <i class="bi bi-star-fill starIcon" ></i>
                                                  </div><div style="width: .5rem !important; overflow-x: clip;">
                                                    <i class="bi bi-star-fill starIcon" style="margin-left:-.5rem" ></i>
                                                    </div>`
                                        }
                                    }

                    rating += '</div></div>'
                    console.log( rating );
                    console.log( msg );
                    $("#modal_view_review").find(".rating").html(rating);
                    $("#modal_view_review").find(".review").html(msg);
                });

                // function editParameter(id,name,type,duration,mrp) {

                    

                //     let editParam = "";

                //     editParam += ` <div class="row">

                //           <div class="col-md-12">

                //             <div class="mb-3">

                //               <label class="form-label" for="basic-default-fullname">Counseling Parameters Name</label>

                //               <input type="text" name="para_name" class="form-control" id="basic-default-fullname" required placeholder="Counseling Parameters Name" value="${name}" />

                //               <div class="render-template-msg"></div>

                //           </div>

                //         </div>

                //       </div>

                      

                //       <div class="row">

                //           <div class="col-md-12">

                //             <div class="mb-3">

                //               <label class="form-label" for="basic-default-fullname">Counseling Type</label>

                //               <select class="form-control" name="counseling_type" id="" placeholder="Counseling Type">

                //                     <option value="">Select Section</option>

                //                     <option value="Career Counseling">Career Counseling</option>

                //                     <option value="Overseas consultation" >Overseas consultation</option>

                //               </select>

                //               <div class="render-template-msg"></div>

                //           </div>

                //         </div>

                //       </div>

                      

                //       <div class="row">

                //           <div class="col-md-12">

                //             <div class="mb-3">

                //               <label class="form-label" for="basic-default-fullname">Counseling MRP</label>

                //               <input type="text" name="mrp" class="form-control" id="basic-default-fullname" required placeholder="Counseling MRP" value="${mrp}"/>

                //               <div class="render-template-msg"></div>

                //           </div>

                //         </div>

                //       </div>

                      

                //       <div class="row">

                //           <div class="col-md-12">

                //             <div class="mb-3">

                //               <label class="form-label" for="basic-default-fullname">Counseling Duration</label>

                //               <select class="form-control" name="duration" id="" placeholder="Counseling Duration">

                //                     <option value="">Select Section</option>

                //                     <option value="30">30</option>

                //                     <option value="60">60</option>

                //                     <option value="90">90</option>

                //               </select>

                //               <div class="render-template-msg"></div>

                //           </div>

                //         </div>

                //         <input type="hidden" name="id" value="${id}">

                //       </div>`;

                        

                       

                      

                //       $("#paramData").append(editParam);

                //       $("#paramData").empty().append(editParam);

                //     $("#editParameter").modal("show");

                // }

                    

            </script>