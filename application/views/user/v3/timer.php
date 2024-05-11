
<script type="text/javascript">
let assessmentStart = '<?= $assessmentStart ?>';

let EndTime = '<?= $EndTime ?>';
if( typeof myTimeout !== 'undefined'  ){
    clearTimeout(myTimeout);
}
var myTimeout;
$('.closeButton').click(function(){
    var base_url = '<?= base_url() ?>';
    var code = '<?= $code ?>';
    $.ajax({
        type : 'post',
        url : '<?php echo base_url()."uce_version_3/variation-time-update/"; ?>'+code+"/<?= $partName ?>",
        success : function(data){
            var response = JSON.parse(data);
            if( response.status == 'success' ){
                assessmentStart = new Date(response.data.dateTime);
                EndTime = new Date(response.data.newDateTime);
                myTimeout = setInterval( start , 1000);
            }
        }
    });
});
$('.timerDiv').removeClass('d-none');
let part = '<?= $partName ?>';
function start(){
    // console.log( 'Heloo' );
   
        // Get Date Now
    let dateNow = new Date().getTime();
    endTime = new Date(EndTime).getTime();
    // console.log( endTime , dateNow );
    // Find The Difference Between The Time Now And The Countdown Date
    let dateDiff = endTime - dateNow;
    console.log( dateDiff , endTime , EndTime );
    let days = Math.floor(dateDiff / (1000 * 60 * 60 * 24));
    let hours = Math.floor((dateDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((dateDiff % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((dateDiff % (1000 * 60)) / 1000);
    if (dateDiff < 0) {
        clearInterval(myTimeout);
        $.ajax({
                url: "<?php echo base_url(); ?>uce-version-3/finish_time",
                type : "post",
                dataType: "json",
                data:{
                    part: '<?= $partName ?>',
                    code : '<?= $code ?>'
                },
                success: function(data){
                    if(data.status == 'success')
                    {
                    alert('Time allocated for this assessment is over.');
                    // $('#exampleModalLong2').show();
                    window.location = "<?php echo base_url().'assessment-variations/three/'.$nextPartName."/".base64_encode($code) ?>";
                    }
                }
            });
        }
        else{
            document.querySelector(".minutes").innerHTML = minutes < 10 ? `0${minutes}` : minutes;
            document.querySelector(".seconds").innerHTML = seconds < 10 ? `0${seconds}` : seconds;
        }
        if (dateDiff <= 0) {
            clearInterval(myTimeout);
        }
}

if( EndTime != '' ){
    console.log( EndTime );
    myTimeout = setInterval( start , 1000);
}

function preventBack() { window.history.forward(); }
setTimeout("preventBack()", 0);
window.onunload = function () { null };
</script>