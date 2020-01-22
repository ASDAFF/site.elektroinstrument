function button_comment(object_id, object_name, comments_iblock_id, url, path, template, captcha, pre_moder, props) {
	$.ajax({
		type: "POST",
		url : path+"/component.php",
		data: ({
			NAME            : $("#comment_name").val(),
			captcha_word    : $("#comment_captcha_word").val(),
            captcha_sid     : $("#comment_captcha_sid").val(),
            TEXT            : $("#comment_text").val(),
			METHOD			: $("#comment_method").val(),
            URL             : url,
            OID             : object_id,
			ONAME           : object_name,
            CID             : comments_iblock_id,
            PATH            : path,
            TEMPLATE        : template,            
            CAPTCHA         : captcha,
            PRE_MODER       : pre_moder,
            PROPS           : props
        }),
        success: function (html) {
            $("#echo_comment_form").html(html);
        }
    });
}