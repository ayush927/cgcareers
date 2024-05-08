<body class="hold-transition sidebar-mini">

<div class="wrapper">

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
                  // pre(  $user , 1 );
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
                        echo "<tr><td>";
                        echo $solution;
                        echo "</td>";
                        echo "<td>";
                        echo $i;
                        echo "</td>";
                        echo "<td>";
                        echo $n;
                        echo "</td>";                       
                        echo "<td>";
                        echo ( !empty($solutionLink) ?  base_url().'UserController/take-assessment/'.$solutionLink['link'] : '<a class="btn btn-info" href="'.base_url().'UserController/create-assessment-link/'.$solution.'"> Create Link </a>' );
                        echo "</td></tr>";
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

</script>