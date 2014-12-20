<?php echo $header; ?>

<script>
jQuery(document).ready(function($){
  $('body').addClass('account_return_customer');
});
</script>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1 style="vertical-align: top;">舊網站用戶？<img src="/image/data/login/old_website.png" style="max-width: 300px; margin-left: 200px;"></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <p>請輸入您註冊賬戶時填寫的電子郵件地址以及重設密碼，點擊繼續。<br>於電郵信箱開啟連結，你的賬戶將會重新開通</p>
    <div class="content">
      <table class="form">
        <tr>
          <td>郵箱地址：</td>
          <td><input type="text" name="email" value="" /></td>
        </tr>
        <tr>
          <td>設定新密碼：</td>
          <td><input type="password" name="password" value="" /></td>
        </tr>
        <tr>
          <td>設定新密碼（確認輸入）：</td>
          <td><input type="password" name="password2" value="" /></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>