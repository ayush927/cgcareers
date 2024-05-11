<body class="hold-transition sidebar-mini">

<div class="wrapper">
<style>
  .copy-text-btn:active {
    background: #809ce2;
  }
  .copy-text-btn button:before {
    content: "Copied";
    position: absolute;
    top: -45px;
    right: 0px;
    background: #5c81dc;
    padding: 8px 10px;
    border-radius: 20px;
    font-size: 15px;
    display: none;
  }
  .copy-text-btn button:after {
    content: "";
    position: absolute;
    top: -20px;
    right: 25px;
    width: 10px;
    height: 10px;
    background: #5c81dc;
    transform: rotate(45deg);
    display: none;
  }
  .copy-text-btn.active button:before,
  .copy-text-btn.active button:after {
    display: block;
  }
</style>

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>All Codes</h1>

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

      <?php view('message'); ?>

      <div class="row">

          <div class="col-12">

            <div class="card">

              <div class="card-header">

                <h3 class="card-title">Code List</h3>


              </div> 

              <!-- /.card-header -->

              

              <div class="card-body">

                <table id="example2" class="table table-bordered table-striped">

                  <thead>

                    <tr>

                    

                      <th>Solution</th>

                      <th>Total Codes Purchased</th>

                      <th>Unused Codes</th>
                      
                      <th>Solution Link</th>

                    </tr>

                  </thead>

                  <tbody>
                    <?php
                      $email = $user['email'];

                      foreach ($s->result() as $solution)  
                      {
                        $solution = $solution->solution;
                        $where = "email='$email' and solution='$solution'";
                        $this->db->where($where);
                        $d = $this->db->get('generated_code_details');
                        $solutionLink = getQuery( [ 'where' => [ 'reseller_id' => $user['id'] , 'solution' => $solution ] , 'table' => 'assessment_link' , 'single' => true ] );
                        $where2 = "email='$email' and solution='$solution' and status='UnUsed'";
                        $this->db->where($where2);
                        $n = $this->db->get('generated_code_details')->num_rows();
                        $i=0;
                        foreach( $d->result() as $d )
                        {
                            $i++;
                        }
                        if($i>0)
                        {
                        ?>
                          <tr>
                            <td> <?= $solution;?></td>
                            <td> <?= $i ?> </td>
                            <td> <?= $n ?></td>                       
                            <td> <?php
                            if( !empty($solutionLink) ) {
                              $assessment_link = base_url().'take-assessment/'.$solutionLink['link'];
                            ?>
                              <span id="<?= $solution ?>"> <?= $assessment_link ?> </span> <i onclick='copySelection("<?= $solution ?>")' class='text-copy-btn btn btn-info fa fa-copy' title='copy'></i> <i onclick='shareSelection("<?= $assessment_link ?>")' class="btn btn-success fa fa-share-square" title='Share'></i>
                            <?php
                              }
                              else{
                            ?> 
                              <a class="btn btn-info" href="<?=base_url().'common-action/create-assessment-link/'.$solution ?> "> Create Link </a>
                            <?php
                              }
                            ?>
                          </td>
                        </tr>
                        <?php
                          }
                        }
                      // $email = $user['email'];

                      // foreach ($s->result() as $solution)  
                      // {
                      //     $solution = $solution->solution;

                      //     $where = "email='$email' and solution='$solution'";

                      //     $this->db->where($where);

                      //     $d = $this->db->get('generated_code_details');

                      //     $solutionLink = getQuery( [ 'where' => [ 'reseller_id' => $user['id'] , 'solution' => $solution ] , 'table' => 'assessment_link' , 'single' => true ] );

                      //     $where2 = "email='$email' and solution='$solution' and status='UnUsed'";

                      //     $this->db->where($where2);

                      //     $n = $this->db->get('generated_code_details')->num_rows();

                      //     $i=0;

                      //     foreach( $d->result() as $d )
                      //     {
                      //         $i++;
                      //     }
                      //     if($i>0)
                      //     {
                      //       echo "<tr><td>";
                      //       echo $solution;
                      //       echo "</td>";
                      //       echo "<td>";
                      //       echo $i;
                      //       echo "</td>";
                      //       echo "<td>";
                      //       echo $n;
                      //       echo "</td>";                       
                      //       echo "<td>";
                      //       echo ( !empty($solutionLink) ?  base_url().'UserController/take-assessment/'.$solutionLink['link'] : '<a class="btn btn-info" href="'.base_url().'UserController/create-assessment-link/'.$solution.'"> Create Link </a>' );
                      //       echo "</td></tr>";
                      //     }
                      // }
                    ?>
                  </tbody>

                </table>
              </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

  $(function () {

    $("#example1").DataTable({

      "responsive": true, "lengthChange": false, "autoWidth": false,

      "buttons": ["excel"]

    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({

      "paging": false,

      "lengthChange": false,

      "searching": false,

      "ordering": true,

      "info":false ,

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