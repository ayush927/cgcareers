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