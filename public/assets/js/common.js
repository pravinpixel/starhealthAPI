  $('#kt_forgot_in_submit').click(function () {

let form_data = $("#forgot-form").serialize();
console.log(form_data);
$.ajax({
    url: submit_url,
    type: "POST",
    data: form_data,
    success: function (response) {
      $('.field-error').text(" ");
        toastr.success(response.message);
    },
    error: function (response) {
      console.log(response);
        $("#forgot-formt").attr("disabled", false);
        $.each(response.responseJSON.errors, function (field_name, error) {
            $('#' + field_name + '-error').text(error[0]);
        });
        toastr.error(response.responseJSON.message);
    }
});
});
