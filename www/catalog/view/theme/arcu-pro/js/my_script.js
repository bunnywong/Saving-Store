// $('.scrollbox:first-child').eq(0).height(1000);

// ---------- ---------- ---------- ---------- ----------
// jQuery Fn.

(function ( $ ) {
	// --------------------------------------------------
	// Fn. By module

	$.fn.district = function(){
		// address[1][zone_id]
		$(this).children('option:nth-child(n+2)')
			.each(function(){
				var str = $(this).text();
					str = str.substring(5);

				$(this).text(str);
			});
	}

	$.fn.my_date_picker = function(){
		// Ref: lucianocosta.info/jquery.mtz.monthpicker
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

		$(this).children().children('input')	// [出生日期 / 預產期]
			.after('<span class="act_clear"><span>清除</span></span>')
			.monthpicker(options).end()
		// Clear field
		.find('span.act_clear').click(function(){
			$('input.mtz-monthpicker-widgetcontainer').val('N/A');
		});
	}

	$.fn.zh_order_status = function(){
		$(this).each(function(){
			var status = $(this).text();
//			console.log(status);
			if( status != 'Canceled' & status != 'Complete' && status != 'Pending' && status != '' )
				$(this).remove();

			if( status == 'Canceled' )
				$(this).text('已取消');
			if( status == 'Complete' )
				$(this).text('已完成');
			if( status == 'Pending' )
				$(this).text('處理中');
		});
	}// !$.fn.zh_order_status
	// --------------------------------------------------
	// Fn. By page

	$.fn.backend = function(){

		setTimeout(function(){
			$('table tbody tr td select[name="address[1][zone_id]"').district();
		}, 1000);
		$(this).hasClass('owner')
			$(this).owner();

	}// !$.fn.backend

	$.fn.owner = function(){
		$('#sale > ul > li:nth-child(4)').click(function(){
			var url = $(this).find('ul > li:first-child > a').attr('href');
			window.location = url;
		});
	}// !$.fn.owner

	$.fn.admin_order_detail = function(){

		// Default <select> of Hong Kong
		if( $('select[name="payment_country_id"] option:selected').text() != 'Hong Kong' )
			$('select[name="payment_country_id"] option:contains("Hong Kong")').prop('selected', 'selected');
		if( $('select[name="shipping_country_id"] option:selected').text() != 'Hong Kong' )
			$('select[name="shipping_country_id"] option:contains("Hong Kong")').prop('selected', 'selected');

		$('select[name=\'payment_country_id\']').trigger('change');

		// Default <select> of 中西區 to avoid add product bug
		setTimeout(function(){
			if( $.trim($('select[name="payment_zone_id"] option:selected').val()) == "" )
				$('select[name="payment_zone_id"] option:nth-child(2)').prop('selected', 'selected');
		}, 1000);

	}// !$.fn.admin_order_detail

	$.fn.admin_sale_order = function(){

		$('select[name="filter_order_status_id"] option').zh_order_status();

	}

	$.fn.admin_sale_order_form = function(){

		$('select[name="order_status_id"] option').zh_order_status();

	}// !$.fn.admin_sale_order_form

	$.fn.admin_sale_order_info = function(){

		$('select[name="order_status_id"] option').zh_order_status();

	}// !$.fn.admin_sale_order_form



	$.fn.sidebar = function() {

		if(localStorage.getItem('lightbox') == null){

			var str = '<h1 style="vertical-align: top;">舊網站用戶？</h1>';
			str += '<div>';
			str += '<img src="/image/data/login/old_website_goon.png" style="max-height: 150px; margin-right: 20px;">';
			str += '<img src="/image/data/login/old_website_suzuran.png" style="max-height: 150px;">';
			str += '</div>';
			str += '<form action="http://savingstore.com.hk/index.php?route=account/returnuser" method="post" enctype="multipart/form-data" class="returnuser"><p>請輸入您註冊賬戶時填寫的電子郵件地址以及重設密碼，點擊繼續。<br>於電郵信箱開啟連結，你的賬戶將會重新開通</p><div class="content"><table class="form"><tbody><tr><td>郵箱地址：</td><td><input type="text" name="email" value=""></td></tr><tr><td>設定新密碼：</td><td><input type="password" name="password" value=""></td></tr><tr><td>設定新密碼（確認輸入）：</td><td><input type="password" name="password2" value=""></td></tr></tbody></table></div><div class="buttons"><div class="left"></div><div class="right"><input type="submit" value="繼續" class="button"></div></div></form>';

			$( "body" ).append('<div class="lightbox_wrapper"><div class="lightbox"><span class="cross"></span>'+ str +'</div></div>').end();

			$('.cross').click(function(){
				$('.lightbox_wrapper').remove();
			});

			// Set lightbox was showed
			localStorage.setItem('lightbox', 1);
		}

		// Click to cart
		$('#header #cart').click(function(){
			window.location = 'index.php?route=checkout/cart';
		});

		// ---------- ---------- ---------- ---------- ----------
		// IE only

		$('.ie9 .username, .ie9 .password').height(35);

		// ---------- ---------- ---------- ---------- ----------
		// Redirect to Forget pwd & Register

		$('.frm_sidebar_login .btn_not_submit').click(function(){
			$('.frm_sidebar_login').submit(function(){
				return false;
			});

			if( $(this).hasClass('forgot_pwd') ){
				window.location = 'index.php?route=account/forgotten';
			}else{
				window.location = 'index.php?route=account/register';
			}
		});
	}
	// ---------- ---------- ---------- ---------- ----------
	// iFrame handle
	// Cookie ref: https://github.com/carhartl/jquery-cookie

	$.fn.account_login = function() {

		var iframe_url = $.cookie('iframe_url');

		if( iframe_url != null ){
			$.removeCookie('iframe_url', { path: '/' });
			window.location = iframe_url+'&error=1';
		}
	}

	$.fn.account_logout = function() {

		var iframe_url = $.cookie('iframe_url');

		if( iframe_url != null ){
			$.removeCookie('iframe_url', { path: '/' });
			window.location = iframe_url;
		}
	}

	$.fn.account_account = function() {

		var iframe_url = $.cookie('iframe_url');

		if( iframe_url != null ){
			$.removeCookie('iframe_url', { path: '/' });
			window.location = iframe_url;
		}

	}// !$.fn.account_account

	// ---------- ---------- ---------- ---------- ----------

	$.fn.list_view = function() {

		// Added URL wrapper to whole item
		$('body.list_view .products').click(function(){
			window.location = $(this).children('a').attr('href');
		});
	}

	// ---------- ---------- ---------- ---------- ----------

	$.fn.detail_view = function() {
		function isInt(x) {
			return Math.floor(x) === x;
		}
		// ----- ----- ----- ----- -----
		// qty add +

		$('.btn.add').click(function(){
			$('#qty_box').val(function(){
				var qty = $(this).val();
					qty = parseInt(qty) + 1;
				return qty;
			})
		});
		// ----- ----- ----- ----- -----
		// qty less -

		$('.btn.less').click(function(){
			$('#qty_box').val(function(){
				var qty = $(this).val();
				if( qty == 1 ) return 1;

					qty = parseInt(qty) - 1;
				return qty;
			})
		});
		// ----- ----- ----- ----- -----
		// qty box clear to int

		$('#qty_box').change(function(){
			var qty = $(this).val();

			if( qty != parseInt(qty, 10) ){
				$(this).val(1);
			}

		});
		// ----- ----- ----- ----- -----
		// Click to buy + checkout

		$('#buy_and_checkout').click(function(){
			$('#button-cart').trigger('click');
			setTimeout(function(){
				window.location = 'index.php?route=checkout/checkout';
			},  1000)
		});

		$('#buy_and_checkout').mouseover(function(){
//			$(this).children('span').trigger('');
		});

	}// !$.fn.detail_view

	// ---------- ---------- ---------- ---------- ----------

	$.fn.signup = function() {

		setTimeout(function(){
			// District ( May be JS yet by default Fn. )
			$('table tr:[sort="a19"] td select[name="zone_id"]').district();
		}, 1000);

		// Delivery tel issue
		$('table tr:[sort="a12"]').hide()
			.children('td').children('input').val(' ');

		// Add title
		$('table tr:[sort="a50"]')
			.before('<tr><td colspan="2"><h2>子女資料</h3></td></tr>')
			.slideDown('slow');

		// BB day
		$('table tr[sort="a60"]').my_date_picker();

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
					$('table tr:[sort="a65"]')
						.slideUp('fast');
					$('table tr:[sort="a66"]')
						.fadeIn('slow');
				}else{
					$('table tr:[sort="a66"]')
						.slideUp('fast');
					$('table tr:[sort="a65"]')
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
		$('table tr[sort="a27"] td:first-child')
			.html('<span class="required">*</span> 驗証碼 :（你的驗証碼是 <strong>' + rand + '</strong> ）');
			// Clear field for error return
		$('table tr[sort="a27"] td:last-child input').val('');

		// ----- -----  ----- -----  ----- -----  ----- -----  ----- -----
		// Form handle

		$('form').submit(function(){
			// Check is same captcha
			var user_captcha = $('table tr[sort="a27"] td:last-child input').val();
			if( user_captcha != rand )
				$('table tr[sort="a27"] td:last-child input').val('');

			// Check confirm email
			var email 			= $('table tr:[sort="a20"] input').val();
			var confirm_email 	= $('table tr:[sort="a21"] input').val();
			if( email != confirm_email )
					$('table tr:[sort="a21"] input').val('');
		});
	}// !$.fn.signup

	// ---------- ---------- ---------- ---------- ----------
	$.fn.g2redeem = function() {

		var is_visited_cart = localStorage.getItem('visited_cart');
		if( is_visited_cart ){
			localStorage.removeItem("visited_cart");
			$('.btn_continue').attr('href', 'index.php?route=checkout/checkout');	// edit
		}

	}// !g2redeem

	// ---------- ---------- ---------- ---------- ----------
	$.fn.checkout = function() {

	}

	// ---------- ---------- ---------- ---------- ----------
	// Account Pages

	$.fn.account_editaccount = function() {

		$('table.xpersonal tbody')
			// Age, 地域, Confirm email, Captcha, 別名+
			.children('tr:[sort="a15"], tr:[sort="a17"], tr:[sort="a21"], tr:[sort="a27"], tr:nth-child(n+11)')
				.hide()
				.end()

			.find('tr:[sort="a1"] select')		// [ 稱謂 ]
				.hide()
				.before(function(){
					return  $(this).children('option:selected').text();
				}).end()

			.find('tr:[sort="a10"] input')		// [ 姓名 ]
				.hide()
				.before(function(){
					return $(this).val();
				}).end()

			.find('tr:[sort="a16"] .required')	// [  聯繫電話 ]
				.show().end()

			.find('tr:[sort="a16"] input')
				.focus();

	}// $.fn.account_editaccount

	$.fn.account_myaddress_update = function() {
		setTimeout(function(){
			$('select[name="zone_id"]').district();
		}, 1000);
	}// $.fn.account_myaddress_update

	$.fn.account_return_customer = function() {
		if( $.trim($('.warning').text()) == '確認密碼不符' )
			$('input[name="password2').after('<br><span class="required">確認密碼不符</span>');
	}

}( jQuery ));

// --------------------------------------------------

$(function () {
	$(document).ready(function(){
		// ----- ----- ----- ----- -----
		// Admin
		if( $('body').hasClass('backend') )
			$(this).backend();

		if( $('body').hasClass('admin_order_detail') )
			$(this).admin_order_detail();

		if( $('body').hasClass('admin_sale_order') )
			$(this).admin_sale_order();

			if( $('body').hasClass('admin_sale_order_form') )
				$(this).admin_sale_order_form();

			if( $('body').hasClass('admin_sale_order_info') )
				$(this).admin_sale_order_info();

		// ----- ----- ----- ----- -----
		// Public

		$('body').sidebar();

		if( $('body').hasClass('list_view') )
			$(this).list_view();

		if( $('body').hasClass('detail_view') )
			$(this).detail_view();

		if( $('body').hasClass('signup') )
			$('body').signup();

		if( $('body').hasClass('success') )
			$('body').g2redeem();

		if( $('body').hasClass('checkout') )
			$('body').checkout();

		// ----- ----- ----- ----- -----
		// My Account

		if( $('body').hasClass('account_login') )
			$(this).account_login();

		if( $('body').hasClass('account_account') )
			$('body').account_account();

		if( $('body').hasClass('account_logout') )
			$('body').account_logout();

		if( $('body').hasClass('success') )
			$('body').g2redeem();

		if( $('body').hasClass('account_editaccount') )
			$('body').account_editaccount();

		if( $('body').hasClass('account_myaddress_update') )
			$('body').account_myaddress_update();

		if( $('body').hasClass('account_return_customer') )
			$('body').account_return_customer();

	}); // !$(document).ready

	// ---------- ---------- ---------- ---------- ----------
	// Random

	var rand = '';

});

