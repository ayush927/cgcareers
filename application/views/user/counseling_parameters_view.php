<?php //echo "<pre>";print_r($paymentGateway);die;//echo "fgfgfgfgfgff";die; ?>
<body class="hold-transition sidebar-mini">
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
                <h3 class="card-title">Counseling Parameters View</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                    <th>S NO</th>
                      <th>Event Name</th>
                      <th>Event Minutes</th>
                      <th>Event MRP</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                          <?php //echo "<pre>";print_r($flow);
                          $i=1;
                          if(isset($flow)){
                          foreach($flow as $row2) { ?>
                            <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row2['event_name'];?></td> 
                            <td><?php echo $row2['minutes'];?></td>
                            <td><?php echo $row2['price'];?></td>
                            <td>
                                <a class='btn btn-success' href="<?= base_url()?>calendly/user_request_code_online/<?=$row2['id'] ?>/<?= base64_encode($user['email']) ?>/<?= $reseller_sp['email'] ?>"> Book Appointment </a>
                            </td>
                         </tr>
                        <?php $i++; }
                        } else { ?>  
                                    <tr> <td><p>Data Not Found!</p></td></tr>
                        <?php } ?>
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