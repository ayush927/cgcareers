<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Assessment Variation</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right pt-1">
                      <li class="breadcrumb-item"><a href="<?php echo base_url("UserController/dashboard"); ?>">Dashboard</a></li>
                      <li class="breadcrumb-item active">Assessment Variation</li>
                    </ol>
                  </div>
                </div>
              </div><!-- /.container-fluid -->
            </section>
            <section class="content">
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
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-md-3">
                            <h3 class="card-title">All Sources</h3>
                          </div>
                        </div>
                      </div> 
                      <!-- /.card-header -->
                      
                      <div class="card-body table-responsive p-0">
                        <table class="table table-bordered text-nowrap">
                          <thead>
                            <tr>
                              <th>Solution</th>
                              <th>Current Variation</th>
                              <th>Update Variation</th>
                              <th>Current Report Variation</th>
                              <th>Update Report Variation</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>UCE</td>
                              <td><strong><?= ucwords($variation['uce_variation']) ?></strong></td>
                              <td>
                                  <a class="btn btn-sm btn-primary" href="<?php echo base_url("userController/update-variation/one")?>">One</a>
                                  <a class="btn btn-sm btn-info" href="<?php echo base_url("userController/update-variation/two")?>">Two</a>
                                  <a class="btn btn-sm btn-secondary" href="<?php echo base_url("userController/update-variation/three")?>">Three</a>
                              </td>
                              <td><strong><?= ucwords($variation['uce_report_variation']) ?></strong></td>
                              <td>
                                  <a class="btn btn-sm btn-primary" href="<?php echo base_url("userController/update-report-variation/one")?>">One</a>
                                  <a class="btn btn-sm btn-info" href="<?php echo base_url("userController/update-report-variation/two")?>">Two</a>
                                  <a class="btn btn-sm btn-secondary" href="<?php echo base_url("userController/update-report-variation/three")?>">Three</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        
                      </div>
                     
                      
                     
                    </form>
                      <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>