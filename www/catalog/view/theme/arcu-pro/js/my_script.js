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
	}

	setTimeout(function(){
		refine_district();
	}, 2000);

	// ---------- ---------- ---------- ---------- ----------
});
