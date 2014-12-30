<?php // Ref: http://code.tutsplus.com/tutorials/create-a-custom-page-in-opencart--cms-22054 ?>

<link rel="stylesheet" href="catalog/view/theme/arcu-pro/stylesheet/my_style.css" />
<style>
    .err_msg{
        color: red;
    }
    .pointer{
        cursor: pointer;
    }
    .padding_adjust{
        padding-top: 3px;
    }
    .login-con-goon {
        width: 260px;
        height: 375px;
        margin: 0 auto;
        box-sizing: border-box;
        padding: 105px 20px 0px 20px;
        background: url("/image/data/login/goon-s1.png");
        position: relative;
        font-size: 12px;
    }
    .login-con-goon-loged {
        width: 260px;
        height: 375px;
        margin: 0 auto;
        box-sizing: border-box;
        padding: 140px 20px 0px 20px;
        background: lightblue;
        background: url("/image/data/login/goon-s2.png");
        position: relative;
        font-size: 12px;
        color: black;
    }
    .div-con-goon{
        width: 100%;
        margin-bottom: 15px;
        display: inline-block;
    }
    .username-goon {
        width: 100%;
        height: 25px;
        border: 1px #a8a8a8 solid;
        padding: 5px;
        font-size: 12px;
        border-radius: 8px;
        box-sizing: border-box;
        background: #fff;
        display: inline-block;
        transition: all 0.4s;
    }
    .full-btn-goon:hover, .half-left-goon:hover, .half-right-goon:hover {
        background: #006bbd;
    }
    .password-goon {
        width: 100%;
        height: 25px;
        border: 1px #a8a8a8 solid;
        padding: 5px;
        font-size: 12px;
        border-radius: 8px;
        box-sizing: border-box;
        background: #fff;
        display: inline-block;
        transition: all 0.4s;
    }
    .full-btn-goon {
        width: 100%;
        height: 25px;
        border-radius: 2em;
        border: 2px #fff solid;
        color: #fff;
        text-align: center;
        box-sizing: border-box;
        background: #71bdf7;
        display: inline-block;
        transition: all 0.4s;
    }
    .half-left-goon {
        width: 85px;
        height: 25px;
        border-radius: 2em;
        border: 2px #fff solid;
        color: #fff;
        text-align: center;
        box-sizing: border-box;
        background: #71bdf7;
        float: left;
        display: inline-block;
        transition: all 0.4s;
    }
    .half-right-goon {
        width: 85px;
        height: 25px;
        border-radius: 2em;
        border: 2px #fff solid;
        color: #fff;
        text-align: center;
        box-sizing: border-box;
        background: #71bdf7;
        float: right;
        display: inline-block;
        transition: all 0.4s;
    }
    .user-mail-title-goon{
        width: 50px;
        font-size: 15px;
        display: inline-block;
        color:blueviolet;
    }
    .user-mail-goon{
        font-size: 15px;
        color:blueviolet;
    }
</style>

<div class="frm_sidebar_login_wrapper">
    <?php if( $this->customer->getEmail() == '' ): ?>
    <form action="<?= $base; ?>index.php?route=account/login" method="post" enctype="multipart/form-data" class="frm_sidebar_login" id="login_form">
        <div class="login-con-goon">
            <span class="err_msg"></span>
            <div class="div-con-goon">
                <input type="email" name="email" class="username-goon" placeholder="電郵">
            </div>
            <div class="div-con-goon">
                <input type="password" class="password-goon" name="password" placeholder="密碼">
            </div>
            <div class="div-con-goon">
                <span class="half-left-goon pointer padding_adjust" onclick="forget_pwd()">忘記密碼</span>
                <input type="submit" value="登入" class="half-right-goon" >
            </div>
            <div class="div-con-goon pointer" onclick="register()">
                <span class="full-btn-goon padding_adjust">會員註冊</span>
            </div>
            <div class="div-con-goon  pointer" onclick="return_user()">
                <span class="full-btn-goon padding_adjust">舊會員登入</span>
            </div>
        </div>
    </form>

<?php else: ?>
    <div class="login-con-goon-loged">
        <div class="div-con-goon">
            <span class="user-mail-title-goon">電郵 :</span>
            <span class="user-mail-goon"><?= $this->customer->getEmail(); ?></span>
        </div>
        <div class="div-con-goon">
            <span class="user-mail-title-goon">名稱 :</span>
            <span class="user-mail-goon"><?=  $this->customer->getFirstName(); ?></span>
        </div>
        <div class="div-con-goon">
            <span class="user-mail-title-goon">積分 :</span>
            <span class="user-mail-goon">
                <?php
                    if( $this->customer->getRewardPoints() > 0 )
                            echo $this->customer->getRewardPoints();
                        else
                            echo 0;
                ?>
            </span>
        </div>
        <div class="div-con-goon">
            <div class="half-left-goon padding_adjust pointer" onclick="edit_info()">會員資料</div>
            <div class="half-right-goon padding_adjust pointer logout" onclick="logout()">登出</div>
        </div>
    </div>


    <?php endif; ?>
</div> <!-- frm_sidebar_login_wrapper -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://savingstore.com.hk/catalog/view/theme/arcu-pro/js/jquery_cookie/src/jquery.cookie.js"></script>

<script>
    function get(n) {
      var half = location.search.split(n + '=')[1];
      //alert(location);
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
    function return_user(){
        window.top.location = base + 'index.php?route=account/returnuser';
    }
    function logout(){
        window.location = base + 'index.php?route=account/logout';
    }
    function set_url(){
        var url     = document.URL;
        var tail    = url.indexOf('&');

        // No error string's url before submit
        if( tail != -1 )
            url = url.substring(url, tail);

        $.cookie('iframe_url', url, { expires: 7, path: '/' });
    }
    function edit_info(){
        window.top.location = base + 'index.php?route=account/account';
    }

    // ---------- ---------- ---------- ---------- ----------
    $('form').submit(function(){
        set_url();
    });

    $('.logout').click(function(){
        set_url();
    });

    if( get('error') ){
        $('.err_msg').text('用戶名稱或密碼不正確');
    }

    // External script
    // $.fn.account_login
    // $.fn.account_logout
    // $.fn.account_account

</script>