function button_boc(path, required, element_id) {
    $.ajax({
        type: "POST",
        url : path + "/script.php",
        data: ({
            NAME			: $("#boc_cart_name").val(),
            TEL				: $("#boc_cart_tel").val(),
			EMAIL			: $("#boc_cart_email").val(),
			MESSAGE			: $("#boc_cart_message").val(),
			captcha_word	: $("#boc_captcha_word_" + element_id).val(),
            captcha_sid		: $("#boc_captcha_sid_" + element_id).val(),
			METHOD			: $("#boc_cart_method").val(),
			personTypeId	: $("#boc_cart_personTypeId").val(),
			propNameId		: $("#boc_cart_propNameId").val(),
			propTelId		: $("#boc_cart_propTelId").val(),
			propEmailId		: $("#boc_cart_propEmailId").val(),
			deliveryId		: $("#boc_cart_deliveryId").val(),
			paysystemId		: $("#boc_cart_paysystemId").val(),
			buyMode			: $("#boc_cart_buyMode").val(),			
			dubLetter		: $("#boc_cart_dubLetter").val(),			
			REQUIRED		: required,
			ELEMENT_ID		: element_id
        }),
        success: function (html) {
            $("#echo_boc_cart_form").html(html);
        }
    });
}