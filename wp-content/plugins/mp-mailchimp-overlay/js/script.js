jQuery(document).ready(function () {
    jQuery("form[name='mpm-settings-form']").submit(function (event) {
        event.preventDefault();

        var self = this,
            apiKey = jQuery(self).find("[name='mp_api_key']").val(),
            listId = jQuery(self).find("[name='mp_list_id']").val(),
            stringClose = jQuery(self).find("[name='mp_string_close']").val(),
            stringMessage = jQuery(self).find("[name='mp_string_message']").val(),
            couponDiscount = jQuery(self).find("[name='mp_coupon_discount']").val(),
            couponMailSubject = jQuery(self).find("[name='mp_coupon_mail_subject']").val(),
            couponMailContent = jQuery(self).find("[name='mp_coupon_mail_content']").val(),
            loadingText = "Saving settings, Please wait...",
            successText = "Settings has been saved",
            errorText = "Something went wrong, Please try again.",
            addLoading = function () {
                jQuery(self).find(".message .fa").addClass("loading");
                jQuery(self).find(".message p").html(loadingText);
            },
            clearLoading = function () {
                jQuery(self).find(".message .fa").removeClass("loading");
            },
            successMessage = function () {bas
                jQuery(self).find(".message p").html(successText);
            },
            errorMessage = function () {
                jQuery(self).find(".message p").html(errorText);
            };

        addLoading()
        jQuery.ajax({
            "url": ajax_object.ajax_url,
            "method": "POST",
            "data": {
                "action": "mp_update_admin",
                "mp_api_key": apiKey,
                "mp_list_id": listId,
                "mp_string_close": stringClose,
                "mp_string_message": stringMessage,
                "mp_coupon_discount": couponDiscount,
                "mp_coupon_mail_subject": couponMailSubject,
                "mp_coupon_mail_content": couponMailContent
            },
            "success": function () {
                clearLoading();
                successMessage();
            },
            "error": function () {
                clearLoading();
                errorMessage();
            }
        });
    });
});
