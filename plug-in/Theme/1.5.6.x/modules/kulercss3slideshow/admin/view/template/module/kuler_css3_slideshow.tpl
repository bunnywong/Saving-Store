<?php

/*----------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/-----------------------------------------------------------------------------*/

?>

<?php echo $header; ?>
<?php if (isset($ksb_building_mode)) { ?>
    <style type="text/css">
        #header, .breadcrumb, .box > .heading .buttons,.vtabs, #footer {
            display: none !important;
        }

        #content {
            padding: 0 !important;
        }

        .box > .content {
            background: none !important;
        }

        .vtabs-content {
            padding-left: 15px !important;
        }
    </style>
<?php } ?>
<script type="text/javascript">
    <?php if (isset($ksb_updated_module)) { ?>
    var ksb_updated_module = <?php echo $ksb_updated_module; ?>;
    <?php } ?>
</script>

<script type="text/javascript">
    var base = '<?php echo $base; ?>',
        token = '<?php echo $token ?>',
        fetchBannerUrl = '<?php echo $fetchBannerUrl; ?>',
        modules = <?php echo json_encode($modules); ?>,
        defaultModule = <?php echo json_encode($defaultModule); ?>,
        defaultImage = <?php echo json_encode($defaultImage); ?>,
        languages = <?php echo json_encode($languages); ?>,
        errorImageSource = <?php echo json_encode($errorImageSource); ?>,
        errorDimension = <?php echo json_encode($error_dimension); ?>;
</script>
<div id="content" class="kuler-module">
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning" style="margin-top: 15px;"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
    <div class="heading clearafter">
		<h1><img src="./view/kulercore/images/logos/kuler_css3_slideshow.png" alt="<?php echo $heading_title; ?>" /></h1>
        <div class="buttons">
          <a onclick="$('#form').submit();" class="button save-settings"><?php echo 'Save' ?></a>
          <a onclick="$('#op').val('close'); $('#form').submit();" class="button cancel-settings"><?php echo 'Close' ?></a>
          <a href="<?php echo $cancel; ?>" class="button cancel-settings"><?php echo 'Cancel' ?></a>
       </div>
    </div>
    <div class="content">
        <form id="form" action="<?php echo $action; ?>" method="post">
            <input type="hidden" name="tab" id="tab" value="<?php echo $tab ?>" />
            <input type="hidden" name="op" id="op" />

            <div style="margin: 20px 0 0 30px;">
                <label><?php echo __('Store'); ?>:</label>
                <select name="store_id" id="StoreSelector">
                    <?php foreach ($stores as $store_id => $store_name) { ?>
                        <option value="<?php echo $store_id; ?>"<?php if ($store_id == $selected_store_id) echo ' selected="selected"'; ?>><?php echo $store_name; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div id="ModuleContainer">
                <ul class="vtabs">
                    <li>
                        <span id="AddModule" class="module-add">
                            <?php echo __('ADD MODULE'); ?>
                            &nbsp;
                            <img class="add-element" src="view/kulercore/images/icons/icon-add.png" />
                        </span>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>

<script id="TemplateModuleHeading" type="text/html">
    <li id="TabModuleHeading_{{= moduleRow}}">
        <a id="TabModuleTitle_{{= moduleRow}}" class="TabModuleTitle" data-tab="{{= moduleRow}}" href="#TabModule_{{= moduleRow}}">
            <b id="ModuleTabTitle_{{= moduleRow }}">{{= module.title[<?php echo $language_id; ?>] }}</b>
            <img data-module="{{= moduleRow}}" src="view/kulercore/images/icons/icon-delete.png" class="remove-element RemoveModule" title="<?php echo __('Remove this module'); ?>" >
        </a>
    </li>
</script>

<script id="TemplateModule" type="text/html">
<div id="TabModule_{{= moduleRow}}" class="vtabs-content clearafter">
    <h2><?php echo __('MODULE'); ?></h2>
    <table class="form">
        <tbody>
            <tr>
                <td><?php echo __('Status'); ?>:</td>
                <td>
                    <div class="kuler-switch-btn">
                        <input type="hidden" name="modules[{{= moduleRow}}][status]" value="0"{{ if (module.status == 0) { }} checked="checked" {{ } }} />
                        <input type="checkbox" name="modules[{{= moduleRow}}][status]" value="1"{{ if (module.status == 1) { }} checked="checked" {{ } }} />
                        <span class="kuler-switch-btn-holder"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Show Title'); ?>:</td>
                <td>
                    <div class="kuler-switch-btn">
                        <input type="hidden" name="modules[{{= moduleRow}}][show_title]" value="0"{{ if (module.show_title == 0) { }} checked="checked" {{ } }} />
                        <input type="checkbox" name="modules[{{= moduleRow}}][show_title]" value="1"{{ if (module.show_title == 1) { }} checked="checked" {{ } }} />
                        <span class="kuler-switch-btn-holder"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Title'); ?>:</td>
                <td>
                    <?php foreach ($languages as $language) { ?>
                        <input type="text"<?php if ($language['language_id'] == $language_id) { ?>class="ModuleTitle" data-module-row="{{= moduleRow }}" <?php } ?> data-shortcode="#ModuleShortCode_{{= moduleRow }}" name="modules[{{= moduleRow}}][title][<?php echo $language['language_id']; ?>]" value="{{= module.title[<?php echo $language['language_id']; ?>] }}" />
                        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /> <br />
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Short Code'); ?>:</td>
                <td>
                    <input type="text" id="ModuleShortCode_{{= moduleRow }}" class="ModuleShortCode" name="modules[{{= moduleRow }}][shortcode]" value="{{= module.shortcode }}" readonly="readonly" size="40" />
                </td>
            </tr>
            <tr>
                <td><?php echo __('Layout'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][layout_id]">
                        <?php foreach ($layouts as $layout) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>"{{ if (module.layout_id == <?php echo $layout['layout_id'] ?>) { }} selected="selected" {{ } }}><?php echo $layout['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Position'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][position]">
                        <option value="content_top"{{ if (module.position == 'content_top') { }} selected="selected" {{ } }}><?php echo __('Content Top'); ?></option>
                        <option value="content_bottom"{{ if (module.position == 'content_bottom') { }} selected="selected" {{ } }}><?php echo __('Content Bottom'); ?></option>
                        <option value="column_left"{{ if (module.position == 'column_left') { }} selected="selected" {{ } }}><?php echo __('Content Left'); ?></option>
                        <option value="column_right"{{ if (module.position == 'column_right') { }} selected="selected" {{ } }}><?php echo __('Content Right'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Sort Order'); ?>:</td>
                <td>
                    <input type="text" name="modules[{{= moduleRow}}][sort_order]" size="3" value="{{= module.sort_order}}" />
                </td>
            </tr>
        </tbody>
    </table>
    
    <h2><?php echo __('SETTINGS'); ?></h2>
    <div class="htabs">
        <?php foreach ($languages as $language) {  ?>
        <a href="#Setting_{{= moduleRow}}_<?php echo $language['language_id']; ?>" class="SettingTab" id="SettingTab_<?php echo $language['language_id']; ?>" data-tab="<?php echo $language['language_id']; ?>">
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
        </a>
        <?php } ?>
    </div>

    <?php foreach ($languages as $language) { ?>
    <div id="Setting_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
        <table class="form">
            <tbody>
            <tr>
              <td><?php echo __('Slideshow Type'); ?>:</td>
              <td>
	              <select name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][slideshow_type]" id="SlideshowType_{{= moduleRow }}_<?php echo $language['language_id']; ?>" class="SlideshowType" data-module="{{= moduleRow }}" data-language-id="<?php echo $language['language_id']; ?>">
		              <option value="split"{{ if (module.data[<?php echo $language['language_id']; ?>].slideshow_type == 'split') { }} selected="selected" {{ } }}><?php echo __('Split'); ?></option>
		              <option value="full-screen"{{ if (module.data[<?php echo $language['language_id']; ?>].slideshow_type == 'full-screen') { }} selected="selected" {{ } }}><?php echo __('Full Screen Background'); ?></option>
	              </select>
              </td>
            </tr>
            <tr>
                <td><?php echo __('Image Source'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][image_source]" class="ImageSourceChooser" data-module="{{= moduleRow}}" data-language-id="<?php echo $language['language_id']; ?>">
                        <option value="0">--</option>
                        <option value="banner"{{ if (module.data[<?php echo $language['language_id']; ?>].image_source == 'banner') { }} selected="selected" {{ } }}>Banner</option>
                        <option value="images"{{ if (module.data[<?php echo $language['language_id']; ?>].image_source == 'images') { }} selected="selected" {{ } }}>Images</option>
                    </select>
                    {{ if (errorImageSource[moduleRow] && errorImageSource[moduleRow][<?php echo $language['language_id']; ?>]) { }}
                    <span class="error">{{= errorImageSource[moduleRow][<?php echo $language['language_id']; ?>] }}</span>
                    {{ } }}
                </td>
            </tr>
            <tr id="BannerField_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Banner'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][banner]" id="BannerChooser_{{= moduleRow }}_<?php echo $language['language_id']; ?>" class="BannerChooser" data-module="{{= moduleRow}}" data-language-id="<?php echo $language['language_id']; ?>">
                        <option value="0">--</option>
                        <?php foreach ($banners as $banner) { ?>
                        <option value="<?php echo $banner['banner_id']; ?>"{{ if (module.data[<?php echo $language['language_id']; ?>].banner == <?php echo $banner['banner_id']; ?>) { }} selected="selected" {{ } }}><?php echo $banner['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Slideshow Layout'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][dimension]" class="DimensionType" data-module-row="{{= moduleRow}}" data-language-id="<?php echo $language['language_id']; ?>">
                        <option value="fixed"{{ if (module.data[<?php echo $language['language_id']; ?>].dimension == 'fixed') { }} selected="selected" {{ } }}><?php echo __('Boxed Layout') ?></option>
                        <option value="fluid"{{ if (module.data[<?php echo $language['language_id']; ?>].dimension == 'fluid') { }} selected="selected" {{ } }}><?php echo __('Full Width Layout'); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="WidthRow_{{= moduleRow}}_<?php echo $language['language_id']; ?> RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><span class="required">*</span> <?php echo __('Width'); ?></td>
                <td>
                    <input type="text" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][width]" value="{{= module.data[<?php echo $language['language_id']; ?>].width}}" id="OptionWidth_{{= moduleRow }}_<?php echo $language['language_id']; ?>" placeholder="width" size="5" required="required" />
                    {{ if (errorDimension[moduleRow] && errorDimension[moduleRow][<?php echo $language['language_id']; ?>]) { }}
                    <span class="error">{{= errorDimension[moduleRow][<?php echo $language['language_id']; ?>] }}</span>
                    {{ } }}
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><span class="required">*</span> <?php echo __('Height'); ?></td>
                <td>
	                <input type="text" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][height]" value="{{= module.data[<?php echo $language['language_id']; ?>].height}}" placeholder="height" size="5" required="required" />
	                {{ if (errorDimension[moduleRow] && errorDimension[moduleRow][<?php echo $language['language_id']; ?>]) { }}
                    <span class="error">{{= errorDimension[moduleRow][<?php echo $language['language_id']; ?>] }}</span>
                    {{ } }}
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Transition'); ?>:</td>
                <td>
                    <select name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][transition]" id="Transition_{{= moduleRow}}">
                        <option value="1"{{ if (module.data[<?php echo $language['language_id']; ?>].transition == 1) { }} selected="selected" {{ } }}>1</option>
                        <option value="2"{{ if (module.data[<?php echo $language['language_id']; ?>].transition == 2) { }} selected="selected" {{ } }}>2</option>
                        <option value="3"{{ if (module.data[<?php echo $language['language_id']; ?>].transition == 3) { }} selected="selected" {{ } }}>3</option>
                        <option value="4"{{ if (module.data[<?php echo $language['language_id']; ?>].transition == 4) { }} selected="selected" {{ } }}>4</option>
                    </select>
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Split'); ?>:</td>
                <td>
                    <input type="text" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][split]" value="{{= module.data[<?php echo $language['language_id']; ?>].split}}" required="required" />
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>" data-module="{{= moduleRow }}" data-language-id="<?php echo $language['language_id']; ?>">
                <td><?php echo __('Auto Start'); ?>:</td>
                <td>
                    <div class="kuler-switch-btn">
                        <input type="hidden" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][autostart]" value="0"{{ if (module.data[<?php echo $language['language_id']; ?>].autostart == 0) { }} checked="checked" {{ } }} />
                        <input type="checkbox" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][autostart]" class="SettingAutoStart" data-related-row=".RowSettingDuration_{{= moduleRow }}" value="1"{{ if (module.data[<?php echo $language['language_id']; ?>].autostart == 1) { }} checked="checked" {{ } }} />
                        <span class="kuler-switch-btn-holder"></span>
                    </div>
                </td>
            </tr>
            <tr class="RowSettingDuration_{{= moduleRow }} RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Duration'); ?>:</td>
                <td>
                    <input type="text" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][duration]" value="{{= module.data[<?php echo $language['language_id']; ?>].duration }}" placeholder="{{= defaultModule.data[<?php echo $language['language_id']; ?>].duration }}" size="5" /> ms
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Navigation'); ?>:</td>
                <td>
                    <div class="kuler-switch-btn">
                        <input type="hidden" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][navigation]" value="0"{{ if (module.data[<?php echo $language['language_id']; ?>].navigation == 0) { }} checked="checked" {{ } }} />
                        <input type="checkbox" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][navigation]" value="1"{{ if (module.data[<?php echo $language['language_id']; ?>].navigation == 1) { }} checked="checked" {{ } }} />
                        <span class="kuler-switch-btn-holder"></span>
                    </div>
                </td>
            </tr>
            <tr class="RowRelatedSplitType_{{= moduleRow}}_<?php echo $language['language_id']; ?>">
                <td><?php echo __('Bullet'); ?>:</td>
                <td>
                    <div class="kuler-switch-btn">
                        <input type="hidden" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][bullet]" value="0"{{ if (module.data[<?php echo $language['language_id']; ?>].bullet == 0) { }} checked="checked" {{ } }} />
                        <input type="checkbox" name="modules[{{= moduleRow}}][data][<?php echo $language['language_id']; ?>][bullet]" value="1"{{ if (module.data[<?php echo $language['language_id']; ?>].bullet == 1) { }} checked="checked" {{ } }} />
                        <span class="kuler-switch-btn-holder"></span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <table id="BannerList_{{= moduleRow}}_<?php echo $language['language_id']; ?>" class="list">
            <thead>
            <tr>
                <td class="left"><?php echo __('Title'); ?>:</td>
                <td class="left"><?php echo __('Link'); ?>:</td>
                <td class="center"><?php echo __('Image'); ?>:</td>
                <td class="center"><?php echo __('Sort Order'); ?>:</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td>
                <td class="center"><a href="#" data-module="{{= moduleRow}}" data-language-id="<?php echo $language['language_id']; ?>" class="button AddBanner"><?php echo __('Add Banner'); ?></a></td>
            </tr>
            </tfoot>
        </table>

        <table id="ImageList_{{= moduleRow}}_<?php echo $language['language_id']; ?>" class="list">
            <thead>
            <tr>
                <td class="left"><?php echo __('Title'); ?>:</td>
                <td class="left"><?php echo __('Link'); ?>:</td>
                <td class="center"><?php echo __('Image'); ?>:</td>
                <td class="center"><?php echo __('Sort Order'); ?>:</td>
                <td width="150"></td>
            </tr>
            </thead>
            <tbody>
            {{ if (module.data[<?php echo $language['language_id']; ?>].image_source == 'images') { }}
            {{ for (var i in module.data[<?php echo $language['language_id']; ?>].images) { }}
            {{= Setting.addImage(moduleRow, <?php echo $language['language_id']; ?>, module.data[<?php echo $language['language_id']; ?>].images[i], true) }}
            {{ } }}
            {{ } }}
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td>
                <td class="left">
                    <a href="#" id="AddImage" class="button" data-module="{{= moduleRow}}" data-language-id="<?php echo $language['language_id']; ?>"><?php echo __('Add Image'); ?></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>
</div>
</script>

<script id="TemplateImage" type="text/html">
    <tr id="ImageRow_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}">
        <td class="left"><input type="text" name="modules[{{= moduleRow}}][data][{{= languageId}}][images][{{= imageRow}}][title]" value="{{= image.title}}" /></td>
        <td class="left">
            <input type="text" name="modules[{{= moduleRow}}][data][{{= languageId}}][images][{{= imageRow}}][link]" value="{{= image.link }}" />
        </td>
        <td class="center">
            <input type="text" id="Image_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" class="ImageField" data-image="#ImageContainer_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" name="modules[{{= moduleRow}}][data][{{= languageId}}][images][{{= imageRow}}][image]" value="{{= image.image }}" />
            <div class="image">
                <div id="ImageContainer_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" class="image-container">
                    <img src="{{= image.thumb}}" />
                </div>
                <a href="#" class="button ImageManager" data-field="Image_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" data-clear="#ClearImage_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" data-image="#ImageContainer_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}"><?php echo __('Browse'); ?></a>
                <a href="#" id="ClearImage_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" class="button"><?php echo __('Clear'); ?></a>
            </div>
        </td>
        <td class="center">
            <input type="text" name="modules[{{= moduleRow}}][data][{{= languageId}}][images][{{= imageRow}}][sort_order]" value="{{= image.sort_order }}" size="5" />
        </td>
        <td class="center"><a href="#" data-image="#ImageRow_{{= moduleRow}}_{{= languageId}}_{{= imageRow}}" class="button RemoveImage"><?php echo __('Remove Image'); ?></a></td>
    </tr>
</script>
<script id="TemplateBanner" type="text/html">
    <tr id="BannerRow_{{= moduleRow}}_{{= imageRow}}">
        <td class="left">
            {{ for (var code in languages) { }}
            <input type="text" name="banner_images[{{= moduleRow}}][{{= languageId}}][{{= imageRow}}][banner_image_description][{{= languages[code]['language_id']}}][title]" value="{{= image.titles[languages[code]['language_id']]['title'] }}" />
            <img src="view/image/flags/{{= languages[code].image}}" title="{{= languages[code].name}}" />
            <br />
            {{ } }}
        </td>
        <td class="left">
            <input type="text" name="banner_images[{{= moduleRow}}][{{= languageId}}][{{= imageRow}}][link]" value="{{= image.link}}"
        </td>
        <td class="center">
            <div class="image">
                <div id="ImageContainer_{{= moduleRow}}_{{= imageRow}}" class="image-container">
                    <img src="{{= image.thumb}}" />
                </div>
                <input type="hidden" id="Image_{{= moduleRow}}_{{= imageRow}}" name="banner_images[{{= moduleRow}}][{{= languageId}}][{{= imageRow}}][image]" value="{{= image.image}}" />
                <a href="#" class="button ImageManager" data-field="Image_{{= moduleRow}}_{{= imageRow}}" data-clear="#ClearImage_{{= moduleRow}}_{{= imageRow}}" data-image="#ImageContainer_{{= moduleRow}}_{{= imageRow}}"><?php echo __('Browse'); ?></a>
                <a href="#" id="ClearImage_{{= moduleRow}}_{{= imageRow}}" class="button"><?php echo __('Clear'); ?></a>
            </div>
        </td>
        <td class="center">
            <input type="text" name="banner_images[{{= moduleRow}}][{{= languageId}}][{{= imageRow}}][sort_order]" value="{{= image.sort_order }}" size="3" />
        </td>
        <td class="center">
            <a href="#" class="button RemoveBannerImage" data-row="#BannerRow_{{= moduleRow}}_{{= imageRow}}"><?php echo __('Remove'); ?></a>
        </td>
    </tr>
</script>

<script type="text/javascript">
// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function(){
  var cache = {};
  
  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
        cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :
      
      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +
        
        // Introduce the data as local variables using with(){}
            "with(obj){p.push('" +
            
        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("{{").join("\t")
          .replace(/((^|\}\})[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)\}\}/g, "',$1,'")
          .split("\t").join("');")
          .split("}}").join("p.push('")
          .split("\r").join("\\'")
        + "');}return p.join('');");
        
    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();
</script>

<script type="text/javascript">
    var saveUrl = '<?php echo $action; ?>';
    saveUrl = saveUrl.replace(new RegExp('&amp;', 'g'), '&');
    $('#StoreSelector').on('change', function () {
        window.location = saveUrl + '&store_id=' + $(this).val();
    });

    var Tab = (function () {
        $.fn.tabs = function (options) {
            var defaults = {
                    prefix: '',
                    key: '',
                    context: document
                },
                selector = this.selector,
                activeKey;

            options = $.extend(defaults, options);

            activeKey = options.prefix + options.key;

            var matches = document.cookie.match(new RegExp(activeKey + '=([^;]+);')), activeValue = 0;

            if (matches) {
                activeValue = matches[1];
            }

            $(selector, options.context).on('click', function (evt) {
                evt.preventDefault();

                $(selector, options.context)
                    .removeClass('selected')
                    .each(function () {
                        $($(this).attr('href')).hide();
                    });

                var $this = $(this);
                $this.addClass('selected');
                $($this.attr('href')).show();

                document.cookie = activeKey + '=' + $this.data('tab');
            });

            this.show();

            if (!$('#' + options.key + '_' + activeValue, options.context).trigger('click').length) {
                $(selector, options.context).eq(0).trigger('click');
            }

            return this;
        };

        return {
            init: function (selector, tabPrefix, tabKey) {
                var context = $.isPlainObject(selector) ? selector.context : document,
                    selector = $.isPlainObject(selector) ? selector.selector : selector;

                $(selector, context).tabs({
                    prefix: tabPrefix,
                    key: tabKey,
                    context: context
                });
            }
        };
    })();

    var $doc = $(document);

    var ImageManager = (function () {
        return {
            init: function (selector, context) {
                var self = this;
                self.$el = $(selector, context || document);

                self.$el.bind('click', function (evt) {
                    evt.preventDefault();
                    self.showDialog($(this).data('field'), $(this).data('image'));
                });

                self.$el.each(function () {
                    var $this = $(this);

                    $($this.data('clear')).bind('click', function () {
                        $('#' + $this.data('field')).val('');
                        $($this.data('image')).html('');

                        return false;
                    });
                });
            },
            showDialog: function (field, image) {
                $('#dialog').remove();

                $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token='+ token +'&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

                $('#dialog').dialog({
                    title: 'Image Manager',
                    close: function (event, ui) {
                        var val;

                        if (val = $('#' + field).val()) {
                            $('#' + field).val(val).trigger('change');

                            $(image).html($('<img />', {src: base + 'image/' + val}));
                        }
                    },
                    bgiframe: false,
                    width: 700,
                    height: 400,
                    resizable: false,
                    modal: false
                });
            }
        };
    })();

    var ModuleTab = (function () {
        var moduleRow = 0, self;

        function addModule(data) {
            data.moduleRow = moduleRow;

            var $tabHeading = $tabContent = '';

            $tabHeading = tmpl('TemplateModuleHeading', data);
            $tabContent = tmpl('TemplateModule', data);

            $tabHeading = $($tabHeading);
            $tabContent = $($tabContent);

            $('#AddModule').parents('li').before($tabHeading);
            $('#ModuleContainer').append($tabContent);

            self.initRemoveButton($tabHeading.find('.RemoveModule'));

            $tabContent.find('.ImageSourceChooser').trigger('change');
            $tabContent.find('.DimensionType').trigger('change');

            Tab.init({'selector' : '.SettingTab', 'context' : $tabContent}, 'kcs_', 'SettingTab');

            moduleRow++;
        }

        return {
            init: function () {
                self = this;

                // Add module
                $('#AddModule').on('click', function (evt) {
                    evt.preventDefault();

                    defaultModule.title = {};

                    <?php foreach ($languages as $language) { ?>
	                <?php if (!empty($language['language_id'])) { ?>
                    defaultModule.title[<?php echo $language['language_id']; ?>] = 'MODULE ' + (moduleRow + 1);
                    <?php } ?>
                    <?php } ?>

                    addModule({module: defaultModule});

                    self.initTabs();
                    self.activeLastTab();
                });

	            var windowWidth = $(window).width();
                $.each(modules, function () {
	                // Auto calculate full width

	                for (var languageId in this.data) {
		                if ('dimension' in this.data[languageId] && this.data[languageId]['dimension'] == 'fluid' && !this.data[languageId]['width']) {
			                this.data[languageId]['width'] = windowWidth;
		                }
	                }

                    addModule({module: this});
                })

                self.initRemoveButton('.RemoveModule');
                self.initTabs();
            },
            initRemoveButton: function (selector) {
                $(selector).on('click', function (evt) {
                    evt.preventDefault();

                    var module = $(this).data('module');

                    $('#TabModuleHeading_' + module + ', ' + '#TabModule_' + module).remove();
                    self.activeLastTab();
                });
            },
            initTabs: function () {
                Tab.init('.TabModuleTitle', 'kcs_', 'TabModuleTitle');
            },
            activeLastTab: function () {
                $('#ModuleContainer .vtabs a:last').trigger('click');
            }
        };
    })();

    var Setting = (function () {
        var imageRows = {};

        function toggleOption(module, languageId, imageSource) {
            if (imageSource == 'images') {
                $('#ImageList_' + module + '_' + languageId).show();
                $('#BannerField_'+ module + '_' + languageId +', #BannerList_' + module + '_' + languageId).hide();
                $('#BannerList_'+ module + '_' + languageId +' tbody').empty();
            } else if (imageSource == 'banner') {
                $('#ImageList_' + module + '_' + languageId).hide();
                $('#ImageList_'+ module + '_' + languageId +' tbody').empty();
                $('#BannerField_'+ module + '_' + languageId +', #BannerList_' + module + '_' + languageId).show();
                $('#BannerChooser_' + module + '_' + languageId).trigger('change');
            } else {
                $('#ImageList_'+ module + '_' + languageId +', #BannerField_'+ module + '_' + languageId +', #BannerList_' + module + '_' + languageId).hide();
            }
        }

        function addBanner(module, languageId, image) {
            if (imageRows[module] === undefined) {
                imageRows[module] = 0;
            }

            var $html = '', imageRow = imageRows[module];

            $html = tmpl('TemplateBanner', {moduleRow: module, languageId: languageId, image: image, imageRow: imageRow});
            $html = $($html);

            $('#BannerList_'+ module + '_' + languageId +' tbody').append($html);
            ImageManager.init('.ImageManager', $html[0]);

            imageRows[module]++;
        }

        function toggleRelatedRow(option) {
            var $option = $(option),
	            $relatedRow = $($(option).data('relatedRow')),
	            module = $option.data('module'),
	            languageId = $option.data('languageId');

	        if ($('.SlideshowType_' + module + '_' + languageId).val() == 'split') {
		        if ($(option).prop('checked')) {
			        $relatedRow.show();
		        } else {
			        $relatedRow.hide();
		        }
	        }
        }

        var self;

        return {
            init: function () {
                self = this;

                $('#ModuleContainer').on('keyup', '.ModuleTitle', function () {
                    $('#ModuleTabTitle_' + $(this).data('moduleRow')).html($(this).val());
                });

	            $('#ModuleContainer').on('change', '.SlideshowType', function () {
		            var module = $(this).data('module'),
			            languageId = $(this).data('languageId');

		            if ($(this).val() == 'split') {
			            $('.RowRelatedSplitType_' + module + '_' + languageId).show();
			            $('.RowRelatedFullScreenType_' + module + '_' + languageId).hide();
		            } else if ($(this).val() == 'full-screen') {
			            $('.RowRelatedSplitType_' + module + '_' + languageId).hide();
			            $('.RowRelatedFullScreenType_' + module + '_' + languageId).show();
		            }

		           $('.DimensionType').trigger('change');
	            });

                // Image Source
                $('#ModuleContainer').on('change', '.ImageSourceChooser',function () {
                    var $this = $(this);

                    toggleOption($this.data('module'), $this.data('languageId'), $this.val());
                });

                // Add image
                $('#ModuleContainer').on('click', '#AddImage', function (evt) {
                    evt.preventDefault();

                    self.addImage($(this).data('module'), $(this).data('languageId'), defaultImage);
                });

                // Remove image
                $('#ModuleContainer').on('click', '.RemoveImage', function (evt) {
                    evt.preventDefault();

                    $($(this).data('image')).remove();
                });

                $('#ModuleContainer').on('change', '.ImageField', function () {
                    $($(this).data('image')).html($('<img />', {src: $(this).val()})); 
                });

                // Banner
                $('#ModuleContainer').on('click', '.AddBanner', function (evt) {
                    evt.preventDefault();

                    var image = {
                        titles: {},
                        link: '',
                        sort_order: ''
                    };

                    // create empty title for each language
                    $.each(languages, function () {
                        image.titles[this.language_id] = {
                            title: ''
                        };
                    });

                    addBanner($(this).data('module'), $(this).data('languageId'), image);
                });

                $('#ModuleContainer').on('change', '.BannerChooser', function () {
                    var module = $(this).data('module'),
                        languageId = $(this).data('languageId');

                    $('#BannerList_'+ module + '_' + languageId +' tbody').empty();

                    if ($(this).val() != 0) {
                        $.get(
                            fetchBannerUrl,
                            {
                                banner_id: $(this).val()
                            },
                            function (data) {
                                $.each(data.images, function () {
                                    addBanner(module, languageId, this);
                                });
                            },
                            'json'
                        );
                    }
                });

                $('#ModuleContainer').on('click', '.RemoveBannerImage', function (evt) {
                    evt.preventDefault();

                    $($(this).data('row')).remove();
                });

                // Auto start option
                $('#ModuleContainer').on('click', '.SettingAutoStart', function () {
                    toggleRelatedRow(this);
                });

                // Dimesion Type
                $('#ModuleContainer').on('change', '.DimensionType', function (evt) {
                    var moduleRow = $(this).data('moduleRow'),
                        languageId = $(this).data('languageId'),
                        type = $(this).val();

	                if ($('#SlideshowType_' + moduleRow + '_' + languageId).val() == 'split') {
		                if (type == 'fixed') {
			                $('.WidthRow_' + moduleRow + '_' + languageId).show();
			                $('.RelatedFluid_' + moduleRow + '_' + languageId).hide();
		                } else if (type == 'fluid') {
			                $('.WidthRow_' + moduleRow + '_' + languageId).hide();
			                $('.RelatedFluid_' + moduleRow + '_' + languageId).show();

			                if (!evt.isTrigger) {
				                $('#OptionWidth_' + moduleRow + '_' + languageId).val($(window).width());
			                }
		                }
	                }
                });

                // init
	            $('.SlideshowType').trigger('change');
                $('.ImageSourceChooser').trigger('change');
                $('.SettingAutoStart').each(function () {
                    toggleRelatedRow(this);
                });
                $('.DimensionType').trigger('change');
            },
            addImage: function (moduleRow, languageId, image, isReturn) {
                if (imageRows[moduleRow] === undefined) {
                    imageRows[moduleRow] = 0;
                }

                var $html = '', imageRow = imageRows[moduleRow];

                $html = tmpl('TemplateImage', {moduleRow: moduleRow, languageId: languageId, imageRow: imageRow, image: image});

                imageRows[moduleRow]++;

                if (isReturn) {
                    return $html;
                } else {
                    $html = $($html);

                    $('#ImageList_'+ moduleRow + '_' + languageId +' tbody').append($html);
                    ImageManager.init('.ImageManager', $html[0]);
                }
            }
        };
    })();

    ModuleTab.init();
    Setting.init();
    ImageManager.init('.ImageManager');
    // Active save tab
    <?php if ($tab) { ?>
    $('.vtabs a[href="<?php echo $tab ?>"]').click();
    <?php } ?>

    (function () {
        var moduleName = '<?php echo $moduleName; ?>';

        function generateShortCode(moduleName, moduleTitle) {
            var shortcode;

            moduleName = moduleName.toLowerCase();
            moduleTitle = moduleTitle.toLowerCase();

            moduleTitle = moduleTitle.replace(/\s+/g, '_');

            return '[' + moduleName + ' ' + moduleTitle + ']';
        }

        // Update the short code when the module title change
        $('#content').on('keyup change', '.ModuleTitle', function () {
            $($(this).data('shortcode')).val(generateShortCode(moduleName, this.value));
        });

        // Select the shortcode when focus
        $('#content').on('click', '.ModuleShortCode', function () {
            this.select();
        });

        $('.ModuleTitle').trigger('change');
    })();

    /* Kuler Site Builder */
    <?php if (isset($ksb_trigger_creation) && $ksb_trigger_creation) { ?>
    (function () {
        $('#AddModule').trigger('click');
    })();
    <?php } ?>
</script>
<?php echo $footer; ?>