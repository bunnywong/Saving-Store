<?php if( $this->customer->getEmail() == '' ): ?>
<div class="frm_sidebar_login_wrapper">
	<form action="<?= $base; ?>index.php?route=account/login" method="post" enctype="multipart/form-data" class="frm_sidebar_login">
		<div class="login-con">
		    <div class="log-in-title"></div>
		    <input type="email" name="email" class="username" placeholder="電郵">
		    <input type="password" name="password" class="password" placeholder="密碼" >
		    <a href="<?= $base; ?>index.php?route=account/forgotten">
		    	<button class="half-btn-left btn_not_submit forgot_pwd">忘記密碼</button>
		    </a>
		    <button class="half-btn-right" type="submit">登入</button>
		    <a href="<?= $base; ?>index.php?route=account/register">
		    	<button class="full-btn btn_not_submit">會員註冊</button>
		    </a>
		</div>
	</form>

<?php else: ?>

	<div class="login-con" class="frm_sidebar_login">
	    <div class="member-title"></div>
	    <div class="user-row">
	        <span class="user-mail-title">電郵 :</span>
	        <span class="user-mail"><?= $this->customer->getEmail(); ?></span>
	    </div>
	    <div class="user-row">
	        <span class="user-name-title">名稱 :</span>
	        <span class="user-name"><?=  $this->customer->getFirstName(); ?></span>
	    </div>
	    <div class="user-row">
	        <span class="user-point-title">積分 :</span>
	        <span class="user-point">
	        	<?php
	        	 	if( $this->customer->getRewardPoints() > 0 )
	        	 			echo $this->customer->getRewardPoints();
	        	 		else
	        	 			echo 0;
	        	 ?>
	        </span>
	    </div>
		<a href="<?= $base; ?>index.php?route=account/account">
	    	<button class="half-btn-left">編輯資料</button>
	    </a>
	    <a href="<?= $base; ?>index.php?route=account/logout">
	    	<button class="half-btn-right">登出</button>
	    </a>
	</div>

<?php endif; ?>
</div>


<div class="sidebar-banners">
<!--
	<a href="#">
		<div id="sidebar-login_form"></div>
	</a>
-->
	<a href="#">
		<div id="sidebar-member_offer"></div>
	</a>
	<a href="http://savingstore.com.hk/index.php?route=product/manufacturer/info&manufacturer_id=8">
		<div id="sidebar-suzuran"></div>
	</a>
	<a href="http://savingstore.com.hk/index.php?route=product/manufacturer/info&manufacturer_id=12">
		<div id="sidebar-goon"></div>
	</a>
</div>