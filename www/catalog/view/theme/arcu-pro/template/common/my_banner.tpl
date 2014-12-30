<div class="top_img_wrapper">
	<?php
		$this->language->load('information/information');

	    $this->load->model('catalog/information');

	  	$information_id 	= 10;	// Hardcode :D
		$information_info	= $this->model_catalog_information->getInformation($information_id);
	//		$this->data['info_heading_title'] = $information_info['title'];
		if( count($information_info) > 0 ){
			$my_banner = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
			echo $my_banner;
		}
	?>
</div>

<div class="frm_sidebar_login_wrapper">
	<?php if( $this->customer->getEmail() == '' ): ?>
	<form action="<?= $base; ?>index.php?route=account/login" method="post" enctype="multipart/form-data" class="frm_sidebar_login">
		<div class="login-con">
		    <div class="log-in-title"></div>
		    <input type="email" name="email" class="username" placeholder="電郵">
		    <input type="password" name="password" class="password" placeholder="密碼" >
		    <a href="<?= $base; ?>index.php?route=account/forgotten">
		    	<span class="half-btn-left btn_not_submit forgot_pwd">忘記密碼</span>
		    </a>
		    <button class="half-btn-right" type="submit">登入</button>
		    <a href="<?= $base; ?>index.php?route=account/register">
		    	<button class="full-btn btn_not_submit">會員註冊</button>
		    </a>
		</div>
	</form>

<?php else: ?>

	<div class="login-con frm_sidebar_login">
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
	    	<button class="half-btn-left">會員資料</button>
	    </a>
	    <a href="<?= $base; ?>index.php?route=account/logout">
	    	<button class="half-btn-right">登出</button>
	    </a>
	</div>
	<?php endif; ?>
</div> <!-- frm_sidebar_login_wrapper -->


<div class="sidebar-banners">
	<a href="#">
		<div id="sidebar-member_offer"></div>
	</a>
	<?php // Hardcode ^_^ ?>
	<a href="<?= $base; ?>index.php?route=product/category&path=59">
		<div id="sidebar-suzuran"></div>
	</a>
	<?php // Hardcode ^_^ ?>
	<a href="<?= $base; ?>index.php?route=product/category&path=60">
		<div id="sidebar-goon"></div>
	</a>
</div>