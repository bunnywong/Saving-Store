/* Mobile issue
	$(window).resize(function(){
		$('#powered').appendTo('#mCSB_1 .mCS_touch');
	});
*/

$(document).ready(function(){
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
		$('.my_district select[name="zone_id"] option:nth-child(n+2)')
		.each(function(){
			var str = $(this).text();
				str = str.substring(5);

			$(this).text(str);
		});
	}// !refine_district()

	function sign_up_refine(){
		if( $('body').hasClass('sign_up') ){
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
			$('table tr[sort="a99"] td:first-child')
				.html('<span class="required">*</span> 驗証碼 (請輪入數字：' + rand + ')');
				// Clear field for error return
			$('table tr[sort="a99"] td:last-child input').val('');

			// ----- -----  ----- -----  ----- -----  ----- -----  ----- -----
			// Form
			$('form').submit(function(){
				var user_captcha = $('table tr[sort="a99"] td:last-child input').val();
				if( user_captcha != rand )
					$('table tr[sort="a99"] td:last-child input').val('');
			});
		}// !Lock: body class
	}// !sign_up_refine()

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

			$('table tr[sort="a60"] input')	// [出生日期 / 預產期]
				.after('<span class="act_clear"><span>清除</span></span>')
				.monthpicker(options).end()
			// Clear field
			.find('span.act_clear').click(function(){
				$('input.mtz-monthpicker-widgetcontainer').val('');
			});
		}// Lock: body class
	}// !my_date_picker()

	setTimeout(function(){
		refine_district();
		sign_up_refine();
		my_date_picker();
	}, 1000);

	// ---------- ---------- ---------- ---------- ----------
	// Global

	// Random
	var rand = '';


	// ---------- ---------- ---------- ---------- ----------
});
