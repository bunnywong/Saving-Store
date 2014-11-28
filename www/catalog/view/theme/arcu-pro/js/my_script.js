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
			window.location = 'http://savingstore.com.hk/index.php?route=account/forgotten';
		}else{
			window.location = 'http://savingstore.com.hk/index.php?route=account/register';
		}
	});
	// ---------- ---------- ---------- ---------- ----------
});
