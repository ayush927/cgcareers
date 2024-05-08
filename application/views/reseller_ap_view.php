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

    /*.tooltip{*/

    /*    display: inline !important;*/

    /*    opacity: 1;*/

    /*}*/

    /*.tooltip:hover span {*/

    /*    padding: 16px 15px;*/

    /*	width: 240px;*/

    /*    opacity: 1;*/

    /*    filter: alpha(opacity=100);*/

    /*    top: -8em;*/

    /*    left: 0;*/

    /*    z-index: 99;*/

    /*    -webkit-transition: all 0.2s ease;*/

    /*    -moz-transition: all 0.2s ease;*/

    /*    -o-transition: all 0.2s ease;*/

    /*    transition: all 0.2s ease;*/

    /*}*/

    

    /*.box b {*/

    /*  color: #fff;*/

    /*}*/

    

    /*.tooltip span {*/

    /*    padding: 0;*/

    /*	width: 0;*/

    	/*background: none repeat scroll 0 0 #222;*/

    /*	color: #F0B015;*/

    /*	font-family: 'Helvetica';*/

    /*	font-size: 0.8em;*/

    /*	font-weight: normal;*/

    /*	line-height: 1.5em;*/

    	/*top: -8em;*/

    /*	left: 0em;*/

    /*	margin-left: 0;	*/

    	/*-- set opacity to 0 otherwise our animations won't work */

    /*	opacity: 0;*/

    /*	filter: alpha(opacity=0);  	*/

    /*	position: absolute;*/

    /*	text-align: center;	*/

    /*	z-index: 2;*/

    /*	text-transform: none;*/

    /*	-webkit-transition: all 0.3s ease;*/

    	/*-moz-transition: all 0.3s ease-in-out;*/

    /*	-o-transition: all 0.3s ease;*/

    	/*transition: all 0.3s ease-in-out;*/

    /*}*/

    

    /*.tooltip span:after {*/

    /*	border-color: #222 rgba(0, 0, 0, 0);*/

    /*	border-style: solid;*/

    /*	border-width: 15px 15px 0;*/

    /*	bottom: -14px;*/

    /*	content: "";*/

    /*	display: block;*/

    /*	left: 0px;*/

    /*	position: absolute;*/

    /*	width: 0;*/

    /*}*/

    

    .ui-tooltip {

        /* tooltip container box */

        white-space: pre-line;

    }

    

    .ui-tooltip-content {

        /* tooltip content */

        white-space: pre-line;

    }

    .starIcon:hover {

        cursor: pointer;

    }

    

    #theStars {

        margin: 0 auto;

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

          Notifications

          </h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right pt-1">

            <li class="breadcrumb-item">

              <a href="https://users.respicite.com/SpController/dashboard"

                >Dashboard</a

              >

            </li>

            <li class="breadcrumb-item active">Notifications</li>

          </ol>

        </div>

      </div>

    </div>

    <!-- /.container-fluid -->

  </section>

    



  <section class="content ">

    <div class="container-fluid">

        <?php 

            $msg = $this->session->flashdata('msg');

            $status = $this->session->flashdata('status');

            if($msg !="")

            {

              ?>     

                <div class="alert alert-<?= $status ?>">

                        <?php echo $msg; ?>

                </div>

                <?php 

            }

        ?>

        <!-- main content #start -->

        <div class="card">              

              <div class="card-body table-responsive p-0">

                <table class="table table-bordered text-nowrap">

                  <thead>

                    <tr>

                        <th>Category</th>

                        <th>Name</th>

                        <th>Nature</th>

                        <th>Message</th>

                        <th>Status</th>

                        <th>AP Request Date</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if(!empty($ap_book_data)){ foreach($ap_book_data as $v){ ?>                    

                        <tr>                            

                            <td>Lead</td>

                            <td>

                                    <?php echo ucwords($v->name);?><i class="fa fa-info-circle" data-html="true" data-toggle="tooltip" data-placement="top" title="Email - <?php echo $v->email;?>  &#013;Phone - <?php echo $v->phone_no;?>  &#013;City - <?php echo $v->location != '' ? $v->location : 'Not Added';?>" aria-hidden="true"></i>

                            </td>

                            <!--<td><?php echo $v->email;?></td>-->

                            <!--<td><?php echo $v->phone_no;?></td>-->

                            <!--<td><?php echo $v->location;?></td>-->

                            <td>

                                <?php if($v->appointment_type == "interested_call_back"){

                                        echo "<p class='book-call'>Call Back</p>";

                                    }

                                    elseif($v->appointment_type == "message"){

                                        echo "<p class='book-msg'>Message</p>";

                                    }

                                    elseif($v->appointment_type == "book_appointment"){

                                        echo "<p class='book-app'>Counselling</p>";

                                    }

                                    elseif($v->appointment_type == "assessment_review"){

                                      echo "<p class='book-app'>Services</p><br>";

                                    }

                                ?>

                            </td>

                            <td>

                                <button type="button" class="btn btn-sm btn-primary btn-view-msg" data-msg="<?php echo htmlspecialchars_decode($v->message,ENT_NOQUOTES);?>">View</button>

                            </td>

                            <td>
                                <?php
                                    if( $v->status != '' ){
                                        $data = getQuery( [ 'where' => [ 'notificationId' => $v->id  ] , 'table' => 'review_rating'  , 'single' => true ] );
                                        if( empty( $data ) ){
                                ?>
                                    <a class="btn btn-sm btn-<?= $v->status == 'pending' ? 'warning' : 'primary' ?> <?= $v->status == 'pending' ? '' : 'btn-view-review' ?> " <?= $v->status == 'completed' ? 'data-review="0"  data-msg="Not Added Review"' : '' ?>  href="<?= $v->status == 'pending' ? base_url()."/userController/booking-status/".$v->id."/completed" : '#' ?>"  >

                                        <?= $v->status == 'pending' ? 'Mark as Completed' : 'Review Awaited' ?>
                                    </a>
                                <?php
                                        }
                                        else{
                                ?>
                                        <a type="button" class="btn btn-sm btn-success btn-view-review" data-review="<?php echo htmlspecialchars_decode($data['rating'], ENT_NOQUOTES);?>"  data-msg="<?php echo htmlspecialchars_decode($data['review'], ENT_NOQUOTES);?>">View Review</a>
                                <?php
                                            if( $data['status'] == 'active' ){
                                ?>
                                            <i class="fa fa-check-circle text-green"  area-hidden='true' data-toggle="tooltip" data-placement="top" title="Review Activated"></i>
                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </td>

                            <td><?php echo date('d-m-Y h:i a', strtotime($v->created_at));?></td>

                        </tr>

                    <?php } }else{ echo "<tr><td colspan='8' class='text-center'>Data Not Found.</td></tr>";} ?>

                  </tbody>

                </table>

              </div>

              <!-- /.card-body -->

            </div>

            <!-- /.card -->

        <!-- main content #end -->



    </div>

  </section>

</div>



<div class="modal fade" id="modal_view_message" aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Message</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">

                

            </div>

            <div class="modal-footer justify-content-between">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>

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

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>



<script>

  const BASE_URL = '<?php echo base_url();?>';

  $(document).on("click",".btn-view-msg",function(){

    let msg = $(this).data("msg");

    $("#modal_view_message").modal("show");;

    $("#modal_view_message").find(".modal-body").html(msg);

  });

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

</script>

