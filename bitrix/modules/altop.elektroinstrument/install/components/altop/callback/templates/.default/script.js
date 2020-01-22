function button_callback(path, template, email_to, required) {
    $.ajax({
        type: "POST",
        url : path+'/component.php',
        data: ({
            NAME			: $("#callback_name").val(),
            TEL				: $("#callback_tel").val(),
			TIME			: $("#callback_time").val(),
			MESSAGE			: $("#callback_message").val(),
			captcha_word	: $("#callback_captcha_word").val(),
            captcha_sid		: $("#callback_captcha_sid").val(),
			METHOD			: $("#callback_method").val(),
			PATH			: path,
            TEMPLATE		: template,
			EMAIL_TO		: email_to,
			REQUIRED		: required
        }),
        success: function (html) {
            $('#echo_callback_form').html(html);
        }
    });
}