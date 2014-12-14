<?php // Ref: http://code.tutsplus.com/tutorials/create-a-custom-page-in-opencart--cms-22054 ?>

<link rel="stylesheet" href="catalog/view/theme/arcu-pro/stylesheet/my_style.css" />
<style>
    a{
        text-decoration: none;
    }
    .err_msg{
        color: yellow;
    }
    .btn_login{
        font-size: 16px;
    }
    .forgot_pwd{
        text-align: center;
        padding: 2px;
    }
    .register{
        display: block;
        padding: 2px;
        text-align: center;
        width: 100%;
    }
</style>

<div class="frm_sidebar_login_wrapper">
    <?php if( $this->customer->getEmail() == '' ): ?>
    <form action="<?= $base; ?>index.php?route=account/login" method="post" enctype="multipart/form-data" class="frm_sidebar_login" id="login_form">
        <div class="login-con">
            <div class="log-in-title"></div>
            <span class="err_msg"></span>
            <input type="email" name="email" class="username" placeholder="電郵">
            <input type="password" name="password" class="password" placeholder="密碼" >
            <a href="#" onclick="forget_pwd()">
                <span class="half-btn-left forgot_pwd">忘記密碼</span>
            </a>
            <button class="half-btn-right btn_login" type="submit">登入</button>
            <a href="#" onclick="register()">
                <span class="full-btn register">會員註冊</span>
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
        <a href="#">
            <button class="half-btn-left" onclick="edit_info()">編輯資料</button>
        </a>
        <a href="<?= $base; ?>index.php?route=account/logout">
            <button class="half-btn-right btn_logout">登出</button>
        </a>
    </div>
    <?php endif; ?>
</div> <!-- frm_sidebar_login_wrapper -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://savingstore.com.hk/catalog/view/theme/arcu-pro/js/jquery_cookie/src/jquery.cookie.js"></script>

<script>
    function get(n) {
      var half = location.search.split(n + '=')[1];
      return half !== undefined ? decodeURIComponent(half.split('&')[0]) : null;
     }
    // --------------------------------------------------

    var base = '<?= $base; ?>';

    function forget_pwd(){
        window.top.location.href = base + 'index.php?route=account/forgotten';
    }
    function register(){
        window.top.location = base + 'index.php?route=account/register';
    }
    function set_url(){
        var url = document.URL;
        // localStorage.setItem(url, 'iframe_url');
        $.cookie('iframe_url', url, { expires: 7, path: '/' });
        // alert($.cookie('iframe_url'));
    }
    function edit_info(){
        window.top.location = base + 'index.php?route=account/account';
    }

    // ---------- ---------- ---------- ---------- ----------
    $('form').submit(function(){
        set_url();
    });

    $('.btn_logout').click(function(){
        set_url();
    });


    if( get('error') ){
        $('.err_msg').text('用戶名稱或密碼不正確');
    }

</script>