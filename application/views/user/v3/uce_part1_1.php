<?php view( 'user/v3/style' ); ?>
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

  <style>
    label {
      font-size : 18px!important;
    }
  </style>

  <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
          <!-- instruction modal -->
        <div class="modal fade" id="exampleModalLong" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Instruction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <strong><?php echo $detail['detail_instruction']; ?></strong>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-my" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-6 col-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;"><?php echo $detail['top_display']; ?></h1>
          </div>
          <div class="col-sm-3 offset-sm-3 col-6">
            <div class="timerDiv d-none">
              <!--<div>Hours<span class="hours"></span></div>-->
              <div>Min<span class="minutes"></span></div>
              <div>Sec<span class="seconds"></span></div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-2"></div>
            <div class="col-md-8">
              <!-- Profile Image -->
              <div class="alert alert-danger msg mt-2"></div>
              <div class="bg-white rounded border-round shadow mt-4">
                <div class="card-body box-profile">
                  <h3 class="profile-username text-center">Part 1</h3>
                  <div class="col-sm-12">
                    <div class="card card-solid shadow">
                      <div class="card-body pb-0">
                        <div class="form-group">
                          <p class="bm-0 h3 top-discription">
                            <strong><?php echo $detail['top_discription']; ?></strong>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 questionsList">
                  </div>
                </div>
                <div class='card-footer'>
                  <div class='col-md-12'>
                    <button class="btn btn-primary btn-my" id="next" onclick='displayNext()' >Next</button>
                    <button class="btn btn-info btn-my" id="submit" onclick='submitToNext()' >Submit To Next Part</button>
                    <!-- <button class="btn btn-info btn-my" id="prev" onclick='displayPrev()' >Prev</button> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Main content -->
  </div>
  <div class="modal fade" id="modal_uce_part" data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='margin-top:160px;'>
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title h1">Instruction</h4>
        </div>
        <div class="modal-body">
          <p class='review h3 font-weight-bold text-secondary'><?php echo $detail['top_discription']; ?></p>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="col-md-12">
            <button data-dismiss="modal" type="button" class="mb-1 btn btn-success closeButton"> I have understood the instructions well. I agree to proceed forward </button><br>
            <button data-dismiss="modal" type="button" class="btn btn-warning closeButton"> I have not understood the instructions completely. However, I agree to proceed further</button>
          </div>
        </div>
      </div>
    </div>
  </div> 
  <?php view( 'user/v3/assessment_script' ) ?>
  
  <?php 
    if( $partName == 'uce_part2' || $partName == 'uce_part2_2' || $partName == 'uce_part2_3' || $partName == 'uce_part2_4' || $partName == 'uce_part2_5' ||$partName == 'uce_part2_6'  ){
      view( 'user/v3/timer' );
    }
  ?>