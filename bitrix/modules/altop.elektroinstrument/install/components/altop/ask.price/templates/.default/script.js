function button_ask_price(path, template, email_to, required, element_id) {
    $.ajax({
        type: "POST",
        url : path+'/component.php',
        data: ({
            NAME			: $("#ask_price_name_" + element_id).val(),            
			TEL				: $("#ask_price_tel_" + element_id).val(),
			TIME			: $("#ask_price_time_" + element_id).val(),
			MESSAGE			: $("#ask_price_message_" + element_id).val(),
			captcha_word	: $("#ask_price_captcha_word_" + element_id).val(),
            captcha_sid		: $("#ask_price_captcha_sid_" + element_id).val(),
			METHOD			: $("#ask_price_method_" + element_id).val(),
			PATH			: path,
            TEMPLATE		: template,
			EMAIL_TO		: email_to,
			REQUIRED		: required,
			ELEMENT_ID		: element_id
        }),
        success: function (html) {
            $('#echo_ask_price_form_' + element_id).html(html);
        }
    });
}