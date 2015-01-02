<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<script>
	function hide_hk(){	// For step 3 & 4
		$('#shipping-existing > .xten label').each(function(){
				if( $.trim($(this).text()) == 'Hong Kong' )
					$(this).parent().hide();
			});
	}// !step3and4_hide_hk()

	function do_valid_address(){
		alert('do_valid_address');
		if( $('#shipping-existing > .xten label').length == 1 && $.trim($('#shipping-existing > .xten label').text()) == 'Hong Kong'){
			$('#shipping-existing > .xten label').parent().hide();
			$('#button-shipping-address').hide();
		}else{
			$('#button-shipping-address').fadeIn();
		}
	}// !do_valid_address()

	$(document).ready(function() {
		$('body').addClass('checkout');
	});
</script>

<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="checkout">
    <div id="checkout">
      <div class="checkout-heading"><?php echo $text_checkout_option; ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php if (!$logged) { ?>
    <div id="payment-address">
      <div class="checkout-heading"><span><?php echo $text_checkout_account; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } else { ?>
    <div id="payment-address">
      <div class="checkout-heading"><span><?php echo $text_checkout_payment_address; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <?php if ($shipping_required) { ?>
    <div id="shipping-address">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_address; ?></div>
      <div class="checkout-content"></div>
    </div>
    <div id="shipping-method">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <div id="payment-method">
      <div class="checkout-heading"><?php echo $text_checkout_payment_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <div id="confirm">
      <div class="checkout-heading"><?php echo $text_checkout_confirm; ?></div>
      <div class="checkout-content"></div>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#checkout .checkout-content input[name=\'account\']').live('change', function() {
	if ($(this).attr('value') == 'register') {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_account; ?>');
	} else {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
	}
});

// My Script: btn.update
$('.checkout-heading a').live('click', function() {
	$('.checkout-content').slideUp('slow');

	$(this).parent().parent().find('.checkout-content').slideDown('slow');
	hide_hk();	// My Script
});
<?php if (!$logged) { ?>
$(document).ready(function() {
	<?php if(isset($quickconfirm)) { ?>
		quickConfirm();
	<?php }else{ ?>
	$.ajax({
		url: 'index.php?route=checkout/xlogin',
		dataType: 'html',
		success: function(html) {
			$('#checkout .checkout-content').html(html);

			$('#checkout .checkout-content').slideDown('slow');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	<?php } ?>
});
<?php } else { ?>
$(document).ready(function() {
	<?php if(isset($quickconfirm)) { ?>
		quickConfirm();
	<?php }else{ ?>
	// Step 2
	$.ajax({
		url: 'index.php?route=checkout/xpayment_address',
		dataType: 'html',
		success: function(html) {
			$('#payment-address .checkout-content').html(html);

			$('#payment-address .checkout-content').slideDown('slow');

			// My Script: Skip step 2
			$('#button-payment-address').trigger('click');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	<?php } ?>
});
<?php } ?>

// Checkout
$('#button-account').live('click', function() {
	localStorage.setItem('visited_cart', true);
	window.location = 'index.php?route=account/signup';
/*	$.ajax({
		url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').attr('value'),
		dataType: 'html',
		beforeSend: function() {
			$('#button-account').attr('disabled', true);
			$('#button-account').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-account').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(html) {
			$('.warning, .error').remove();

			$('#payment-address .checkout-content').html(html);

			$('#checkout .checkout-content').slideUp('slow');

			$('#payment-address .checkout-content').slideDown('slow');

			$('.checkout-heading a').remove();

			$('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	}); // !aJax*/
});

// Login
$('#button-login').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xlogin/validate',
		type: 'post',
		data: $('#checkout #login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#checkout .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Register
$('#button-register').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xregister/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').attr('disabled', true);
			$('#button-register').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-register').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
				<?php if ($options) { ?>
		        <?php foreach ($options as $option) { ?>
		        	if (json['error']['optionVE<?php echo $option['option_id']; ?>']) {
		        		$('#payment-address input[name=\'optionVE<?php echo $option['option_id']; ?>\'] ').after('<span class="error">' + json['error']['optionVE<?php echo $option['option_id']; ?>'] + '</span>');
		            	}
		        <?php }?>
		        <?php }?>

				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['email']) {
					$('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
				}

				if (json['error']['password']) {
					$('#payment-address input[name=\'password\'] + br').after('<span class="error">' + json['error']['password'] + '</span>');
				}

				if (json['error']['confirm']) {
					$('#payment-address input[name=\'confirm\'] + br').after('<span class="error">' + json['error']['confirm'] + '</span>');
				}
			} else if(json['addressRequired']){
				$.ajax({
					url: 'index.php?route=checkout/xpayment_address',
					dataType: 'html',
					success: function(html) {
						location.reload(true);



					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				}else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);

							$('#payment-address .checkout-content').slideUp('slow');

							$('#shipping-method .checkout-content').slideDown('slow');

							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

							$.ajax({
								url: 'index.php?route=checkout/xshipping_address',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php?route=checkout/xshipping_address',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('#payment-address .checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');
							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('#payment-address .checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#checkout .checkout-heading a').remove();
						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/xpayment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);

						$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Payment Address
$('#button-payment-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xpayment_address/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address textarea, #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-address').attr('disabled', true);
			$('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-payment-address').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
				<?php if ($options) { ?>
		        <?php foreach ($options as $option) { ?>
		        <?php if($option['section']==2){ ?>
		        	if (json['error']['optionVE<?php echo $option['option_id']; ?>']) {
		        		$('#payment-address input[name=\'optionVE<?php echo $option['option_id']; ?>\'] ').after('<span class="error">' + json['error']['optionVE<?php echo $option['option_id']; ?>'] + '</span>');
		            	}
		        <?php }?>
		        <?php }?>
		        <?php }?>
				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($shipping_required) { ?>
				$.ajax({
					url: 'index.php?route=checkout/xshipping_address',
					dataType: 'html',
					success: function(html) {
						$('#shipping-address .checkout-content').html(html);

						$('#payment-address .checkout-content').slideUp('slow');

						$('#shipping-address .checkout-content').slideDown('slow');

						$('#payment-address .checkout-heading a').remove();
						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

						// This: 第 2 步： 送貨地址
						// My Script: Skip step 3
//						$('#button-shipping-address').trigger('click');

						// Existing Address
						$('#shipping-address .xleft > .xten label, #shipping-address > .checkout-heading > a')
							.click(function(){
								do_valid_address();
							});

						// Add Address
						$('#shipping-address .xright > .xten label')
							.click(function(){
								$('#button-shipping-address').fadeIn();
							});

						// Initial
						do_valid_address();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('#payment-address .checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/xpayment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Shipping Address
$('#button-shipping-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xshipping_address/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address textarea, #shipping-address input[type=\'hidden\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').attr('disabled', true);
			$('#button-shipping-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping-address').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
				<?php if ($options) { ?>
		        <?php foreach ($options as $option) { ?>
		        <?php if($option['section']==2){ ?>
		        	if (json['error']['optionVE<?php echo $option['option_id']; ?>']) {
		        		$('#shipping-address input[name=\'optionVE<?php echo $option['option_id']; ?>\'] ').after('<span class="error">' + json['error']['optionVE<?php echo $option['option_id']; ?>'] + '</span>');
		            	}
		        <?php }?>
		        <?php }?>
		        <?php }?>

				if (json['error']['firstname']) {
					$('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['email']) {
					$('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

						$.ajax({
							url: 'index.php?route=checkout/xshipping_address',
							dataType: 'html',
							success: function(html) {
								$('#shipping-address .checkout-content').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});

				$.ajax({
					url: 'index.php?route=checkout/xpayment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Guest
$('#button-guest').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xguest/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest').attr('disabled', true);
			$('#button-guest').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
				<?php if ($options) { ?>
		        <?php foreach ($options as $option) { ?>
		        	if (json['error']['optionVE<?php echo $option['option_id']; ?>']) {
		        		$('#payment-address input[name=\'optionVE<?php echo $option['option_id']; ?>\'] ').after('<span class="error">' + json['error']['optionVE<?php echo $option['option_id']; ?>'] + '</span>');
		            	}
		        <?php }?>
		        <?php }?>

				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['email']) {
					$('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);

							$('#payment-address .checkout-content').slideUp('slow');

							$('#shipping-method .checkout-content').slideDown('slow');

							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

							$.ajax({
								url: 'index.php?route=checkout/xguest_shipping',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php?route=checkout/xguest_shipping',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('#payment-address .checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');

							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('#payment-address .checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Guest Shipping
$('#button-guest-shipping').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/xguest_shipping/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-address textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest-shipping').attr('disabled', true);
			$('#button-guest-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest-shipping').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
				<?php if ($options) { ?>
		        <?php foreach ($options as $option) { ?>
		        <?php if($option['section']==2){ ?>
		        	if (json['error']['optionVE<?php echo $option['option_id']; ?>']) {
		        		$('#shipping-address input[name=\'optionVE<?php echo $option['option_id']; ?>\'] ').after('<span class="error">' + json['error']['optionVE<?php echo $option['option_id']; ?>'] + '</span>');
		            	}
		        <?php }?>
		        <?php }?>
		        <?php }?>

				if (json['error']['firstname']) {
					$('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-shipping-method').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/validate',
		type: 'post',
		data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-method').attr('disabled', true);
			$('#button-shipping-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping-method').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('#shipping-method .checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

						// My Script: Skip step 5
						$('#button-payment-method').trigger('click');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-payment-method').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_method/validate',
		type: 'post',
		data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-method').attr('disabled', true);
			$('#button-payment-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-payment-method').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/xconfirm',
					dataType: 'html',
					success: function(html) {
						$('#confirm .checkout-content').html(html);

						$('#payment-method .checkout-content').slideUp('slow');

						$('#confirm .checkout-content').slideDown('slow');

						$('#payment-method .checkout-heading a').remove();

						$('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
function quickConfirm(module){
	$.ajax({
		url: 'index.php?route=checkout/xconfirm',
		dataType: 'html',
		success: function(html) {
			$('#confirm .checkout-content').html(html);
			$('#confirm .checkout-content').slideDown('slow');
			$('.checkout-heading a').remove();
			$('#checkout .checkout-heading a').remove();
			$('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			$('#shipping-address .checkout-heading a').remove();
			$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			$('#shipping-method .checkout-heading a').remove();
			$('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			$('#payment-address .checkout-heading a').remove();
			$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			$('#payment-method .checkout-heading a').remove();
			$('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//--></script>
<style type="text/css">
<!--
.xten label {
    margin:4px;
    background-color:#ccc;
    border-radius:4px;
    border:1px solid #D0D0D0;
    overflow:auto;
    float:left;
    text-align:center;
    display:block;
    padding: 4px;
    width:80%;
}
.xten label:hover {
    background:#9E2323;
    color:#fff;
    width:80%;
}
.xten   input {
    position:absolute;
    display:none;
}
.xten input:checked  +label {
    background-color:#9E2323;
    color:#fff;
    width:80%;
}
.xleft , .xright{
	width:48%;
}
.xleft{float:left}.xright{float:right}
-->
</style>
<?php echo $footer; ?>