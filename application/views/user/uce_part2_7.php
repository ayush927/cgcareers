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

    $field = $this->uri->segment(2);

    $this->db->where('solution',$field);

    $detail = $this->db->get('solution_instruction')->row();

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

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

            <button type="button" class="btn btn-sm btn-my mt-1" data-toggle="modal" data-target="#exampleModalLong">

              Read insturction

            </button>

            </ol>

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

           <p><b><?php echo 'Q. '.$q->qno.'. '.$q->question; ?></b></p>

        </div>

        

        <div class="form-group clearfix">

                        

                        <div class="icheck-success">

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 1; ?>' id="<?php echo $q->qno.'o'.'1'; ?>">

                          <label for="<?php echo $q->qno.'o'.'1'; ?>">

                            <?php echo "Yes"; ?>

                          </label>

                        </div>



                        <div class="icheck-success">

                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 2; ?>' id="<?php echo $q->qno.'o'.'2'; ?>">

                          <label for="<?php echo $q->qno.'o'.'2'; ?>">

                            <?php echo "No"; ?>

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

  </div>

  <div class="modal fade" id="modal_uce_part" data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='margin-top:160px;'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h1">Introduction</h4>
            </div>
            <div class="modal-body">
                <p class='review h3 font-weight-bold text-secondary'><?php echo $detail->top_discription; ?></p>
            </div>
            <div class="modal-footer justify-content-between">
              <div class="col-md-12">
                  <button data-dismiss="modal" type="button" class="mb-1 btn btn-success closeButton">I have understood the instructions well. I agree to proceed forward</button><br>
                  <button data-dismiss="modal" type="button" class="btn btn-warning closeButton">I have not understood the instructions completely. However, I agree to proceed further</button>
              </div>
            </div>
        </div>
    </div>  
  </div>


  <script>
    var key = 'uce_part2_7';
  </script>
  

  

