<div class="table-responsive">
    <table id="clock-in-out-table" class="display" cellspacing="0" width="100%">
    </table>
</div>

<div class="modal fade" id="myModal">
<form action="#" id="in_location">
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= app_lang("not_clocked_id_yet") ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      <div class="clearfix">
            <div class="form-group">
                <div class="row">
                    <input type="text" id="data_post_id" hidden>
                    <?php
                            echo form_input(array(
                                "id" => "user_inlocation",
                                "name" => "user_inlocation",
                                "class" => "form-control",
                                "placeholder" => app_lang('location'),
                                "autocomplete" => "off",
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                </div>
            </div>
            
        </div>
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="button" class="btn btn-primary btn_save"><span data-feather="check-circle" class="icon-16 "></span> <?php echo app_lang('save'); ?></button></div>
    </div>
  </div>
                </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#clock-in-out-table").appTable({
            source: '<?php echo_uri("attendance/clock_in_out_list_data/"); ?>',
            order: [[0, "asc"]],
            columns: [
                {title: "<?php echo app_lang("team_members"); ?>"},
                {title: "<?php echo app_lang("status"); ?>", class: "w300"},
                {title: "<?php echo app_lang("clock_in_out"); ?>", class: "text-center w200"}
            ]
        });
    });
    $(document).on('click', '.spinning-btn', function(e) {
    e.preventDefault();
    
    var actionUrl = $(this).data('action-url');
    var data_post_id = $(this).data('post-id');
    $('#data_post_id').val(data_post_id);
    var pageName = $(this).data('page-name');
    if (pageName=='clockout') {
        $('#myModal').modal('show'); 
    }
});

$('.btn_save').click(function(e) {
    var in_location = $("#user_inlocation").val();
    var data_post_id = $("#data_post_id").val();
    e.preventDefault();
        $.ajax({
            url: '<?= base_url('attendance/log_time') ?>',
            type: 'POST',
            data: {location:in_location,user_id:data_post_id},
             beforeSend: function() {
                $("#btn_save").text("Sending....");
            },
            success: function(msg) {
                // var data = jQuery.parseJSON(msg);
                // $("#btn_save").text("Send OTP");
                // if (data.status == false) {
                //     toastr.error('Email id not found.');
                // } else if(data.status == true) {
				// 	timmerCount();
                //     $("#timer").css("display", "block");
                //     toastr.info('OTP has been sent your email ID '+email+'. The OTP will expire after 5 minutes.');
                //       $("#otp_input").css("display", "block");
                //       $("#verify_btn").css("display", "block");
                //       $("#loginbtn").attr("disabled", true);
                //       $("#loginbtn").text("Send OTP");
                // }
            },
        });
    
});

</script>