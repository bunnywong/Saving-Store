<?php echo $header; ?>
	<div id="content" class="kuler-module">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<?php if ($error_warning) { ?>
			<div class="warning" style="margin-top: 15px;"><?php echo $error_warning; ?></div>
		<?php } ?>
		<?php if ($success) { ?>
			<div class="success" style="margin-top: 15px;"><?php echo $success; ?></div>
		<?php } ?>
		<div class="box">
			<div class="heading clearafter">
				<h1><img src="./view/kulercore/images/logos/kbm.png" alt="<?php echo _t('heading_module_title'); ?>" /></h1>
				<div class="buttons">
					<a onclick="$('#form').submit();" class="button save-settings"><?php echo _t('button_save'); ?></a>
					<a onclick="$('#op').val('close'); $('#form').submit();" class="button cancel-settings"><?php echo _t('button_close'); ?></a>
					<a href="<?php echo $cancel; ?>" class="button cancel-settings"><?php echo _t('button_cancel'); ?></a>
				</div>
			</div>
			<div class="content">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" name="op" id="op" />

					<div style="margin: 20px 0 0 30px;">
						<label><?php echo _t('entry_store'); ?></label>
						<select name="store_id" id="StoreSelector">
							<?php foreach ($stores as $store_id => $store_name) { ?>
								<option value="<?php echo $store_id; ?>"<?php if ($store_id == $selected_store_id) echo ' selected="selected"'; ?>><?php echo $store_name; ?></option>
							<?php } ?>
						</select>
					</div>

					<ul id="ModuleTabItems" class="vtabs">
						<li><span class="module-add"><?php echo _t('button_add_module'); ?>&nbsp;<img id="ModuleAdder" class="add-element" src="view/kulercore/images/icons/icon-add.png" /></span></li>
					</ul>
					<div id="ModuleContainer"></div>
				</form>
			</div>
		</div>
	</div>

	<script id="ModuleTabTemplate" type="text/x-handlebars-template">
		<li id="ModuleTabItem_{{tab.row}}" class="ModuleTabItem">
			<a href="#Module_{{tab.row}}" id="ModuleTab_{{tab.row}}" data-tab="{{tab.row}}" data-row="{{tab.row}}">
				<b id="ModuleTabTitle_{{tab.row}}">{{tab.main_title}}</b>
				<img class="remove-element ModuleRemover" src="view/kulercore/images/icons/icon-delete.png" data-module="{{tab.row}}" />
			</a>
		</li>
	</script>

	<script id="ModuleTemplate" type="text/x-handlebars-template">
		<div id="Module_{{module.row}}" class="vtabs-content">
			<table class="form">
				<tr>
					<td><?php echo _t('entry_status'); ?></td>
					<td>
						<div class="kuler-switch-btn">
							<input type="hidden" name="modules[{{module.row}}][status]" value="0" />
							<input type="checkbox" name="modules[{{module.row}}][status]" value="1"{{#compare module.status 1}} checked="checked"{{/compare}} />
							<span class="kuler-switch-btn-holder"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_show_title'); ?></td>
					<td>
						<div class="kuler-switch-btn">
							<input type="hidden" name="modules[{{module.row}}][show_title]" value="0" />
							<input type="checkbox" name="modules[{{module.row}}][show_title]" value="1"{{#compare module.show_title 1}} checked="checked"{{/compare}} />
							<span class="kuler-switch-btn-holder"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_title'); ?></td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<p>
								<input type="text" name="modules[{{module.row}}][title][<?php echo $language['language_id']; ?>]"<?php if ($language['language_id'] == $config_language_id) { ?> class="ModuleTitle" data-module-row="{{module.row}}" <?php } ?>value="{{languageText module.title <?php echo $language['language_id']; ?>}}" size="50" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
							</p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_layout'); ?></td>
					<td>
						<select name="modules[{{module.row}}][layout_id]" style="width: 150px;">
							<?php foreach ($layouts as $layout) { ?>
								<option value="<?php echo $layout['layout_id']; ?>"{{#compare module.layout_id <?php echo $layout['layout_id']; ?>}} selected="selected"{{/compare}}><?php echo $layout['name']; ?></option>
                        <?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_position'); ?></td>
					<td>
						<select name="modules[{{module.row}}][position]" style="width: 150px;">
							<?php foreach ($positions as $position_value => $position_name) { ?>
								<option value="<?php echo $position_value; ?>"{{#compare module.position '<?php echo $position_value; ?>'}} selected="selected"{{/compare}}><?php echo $position_name ?></option>
                        <?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_sort_order'); ?></td>
					<td>
						<input type="text" name="modules[{{module.row}}][sort_order]" value="{{module.sort_order}}" size="5" />
					</td>
				</tr>
			</table>

			<h2><?php echo _t('text_display_setting'); ?></h2>
			<table class="form">
				<tr>
					<td><?php echo _t('entry_exclude_category'); ?></td>
					<td>
						<div class="scrollbox">
							<?php $category_index = 0; ?>
							<?php foreach ($category_options as $category_id => $category_name) { ?>
								<div class="<?php echo $category_index % 2 ? 'odd' : 'even'; ?>">
									<input type="checkbox" name="modules[{{module.row}}][exclude_categories][]" value="<?php echo $category_id; ?>"{{#inArray <?php echo $category_id; ?> module.exclude_categories}} checked="checked"{{/inArray}} />
									<?php echo $category_name; ?>
								</div>
								<?php $category_index++; ?>
							<?php } ?>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_limit'); ?></td>
					<td>
						<input type="text" name="modules[{{module.row}}][limit]" value="{{module.limit}}" size="5" />
					</td>
				</tr>
				<tr>
					<td><?php echo _t('entry_avatar_image_size'); ?></td>
					<td>
						<input type="text" name="modules[{{module.row}}][avatar_size]" value="{{module.avatar_size}}" size="5" />
						{{#isTrue 'ErrorsAvatarSize' module.row}}
						<p class="error">{{prop 'ErrorsAvatarSize' module.row}}</p>
						{{/isTrue}}
					</td>
				</tr>
			</table>
		</div>
	</script>

	<script type="text/javascript">
	var g_token = '<?php echo $token; ?>',
		g_storeId = <?php echo $selected_store_id; ?>,
		g_config_language_id = <?php echo $config_language_id; ?>,
		g_texts = <?php echo json_encode($__); ?>,

		g_defaultModule = <?php echo json_encode($default_module); ?>,
		g_modules = <?php echo json_encode($modules); ?>,
		Languages = JSON.parse('<?php echo json_encode($languages); ?>'),
		ErrorsAvatarSize = JSON.parse('<?php echo json_encode($error_avatar_size); ?>');

	function _t(text) {
		return text in g_texts ? g_texts[text] : text;
	}

	var TemplateEngine = (function (Handlebars, configLanguageId) {
		return {
			init: function () {
				Handlebars.registerHelper('compare', function(lvalue, rvalue, options) {

					if (arguments.length < 3)
						throw new Error("Handlerbars Helper 'compare' needs 2 parameters");

					operator = options.hash.operator || "==";

					var operators = {
						'==':       function(l,r) { return l == r; },
						'===':      function(l,r) { return l === r; },
						'!=':       function(l,r) { return l != r; },
						'<':        function(l,r) { return l < r; },
						'>':        function(l,r) { return l > r; },
						'<=':       function(l,r) { return l <= r; },
						'>=':       function(l,r) { return l >= r; },
						'typeof':   function(l,r) { return typeof l == r; }
					}

					if (!operators[operator])
						throw new Error("Handlerbars Helper 'compare' doesn't know the operator "+operator);

					var result = operators[operator](lvalue,rvalue);

					if( result ) {
						return options.fn(this);
					} else {
						return options.inverse(this);
					}
				});

				Handlebars.registerHelper('languageText', function (texts, index) {
					if (typeof texts == 'object') {
						return index in texts ? new Handlebars.SafeString(texts[index]) : configLanguageId in texts ? new Handlebars.SafeString(texts[configLanguageId]) : '';
					} else {
						return '';
					}
				});

				Handlebars.registerHelper('isTrue', function (obj, prop, options) {
					if (prop in window[obj] && window[obj][prop]) {
						return options.fn(this);
					} else {
						return '';
					}
				});

				Handlebars.registerHelper('prop', function (obj, prop) {
					return window[obj][prop];
				});

				Handlebars.registerHelper('inArray', function (value, array, options) {
					var isIn = $.isArray(array) && array.indexOf(value.toString()) !== -1 ? true : false;

					return isIn ? options.fn(this) : '';
				});
			}
		}
	})(Handlebars, g_config_language_id);

	var Tab = (function () {
		$.fn.tabs = function (options) {
			var defaults = {
					prefix: '',
					key: ''
				},
				selector = this.selector,
				activeKey;

			options = $.extend(defaults, options);

			activeKey = options.prefix + options.key;

			var matches = document.cookie.match(new RegExp(activeKey + '=([^;]+);')), activeValue = 0;

			if (matches) {
				activeValue = matches[1];
			}

			$('body').on('click', selector, function (evt) {
				evt.preventDefault();

				$(selector)
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

			if (!$('#' + options.key + '_' + activeValue).trigger('click').length) {
				$(selector).eq(0).trigger('click');
			}
		};

		return {
			init: function (selector, tabPrefix, tabKey) {
				var context = $.isPlainObject(selector) ? selector.context : document,
					selector = $.isPlainObject(selector) ? selector.selector : selector;

				$(selector, context).tabs({
					prefix: tabPrefix,
					key: tabKey
				});
			}
		};
	})();

	var StoreSelector = (function () {
		return {
			init: function () {
				var saveUrl = '<?php echo $action; ?>';
				saveUrl = saveUrl.replace(new RegExp('&amp;', 'g'), '&');

				$('#StoreSelector').on('change', function () {
					window.location = saveUrl + '&store_id=' + $(this).val();
				});
			}
		};
	})();

	var Autocomplete = (function () {
		var that;

		return {
			init: function (selector, context) {
				that = this;
				var $selector = $(selector, context || document);

				$selector.each(function () {
					var $items = $($(this).data('items')),
						$list = $($(this).data('list'));

					that.renderItems($list, $items, that.fetchItems($items[0]));
				});

				$selector.autocomplete({
					delay: 200,
					source: function(request, response) {
						$.ajax({
							url: this.element.data('url'),
							data: {
								filter_name: encodeURIComponent(request.term)
							},
							dataType: 'json',
							success: function(json) {
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: 'category_id' in item ? item.category_id : item.product_id
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						var $this = $(this),
							$list = $($this.data('list')),
							$items = $($this.data('items')),
							items = that.fetchItems($items[0]);

						this.value = '';

						for (var i = 0; i < items.length; i++) {
							if (ui.item.value == items[i].id) {
								return false;
							}
						}

						items.push({
							id: ui.item.value,
							title: ui.item.label
						});
						that.renderItems($list, $items, items);

						$items.val(JSON.stringify(items));

						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			},
			fetchItems: function(el) {
				return el.value ? JSON.parse(el.value) : [];
			},
			renderItems: function($list, $items, items) {
				var itemPrefix = $list.data('item-prefix'),
					i = 0,
					itemHtml = '';

				$list.empty();

				for (; i < items.length; i++) {
					itemHtml += '<div id="'+ itemPrefix + items[i].id + '" class="'+ (i % 2 ? 'odd' :'even') +'">'+ items[i].title +'<img src="view/kulercore/images/icons/icon-delete.png" data-item-id="'+ items[i].id +'"/></div>';
				}

				$list.append(itemHtml);

				$list.find('img').on('click', function () {
					var $remover = $(this),
						oldItems = that.fetchItems($items[0]),
						newItems = [];

					for (var i = 0; i < oldItems.length; i++) {
						if ($remover.data('itemId') != oldItems[i].id) {
							newItems.push(oldItems[i]);
						}
					}

					that.renderItems($list, $items, newItems);

					$items.val(JSON.stringify(newItems));
				});
			}
		};
	})();

	var ModuleTab = (function (Tab) {
		var that;
		return {
			row: -1,
			init: function () {
				that = this;

				that.$tabList = $('#ModuleTabItems');

				that.initTemplate();

				for (var i in g_modules) {
					that.addTab(g_modules[i]);

					g_modules[i].row = that.row;
					Module.addModule(g_modules[i]);
				}

				that.initTab();

				$('#ModuleAdder').on('click', function (evt) {
					evt.preventDefault();

					var title = _t('text_module_title').toUpperCase() + ' ' + (that.row + 2),
						titles = {};

					for (var i in Languages) {
						titles[Languages[i].language_id] = title;
					}

					that.addTab({
						title: titles,
						main_title: title
					});

					var module = g_defaultModule;
					module.row = that.row;

					module.title = titles;

					Module.addModule(module);

					that.$tabList.find('.ModuleTabItem a').trigger('click');
				});
			},
			initTab: function () {
				Tab.init('#ModuleTabItems a', 'kpl_', 'ModuleTab');
			},
			initRemoverButton: function (context) {
				$('.ModuleRemover', context || document).on('click', function (evt) {
					evt.preventDefault();
					var moduleRow = $(this).data('module');

					$('#ModuleTabItem_' + moduleRow + ', #Module_' + moduleRow).remove();
					that.$tabList.find('a:first').trigger('click');
				});
			},
			addTab: function (tab) {
				that.row++;
				tab.row = that.row;

				var tabHtml = that.tabTemplate({
					tab: tab
				});

				tabHtml = $(tabHtml);
				that.$tabList.children('li:last').before(tabHtml);

				that.initRemoverButton(tabHtml[0]);
			},
			initTemplate: function () {
				that.tabTemplate = Handlebars.compile($('#ModuleTabTemplate').html())
			}
		};
	})(Tab);

	var Module = (function (Autocomplete) {
		var that;

		return {
			init: function () {
				that = this;

				that.initTemplate();

				$('#ModuleContainer').on('keyup', '.ModuleTitle', function () {
					$('#ModuleTabTitle_' + $(this).data('moduleRow')).html($(this).val());
				});

			},
			initTemplate: function () {
				that.template = Handlebars.compile($('#ModuleTemplate').html());
			},
			addModule: function (module) {
				var html = that.template({module: module});

				$('#ModuleContainer').append(html);

				var $module = $('#Module_' + module.row);

				$module.find('.ProductDisplay').on('change', function () {
					if (this.value == 'all') {
						$module.find('.CustomRelatedRow').hide();
					} else {
						$module.find('.CustomRelatedRow').show();
					}
				})
					.trigger('change');

				Autocomplete.init($module.find('.Autocomplete'));
			}
		};
	})(Autocomplete);

	$(function () {
		TemplateEngine.init();

		Module.init();
		ModuleTab.init();

		StoreSelector.init();
	});
	</script>
<?php echo $footer; ?>