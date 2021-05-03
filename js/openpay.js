jQuery(document).ready(function($) {
    $('.openpayajax').click(function(){
		$('.responsemsg,ajax-loader').remove();
        $(this).parent().append("<img class='ajax-loader' src='../wp-content/plugins/openpay-for-woocommerce/images/ajax-loader.gif'/>");
        $(this).parent().append("<div class='responsemsg'></div>");
        var data = {
            'action' : 'openpay_minmax',
            'auth_user': $('input[name="woocommerce_wc-gateway-openpay_auth_user"]').val(),
            'auth_token': $('input[name="woocommerce_wc-gateway-openpay_auth_token"]').val(),
            'payment_mode': $('select[name="woocommerce_wc-gateway-openpay_payment_mode"]').val(),
            'region': $('select[name="woocommerce_wc-gateway-openpay_region"]').val() 
        };
        $.ajax({
            type : "post",
            dataType : "json",
            url : minMaxAjax.url,
            data : data,
            success: function(response) {
                console.log(response);
                $('input[name="woocommerce_wc-gateway-openpay_auth_user"]').val(response.auth_user);
                $('input[name="woocommerce_wc-gateway-openpay_auth_token"]').val(response.auth_token);
                $('select[name="woocommerce_wc-gateway-openpay_payment_mode"]').val(response.payment_mode);
                $('select[name="woocommerce_wc-gateway-openpay_region"]').val(response.region);
                $('input[name="woocommerce_wc-gateway-openpay_minimum"]').val(response.minimum);
                $('input[name="woocommerce_wc-gateway-openpay_maximum"]').val(response.maximum);
                $('.ajax-loader').hide();
                if (response.success) {
                    $('.responsemsg').html('Values updated successfully!');
                    $('.responsemsg').css('color','green');
                } else {
                    $('.responsemsg').html('Retailer identity key supplied not valid!');
                    $('.responsemsg').css('color','red');
                }
            }
        });
    });
});