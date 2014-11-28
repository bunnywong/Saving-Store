<?php echo $header; ?>
<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
			<h2><?php echo $text_your_details; ?></h2>
			<div class="content">
				<table class="form">
					<tr>
						<td><?php echo $entry_firstname; ?><span class="required">*</span></td>
						<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" /></td>
					</tr>
					<?php if ($error_firstname) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_firstname; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_lastname; ?><span class="required">*</span></td>
						<td><input type="text" name="lastname" value="<?php echo $lastname; ?>" /></td>
					</tr>
					<?php if ($error_lastname) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_lastname; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_email; ?><span class="required">*</span></td>
						<td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
					</tr>
					<?php if ($error_email) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_email; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_telephone; ?><span class="required">*</span></td>
						<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" /></td>
					</tr>
					<?php if ($error_telephone) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_telephone; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_fax; ?></td>
						<td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="buttons">
				<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
				<div class="right">
					<button type="submit" class="button"><?php echo $button_continue; ?></button>
				</div>
			</div>
		</form>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>