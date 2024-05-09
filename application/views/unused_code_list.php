<body class="hold-transition sidebar-mini">

<div class="wrapper">

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Unused Code List</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>

              <li class="breadcrumb-item active">Code List</li>

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

                <h3 class="card-title">Code List</h3>



                <!-- <div class="card-tools">

                  <div class="input-group input-group-sm" style="width: 150px;">

                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">



                    <div class="input-group-append">

                      <button type="submit" class="btn btn-default">

                        <i class="fas fa-search"></i>

                      </button>

                    </div>

                  </div>

                </div>-->

              </div> 

              <!-- /.card-header -->

              

              <!-- <div class="card-body table-responsive p-0" id="employee_table"> -->



              <!-- <div align="center">  

                     <button name="create_excel" id="create_excel" class="btn btn-success">Create Excel File</button>  

                </div> -->

                <div class="card-body">

                <table id="example1" class="table table-bordered table-striped">

                <!-- <div class="table-responsive" >   

                <table class="table table-bordered text-nowrap"> -->

                  <thead>

                    <tr>

                    <th>Solution</th>
                      <th>Code</th>
                      <th>Status</th>
                      <th>Link</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    if( is_array( $h ) ){
                      foreach ($h as $row)
                      {
                    ?>
                      <tr>
                        <td><?php echo $row->solution;?></td>
                        <td><?php echo $row->code;?></td>
                        <td><?php echo $row->status;?></td>
                        <td> 
                          <?php
                            if( $row->link_status == 'enabled' ){
                              $assessment_link = base_url().'take-assessment/'.base64_encode($row->code);
                            ?>
                              <span style='display:none;' id="<?= $row->solution ?>"> <?= $assessment_link ?> </span> <i onclick='copySelection("<?= $row->solution ?>")' class='text-copy-btn btn btn-info fa fa-copy' title='copy'></i> <i onclick='shareSelection("<?= $assessment_link ?>")' class="btn btn-success fa fa-share-square" title='Share'></i>
                            <?php
                              }
                              else{
                            ?> 
                              <a class="btn btn-info" href="<?=base_url().'common-action/create-assessment-link/'.$row->code ?> "> Create Link </a>
                            <?php
                              }
                          ?>
                        </td>
                      </tr>
                    <?php 
                      }
                    }
                    else{
                        foreach ($h->result() as $row)  
                        {
                          ?>
                            <tr>
                              <td><?php echo $row->solution;?></td>
                              <td><?php echo $row->code;?></td>
                              <td><?php echo $row->status;?></td>
                              <td> 
                                <?php
                                  if( $row->link_status == 'enabled' ){
                                    $assessment_link = base_url().'take-assessment/'.base64_encode($row->code);
                                  ?>
                                    <span style='display:none;' id="<?= $row->solution ?>"> <?= $assessment_link ?> </span> <i onclick='copySelection("<?= $row->solution ?>")' class='text-copy-btn btn btn-info fa fa-copy' title='copy'></i> <i onclick='shareSelection("<?= $assessment_link ?>")' class="btn btn-success fa fa-share-square" title='Share'></i>
                                  <?php
                                    }
                                    else{
                                  ?> 
                                    <a class="btn btn-info" href="<?=base_url().'common-action/create-assessment-link/'.$row->code ?> "> Create Link </a>
                                  <?php
                                    }
                                ?>
                              </td>
                            </tr>
                          <?php 
                        }
                    }
                  ?> 

                      

                      

                  

                    

                    

                    

                  </tbody>

                </table>

                

              </div>

             

              

             

</form>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

  $(function () {

    $("#example1").DataTable({

    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({

      "paging": true,

      "lengthChange": false,

      "searching": false,

      // "ordering": true,

      "info": true,

      "autoWidth": false,

      "responsive": true,

    });

  });

  function copySelection( solution ){
      var temp = $("<input>");
      $("body").append(temp);
      temp.val($('#'+solution).text()).select();
      document.execCommand("copy");
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Link Has been copied",
        showConfirmButton: false,
        timer: 1500,
        width : 200
      });
      temp.remove();
  }
  

  function shareSelection( link ){
    Swal.fire({
      title: "Submit link Through Email",
      input: "email",
      inputAttributes: {
        autocapitalize: "off"
      },
      showCancelButton: true,
      confirmButtonText: "Share Link",
      showLoaderOnConfirm: true,
      preConfirm: async (login) => {
        try {
          const sendMailUrl = `<?= base_url('common-action/send-email/') ?>${btoa(link)}/${btoa(login)}`;
          const response = await fetch(sendMailUrl);
          // if (!response.ok) {
            // return Swal.showValidationMessage(`
              // ${JSON.stringify(await response.json())}
            // `);
          // }
          return response.json();
        } catch (error) {
          Swal.showValidationMessage(`
            Request failed: ${error}
          `);
        }
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      console.log( result );
      if (result.isConfirmed) {
        if( result.value.msg == 'OTP SEND' ){ 
          msg  = 'OTP has been send please check and take assessment'
          
        }
        else if( result.value.msg == 'NOT USER' ){
          msg  = 'User already exist in system , please check email address'
        }
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: msg,
          showConfirmButton: false,
          timer: 1500,
          width : 400
        });
      }
    });
  }

</script>

      