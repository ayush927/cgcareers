<br>

<br>

<footer class="fixed-bottom bg-white border-top">

  <!-- Copyright -->
  <div class="text-center" style="margin: 10px;font-size: .8em;">Copyright &copy; <?php echo date("Y"); ?> 
    <a href="https://respicite.com"> Respicite. All Rights Reserved </a>
  </div>
  <!-- Copyright -->

</footer>

  <!-- Control Sidebar -->

  <!--<aside class="control-sidebar control-sidebar-dark">-->

    <!-- Control sidebar content goes here -->

  <!--</aside>-->

  <!-- /.control-sidebar -->

</div>

 <?php

//         echo "Hello";

//         die;

//     ?>

<!-- ./wrapper -->

<!-- jQuery -->

<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- jQuery UI 1.11.4 -->

<script src="<?php echo base_url().'assets/plugins/jquery-ui/jquery-ui.min.js';?>"></script>

<script src="<?php echo base_url()?>assets/draganddrop.js?rndstr=<?=  substr(md5(rand()) , 10 ) ?>"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>

  $.widget.bridge('uibutton', $.ui.button)

</script>

<!-- Bootstrap 4 -->

<script src="<?php echo base_url().'assets/plugins/bootstrap/js/bootstrap.min.js';?>"></script>

<!-- ChartJS -->

<!-- <script src="<?php echo base_url().'assets/plugins/chart.js/Chart.min.js';?>"></script> -->

<!-- Sparkline -->

<!-- <script src="<?php echo base_url().'assets/plugins/sparklines/sparkline.js';?>"></script> -->

<!-- JQVMap -->

<!-- <script src="<?php echo base_url().'assets/plugins/jqvmap/jquery.vmap.min.js';?>"></script> -->

<!-- <script src="<?php echo base_url().'assets/plugins/jqvmap/maps/jquery.vmap.usa.js';?>"></script> -->

<!-- jQuery Knob Chart -->

<!-- <script src="<?php echo base_url().'assets/plugins/jquery-knob/jquery.knob.min.js';?>"></script> -->

<!-- daterangepicker -->

<script src="<?php echo base_url().'assets/plugins/moment/moment.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/daterangepicker/daterangepicker.js';?>"></script>

<!-- Tempusdominus Bootstrap 4 -->

<script src="<?php echo base_url().'assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js';?>"></script>

<!-- Summernote -->

<!-- <script src="<?php echo base_url().'assets/plugins/summernote/summernote-bs4.min.js';?>"></script> -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- overlayScrollbars -->

<script src="<?php echo base_url().'assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js';?>"></script>

<!-- AdminLTE App -->

<script src="<?php echo base_url().'assets/dist/js/adminlte.js';?>"></script>

<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url().'assets/dist/js/demo.js';?>"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- <script src="<?php echo base_url().'assets/dist/js/pages/dashboard.js';?>"></script> -->

<script src="<?php echo base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js';?>"></script>

<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js';?>"></script>

<!-- <script src="<?php echo base_url().'assets/plugins/jszip/jszip.min.js';?>"></script> -->

<!-- <script src="<?php echo base_url().'assets/plugins/pdfmake/pdfmake.min.js';?>"></script> -->

<script src="<?php echo base_url().'assets/plugins/pdfmake/vfs_fonts.js';?>"></script>

<!-- <script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.html5.min.js';?>"></script> -->

<!-- <script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.print.min.js';?>"></script> -->

<!-- <script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.colVis.min.js';?>"></script> -->

<!-- <script src="<?php echo base_url().'assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js'; ?>"></script> -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
      // var hours = 5; // to clear the localStorage after 1 hour
               // (if someone want to clear after 8hrs simply change hours=8)
      var now = new Date().getTime();
      var setupTime = localStorage.getItem('setupTime');
      if (setupTime == null){
          localStorage.setItem('setupTime', now)
      }
      else{
          if(now-setupTime > 15*60*1000) {
              localStorage.clear()
              localStorage.setItem('setupTime', now);
          }
      }
      if( typeof key !== "undefined" ){
        hadModal = localStorage.getItem(key);
        
        // Show the modal only if new user
        if (!hadModal) {
          $('#modal_uce_part').modal('show');
        }
      }
      // If modal is displayed, store that in localStorage
      $('.closeButton').on('click', function () {
          localStorage.setItem(key, true);
      })
      new DataTable('#example');

      $(function () {

        $('[data-toggle="tooltip"]').tooltip()

      })

      $('#summernote').summernote({});

      $('ul.float, ul.inline').sortable({

        update: function(evt) {

          console.log(JSON.stringify($(this).sortable('serialize')));

        }

      });

      // $(function () {

      //   bsCustomFileInput.init();

      // });

      var base_url = '<?= base_url() ?>';

      $('#clusterId').change(function(){

          var request = new XMLHttpRequest();

          var id = $('#clusterId').val();

          // Instantiating the request object

          request.open("GET", base_url+"career-library/get-career-path/"+btoa(id));



          // Defining event listener for readystatechange event

          request.onreadystatechange = function() {

              // Check if the request is compete and was successful

              if(this.readyState === 4 && this.status === 200) {

                  // Inserting the response from server into an HTML element

                  var data = JSON.parse(this.responseText)

                  // console.log(data.result)

                  $("#careerPath").html(data.result)

              }

          };

          // Sending the request to the server

          request.send();

      });


      $('#domainId').change(function(){

        var request = new XMLHttpRequest();

        var id = $('#domainId').val();

        // Instantiating the request object

        request.open("GET", base_url+"skill-development/get-sub-domain/"+btoa(id));



        // Defining event listener for readystatechange event

        request.onreadystatechange = function() {

            // Check if the request is compete and was successful

            if(this.readyState === 4 && this.status === 200) {

                // Inserting the response from server into an HTML element

                var data = JSON.parse(this.responseText)

                // console.log(data.result)

                $("#subDomainData").html(data.result)

            }

        };

        // Sending the request to the server

        request.send();

      });

      $('.btn-image').click(function (){

          $('#myModal').addClass('show')

      })

      $('.img-select').click(function (){

        $('#imageId').val($(this).data('id'));

        $('#myModal').removeClass('show')
        
        $('.modal-backdrop').remove()
        
        $("#myModal").css("display","");
        
        $('body').removeClass('modal-open')
        
      })
      
      $('#background_type').change(function (){

        var type = $(this).val();
        
        if( type != 1 ){
          
          $('#background_image').addClass('d-none');
          
          $('#background_color').removeClass('d-none');
          
        }
        
        else{
          $('#background_image').removeClass('d-none');
          $('#background_color').addClass('d-none'); 
        }
      })
      


      
      // $("#modal_uce_part").modal("show");
      $( '#messageModalbutton' ).click( function() {
        let modalMessage = $('#messageModal');
        modalMessage.removeClass('fade');
        modalMessage.css('display' , 'block');
        modalMessage.css('animate' , 'bounce');
        modalMessage.css('transition' , '1s');
      });

      $('.messageclose').click( function(){
          let modalMessage = $('#messageModal');
          modalMessage.addClass('fade');
          modalMessage.css('display' , 'none');
      });
      $('.select2').select2();
  </script>

  <?php
    if( isset($edit['whatyoulearn']) && isset($edit['skillyouget']) ){
      // pre( $edit['skillyouget'] );
      // pre( $edit['whatyoulearn'] );
  ?>
  <script>
      $(document).ready(function() {
        var selectList = <?= $edit['whatyoulearn'] ?> , selectList1 = <?=  $edit['skillyouget'] ?>  
        $('.whatyoulearnmultiple').select2({}).select2('val',selectList);
        $('.skillgetmultiple').select2({}).select2('val',selectList1);
        
      });
  </script>
  <?php
    }
    else{
  ?>
      <script>
        $(document).ready(function() {
            $('.whatyoulearnmultiple').select2();
            $('.skillgetmultiple').select2();
        });
      </script>
  <?php
    }
  ?>
  <script>
    // window.onload = function () {
    //   $("#exampleModalLong").modal("show");
    // };
    $(window).on('load', function(){
      $('.loading-overlay').addClass('d-none');
    })
    // $(document).ready(function(){
    // })
  </script>

</body>

</html>

