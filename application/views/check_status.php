<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Check Status</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>"
    />
    <!-- Theme style -->
    <link
      rel="stylesheet"
      href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>"
    />
    <link
      rel="stylesheet"
      href="<?php echo base_url('/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>"
    />
    <link href="<?php echo base_url('/assets/favicon.png');?>" rel="shortcut icon" type="image/png">
    <style>   
    .bg-color-1{
        background-color: #fc9928;
    }
    .text-color-1{
        color: #fc9928;
    }
    canvas{
      /*prevent interaction with the canvas*/
      pointer-events:none;
    }
    .border-captcha{
      /* padding-left: 7%; */
      border: 1px solid #e59c1d;
      border-radius: 5px;
      justify-content: center;
      display: flex;
    }
  </style>
  </head>
  <body onload="createCaptcha()" class="hold-transition login-page" style="background-image: url(<?php echo base_url('/assets/bg.svg'); ?>);background-repeat: no-repeat;
  background-attachment: fixed; background-size: cover; height:100vh !important;" >
    <div class="login-box">
        <div class="card">
        <!-- Forgot Password #START -->
      <div class="forgot-password"> 
          <div class="card-header text-center text-white bg-color-1">
          <div class="brand-logo m-0 p-1">
               <?php 

// echo " Hello ";
// die;
      if(!empty($sp_logo)){
          $path_logo = base_url($sp_logo['logo']);
          if(!file_exists($path_logo))
          echo "<img src='".$path_logo."' style='width: 6rem;margin-right: 16px;'>";
          else
          echo "<img src='".base_url('/assets/b-logo.png')."' style='width: 11rem;margin-right: 16px;'>";
      }else{
          echo "<img src='".base_url('/assets/b-logo.png')."' style='width: 11rem;margin-right: 16px;'>";

      }      
      ?>

                  </div>
          <p class="m-0">Check Status</p>
        </div>
        <div class="card-body">

          <!-- message -->
        <div class="alert alert-danger pb-0 show-msg" style="display:none;"><p>You did not select a file to upload.</p></div>
        <!-- message -->
        <?php
            if( !isset( $status['Registration'] ) ){
        ?>
            <form action=""  method="get" >
              <div class="input-group mb-3">
                <input type="email" class="form-control" name="email_id" value="" placeholder="Email" required />
                <div class="input-group-append">
                    <div class="input-group-text text-success">
                      <span class="fas fa-envelope"></span>
                  </div>
                </div>
                <div class="invalid-feedback"></div>
              </div>
              <div class="form-group mb-3">
                <label class="text-center d-block">CAPTCHA Security Check</label>
                <div id="captcha" class='col-md-6 offset-md-3  border-captcha text-center'></div>
                <label class='form-label mt-0 text-center d-block'> Type The Characters Below Correctly </label>
                <input type="text" class="form-control" placeholder="Enter Captcha Here" id="cpatchaTextBox" />
              </div>
              <div class="row">
                <div class="col-12 mb-2">
                  <button id='btn-submit' style="font-size: 1.5em;" type="submit" class="btn bg-color-1 text-white btn-block">
                    Check Status
                  </button>
                </div>
              <!-- /.col -->
              </div>
          </form>
        <?php
            }
            else{
              foreach ($status as $key => $value) 
              {  
                if( $key != 'iam' ){
                  ?>
                    
                    <div class='row pt-2'> 
                      <div class='col-md-6'>
                        <strong> <?= ucwords($key) ?> </strong> : - 
                      </div>
                      <div class='col-md-2'>
                      </div>
                      <div class='col-md-4'>
                      <?php
                        if(filter_var($value , FILTER_VALIDATE_URL)){
                      ?>
                            <a class='btn bg-color-1 text-white' href="<?=$value?>" target="_blank" >Click Here</a>
                      <?php
                        } 
                        else{
                          echo $value;
                        }
                      ?>
                      </div>
                    </div>
                    <!-- <?php
                      //if( $key == 'New Student Registration'){
                    ?> -->
                      <hr>
                    <!-- <?php
                      //}
                    ?> -->
                  <?php
                }
              }
            }
        ?>
        </div>
      </div>
      <!-- Forgot Password #END -->
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var URL = "<?php echo base_url("UserController/ajax_work");?>";
    </script>

<script>
    var code;
      function createCaptcha() {
        //clear the contents of captcha div first 
        document.getElementById('captcha').innerHTML = "";
        var charsArray =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
        var lengthOtp = 6;
        var captcha = [];
        for (var i = 0; i < lengthOtp; i++) {
          //below code will not allow Repetition of Characters
          var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
          if (captcha.indexOf(charsArray[index]) == -1)
            captcha.push(charsArray[index]);
          else i--;
        }
        var canv = document.createElement("canvas");
        canv.id = "captcha";
        canv.width = 120;
        canv.height = 50;
        var ctx = canv.getContext("2d");
        ctx.font = "25px Georgia";
        ctx.strokeText(captcha.join(""), 0, 30);
        //storing captcha so that can validate you can save it somewhere else according to your specific requirements
        code = captcha.join("");
        document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
      }
      let checkCaptcha = false;
      $('#btn-submit').on('click',function(e){
            if( checkCaptcha !== true ){
                var form = $('#forgot_pass');
                if( document.getElementById("cpatchaTextBox").value == code ) {
                    Swal.fire({
                        title: "Captcha",
                        text: "You Entered Correct Captcha",
                        icon: "success",
                        confirmButtonColor: "#fc9928",
                    }).then((result) => {
                        // console.log( result );
                        if (result.isConfirmed){
                            document.getElementById("cpatchaTextBox").disabled = true;
                            checkCaptcha = true;
                            return true;
                        }
                    });
                }
                else{
                    checkCaptcha = false;
                    createCaptcha();
                    Swal.fire({
                        title: "Captcha",
                        text: "You Entered Incorrect Captcha, Please Try Again",
                        icon: "error",
                        confirmButtonColor: "#fc9928"
                    });
                    return false;
                }
            }
            // e.preventDefault();
        });
      // function validateCaptcha(){
      //   // debugger
      //   if( document.getElementById("cpatchaTextBox").value == code ) {
      //     Swal.fire({
      //       title: "Captcha",
      //       text: "You Entered Correct Captcha",
      //       icon: "success",
      //       confirmButtonColor: "#fc9928",
      //     }).then((result) => {
      //       if (result.isConfirmed) {
      //         return true;
      //       }
      //     });
      //   }
      //   else{
      //     event.preventDefault();
      //     Swal.fire({
      //       title: "Captcha",
      //       text: "You Entered Incorrect Captcha, Please Try Again",
      //       icon: "error",
      //       confirmButtonColor: "#fc9928"
      //     });
      //     createCaptcha();
      //     return false;
      //   }
      // }
  </script>
</body>
</html>
