function button_boc(path, required, element_id) {
    $.ajax({
        type: "POST",
        url : path + "/script.php",
        data: ({
            NAME					: $("#boc_name_" + element_id).val(),
            TEL						: $("#boc_tel_" + element_id).val(),
			EMAIL					: $("#boc_email_" + element_id).val(),
			MESSAGE					: $("#boc_message_" + element_id).val(),
			captcha_word			: $("#boc_captcha_word_" + element_id).val(),
            captcha_sid				: $("#boc_captcha_sid_" + element_id).val(),
			METHOD					: $("#boc_method_" + element_id).val(),
			element_props			: $("#boc_element_props_" + element_id).val(),
			element_select_props	: $("#boc_element_select_props_" + element_id).val(),
			quantity				: $("#boc_quantity_" + element_id).val(),
			personTypeId			: $("#boc_personTypeId_" + element_id).val(),
			propNameId				: $("#boc_propNameId_" + element_id).val(),
			propTelId				: $("#boc_propTelId_" + element_id).val(),
			propEmailId				: $("#boc_propEmailId_" + element_id).val(),
			deliveryId				: $("#boc_deliveryId_" + element_id).val(),
			paysystemId				: $("#boc_paysystemId_" + element_id).val(),			
			buyMode					: $("#boc_buyMode_" + element_id).val(),			
			dubLetter				: $("#boc_dubLetter_" + element_id).val(),			
			REQUIRED				: required,
			ELEMENT_ID				: element_id
        }),
        success: function (html) {
            $("#echo_boc_form_" + element_id).html(html);
		}
    });
}