<style>

  a{color:black}

  

  .btn-my {

    color: #fff;

    background-color: #fc9928;

    border-color: #fc9928;

  }



  .btn-my:hover {

    color: #fff;

    background-color: #fc9928;

    border-color: #fc9928;

  }

  .btn-my:active {

    color: #fff;

    background-color: #fc9928;

    border-color: #fc9928;

  }



  .hover-effect:hover{

    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;

  }

   .timerDiv {

      position: absolute;

      top: 50%;

      left: 50%;

      transform: translate(-50%, -50%);

      /*font-size: 30px;*/

      color: #FC9928;

      display: flex;

      justify-content: center;

    }

    .timerDiv div {

      display: flex;

      flex-direction: column;

      align-items: center;

    }

    .timerDiv div:not(:last-child) {

      margin-right: 15px;

    }

    .timerDiv span {

      border: 2px solid;

      padding: 2px 14px;

      border-radius: 6px;

    }

    .timerDiv span:not(:last-child) {

      margin-right: 10px;

    }
    .translationcontainer{
      height: 200px;
      display: block;
    }
    
    .translationbox{
        width: 100px;
        height: 100px;
        position: absolute;
        left: 50%;
        top: 50%;
        translate: -50px -50px;
        background-color: orange;
        color: black;
        z-index: -1;
        text-align: center;
        border-radius: 50%;
        opacity: 1;
    }
    .translationbox1{
        width: 100px;
        height: 100px;
        position: absolute;
        left: 43%;
        top: 50%;
        translate: -50px -50px;
        background-color: orange;
        color: black;
        z-index: -1;
        text-align: center;
        border-radius: 50%;
        opacity: 1;
    }
    .translationbox2{
        width: 100px;
        height: 100px;
        position: absolute;
        left: 57%;
        top: 50%;
        translate: -50px -50px;
        background-color: orange;
        color: black;
        z-index: -1;
        text-align: center;
        border-radius: 50%;
        opacity: 1;
    }
    .translationbox, .translationbox1, .translationbox2{
        animation-name: transitionkey;
        animation-duration: 2s;
        animation-fill-mode:forwards;
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
        animation-direction: reverse;
    }

    .loading-overlay {
      background: rgba(255, 255, 255, 0.7);
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      top: 0;
      z-index: 999999;
      align-items: center;
      justify-content: center;
    }

    .loading-overlay.is-active {
      display: flex;
    }

    @keyframes transitionkey{
        0% {transform: translate(0px,0px); opacity: 1;}
        25% {transform: translate(150px,0px);opacity: 0.5;}
        50% {transform: translate(0px,0px);opacity: 1;}
        75% {transform: translate(-150px,0px);opacity: 0.5;} 
        100% {transform: translate(0px,0px);opacity: 1;}
    }

</style>

<body class="hold-transition login-page">
    <div class="loading-overlay">
      <div class="translationcontainer">
        <div class="translationbox">
            </div>
            <div class="translationbox1">
            </div>
            <div class="translationbox2">
            </div>
        </div>
      </div>
    </div>

<?php 

//echo "<pre>";

    // print_r($this->uri);

    // echo "</pre>";

    // die();

    $field = $this->uri->segment(2);

    //echo $field;die();

    //echo ($this->uri->segment(5))."33";die();

    if($field=='ocs_part')

    {  //echo ($this->uri->segment(5))."34";die();

        $code = base64_decode($this->uri->segment(5));

        //$code = $this->uri->segment(5);

      $f1 = $this->uri->segment(3);

      $f2 = $field.$f1;

      echo "if<br>";

      $this->db->where('solution',$f2);



    }

    else

    { $code = base64_decode($this->uri->segment(3));

      $this->db->where('solution',$field);

        echo "else<br>";

    }

    //echo base64_decode($code)."<br>";die();

    $detail = $this->db->get('solution_instruction')->row();

    //$code=base64_decode($code);

    //$code = base64_decode($this->uri->segment(3));

    if($field == 'ocs_part')

    {

      $where = "code='$code' and link='ocs_part/7/doccps'";  

      

    }

    else

    {

      $where = "code='$code' and link='uce_part2'";

    }

    echo $code."<br>";

    $this->db->where($where);

    //$this->db->where(['code'=>'A447356063', 'link'=>'ocs_part/7/doccps']);

    $row = $this->db->get('user_assessment_info')->row();

    //echo $row->remain_time;

    //print_r($row);die();

    // $remain_time = $row->remain_time;

    // $minutes_to_add = $remain_time;

    // echo "Remaining Time :".$minutes_to_add."<br>";die();

    //Added by Sudhir

    // if($minutes_to_add <0){

    //     $minutes_to_add =0;

    // }

    //End of Added by Sudhir

    //echo "Remaining Time Modified :".$minutes_to_add."<br>";

   



      //commented by manoj 08/09/2022

    // date_default_timezone_set("Asia/Kolkata");

    // $d = date('Y-m-d H:i:s');

    // // echo "Date : ".$d."<br>"; //danish

    // $time = new DateTime($d);

    // // echo "Time 1 :".$time->format('Y-m-d'); //danish

    // $x = new DateInterval('PT'.$minutes_to_add.'M');

    // //echo "Minutes :".$x;

    // $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

    // // echo "Time 2 :".$time->format('Y-m-d')."<br>" ; //danish

    // $stamp = $time->format('M d, Y H:i:s');

?>

    <div class="content-wrapper bg-white">

    <!-- Content Header (Page header) -->

    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">

      <div class="container-fluid">

        <!-- instruction modal -->

        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">

              <div class="modal-dialog" role="document">

                <div class="modal-content">

                  <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLongTitle">Instruction</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                      <span aria-hidden="true">&times;</span>

                    </button>

                  </div>

                  <div class="modal-body">

                    <?php echo $detail->detail_instruction; ?>

                  </div>

                  <div class="modal-footer">

                    <button type="button" class="btn btn-my" data-dismiss="modal">Close</button>

                   

                  </div>

                </div>

              </div>

            </div>

        <div class="row mb-2">

            

          <div class="col-sm-6">

          <h1 class="m-0 pt-2" style="font-size: 1.2em;"><?php echo $detail->top_display; ?></h1>

          </div>

          <div class="col-sm-3 offset-3">

            <div class="timerDiv">

                  <!--<div>Hours<span class="hours"></span></div>-->

                  <div>Min<span class="minutes"></span></div>

                  <div>Sec<span class="seconds"></span></div>

            </div>

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

            <div class="bg-white rounded border-round shadow mt-4">

                <div class="card-body box-profile">

                <h3 class="profile-username text-center">Part 1

                </h3>

                <?php 

                    $msg = $this->session->flashdata('msg');

                    if($msg !="")

                    {

                    ?>     

                    <div class="alert alert-danger">

                        <?php echo $msg; ?>

                    </div>

                    <?php 

                    }

                     $msg2 = $this->session->flashdata('msg2');

                    if($msg2 !="")

                    {

                    ?>

                    <div class="alert alert-success">

                    <?php echo $msg2; ?>

                    </div>

                    <?php 

                    }

                    ?> 

               



      <form action="" method="post">

      <div class="col-sm-12">

            <div class="card card-solid shadow">

                <div class="card-body pb-0">

                <div class="form-group"><p class="bm-0 top-discription">

                    <strong><?php echo $detail->top_discription; ?></strong>

                  </p>

                </div>

               

               <!-- /.card-body -->

                </div>

            </div>

        </div>

            <?php 

              $i = 1;

                foreach($q->result() as $q)

                {

                  

            ?>

            <div class="col-sm-12">

            <div class="card card-solid  hover-effect">

            <div class="card-body pb-0">

            <div class="form-group">

            <p><b><?php echo 'Q. '.$q->qno; ?></b><br>

            <b>A. <?php echo $q->question1; ?></b><br>

            <b>B. <?php echo $q->question2; ?></b><br>

            <b>C. <?php echo $q->question3; ?></b><br>

            <b>D. <?php echo $q->question4; ?></b><br>

            </p>

        </div>

        

        <div class="form-group clearfix">

                        

                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 1; ?>' id="<?php echo $q->qno.'o'.'1'; ?>">

                          <label for="<?php echo $q->qno.'o'.'1'; ?>">

                            A-B

                          </label>

                        </div>



                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 2; ?>' id="<?php echo $q->qno.'o'.'2'; ?>">

                          <label for="<?php echo $q->qno.'o'.'2'; ?>">

                            A-C

                          </label>

                        </div>

                   

                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 3; ?>' id="<?php echo $q->qno.'o'.'3'; ?>">

                          <label for="<?php echo $q->qno.'o'.'3'; ?>">

                            A-D

                          </label>

                        </div>

        </div>

        <div class="form-group clearfix">

                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 4; ?>' id="<?php echo $q->qno.'o'.'4'; ?>">

                          <label for="<?php echo $q->qno.'o'.'4'; ?>">

                           B-C

                          </label>

                        </div>

                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 5; ?>' id="<?php echo $q->qno.'o'.'5'; ?>">

                          <label for="<?php echo $q->qno.'o'.'5'; ?>">

                           B-D

                          </label>

                        </div>

                        <div class="icheck-success d-inline" style='padding: 10px;'>

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 6; ?>' id="<?php echo $q->qno.'o'.'6'; ?>">

                          <label for="<?php echo $q->qno.'o'.'6'; ?>">

                           C-D

                          </label>

                        </div>

        </div>                               

                

              </div>

        </div>

        </div>         <!-- radio -->

                

              <?php 

              $i++;

    }

    ?>      <div class="row">

            <div class="col-8">

            </div>

          <!-- /.col -->

          <div class="col-4">

          <input type="hidden" name="code" id='code' value='<?= $code; ?>'>

        <input type="hidden" name="remain_time" id="remain_time" value=''>

            <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-my btn-block">Save & Next</button>

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

         

        </div>

        <!-- /.row -->



               



      </div><!-- /.container-fluid -->

    </section>

    <!-- /.content -->

  <?php 

    include('timer.php');

  ?>