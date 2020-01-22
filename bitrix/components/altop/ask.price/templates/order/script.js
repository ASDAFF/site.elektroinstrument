function button_order(path, template, email_to, required, element_id) {
    $.ajax({
        type: "POST",
        url : path+'/component.php',
        data: ({
            NAME			: $("#order_name_" + element_id).val(),
            TEL				: $("#order_tel_" + element_id).val(),
			TIME			: $("#order_time_" + element_id).val(),
			MESSAGE			: $("#order_message_" + element_id).val(),
			captcha_word	: $("#order_captcha_word_" + element_id).val(),
            captcha_sid		: $("#order_captcha_sid_" + element_id).val(),
			METHOD			: $("#order_method_" + element_id).val(),
			PATH			: path,
            TEMPLATE		: template,
			EMAIL_TO		: email_to,
			REQUIRED		: required,
			ELEMENT_ID		: element_id
        }),
        success: function (html) {
            $('#echo_order_form_' + element_id).html(html);
        }
    });
}