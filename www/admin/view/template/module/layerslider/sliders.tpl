<?php echo $header; $module_row=0; ?>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-sm-12">
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
						<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>

				<?php if ($error_warning) { ?>
					<div class="alert alert-block alert-danger fade in"><?php echo $error_warning; ?></div>
				<?php } ?>
				<?php if (isset($success) && $success) { ?>
					<div class="success"><?php echo $success; ?></div>
				<?php } ?>

				<section class="panel">
					<header class="panel-heading clearfix">
						<h4><img src="./view/kulercore/images/logos/kuler_layer_slider.png" alt="<?php echo $this->data['heading_title']; ?>" /></h4>
                        <div class="pull-right">
	                        <div class="btn-group">
	                            <button class="btn btn-success" onclick="$('#form').submit();"><?php echo $this->language->get('SAVE ALL AND EDIT SLIDER'); ?></button>
		                        <button data-toggle="dropdown" class="btn btn-success dropdown-toggle" type="button"><span class="caret"></span></button>
		                        <ul role="menu" class="dropdown-menu">
			                        <li><a onclick="$('#action_mode').val('create-new');$('#form').submit();"><?php echo $this->language->get('Save All And Create New Slider'); ?></a></li>
			                        <li><a onclick="$('#action_mode').val('module-only');$('#form').submit();"><?php echo 'Save Modules Only'; ?></a></li>
		                        </ul>
	                        </div>
	                        <a class="btn btn-danger" href="<?php echo $cancel; ?>"><?php echo $button_cancel; ?></a>
                         </div>
					</header>
					<div class="panel-body">
						<ul class="nav nav-tabs">
							<li><a href="#tab-slidergroups"><?php echo $this->language->get("Slider Management");?></a></li>
							<li><a href="#tab-listmodules"><?php echo $this->language->get("Modules Asignment");?></a></li>
							<li><a href="#tab-importtools"><?php echo $this->language->get("Import Tools");?></a></li>
						</ul>

						<div class="tab-content">
							<form class="form-horizontal" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
								<div id="tab-slidergroups" class="tab-pane">
									<div class="list-group col-sm-2">
										<?php if ($slidergroups) { ?>
										<?php foreach( $slidergroups as $sgroup ) {  ?>
											<a class="list-group-item<?php if( $sgroup['id'] == $id ) { ?> active<?php } ?>" href="<?php echo $this->url->link('module/kuler_layer_slider', 'id='.$sgroup['id'].'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $sgroup['title'];?>
												<span>(ID: <?php echo $sgroup['id'];?>)</span>
											</a>
										<?php } ?>
										<?php } ?>
									</div>

									<input type="hidden" name="action_mode" id="action_mode">
									<div class="col-sm-9">
										<?php if ($id) { ?>
											<div style="margin-bottom: 20px; float: right;">
												<a class="btn btn-success btn-sm" id="btn-preview-ingroup" href="<?php echo  $this->url->link('module/kuler_layer_slider/preview', 'id='.$id.'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('Preview');?></a>
												<a class="btn btn-success btn-sm" href="<?php echo  $this->url->link('module/kuler_layer_slider/layer', 'group_id='.$id.'&token=' . $this->session->data['token'], 'SSL');?>" class="btn" id="preview-sliders"><?php echo 'Manage Slides'; ?></a>
												<a class="btn btn-success btn-sm" href="<?php echo  $this->url->link('module/kuler_layer_slider/export', 'id='.$id.'&token=' . $this->session->data['token'], 'SSL');?>" id="preview-sliders">
													<?php echo $this->language->get('Export Slider'); ?>
												</a>
												<a class="btn btn-danger btn-sm" href="<?php echo  $this->url->link('module/kuler_layer_slider/delete', 'id='.$id.'&token=' . $this->session->data['token'], 'SSL');?>" onclick="return confirm('Are you sure to delete this?');" id="preview-sliders">
													<?php echo 'Delete'; ?>
												</a>
											</div>
										<?php } ?>

										<h4 style="margin-bottom: 50px;">
											<?php if( $id ) { ?>
												<?php echo $this->language->get('Edit Slider');?>: <strong><?php echo $params['title'];?> (ID: <?php echo $id;?>)</strong>
											<?php } else { ?>
												<?php echo $this->language->get('Create New Slider');?>
											<?php } ?>
										</h4>

										<input type="hidden" name="id" value="<?php echo $id; ?>">
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Slider Title');?></label>
											<div class="col-sm-9">
												<input type="text" class="form-control" name="slider[title]" value="<?php echo $params['title'];?>"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Short Code');?></label>
											<div class="col-sm-3">
												<input type="text" class="form-control" value="<?php echo $params['shortcode'];?>" readonly onclick="this.select();" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Delay');?></label>
											<div class="col-sm-2">
												<input type="number" class="form-control" name="slider[delay]" value="<?php echo $params['delay'];?>"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('FullWidth Mode');?></label>
											<div class="col-sm-2">
												<select class="form-control" name="slider[fullwidth]">
													<?php foreach( $fullwidth as $key => $value ) { ?>
														<option value="<?php echo $key;?>" <?php if( isset($params['fullwidth']) && ($key == $params['fullwidth']) ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Slider Demension');?></label>
											<div class="col-sm-2"><input class="form-control" type="number" name="slider[width]" value="<?php echo $params['width'];?>"/></div>
											<div class="col-sm-2"><input class="form-control" type="number" name="slider[height]" value="<?php echo $params['height'];?>"/></div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Touch Mobile');?></label>
											<div class="col-sm-9">
												<input type="hidden" name="slider[touch_mobile]" value="0" />
												<input type="checkbox" class="switch" data-on="success" name="slider[touch_mobile]" value="1"<?php if( $params['touch_mobile'] == 1 ){ ?> checked="checked" <?php } ?> />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Stop On Hover');?></label>
											<div class="col-sm-9">
												<input type="hidden" name="slider[stop_on_hover]" value="0" />
												<input type="checkbox" class="switch" data-on="success" name="slider[stop_on_hover]" value="1"<?php if( $params['stop_on_hover'] == 1 ){ ?> checked="checked" <?php } ?> />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Shuffle Mode');?></label>
											<div class="col-sm-9">
												<input type="hidden" name="slider[shuffle_mode]" value="0" />
												<input type="checkbox" class="switch" data-on="success" name="slider[shuffle_mode]" value="1"<?php if( $params['shuffle_mode'] == 1 ){ ?> checked="checked" <?php } ?> />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->language->get('Image Cropping');?></label>
											<div class="col-sm-9">
												<input type="hidden" name="slider[image_cropping]" value="0" />
												<input type="checkbox" class="switch" data-on="success" name="slider[image_cropping]" value="1"<?php if( $params['image_cropping'] == 1 ){ ?> checked="checked" <?php } ?> />
											</div>
										</div>

										<fieldset>
											<legend><?php echo $this->language->get('Appearance');?></legend>

											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Shadow Type');?></label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[shadow_type]">
														<?php foreach( $shadow_types as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( $key == $params['shadow_type'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Show Time Line');?></label>
												<div class="col-sm-9">
													<input type="hidden" name="slider[show_time_line]" value="0" />
													<input type="checkbox" class="switch" data-on="success" name="slider[show_time_line]" value="1"<?php if( $params['show_time_line'] == 1 ){ ?> checked="checked" <?php } ?> />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Time Liner Position</label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[time_line_position]">
														<?php foreach( $linepostions as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( $key == $params['time_line_position'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Background Color');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="text" name="slider[background_color]" value="<?php echo $params['background_color'];?>"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Margin');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="text" name="slider[margin]" value="<?php echo $params['margin'];?>"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Padding(border)');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="text" name="slider[padding]" value="<?php echo $params['padding'];?>"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Show Background Image');?></label>
												<div class="col-sm-2">
													<input type="hidden" name="slider[background_image]" value="0" />
													<input type="checkbox" class="switch" data-on="success" name="slider[background_image]" value="1"<?php if( $params['background_image'] == 1 ){ ?> checked="checked" <?php } ?> />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Background URL');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="text" value="<?php echo $params['background_url'];?>" name="slider[background_url]"/>
												</div>
											</div>
										</fieldset>

										<fieldset>
											<legend><?php echo $this->language->get('Navigator');?></legend>

											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Navigator Type');?></label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[navigator_type]">
														<?php foreach( $navigator_types as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( $key == $params['navigator_type'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Arrows');?></label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[navigator_arrows]">
														<?php foreach( $navigation_arrows as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( $key == $params['navigator_arrows'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Style');?></label>
												<div class="col-sm-2">
													<select class="form-control" name="slider[navigation_style]">
														<?php foreach( $navigation_style as $key => $value ) { ?>
															<option value="<?php echo $key;?>" <?php if( $key == $params['navigation_style'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Offset Horizontal');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['offset_horizontal'];?>" name="slider[offset_horizontal]"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Offset Vertical');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['offset_vertical'];?>" name="slider[offset_vertical]"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Always Show Navigator');?></label>
												<div class="col-sm-9">
													<input type="hidden" name="slider[show_navigator]" value="0" />
													<input type="checkbox" class="switch" data-on="success" name="slider[show_navigator]" value="1"<?php if( $params['show_navigator'] == 1 ){ ?> checked="checked" <?php } ?> />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Hide Navigator After');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['hide_navigator_after'];?>" name="slider[hide_navigator_after]"/>
												</div>
											</div>
										</fieldset>

										<fieldset>
											<legend><?php echo $this->language->get('Thumbnails');?></legend>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Thumbnail Width');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['thumbnail_width'];?>" name="slider[thumbnail_width]"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Thumbnail Height');?> </label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['thumbnail_height'];?>" name="slider[thumbnail_height]"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Number of Thumbnails');?> </label>
												<div class="col-sm-2">
													<input class='form-control' type="number" value="<?php echo $params['thumbnail_amount'];?>" name="slider[thumbnail_amount]"/>
												</div>
											</div>
										</fieldset>

										<fieldset>
											<legend><?php echo $this->language->get('Mobile Visiblity');?></legend>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $this->language->get('Hide Under Width');?></label>
												<div class="col-sm-2">
													<input class="form-control" type="number" value="<?php echo $params['hide_screen_width'];?>" name="slider[hide_screen_width]"/>
												</div>
											</div>
										</fieldset>
									</div>
								</div>

								<div id="tab-listmodules" class="tab-pane">
									<table id="module" class="table table-hover table-bordered">
									    <thead>
									        <tr>
									            <th class="left"><?php echo $entry_banner; ?></th>
										        <th class="left"><?php echo $this->language->get('Store:') ?></th>
									            <th class="left"><?php echo $entry_layout; ?></th>
									            <th class="left"><?php echo $entry_position; ?></th>
									            <th class="left"><?php echo $entry_status; ?></th>
									            <th class="right"><?php echo $entry_sort_order; ?></th>
									            <th></th>
									        </tr>
									    </thead>

									    <?php $module_row = 0; ?>
									    <?php foreach ($modules as $module) { ?>
									    	<tbody id="module-row<?php echo $module_row; ?>">
									    	<tr>
									    		<td class="left"><select class="form-control" name="kuler_layer_slider_module[<?php echo $module_row; ?>][group_id]">
									    				<?php foreach ($slidergroups as $sg) { ?>
									    					<?php if ($sg['id'] == $module['group_id']) { ?>
									    						<option value="<?php echo $sg['id']; ?>" selected="selected"><?php echo $sg['title']; ?></option>
									    					<?php } else { ?>
									    						<option value="<?php echo $sg['id']; ?>"><?php echo $sg['title']; ?></option>
									    					<?php } ?>
									    				<?php } ?>
									    			</select></td>

											    <td class="left">
												    <select class="form-control" name="kuler_layer_slider_module[<?php echo $module_row; ?>][store_id]" style="width: 300px;">
													    <?php foreach ($store_options as $store_id => $store_name) { ?>
														    <option value="<?php echo $store_id; ?>"<?php if ($store_id == $module['store_id']) echo ' selected="selected"'; ?>><?php echo $store_name; ?></option>
													    <?php } ?>
												    </select>
											    </td>

									    		<td class="left">
									    			<select class="form-control" name="kuler_layer_slider_module[<?php echo $module_row; ?>][layout_id]">
									    				<?php foreach ($layouts as $layout) { ?>
									    					<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
									    						<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
									    					<?php } else { ?>
									    						<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
									    					<?php } ?>
									    				<?php } ?>
									    			</select></td>
									    		<td class="left"><select class="form-control" name="kuler_layer_slider_module[<?php echo $module_row; ?>][position]">
									    				<?php if ($module['position'] == 'content_top') { ?>
									    					<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
									    				<?php } else { ?>
									    					<option value="content_top"><?php echo $text_content_top; ?></option>
									    				<?php } ?>
									    				<?php if ($module['position'] == 'content_bottom') { ?>
									    					<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
									    				<?php } else { ?>
									    					<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
									    				<?php } ?>
									    				<?php if ($module['position'] == 'column_left') { ?>
									    					<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
									    				<?php } else { ?>
									    					<option value="column_left"><?php echo $text_column_left; ?></option>
									    				<?php } ?>
									    				<?php if ($module['position'] == 'column_right') { ?>
									    					<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
									    				<?php } else { ?>
									    					<option value="column_right"><?php echo $text_column_right; ?></option>
									    				<?php } ?>

									    			</select></td>
									    		<td class="left">
													<input type="hidden" name="kuler_layer_slider_module[<?php echo $module_row; ?>][status]" value="0" />
													<input type="checkbox" class="switch" data-on="success" name="kuler_layer_slider_module[<?php echo $module_row; ?>][status]" value="1"<?php if ($module['status'] == 1) echo ' checked="checked"'; ?> />
											    </td>
									    		<td class="right"><input class="form-control" type="number" name="kuler_layer_slider_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" style="width: 70px;" /></td>
									    		<td class="left"><a class="btn btn-danger" onclick="$('#module-row<?php echo $module_row; ?>').remove();"><?php echo $button_remove; ?></a></td>
									    	</tr>
									    	</tbody>
									    	<?php $module_row++; ?>
									    <?php } ?>
									    <tfoot>
									    	<tr>
									    		<td colspan="6"></td>
									    		<td class="left"><a onclick="addModule();" class="btn btn-success"><?php echo $button_add_module; ?></a></td>
									    	</tr>
									    </tfoot>
									</table>
								</div>
							</form>

							<div id="tab-importtools" class="tab-pane">
								<form method="post" class="form-inline" enctype="multipart/form-data" action="<?php echo $actionImport; ?>">
									<div class="form-group">
										<input class="form-control" type="file" class="input_import_slider" name="import_file">
									</div>
									<div class="form-group">
										<input class="btn btn-success" type="submit" value="<?php echo $this->language->get('Import Slider');?>"  >
									</div>
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</section>
</section>
 <script type="text/javascript">

	$("#btn-preview-ingroup").click( function(){
		var url = $(this).attr("href");
			$('#dialog').remove();
			$('#container').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe name="iframename2" src="'+url+'" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			$('#dialog').dialog({
				title: '<?php echo $this->language->get("Preview Management");?>',
				close: function (event, ui) {
 
				},	
				bgiframe: true,
				width: 1000,
				height: 500,
				resizable: false,
				modal: true
		});	 
		return false; 
	} );
 </script>	
 	<script type="text/javascript">
 	
 		$(".nav-tabs a")
		    .on('click', function () {
			    var $this = $(this);

			    $this.parents('.nav-tabs').find('li').removeClass('active');
			    $this.parent().addClass('active');
		    })
		    .tabs();
		$('#tabs-modules a').click( function(){
			$.cookie("sactived_tab", $(this).attr("href") );
		} );

		if( $.cookie("sactived_tab") !="undefined" ){
			$('#tabs-modules a').each( function(){ 
				if( $(this).attr("href") ==  $.cookie("sactived_tab") ){
					$(this).click();
					return ;
				}
			} );
			
		}
    	
    	</script>


   <script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select class="form-control" name="kuler_layer_slider_module[' + module_row + '][group_id]">';
	<?php foreach ($slidergroups as $sg) { ?>
	html += '      <option value="<?php echo $sg['id']; ?>"><?php echo addslashes($sg['title']); ?></option>';
	<?php } ?>
	html += '    </select></td>';

	html += '    <td class="left">';
	html += '	    <select class="form-control" name="kuler_layer_slider_module['+ module_row +'][store_id]" style="width: 300px;">';
	html += '		    <?php foreach ($store_options as $store_id => $store_name) { ?>';
	html += '			    <option value="<?php echo $store_id; ?>"><?php echo $store_name; ?></option>';
	html += '		    <?php } ?>';
	html += '	    </select>';
	html += '    </td>';
 
	html += '    <td class="left"><select class="form-control" name="kuler_layer_slider_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';	
	html += '    <td class="left"><select class="form-control" name="kuler_layer_slider_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><input type="hidden" name="kuler_layer_slider_module[' + module_row + '][status]" value="0" /><input type="checkbox" class="switch" data-on="success" name="kuler_layer_slider_module[' + module_row + '][status]" value="1" checked="checked" />';
    html += '    </td>';
	html += '    <td class="right"><input class="form-control" type="number" name="kuler_layer_slider_module[' + module_row + '][sort_order]" value="" style="width: 70px;" /></td>';
	html += '    <td class="left"><a class="btn btn-danger" onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);

	$('#module #module-row' + module_row + ' .switch').bootstrapSwitch();

	module_row++;
}
//--></script>
<script>
	$('.switch').bootstrapSwitch();
</script>
<?php echo $footer; ?>