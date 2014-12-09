/* Mobile issue
	$(window).resize(function(){
		$('#powered').appendTo('#mCSB_1 .mCS_touch');
	});
*/

$(document).ready(function(){

	$('#header #cart').click(function(){
		window.location = base + 'index.php?route=checkout/cart';
	});

	// ---------- ---------- ---------- ---------- ----------
	// Redirect to Forget pwd & Register

	$('.frm_sidebar_login .btn_not_submit').click(function(){
		$('.frm_sidebar_login').submit(function(){
			return false;
		});

		if( $(this).hasClass('forgot_pwd') ){
			window.location = base + 'index.php?route=account/forgotten';
		}else{
			window.location = base + 'index.php?route=account/register';
		}
	});
	// ---------- ---------- ---------- ---------- ----------

	function refine_district(){
		$('body.signup .my_district select[name="zone_id"] option:nth-child(n+2), body.checkout .xaddress option:nth-child(n+2)')
		.each(function(){
			var str = $(this).text();
				str = str.substring(5);

			$(this).text(str);
		});
	}// !refine_district()

	function signup_refine(){
		if( $('body').hasClass('signup') ){



			// add_title
			$('table tr:[sort="a50"]')
				.before('<tr><td colspan="2"><h2>子女資料</h3></td></tr>')
				.slideDown('slow');

			// Hospital
				// Hide Hospital row
				$('table tr:[sort="a65"], table tr:[sort="a66"]')
					.hide();
				// IO
			$('table tr:[sort="a61"] select')
				.change(function(){
					var selected_index = $('table tr:[sort="a61"] select option:selected').index();	// 1 = Private , 2 = Public
					var my_row_1 = '';
					var my_row_0 = '';

					if( selected_index == 1 ){
						$('table tr:[sort="a66"]')
							.slideUp('fast');
						$('table tr:[sort="a65"]')
							.fadeIn('slow');
					}else{
						$('table tr:[sort="a65"]')
							.slideUp('fast');
						$('table tr:[sort="a66"]')
							.fadeIn('slow');
					}


				});

			// [想要的大王紙產品..]
			$('table tr:[sort="a70"] td, table tr:[sort="a95"] td')
				.animate({'padding-top': '50px'},'slow');

			// Receive EDM
				// Styling
					// Append to
			$('table tr[sort="a74"] td:last-child')
				.appendTo('table tr[sort="a74"] td:first-child');
			$('table tr[sort="a74"] td:first-child')
					// Wrap inner
				.wrapInner('<div class="edm_wrapper"></div>')
					// Cross <td>
				.attr('colspan',2).end()
				// Default checked
				.find('table tr[sort="a74"] input')
					.attr('checked', 'checked');
			$('table tr[sort="a75"]').css('opacity', 0);
				// Bind default EDM checkbox
			$('table tr[sort="a74"] input')
				.change(function(){
					var checked = $(this).attr('checked');
					if( checked == 'checked'){
						$('table tr[sort="a75"] input:last-child')
							.prop('checked', false).end()
						.find('table tr[sort="a75"] input:first-child')
							.prop('checked', true)
					}else{
						$('table tr[sort="a75"] input:first-child')
							.prop('checked', false).end()
						.find('table tr[sort="a75"] input:last-child')
							.prop('checked', true)
					}
			});

			// Captcha
			rand = Math.floor((Math.random() * 10000) + 1);

				// Tip
			$('table tr[sort="a9"] td:first-child')
				.html('<span class="required">*</span> 驗証碼 (請輪入數字：' + rand + ')');
				// Clear field for error return
			$('table tr[sort="a99"] td:last-child input').val('');

			// ----- -----  ----- -----  ----- -----  ----- -----  ----- -----
			// Form
			$('form').submit(function(){
				var user_captcha = $('table tr[sort="a99"] td:last-child input').val();
				if( user_captcha != rand )
					$('table tr[sort="a99"] td:last-child input').val('');

				var email 			= $('table tr:[sort="a20"] input').val();
				var confirm_email 	= $('table tr:[sort="a21"] input').val();
				if( email != confirm_email )
						$('table tr:[sort="a21"] input').val('');
			});
		}// !Lock: body class
	}// !signup_refine()

	function my_date_picker(){
		if( $('body').hasClass('my_date_picker') ){
			// Ref: lucianocosta.info/jquery.mtz.monthpicker
			// Wrap Fn.
			var this_year = new Date();
			this_year = this_year.getFullYear();
			this_year = parseInt(this_year);

			options = {
					    pattern: 'yyyy-mm', // Default is 'mm/yyyy' and separator char is not mandatory
					    selectedYear: this_year,
					    startYear: this_year - 6,
					    finalYear: this_year + 1,
					    monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
					};
// todo: xdiv[sort="a60"] input
			$('table tr[sort="a60"] input')	// [出生日期 / 預產期]
				.after('<span class="act_clear"><span>清除</span></span>')
				.monthpicker(options).end()
			// Clear field
			.find('span.act_clear').click(function(){
				$('input.mtz-monthpicker-widgetcontainer').val('');
			});
		}// Lock: body class
	}// !my_date_picker()

	// --------------------------------------------------
	// List View
	function redirect2child(){
		$('body.list_view .products').click(function(){
		window.location = $(this).children('a').attr('href');
		});
	}// !redirect2child()

	// Checkout
	function checkout_refine(){
		if( $('body').hasClass('checkout') ){
			$('#payment-address .xpassword > br').remove();
		}
	}// !checkout_refine()

	function captcha_ignore(){
		if( $('body').hasClass('account') && $('body').hasClass('edit') ||
			 $('body').hasClass('checkout') )
			$('table tr[sort="a99"]').fadeOut('fast').children('td').children('input').val(' ');
	}

	// --------------------------------------------------



	setTimeout(function(){
//		refine_district();
		signup_refine();
		my_date_picker();

		checkout_refine();
		redirect2child();	// List View
		captcha_ignore();
	}, 1000);

	// ---------- ---------- ---------- ---------- ----------

	$(document).ready(function(){
		function isInt(x) {
			return Math.floor(x) === x;
		}

		if ( $('body).hasClass(detail_view') ) {
			function isInt(x) {
				return Math.floor(x) === x;
			}

			// qty add +
			$('.btn.add').click(function(){
				$('#qty_box').val(function(){
					var qty = $(this).val();
						qty = parseInt(qty) + 1;
					return qty;
				})
			});

			// qty less -
			$('.btn.less').click(function(){
				$('#qty_box').val(function(){
					var qty = $(this).val();
					if( qty == 1 ) return 1;

						qty = parseInt(qty) - 1;
					return qty;
				})
			});

			$('#qty_box').change(function(){
				var qty = $(this).val();
				if( isInt(qty) == false)
					$(this).val(1);
			});
		}
	});

	// --------------------------------------------------
	// jQuery Fn.

	(function ( $ ) {

		// ---------- ---------- ---------- ---------- ----------
		$.fn.g2redeem = function() {

			var is_visited_cart = localStorage.getItem('visited_cart');
			if( is_visited_cart ){
				localStorage.removeItem("visited_cart");
				$('.btn_continue').attr('href', 'index.php?route=checkout/checkout');	// edit
			}

		}// !success()

		// ---------- ---------- ---------- ---------- ----------
		$.fn.editaccount = function() {

			$('table tbody')
				.children('tr:[sort="a9"], tr:[sort="a21"]')	// Captcha, Confirm email
				.hide().end()
				.find('tr:[sort="a20"] input')
					.prop('disabled', 'disabled')
					.addClass('disabled');

		}// !editaccount()

	}( jQuery ));

	// --------------------------------------------------

	$(function () {
		$(document).ready(function(){
		// ----- ----- ----- ----- -----
		// User / Guest

		if( $('body').hasClass('success') )
			$('body').g2redeem();

		// ----- ----- ----- ----- -----
		// Account


		if( $('body').hasClass('success') )
			$('body').g2redeem();

		if( $('body').hasClass('editaccount') )
			$('body').editaccount();
});
	});

		// ---------- ---------- ---------- ---------- ----------




/*(function($) {
	$.fn.success = function() {
		alert();
	};
})(this.jQuery);*/

	// Random
	var rand = '';


	// ---------- ---------- ---------- ---------- ----------
});
