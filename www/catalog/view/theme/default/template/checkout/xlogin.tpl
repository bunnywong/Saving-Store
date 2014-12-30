<div class="left">
  <h2><?php echo $text_new_customer; ?></h2>
  <p><?php echo $text_checkout; ?></p>
  <label for="register">
    <?php if ($account == 'register') { ?>
    <input type="radio" name="account" value="xregister" id="register" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="xregister" id="register" />
    <?php } ?>
    <b><?php echo $text_register; ?></b></label>
  <br />
  <?php if ($guest_checkout) { ?>
  <label for="guest">
    <?php if ($account == 'guest') { ?>
    <input type="radio" name="account" value="xguest" id="guest" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="xguest" id="guest" />
    <?php } ?>
    <b><?php echo $text_guest; ?></b></label>
  <br />
  <?php } ?>
  <br />
  <p><?php echo $text_register_account; ?></p>
  <input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="button" />
  <br />
  <br />
</div>
<div id="login" class="right">
  <h2>舊客户</h2>
  <div>
    <span>GOON 或 Suzuran 註冊用戶首次到訪？
      <br />請<a href="/index.php?route=account/returnuser"><strong>按此</strong></a>設定新密碼</span>
  </div>

  <div class="hidden">
    <h2><?php echo $text_returning_customer; ?></h2>
    <p><?php echo $text_i_am_returning_customer; ?></p>
    <b><?php echo $entry_email; ?></b><br />
    <input type="text" name="email" value="" />
    <br />
    <br />
    <b><?php echo $entry_password; ?></b><br />
    <input type="password" name="password" value="" />
    <br />
    <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
    <br />
    <input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" /><br />
    <br />
  </div>
</div>