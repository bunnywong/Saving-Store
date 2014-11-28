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
	                    <span class="pull-right">
		                    <a class="btn btn-success" onclick="$('#slider-form').submit();"><?php echo $button_save; ?></a>
		                    <a id="btn-preview-ingroup" class="btn btn-success" href="<?php echo  $this->url->link('module/kuler_layer_slider/preview', 'id='.$group_id.'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('Preview Slider');?></a>
	                        <a class="btn btn-danger" href="<?php echo $cancel; ?>"><?php echo $button_cancel; ?></a>
	                     </span>
					</header>
					<div class="panel-body">
						<h4><?php echo $this->language->get('List Of Slides');?>: <a href="<?php echo  $this->url->link('module/kuler_layer_slider', 'id='.$group_id.'&token=' . $this->session->data['token'], 'SSL');?>"><span><?php echo $sliderGroup['title'];?></span></a></h4>
						<p><?php echo $this->language->get('Drag and drop to sort slider in list');?></p>

						<div class="group-sliders clearfix">
							<div class="new-slider-item">
								<a href="<?php echo  $this->url->link('module/kuler_layer_slider/layer', 'group_id='.$group_id.'&token=' . $this->session->data['token'], 'SSL')?>">
									<i class="fa fa-plus-circle" style="font-size: 80px; width: 100%; height: 100%; text-align: center; line-height: 120px; color: #FFF;"></i>
								</a>
								<div><?php echo $this->language->get('text_create_new');?></div>
							</div>
							<?php foreach( $sliders as $slider )  { ?>
								<div class="slider-item <?php echo ( $slider['id'] == $slider_id ? 'active':'');?><?php echo $slider['status'] ? ' status-on' : ' status-off'; ?>" id="slider_<?php echo $slider['id'];?>">
									<a class="image" href="<?php echo  $this->url->link('module/kuler_layer_slider/layer', 'id='.$slider['id'].'&group_id='.$slider['group_id'].'&token=' . $this->session->data['token'], 'SSL')?>">
										<img src="<?php echo HTTP_CATALOG."image/".$slider["image"];?>" height="86"/>
									</a>
									<a  title="<?php echo $this->language->get('Clone this');?>" class="slider-clone btn btn-success btn-md" href="<?php echo  $this->url->link('module/kuler_layer_slider/copythis', 'id='.$slider['id'].'&group_id='.$slider['group_id'].'&token=' . $this->session->data['token'], 'SSL')?>"><i class="fa fa-copy"></i></a>
									<a  title="<?php echo $this->language->get('Delete this');?>" class="slider-delete btn btn-danger btn-md" href="<?php echo  $this->url->link('module/kuler_layer_slider/deleteslider', 'id='.$slider['id'].'&group_id='.$slider['group_id'].'&token=' . $this->session->data['token'], 'SSL')?>" onclick="return confirm('<?php echo $this->language->get('text_confirm_delete');?>')"><i class="fa fa-trash-o"></i></a>
									<a class="slider-status" href="<?php echo  $this->url->link('module/kuler_layer_slider/layer', 'id='.$slider['id'].'&group_id='.$slider['group_id'].'&token=' . $this->session->data['token'], 'SSL')?>">
										<?php if ($slider['status']) { ?>
											<i class="fa fa-check"></i>
										<?php } else { ?>
											<i class="fa fa-times"></i>
										<?php } ?>
									</a>
									<div><?php echo $slider['title']; ?></div>
								</div>
							<?php } ?>
						</div>

						<?php if( $slider_id )  { ?>
							<h4><?php echo $this->language->get('Edit Slide');?>: <span><?php echo $slider_title;?></span></h4>
						<?php  } else { ?>
							<h4><?php echo $this->language->get('Create New Slide');?></h4>
						<?php } ?>

						<form class="form-horizontal" role="form" method="post" id="slider-editor-form">
							<input type="hidden" id="slider_group_id" name="slider_group_id" value="<?php echo $group_id;?>"/>
							<div id="slider-warning"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Title")?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_title" size="100" value="<?php echo $slider_title;?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Status")?></label>
								<div class="col-sm-6">
									<input type="hidden" name="slider_status" value="0" />
									<input type="checkbox" class="switch" data-on="success" name="slider_status" value="1"<?php if( $params['slider_status'] == 1 ){ ?> checked="checked" <?php } ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Transition")?></label>
								<div class="col-sm-6">
									<select class="form-control" name="slider_transition">
										<?php foreach( $transtions as $key => $value ) { ?>
											<option value="<?php echo $key;?>" <?php if( $key == $params['slider_transition'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Slot Amount");?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_slot" value="<?php echo $params['slider_slot'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Transition Rotation")?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_rotation" value="<?php echo $params['slider_rotation'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Transition Duration")?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_duration" value="<?php echo $params['slider_duration'];?>" >
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Delay")?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_delay" value="<?php echo $params['slider_delay'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Enable Link")?></label>
								<div class="col-sm-6">
									<input type="hidden" name="slider_enable_link" value="0" />
									<input type="checkbox" class="switch" data-on="success" name="slider_enable_link" value="1"<?php if( $params['slider_enable_link'] == 1 ){ ?> checked="checked" <?php } ?> />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Link")?></label>
								<div class="col-sm-6">
									<input class="form-control" type="text" name="slider_link" value="<?php echo $params['slider_link'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get("Thumbnail")?></label>
								<div class="col-sm-6">
									<div class="image">
										<?php $no_image= ''; ?>
										<img src="<?php echo $slider_thumbnail; ?>" alt="" id="thumb_slider_thumbnail" />
										<input type="hidden" name="slider_thumbnail" id="slider_thumbnail" value="<?php echo $params['slider_thumbnail'];?>">
										<br />
										<div class="btn-group">
											<a class="btn btn-success btn-xs" onclick="image_upload('slider_thumbnail', 'thumb_slider_thumbnail');"><?php echo $text_browse; ?></a>
											<a class="btn btn-danger btn-xs" onclick="$('#thumb_slider_thumbnail').attr('src', '<?php echo $no_image; ?>'); $('#slider_thumbnail').attr('value', '');"><?php echo $text_clear; ?></a>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get('Full Width Video');?></label>
								<div class="col-sm-1">
									<select class="form-control" name="slider_usevideo">
										<?php foreach( $usevideo as $key => $value ) { ?>
											<option value="<?php echo $key;?>" <?php if( $key == $params['slider_usevideo'] ){ ?> selected="selected" <?php } ?> ><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-1" style="line-height: 30px; width: 80px;">
									<?php echo $this->language->get('Video ID');?>
								</div>
								<div class="col-sm-2">
									<input class="form-control" type="text" name="slider_videoid" value="<?php echo $params['slider_videoid'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $this->language->get('Auto Play');?></label>
								<div class="col-sm-6">
									<input type="hidden" name="slider_videoplay" value="0" />
									<input class="switch" data-on="success" type="checkbox" class="switch" data-on="success" name="slider_videoplay" value="1"<?php if( $params['slider_videoplay'] == 1 ){ ?> checked="checked" <?php } ?> />
								</div>
							</div>

							<input name="slider_id" type="hidden" id="slider_id" value="<?php echo $slider_id;?>" />
							<input name="slider_image" id="slider-image" type="hidden" value="<?php echo $slider_image;?>">
						</form>

						<div class="layers-wrapper" id="slider-toolbar" style="width: 100%;">
							<h3><?php echo $this->language->get("Layers Editor")?></h3>

							<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"  id="slider-form">
								<div class="col-sm-9 slider-toolbar">
									<button type="button" class="btn btn-success" id="btn-update-slider"><?php echo $this->language->get("Update Slide Image")?></button>
									<div class="btn btn-success btn-create" data-action="add-image">
										<i class="fa fa-picture-o"></i> <?php echo $this->language->get("Add Layer Image")?>
									</div>
									<div class="btn btn-success btn-create" data-action="add-video">
										<i class="fa fa-toggle-right"></i> <?php echo $this->language->get("Add Layer Video")?>
									</div>
									<div class="btn btn-success btn-create" data-action="add-text">
										<i class="fa fa-font"></i>
										<?php echo $this->language->get("Add Layer Text")?>
									</div>
									<div class="btn-delete" data-action="delete-layer" style="display: inline;">
										<button type="button" class="btn btn-danger">
											<i class="fa fa-trash-o"></i>
											<?php echo $this->language->get('Delete Layer'); ?>
										</button>
									</div>
								</div>
								<div class="col-sm-3" style="text-align: right;">
									<button type="button" class="btn btn-success" onclick="$('#slider-form').submit();"><?php echo $button_save; ?></button>
									<a class="btn btn-success" id="btn-preview-slider"><?php echo $this->language->get("Preview This Slide")?></a>
								</div>
								<div class="col-sm-10 slider-layers" >



								<div class="slider-editor-wrap" style="width:<?php echo $sliderWidth;?>px;height:<?php echo $sliderHeight;?>px">
									<div class="simage">
										<img src="<?php echo $slider_image_src;?>">
									</div>
									<div class="slider-editor" id="slider-editor" style="width:<?php echo $sliderWidth;?>px;height:<?php echo $sliderHeight;?>px">

									</div>
								</div>
								<div class="layer-video-inpts form-horizontal" id="dialog-video">
									<div class="form-group">
									    <label class="col-sm-3 control-label"><?php echo $this->language->get("Video Type")?></label>

									    <div class="col-sm-5">
										    <select class="form-control" name="layer_video_type" id="layer_video_type">
										    	<option value="youtube"><?php echo $this->language->get("Youtube");?></option>
										    	<option value="vimeo"><?php echo $this->language->get("Vimeo");?></option>
										    </select>									    	
									    </div>
									</div>
									<div class="form-group">
									    <label class="col-sm-3 control-label">Video ID</label>

									    <div class="col-sm-8">
									    	<input class="form-control" name="layer_video_id" type="text" id="dialog_video_id" />
										    <p class="help-block"><?php echo $this->language->get("For example youtube");?>: <b>VA770wpLX-Q</b> and vimeo: <b>17631561</b></p>								    	
									    </div>
									</div>
									<div class="form-group">
									    <label class="col-sm-3 control-label"><?php echo $this->language->get("Width")?></label>
									    <div class="col-sm-2">
										    <input class="form-control" name="layer_video_width" type="text" value="300" />
									    </div>
									    <label class="col-sm-2 control-label"><?php echo $this->language->get("Height")?></label>
								        <div class="col-sm-2">
								    	    <input class="form-control" name="layer_video_height" type="text" value="200">
								        </div>
									</div>

									<input type="hidden" name="layer_video_thumb" id="layer_video_thumb">

									<div class="form-group">
										<label class="col-sm-3 control-label">&nbsp;</label>
										<div class="btn-group">
											<div class="btn btn-success btn-sm layer-find-video"><?php echo $this->language->get("Find Video")?></div>
											<div class="btn btn-success btn-sm layer-apply-video" id="apply_this_video" style="display:none;"><?php echo $this->language->get("Use This Video")?></div>
											<div class="btn btn-danger btn-sm" onclick="$('#dialog-video').hide();"><?php echo $this->language->get("Close");?></div>
										</div>
									</div>
									<div id="video-preview"></div>
								</div>
								<div class="slider-foot">
									<div class="layer-collection-wrapper col-sm-5 pull-right">
										<fieldset>
											<legend><?php echo $this->language->get("Layer Collection")?></legend>
											<div class="layer-collection" id="layer-collection"></div>
										</fieldset>
									</div>
									<div class="layer-form col-sm-5 pull-left" id="layer-form">
										<fieldset>
											<legend><?php echo $this->language->get("Edit Layer Data")?></legend>

											<input type="hidden" id="layer_id" name="layer_id"/>
											<input type="hidden" id="layer_content" name="layer_content"/>
											<input type="hidden" id="layer_type" name="layer_type"/>

											<div class="form-horizontal">
												<div class="form-group">
													<label class="col-sm-2 control-label">Class Style</label>
													<div class="col-sm-3">
														<input class="form-control" type="text" name="layer_class" id="input-layer-class" />
													</div>
													<div class="btn-group col-sm-6">
														<button type="button" class="btn btn-success btn-xs btn-typo" id="btn-insert-typo"><?php echo $this->language->get("Insert Typo")?></button>
														<button type="button" class="btn btn-danger btn-xs btn-clear" onclick="$('#input-layer-class').val('')"><?php echo $this->language->get("Clear");?></button>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label"><?php echo $this->language->get("Text")?></label>
													<div class="col-sm-9">
														<textarea class="form-control" style="width:90%; height:60px" name="layer_caption" id="input-slider-caption" data-for="caption-layer" ></textarea>
														<p class="help-block"><?php echo $this->language->get("Allow insert html code");?></p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label"><?php echo $this->language->get("Effect"); ?></label>
													<label class="col-sm-1 control-label" style="width: 100px;"><?php echo $this->language->get("Animation"); ?></label>
													<div class="col-sm-3">
														<select class="form-control" name="layer_animation">
															<option selected="selected" value="fade"><?php echo $this->language->get("Fade");?></option>
															<option value="sft"><?php echo $this->language->get("Short from Top");?></option>
															<option value="sfb"><?php echo $this->language->get("Short from Bottom");?></option>
															<option value="sfr"><?php echo $this->language->get("Short from Right");?></option>
															<option value="sfl"><?php echo $this->language->get("Short from Left");?></option>
															<option value="lft"><?php echo $this->language->get("Long from Top");?></option>
															<option value="lfb"><?php echo $this->language->get("Long from Bottom");?></option>
															<option value="lfr"><?php echo $this->language->get("Long from Right");?></option>
															<option value="lfl"><?php echo $this->language->get("Long from Left");?></option>
															<option value="randomrotate"><?php echo $this->language->get("Random Rotate");?></option>
														</select>
													</div>
													<label class="col-sm-1 control-label"><?php echo $this->language->get("Easing"); ?></label>
													<div class="col-sm-3">
														<select class="form-control" name="layer_easing">
															<option value="easeOutBack">easeOutBack</option>
															<option value="easeInQuad">easeInQuad</option>
															<option value="easeOutQuad">easeOutQuad</option>
															<option value="easeInOutQuad">easeInOutQuad</option>
															<option value="easeInCubic">easeInCubic</option>
															<option value="easeOutCubic">easeOutCubic</option>
															<option value="easeInOutCubic">easeInOutCubic</option>
															<option value="easeInQuart">easeInQuart</option>
															<option value="easeOutQuart">easeOutQuart</option>
															<option value="easeInOutQuart">easeInOutQuart</option>
															<option value="easeInQuint">easeInQuint</option>
															<option value="easeOutQuint">easeOutQuint</option>
															<option value="easeInOutQuint">easeInOutQuint</option>
															<option value="easeInSine">easeInSine</option>
															<option value="easeOutSine">easeOutSine</option>
															<option value="easeInOutSine">easeInOutSine</option>
															<option value="easeInExpo">easeInExpo</option>
															<option selected="selected" value="easeOutExpo">easeOutExpo</option>
															<option value="easeInOutExpo">easeInOutExpo</option>
															<option value="easeInCirc">easeInCirc</option>
															<option value="easeOutCirc">easeOutCirc</option>
															<option value="easeInOutCirc">easeInOutCirc</option>
															<option value="easeInElastic">easeInElastic</option>
															<option value="easeOutElastic">easeOutElastic</option>
															<option value="easeInOutElastic">easeInOutElastic</option>
															<option value="easeInBack">easeInBack</option>
															<option value="easeOutBack">easeOutBack</option>
															<option value="easeInOutBack">easeInOutBack</option>
															<option value="easeInBounce">easeInBounce</option>
															<option value="easeOutBounce">easeOutBounce</option>
															<option value="easeInOutBounce">easeInOutBounce</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label"><?php echo $this->language->get("Speed"); ?></label>
													<div class="col-sm-2">
														<input class="form-control" name="layer_speed" type="number" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label"><?php echo $this->language->get("Position"); ?></label>
													<label class="col-sm-1 control-label"><?php echo $this->language->get("Top"); ?></label>
													<div class="col-sm-3">
														<input class="form-control" type="number" name="layer_top">
													</div>
													<label class="col-sm-1 control-label"><?php echo $this->language->get("Left"); ?></label>
													<div class="col-sm-3">
														<input class="form-control" type="number" name="layer_left">
													</div>
												</div>
											</div>
										</fieldset>

										<div class="other-effect">
											<fieldset>
												<legend><?php echo $this->language->get("Other Animation");?></legend>
												<div class="form-horizontal">
													<div class="form-group">
														<label class="col-sm-2 control-label"><?php echo $this->language->get("End Time"); ?></label>
														<div class="col-sm-2">
															<input class="form-control" type="number" name="layer_endtime" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-2 control-label"><?php echo $this->language->get("End Speed"); ?></label>
														<div class="col-sm-2">
															<input class="form-control" type="number" name="layer_endspeed" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-2 control-label"><?php echo $this->language->get("End Animation");?></label>
														<div class="col-sm-6">
															<select class="form-control" name="layer_endanimation">
																<option selected="selected" value="auto"><?php echo $this->language->get("Choose Automatic");?></option>
																<option value="fadeout"><?php echo $this->language->get("Fade Out");?></option>
																<option value="stt"><?php echo $this->language->get("Short to Top");?></option>
																<option value="stb"><?php echo $this->language->get("Short to Bottom");?></option>
																<option value="stl"><?php echo $this->language->get("Short to Left");?></option>
																<option value="str"><?php echo $this->language->get("Short to Right");?></option>
																<option value="ltt"><?php echo $this->language->get("Long to Top");?></option>
																<option value="ltb"><?php echo $this->language->get("Long to Bottom");?></option>
																<option value="ltl"><?php echo $this->language->get("Long to Left");?></option>
																<option value="ltr"><?php echo $this->language->get("Long to Right");?></option>
																<option value="randomrotateout"><?php echo $this->language->get("Random Rotate Out");?></option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-2 control-label"><?php echo $this->language->get("End Easing");?></label>
														<div class="col-sm-6">
															<select class="form-control" name="layer_endeasing">
																<option selected="selected" value="nothing">No Change</option>
																<option value="easeOutBack">easeOutBack</option>
																<option value="easeInQuad">easeInQuad</option>
																<option value="easeOutQuad">easeOutQuad</option>
																<option value="easeInOutQuad">easeInOutQuad</option>
																<option value="easeInCubic">easeInCubic</option>
																<option value="easeOutCubic">easeOutCubic</option>
																<option value="easeInOutCubic">easeInOutCubic</option>
																<option value="easeInQuart">easeInQuart</option>
																<option value="easeOutQuart">easeOutQuart</option>
																<option value="easeInOutQuart">easeInOutQuart</option>
																<option value="easeInQuint">easeInQuint</option>
																<option value="easeOutQuint">easeOutQuint</option>
																<option value="easeInOutQuint">easeInOutQuint</option>
																<option value="easeInSine">easeInSine</option>
																<option value="easeOutSine">easeOutSine</option>
																<option value="easeInOutSine">easeInOutSine</option>
																<option value="easeInExpo">easeInExpo</option>
																<option value="easeOutExpo">easeOutExpo</option>
																<option value="easeInOutExpo">easeInOutExpo</option>
																<option value="easeInCirc">easeInCirc</option>
																<option value="easeOutCirc">easeOutCirc</option>
																<option value="easeInOutCirc">easeInOutCirc</option>
																<option value="easeInElastic">easeInElastic</option>
																<option value="easeOutElastic">easeOutElastic</option>
																<option value="easeInOutElastic">easeInOutElastic</option>
																<option value="easeInBack">easeInBack</option>
																<option value="easeOutBack">easeOutBack</option>
																<option value="easeInOutBack">easeInOutBack</option>
																<option value="easeInBounce">easeInBounce</option>
																<option value="easeOutBounce">easeOutBounce</option>
																<option value="easeInOutBounce">easeInOutBounce</option>
															</select>
														</div>
													</div>
												</div>
											</fieldset>

										</div>

										</div>
									</div>
								</div>
								</div>
							</form>
						</div>
					</div>
				</section>
			</div>
		</div>
	</section>
</section>
<script type="text/javascript">
$(".group-sliders").sortable({ accept:".slider-item",
								  update:function() {   
								  	 var ids = $( ".slider-item" ).sortable( "toArray" );
								  	 var params = '';
								  	 var j=1;
								  	 $.each( ids, function(i,e){
								  	 	params += 'id['+$(e).attr('id').replace("slider_","")+']='+(j++)+"&";
								  	 } );

								  	 $.ajax({
										 url:'<?php echo str_replace("&amp;","&",$actionUpdatePostURL); ?>',
										data: params,
										type:'POST'
										});
								  	 // alert( params );
 							      } 
});

$("#btn-update-slider").click( function(){ 
			var field = 'slider-image';
			$('#dialog').remove();
			
			$('#container').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			
			$('#dialog').dialog({
				title: 'Image Management',
				close: function (event, ui) {
					if ( $('#' + field).attr('value')) { 
						var src= '<?php echo HTTP_CATALOG?>image/'+$('#' + field).attr('value');
						$(".slider-editor-wrap .simage").html( '<img src="'+src+'">'  ); 
					}
				},	
				bgiframe: false,
				width: 700,
				height: 400,
				resizable: false,
				modal: false
		});
});


</script>
<?php


 // echo '<pre>'.print_r( $sliderGroup,1 ); die;
?> 
<script type="text/javascript">
	$( document ).ready( function(){
		var JSONLIST = '<?php echo json_encode( $layers ); ?>';
		 
		var $pavoEditor = $(document).pavoSliderEditor(); 
		var SURLIMAGE = 'index.php?token=<?php echo $token; ?>';
		var SURL = '<?php echo HTTP_CATALOG ?>';
		$pavoEditor.process(SURL, SURLIMAGE, <?php echo $sliderGroup['params']['delay']; ?> ); 
		$pavoEditor.createList( JSONLIST  );
	});


	$("#btn-preview-ingroup").click( function(){
		var url = $(this).attr("href");
			$('#dialog').remove();
			$('#container').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe name="iframename2" src="'+url+'" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			$('#dialog').dialog({
				title: 'Preview Management',
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
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#container').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script>
	$(document).ready(function () {
		$('.switch').bootstrapSwitch();
	});
</script>
<?php echo $footer; ?>