/*! Magnific Popup - v0.8.1 - 2013-05-02
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2013 Dmitry Semenov; */
;(function($) {

/*>>core*/
/**
 * 
 * Magnific Popup Core JS file
 * 
 */


/**
 * Private static constants
 */
var CLOSE_EVENT = 'Close',
	BEFORE_APPEND_EVENT = 'BeforeAppend',
	MARKUP_PARSE_EVENT = 'MarkupParse',
	OPEN_EVENT = 'Open',
	CHANGE_EVENT = 'Change',
	NS = 'lightbox',
	EVENT_NS = '.' + NS,
	READY_CLASS = 'lightbox-ready',
	REMOVING_CLASS = 'lightbox-removing',
	PREVENT_CLOSE_CLASS = 'lightbox-prevent-close';


/**
 * Private vars 
 */
var lightbox, // As we have only one instance of MagnificPopup object, we define it locally to not to use 'this'
	MagnificPopup = function(){},
	_prevStatus,
	_window = $(window),
	_body,
	_document,
	_prevContentType,
	_wrapClasses,
	_currPopupType;


/**
 * Private functions
 */
var _lightboxOn = function(name, f) {
		lightbox.ev.on(NS + name + EVENT_NS, f);
	},
	_getEl = function(className, appendTo, html, raw) {
		var el = document.createElement('div');
		el.className = 'lightbox-'+className;
		if(html) {
			el.innerHTML = html;
		}
		if(!raw) {
			el = $(el);
			if(appendTo) {
				el.appendTo(appendTo);
			}
		} else if(appendTo) {
			appendTo.appendChild(el);
		}
		return el;
	},
	_lightboxTrigger = function(e, data) {
		lightbox.ev.triggerHandler(NS + e, data);

		if(lightbox.st.callbacks) {
			// converts "lightboxEventName" to "eventName" callback and triggers it if it's present
			e = e.charAt(0).toLowerCase() + e.slice(1);
			if(lightbox.st.callbacks[e]) {
				lightbox.st.callbacks[e].apply(lightbox, $.isArray(data) ? data : [data]);
			}
		}
	},
	_setFocus = function() {
		(lightbox.st.focus ? lightbox.content.find(lightbox.st.focus).eq(0) : lightbox.wrap).focus();
	},
	_getCloseBtn = function(type) {
		if(type !== _currPopupType || !lightbox.currTemplate.closeBtn) {
			lightbox.currTemplate.closeBtn = $( lightbox.st.closeMarkup.replace('%title%', lightbox.st.tClose ) );
			_currPopupType = type;
		}
		return lightbox.currTemplate.closeBtn;
	};



/**
 * Public functions
 */
MagnificPopup.prototype = {

	constructor: MagnificPopup,

	/**
	 * Initializes Magnific Popup plugin. 
	 * This function is triggered only once when $.fn.magnificPopup or $.magnificPopup is executed
	 */
	init: function() {
		var appVersion = navigator.appVersion;
		lightbox.isIE7 = appVersion.indexOf("MSIE 7.") !== -1; 
		lightbox.isAndroid = (/android/gi).test(appVersion);
		lightbox.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);

		// We disable fixed positioned lightbox on devices that don't handle it nicely.
		// If you know a better way of detecting this - let me know.
		lightbox.probablyMobile = (lightbox.isAndroid || lightbox.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent) );
		_body = $(document.body);
		_document = $(document);

		lightbox.popupsCache = {};
	},

	/**
	 * Opens popup
	 * @param  data [description]
	 */
	open: function(data) {

		if(lightbox.isOpen) return;

		var i;

		lightbox.types = []; 
		_wrapClasses = '';
		
		lightbox.ev = data.el || _document;

		if(data.isObj) {
			lightbox.index = data.index || 0;
		} else {
			lightbox.index = 0;
			var items = data.items,
				item;
			for(i = 0; i < items.length; i++) {
				item = items[i];
				if(item.parsed) {
					item = item.el[0];
				}
				if(item === data.el[0]) {
					lightbox.index = i;
					break;
				}
			}
		}


		if(data.key) {
			if(!lightbox.popupsCache[data.key]) {
				lightbox.popupsCache[data.key] = {};
			}
			lightbox.currTemplate = lightbox.popupsCache[data.key];
		} else {
			lightbox.currTemplate = {};
		}



		lightbox.st = $.extend(true, {}, $.magnificPopup.defaults, data ); 
		lightbox.fixedContentPos = lightbox.st.fixedContentPos === 'auto' ? !lightbox.probablyMobile : lightbox.st.fixedContentPos;
		
		lightbox.items = data.items.length ? data.items : [data.items];

		// Building markup
		// main containers are created only once
		if(!lightbox.bgOverlay) {

			// Dark overlay
			lightbox.bgOverlay = _getEl('bg').on('click'+EVENT_NS, function() {
				lightbox.close();
			});

			lightbox.wrap = _getEl('wrap').attr('tabindex', -1).on('click'+EVENT_NS, function(e) {

				var target = e.target;
				if($(target).hasClass(PREVENT_CLOSE_CLASS)) {
					return;
				}

				if(lightbox.st.closeOnContentClick) {
					lightbox.close();
				} else {
					// close popup if click is not on a content, on close button, or content does not exist
					if( !lightbox.content || 
						$(target).hasClass('lightbox-close') ||
						(lightbox.preloader && e.target === lightbox.preloader[0]) || 
						(target !== lightbox.content[0] && !$.contains(lightbox.content[0], target)) ) {
						lightbox.close();
					}
				}
			});

			lightbox.container = _getEl('container', lightbox.wrap);
		}
		if(lightbox.st.preloader) {
			lightbox.preloader = _getEl('preloader', lightbox.container, lightbox.st.tLoading);
		}
		lightbox.contentContainer = _getEl('content', lightbox.container);



		// Initializing modules
		var modules = $.magnificPopup.modules;
		for(i = 0; i < modules.length; i++) {
			var n = modules[i];
			n = n.charAt(0).toUpperCase() + n.slice(1);
			lightbox['init'+n].call(lightbox);
		}
		_lightboxTrigger('BeforeOpen');


		// Close button
		if(!lightbox.st.closeBtnInside) {
			lightbox.wrap.append( _getCloseBtn() );
		} else {
			_lightboxOn(MARKUP_PARSE_EVENT, function(e, template, values, item) {
				values.close_replaceWith = _getCloseBtn(item.type);
			});
			_wrapClasses += ' lightbox-close-btn-in';
		}

		if(lightbox.st.alignTop) {
			_wrapClasses += ' lightbox-align-top';
		}

	

		if(lightbox.fixedContentPos) {
			lightbox.wrap.css({
				overflow: lightbox.st.overflowY,
				overflowX: 'hidden',
				overflowY: lightbox.st.overflowY
			});
		} else {
			lightbox.wrap.css({ 
				top: _window.scrollTop(),
				position: 'absolute'
			});
		}
		if( lightbox.st.fixedBgPos === false || (lightbox.st.fixedBgPos === 'auto' && !lightbox.fixedContentPos) ) {
			lightbox.bgOverlay.css({
				height: _document.height(),
				position: 'absolute'
			});
		}

		

		// Close on ESC key
		_document.on('keyup' + EVENT_NS, function(e) {
			if(e.keyCode === 27) {
				lightbox.close();
			}
		});

		_window.on('resize' + EVENT_NS, function() {
			lightbox.updateSize();
		});


		if(!lightbox.st.closeOnContentClick) {
			_wrapClasses += ' lightbox-auto-cursor';
		}
		
		if(_wrapClasses)
			lightbox.wrap.addClass(_wrapClasses);


		// this triggers recalculation of layout, so we get it once to not to trigger twice
		var windowHeight = lightbox.wH = _window.height();

		
		var bodyStyles = {};

		if( lightbox.fixedContentPos ) {
			var s = lightbox._getScrollbarSize();
			if(s) {
				bodyStyles.paddingRight = s;
			}
		}

		if(lightbox.fixedContentPos) {
			if(!lightbox.isIE7) {
				bodyStyles.overflow = 'hidden';
			} else {
				// ie7 double-scroll bug
				$('body, html').css('overflow', 'hidden');
			}
		}

		
		
		var classesToadd = lightbox.st.mainClass;
		if(lightbox.isIE7) {
			classesToadd += ' lightbox-ie7';
		}
		if(classesToadd) {
			lightbox._addClassTolightbox( classesToadd );
		}

		// add content
		lightbox.updateItemHTML();

		// remove scrollbar, add padding e.t.c
		


		_body.css(bodyStyles);
		
		// add everything to DOM
		lightbox.bgOverlay.add(lightbox.wrap).prependTo( document.body );



		// Save last focused element
		lightbox._lastFocusedEl = document.activeElement;
		
		// Wait for next cycle to allow CSS transition
		setTimeout(function() {
			
			if(lightbox.content) {
				lightbox._addClassTolightbox(READY_CLASS);
				_setFocus();
			} else {
				// if content is not defined (not loaded e.t.c) we add class only for BG
				lightbox.bgOverlay.addClass(READY_CLASS);
			}
			
			// Trap the focus in popup
			_document.on('focusin' + EVENT_NS, function (e) {
				if( e.target !== lightbox.wrap[0] && !$.contains(lightbox.wrap[0], e.target) ) {
					_setFocus();
					return false;
				}
			});

		}, 16);

		lightbox.isOpen = true;
		lightbox.updateSize(windowHeight);
		_lightboxTrigger(OPEN_EVENT);
	},

	/**
	 * Closes the popup
	 */
	close: function() {
		if(!lightbox.isOpen) return;

		lightbox.isOpen = false;
		// for CSS3 animation
		if(lightbox.st.removalDelay)  {
			lightbox._addClassTolightbox(REMOVING_CLASS);
			setTimeout(function() {
				lightbox._close();
			}, lightbox.st.removalDelay);
		} else {
			lightbox._close();
		}
	},

	/**
	 * Helper for close() function
	 */
	_close: function() {
		_lightboxTrigger(CLOSE_EVENT);

		var classesToRemove = REMOVING_CLASS + ' ' + READY_CLASS + ' ';

		lightbox.bgOverlay.detach();
		lightbox.wrap.detach();
		lightbox.container.empty();

		if(lightbox.st.mainClass) {
			classesToRemove += lightbox.st.mainClass + ' ';
		}

		lightbox._removeClassFromlightbox(classesToRemove);

		if(lightbox.fixedContentPos) {
			var bodyStyles = {paddingRight: 0};
			if(lightbox.isIE7) {
				$('body, html').css('overflow', 'auto');
			} else {
				bodyStyles.overflow = 'visible';
			}
			_body.css(bodyStyles);
		}
		
		_document.off('keyup' + EVENT_NS + ' focusin' + EVENT_NS);
		lightbox.ev.off(EVENT_NS);

		// clean up DOM elements that aren't removed
		lightbox.wrap.attr('class', 'lightbox-wrap').removeAttr('style');
		lightbox.bgOverlay.attr('class', 'lightbox-bg');
		lightbox.container.attr('class', 'lightbox-container');

		// remove close button from target element
		if(!lightbox.st.closeBtnInside || lightbox.currTemplate[lightbox.currItem.type] === true ) {
			if(lightbox.currTemplate.closeBtn)
				lightbox.currTemplate.closeBtn.detach();
		}


		if(lightbox._lastFocusedEl) {
			$(lightbox._lastFocusedEl).focus(); // put tab focus back
		}	
		lightbox.currTemplate = null;
		lightbox.prevHeight = 0;
	},
	
	updateSize: function(winHeight) {

		if(lightbox.isIOS) {
			// fixes iOS nav bars https://github.com/dimsemenov/Magnific-Popup/issues/2
			var zoomLevel = document.documentElement.clientWidth / window.innerWidth;
			var height = window.innerHeight * zoomLevel;
			lightbox.wrap.css('height', height);
			lightbox.wH = height;
		} else {
			lightbox.wH = winHeight || _window.height();
		}

		_lightboxTrigger('Resize');

	},


	/**
	 * Set content of popup based on current index
	 */
	updateItemHTML: function() {
		var item = lightbox.items[lightbox.index];

		if(!item.parsed) {
			item = lightbox.parseEl( lightbox.index );
		}
		
		lightbox.currItem = item;

		var type = item.type;		
		if(!lightbox.currTemplate[type]) {
			var markup = lightbox.st[type] ? lightbox.st[type].markup : false;
			if(markup) {
				_lightboxTrigger('FirstMarkupParse', markup);
				lightbox.currTemplate[type] = $(markup);
			} else {
				// if there is no markup found we just define that template is parsed
				lightbox.currTemplate[type] = true;
			}
		}

		if(_prevContentType && _prevContentType !== item.type) {
			lightbox.container.removeClass('lightbox-'+_prevContentType+'-holder');
		}

		var newContent = lightbox['get' + type.charAt(0).toUpperCase() + type.slice(1)](item, lightbox.currTemplate[type]);
		lightbox.appendContent(newContent, type);

		item.preloaded = true;

		_lightboxTrigger(CHANGE_EVENT, item);
		_prevContentType = item.type;
	},


	/**
	 * Set HTML content of popup
	 */
	appendContent: function(newContent, type) {
		lightbox.content = newContent;
		
		if(newContent) {
			if(lightbox.st.closeBtnInside && lightbox.currTemplate[type] === true) {
				// if there is no markup, we just append close button element inside
				if(!lightbox.content.find('.lightbox-close').length) {
					lightbox.content.append(_getCloseBtn());
				}
			} else {
				lightbox.content = newContent;
			}
		} else {
			lightbox.content = '';
		}

		_lightboxTrigger(BEFORE_APPEND_EVENT);
		lightbox.container.addClass('lightbox-'+type+'-holder');

		lightbox.contentContainer.html(lightbox.content);

		if (lightbox.arrowLeft) {
			lightbox.content.append(lightbox.arrowLeft.add(lightbox.arrowRight));

			var supportsFastClick = Boolean($.fn.lightboxFastClick),
				eName = supportsFastClick ? 'lightboxFastClick' : 'click';

			lightbox.content.find('.lightbox-arrow-left')[eName](function() {
				lightbox.prev();
			});			
			lightbox.content.find('.lightbox-arrow-right')[eName](function() {
				lightbox.next();
			});
		}

	},



	
	/**
	 * Creates Magnific Popup data object based on given data
	 * @param  {int} index Index of item to parse
	 */
	parseEl: function(index) {
		var item = lightbox.items[index],
			type = item.type;
		

		if(item.tagName) {
			item = { el: $(item) };
		} else {
			item = { data: item, src: item.src };
		}

		if(item.el) {
			var types = lightbox.types;

			// check for 'lightbox-TYPE' class
			for(var i = 0; i < types.length; i++) {
				if( item.el.hasClass('lightbox-'+types[i]) ) {
					type = types[i];
					break;
				}
			}

			item.src = item.el.attr('data-lightbox-src');
			if(!item.src) {
				item.src = item.el.attr('href');
			}
		}

		item.type = type || lightbox.st.type;
		item.index = index;
		item.parsed = true;
		lightbox.items[index] = item;
		_lightboxTrigger('ElementParse', item);

		return lightbox.items[index];
	},


	/**
	 * Initializes single popup or a group of popups
	 */
	addGroup: function(el, options) {
		var eHandler = function(e) {

			var midClick = options.midClick !== undefined ? options.midClick : $.magnificPopup.defaults.midClick;
			if( midClick || e.which !== 2 ) {
				var disableOn = options.disableOn !== undefined ? options.disableOn : $.magnificPopup.defaults.disableOn;

				if(disableOn) {
					if($.isFunction(disableOn)) {
						if( !disableOn.call(lightbox) ) {
							return true;
						}
					} else { // else it's number
						if( $(window).width() < disableOn ) {
							return true;
						}
					}
				}
					
				e.preventDefault();
				options.el = $(this);
				if(options.delegate) {
					options.items = el.find(options.delegate);
				}
				lightbox.open(options);
			}
			
		};

		if(!options) {
			options = {};
		} 

		var eName = 'click.magnificPopup';
		if(options.items) {
			options.isObj = true;
			el.off(eName).on(eName, eHandler);
		} else {
			options.isObj = false;
			if(options.delegate) {
				el.off(eName).on(eName, options.delegate , eHandler);
			} else {
				options.items = el;
				el.off(eName).on(eName, eHandler);
			}
		}
	},


	/**
	 * Updates text on preloader
	 */
	updateStatus: function(status, text) {

		if(lightbox.preloader) {
			if(_prevStatus !== status) {
				lightbox.container.removeClass('lightbox-s-'+_prevStatus);
			}

			if(!text && status === 'loading') {
				text = lightbox.st.tLoading;
			}

			var data = {
				status: status,
				text: text
			};
			// allows to modify status
			_lightboxTrigger('UpdateStatus', data);

			status = data.status;
			text = data.text;

			lightbox.preloader.html(text);

			lightbox.preloader.find('a').click(function(e) {
				e.stopImmediatePropagation();
			});

			lightbox.container.addClass('lightbox-s-'+status);
			_prevStatus = status;
		}
	},


	
	





	/*
		"Private" helpers that aren't private at all
	 */
	_addClassTolightbox: function(cName) {
		lightbox.bgOverlay.addClass(cName);
		lightbox.wrap.addClass(cName);
	},
	_removeClassFromlightbox: function(cName) {
		this.bgOverlay.removeClass(cName);
		lightbox.wrap.removeClass(cName);
	},
	_hasScrollBar: function(winHeight) {
		if(document.body.clientHeight > (winHeight || _window.height()) ) {
			return true;	
		}
		return false;
	},

	_parseMarkup: function(template, values, item) {
		var arr;
		if(item.data) {
			values = $.extend(item.data, values);
		}
		_lightboxTrigger(MARKUP_PARSE_EVENT, [template, values, item] );

		$.each(values, function(key, value) {
			if(value === undefined || value === false) {
				return true;
			}
			arr = key.split('_');
			if(arr.length > 1) {
				var el = template.find(EVENT_NS + '-'+arr[0]);

				if(el.length > 0) {
					var attr = arr[1];
					if(attr === 'replaceWith') {
						if(el[0] !== value[0]) {
							el.replaceWith(value);
						}
					} else if(attr === 'img') {
						if(el.is('img')) {
							el.attr('src', value);
						} else {
							el.replaceWith( '<img src="'+value+'" class="' + el.attr('class') + '" />' );
						}
					} else {
						el.attr(arr[1], value);
					}
				}

			} else {
				template.find(EVENT_NS + '-'+key).html(value);
			}
		});
	},

	_getScrollbarSize: function() {
		// thx David
		if(lightbox.scrollbarSize === undefined) {
			var scrollDiv = document.createElement("div");
			scrollDiv.id = "lightbox-sbm";
			scrollDiv.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
			document.body.appendChild(scrollDiv);
			lightbox.scrollbarSize = scrollDiv.offsetWidth - scrollDiv.clientWidth;
			document.body.removeChild(scrollDiv);
		}
		return lightbox.scrollbarSize;
	}

}; /* MagnificPopup core prototype end */




/**
 * Public static functions
 */
$.magnificPopup = {
	instance: null,
	proto: MagnificPopup.prototype,
	modules: [],

	open: function(options, index) {
		if(!$.magnificPopup.instance) {
			lightbox = new MagnificPopup();
			lightbox.init();
			$.magnificPopup.instance = lightbox;
		}	

		if(!options) {
			options = {};
		}
		
		options.isObj = true;
		options.index = index === undefined ? 0 : index;
		return this.instance.open(options);
	},

	close: function() {
		return $.magnificPopup.instance.close();
	},

	registerModule: function(name, module) {
		if(module.options) {
			$.magnificPopup.defaults[name] = module.options;
		}
		$.extend(this.proto, module.proto);			
		this.modules.push(name);
	},

	defaults: {   

		// Info about options is docs:
		// http://dimsemenov.com/plugins/magnific-popup/documentation.html#options
		
		disableOn: 0,	

		key: null,

		midClick: false,

		mainClass: '',

		preloader: true,

		focus: '', // CSS selector of input to focus after popup is opened
		
		closeOnContentClick: false,

		closeBtnInside: true, 

		alignTop: false,
	
		removalDelay: 0,
		
		fixedContentPos: 'auto', 
	
		fixedBgPos: 'auto',

		overflowY: 'auto',

		closeMarkup: '<button title="%title%" type="button" class="lightbox-close">&times;</button>',

		tClose: 'Close (Esc)',

		tLoading: 'Loading...'

	}
};



$.fn.magnificPopup = function(options) {
	// Initialize Magnific Popup only when called at least once
	if(!$.magnificPopup.instance) {
		lightbox = new MagnificPopup();
		lightbox.init();
		$.magnificPopup.instance = lightbox;
	}

	lightbox.addGroup($(this), options);
	return $(this);
};


//Quick benchmark
/*
var start = performance.now(),
	i,
	rounds = 1000;

for(i = 0; i < rounds; i++) {

}
console.log('Test #1:', performance.now() - start);

start = performance.now();
for(i = 0; i < rounds; i++) {

}
console.log('Test #2:', performance.now() - start);
*/

/*>>core*/

/*>>inline*/

var INLINE_NS = 'inline',
	_hasPlaceholder;

$.magnificPopup.registerModule(INLINE_NS, {
	options: {
		hiddenClass: NS+'-hide',
		markup: '',
		tNotFound: 'Content not found'
	},
	proto: {

		initInline: function() {
			lightbox.types.push(INLINE_NS);
			_hasPlaceholder = false;

			_lightboxOn(CLOSE_EVENT+'.'+INLINE_NS, function() {
				var item = lightbox.currItem;
				if(item.type === INLINE_NS) {
					if(_hasPlaceholder) {
						for(var i = 0; i < lightbox.items.length; i++) {
							item = lightbox.items[i];
							if(item && item.inlinePlaceholder){
								item.inlinePlaceholder.after( item.inlineElement.addClass(lightbox.st.inline.hiddenClass) ).detach();
							}
						}
					}
					item.inlinePlaceholder = item.inlineElement = null;
				}
			});
		},

		getInline: function(item, template) {
			lightbox.updateStatus('ready');

			if(item.src) {
				var inlineSt = lightbox.st.inline;
				// items.src can be String-CSS-selector or jQuery element
				if(typeof item.src !== 'string') {
					item.isElement = true;
				}

				if(!item.isElement && !item.inlinePlaceholder) {
					item.inlinePlaceholder = _getEl(inlineSt.hiddenClass);
				}
				
				if(item.isElement) {
					item.inlineElement = item.src;
				} else if(!item.inlineElement) {
					item.inlineElement = $(item.src);
					if(!item.inlineElement.length) {
						lightbox.updateStatus('error', inlineSt.tNotFound);
						item.inlineElement = $('<div>');
					}
				}

				if(item.inlinePlaceholder) {
					_hasPlaceholder = true;
				}

				
				
				item.inlineElement.after(item.inlinePlaceholder).detach().removeClass(inlineSt.hiddenClass);
				return item.inlineElement;
			} else {
				lightbox._parseMarkup(template, {}, item);
				return template;
			}
		}
	}
});

/*>>inline*/

/*>>ajax*/
var AJAX_NS = 'ajax',
	_ajaxCur,
	_removeAjaxCursor = function() {
		if(_ajaxCur) {
			_body.removeClass(_ajaxCur);
		}
	};

$.magnificPopup.registerModule(AJAX_NS, {

	options: {
		settings: null,
		cursor: 'lightbox-ajax-cur',
		tError: '<a href="%url%">The content</a> could not be loaded.'
	},

	proto: {
		initAjax: function() {
			lightbox.types.push(AJAX_NS);
			_ajaxCur = lightbox.st.ajax.cursor;

			_lightboxOn(CLOSE_EVENT+'.'+AJAX_NS, function() {
				_removeAjaxCursor();
				if(lightbox.req) {
					lightbox.req.abort();
				}
			});
		},

		getAjax: function(item) {

			if(_ajaxCur)
				_body.addClass(_ajaxCur);

			lightbox.updateStatus('loading');

			var opts = $.extend({
				url: item.src,
				success: function(data, textStatus, jqXHR) {

					_lightboxTrigger('ParseAjax', jqXHR);

					lightbox.appendContent( $(jqXHR.responseText), AJAX_NS );

					item.finished = true;

					_removeAjaxCursor();

					_setFocus();

					setTimeout(function() {
						lightbox.wrap.addClass(READY_CLASS);
					}, 16);

					lightbox.updateStatus('ready');

				},
				error: function() {
					_removeAjaxCursor();
					item.finished = item.loadError = true;
					lightbox.updateStatus('error', lightbox.st.ajax.tError.replace('%url%', item.src));
				}
			}, lightbox.st.ajax.settings);

			lightbox.req = $.ajax(opts);

			return '';
		}
	}
});





	

/*>>ajax*/

/*>>image*/
var _imgInterval,
	_getTitle = function(item) {
		if(item.data && item.data.title !== undefined) 
			return item.data.title;

		var src = lightbox.st.image.titleSrc;

		if(src) {
			if($.isFunction(src)) {
				return src.call(lightbox, item);
			} else if(item.el) {
				return item.el.attr(src) || '';
			}
		}
		return '';
	};

$.magnificPopup.registerModule('image', {

	options: {
		markup: '<div class="lightbox-figure">'+
					'<div class="lightbox-close"></div>'+
					'<div class="lightbox-img"></div>'+
					'<div class="lightbox-bottom-bar">'+
						'<div class="lightbox-counter"></div>'+
						'<div class="lightbox-title"></div>'+
					'</div>'+
				'</div>',
		cursor: 'lightbox-zoom-out-cur',
		titleSrc: 'title', 
		verticalFit: true,
		tError: '<a href="%url%">The image</a> could not be loaded.'
	},

	proto: {
		initImage: function() {
			var imgSt = lightbox.st.image,
				ns = '.image';

			lightbox.types.push('image');

			_lightboxOn(OPEN_EVENT+ns, function() {
				if(lightbox.currItem.type === 'image' && imgSt.cursor) {
					_body.addClass(imgSt.cursor);
				}
			});

			_lightboxOn(CLOSE_EVENT+ns, function() {
				if(imgSt.cursor) {
					_body.removeClass(imgSt.cursor);
				}
				_window.off('resize' + EVENT_NS);
			});

			_lightboxOn('Resize'+ns, function() {
				lightbox.resizeImage();
			});
		},
		resizeImage: function() {
			var item = lightbox.currItem;
			if(!item.img) return;
			if(lightbox.st.image.verticalFit) {
				item.img.css('max-height', lightbox.wH + 'px');
			}
		},
		_onImageHasSize: function(item) {
			if(item.img) {
				
				item.hasSize = true;

				if(_imgInterval) {
					clearInterval(_imgInterval);
				}
				
				item.isCheckingImgSize = false;

				_lightboxTrigger('ImageHasSize', item);

				if(item.imgHidden) {
					lightbox.content.removeClass('lightbox-loading');
					item.imgHidden = false;
				}

			}
		},

		/**
		 * Function that loops until the image has size to display elements that rely on it asap
		 */
		findImageSize: function(item) {

			var counter = 0,
				img = item.img[0],
				lightboxSetInterval = function(delay) {

					if(_imgInterval) {
						clearInterval(_imgInterval);
					}
					// decelerating interval that checks for size of an image
					_imgInterval = setInterval(function() {
						if(img.naturalWidth > 0) {
							lightbox._onImageHasSize(item);
							return;
						}

						if(counter > 200) {
							clearInterval(_imgInterval);
						}

						counter++;
						if(counter === 3) {
							lightboxSetInterval(10);
						} else if(counter === 40) {
							lightboxSetInterval(50);
						} else if(counter === 100) {
							lightboxSetInterval(500);
						}
					}, delay);
				};

			lightboxSetInterval(1);
		},

		getImage: function(item, template) {

			var guard = 0,

				// image load complete handler
				onLoadComplete = function() {
					if(item) {
						if (item.img[0].complete) {
							item.img.off('.lightboxloader');
							
							if(item === lightbox.currItem){
								lightbox._onImageHasSize(item);

								lightbox.updateStatus('ready');
							}

							item.hasSize = true;
							item.loaded = true;
							
						}
						else {
							// if image complete check fails 200 times (20 sec), we assume that there was an error.
							guard++;
							if(guard < 200) {
								setTimeout(onLoadComplete,100);
							} else {
								onLoadError();
							}
						}
					}
				},

				// image error handler
				onLoadError = function() {
					if(item) {
						item.img.off('.lightboxloader');
						if(item === lightbox.currItem){
							lightbox._onImageHasSize(item);
							lightbox.updateStatus('error', imgSt.tError.replace('%url%', item.src) );
						}

						item.hasSize = true;
						item.loaded = true;
						item.loadError = true;
					}
				},
				imgSt = lightbox.st.image;


			var el = template.find('.lightbox-img');
			if(el.length) {
				var img = new Image();
				img.className = 'lightbox-img';
				item.img = $(img).on('load.lightboxloader', onLoadComplete).on('error.lightboxloader', onLoadError);
				img.src = item.src;

				// without clone() "error" event is not firing when IMG is replaced by new IMG
				// TODO: find a way to avoid such cloning
				if(el.is('img')) {
					item.img = item.img.clone();
				}
			}

			lightbox._parseMarkup(template, {
				title: _getTitle(item),
				img_replaceWith: item.img
			}, item);

			lightbox.resizeImage();

			if(item.hasSize) {
				if(_imgInterval) clearInterval(_imgInterval);

				if(item.loadError) {
					template.addClass('lightbox-loading');
					lightbox.updateStatus('error', imgSt.tError.replace('%url%', item.src) );
				} else {
					template.removeClass('lightbox-loading');
					lightbox.updateStatus('ready');
				}
				return template;
			}

			lightbox.updateStatus('loading');
			item.loading = true;

			if(!item.hasSize) {
				item.imgHidden = true;
				template.addClass('lightbox-loading');
				lightbox.findImageSize(item);
			} 

			return template;
		}
	}
});



/*>>image*/

/*>>iframe*/

var IFRAME_NS = 'iframe',

	// IE black screen bug fix
	toggleIframeInIE = function(show) {
		if(lightbox.isIE7 && lightbox.currItem && lightbox.currItem.type === IFRAME_NS) {
			var el = lightbox.content.find('iframe');
			if(el.length) {
				el.css('display', show ? 'block' : 'none');
			}
		}
	};

$.magnificPopup.registerModule(IFRAME_NS, {

	options: {
		markup: '<div class="lightbox-iframe-scaler">'+
					'<div class="lightbox-close"></div>'+
					'<iframe class="lightbox-iframe" frameborder="0" allowfullscreen></iframe>'+
				'</div>',

		srcAction: 'iframe_src',

		// we don't care and support only one default type of URL by default
		patterns: {
			youtube: {
				index: 'youtube.com', 
				id: 'v=', 
				src: '//www.youtube.com/embed/%id%?autoplay=1'
			},
			vimeo: {
				index: 'vimeo.com/',
				id: '/',
				src: '//player.vimeo.com/video/%id%?autoplay=1'
			},
			gmaps: {
				index: '//maps.google.',
				src: '%id%&output=embed'
			}
		}
	},

	proto: {
		initIframe: function() {
			lightbox.types.push(IFRAME_NS);
			toggleIframeInIE(true);
			_lightboxOn(CLOSE_EVENT + '.' + IFRAME_NS, function() {
				toggleIframeInIE();
			});
		},

		getIframe: function(item, template) {
			var embedSrc = item.src;
			var iframeSt = lightbox.st.iframe;
				
			$.each(iframeSt.patterns, function() {
				if(embedSrc.indexOf( this.index ) > -1) {
					if(this.id) {
						if(typeof this.id === 'string') {
							embedSrc = embedSrc.substr(embedSrc.lastIndexOf(this.id)+this.id.length, embedSrc.length);
						} else {
							embedSrc = this.id.call( this, embedSrc );
						}
					}
					embedSrc = this.src.replace('%id%', embedSrc );
					return false; // break;
				}
			});
			
			var dataObj = {};
			if(iframeSt.srcAction) {
				dataObj[iframeSt.srcAction] = embedSrc;
			}
			lightbox._parseMarkup(template, dataObj, item);

			lightbox.updateStatus('ready');

			return template;
		}
	}
});



/*>>iframe*/

/*>>gallery*/
/**
 * Get looped index depending on number of slides
 */
var _getLoopedId = function(index) {
		var numSlides = lightbox.items.length;
		if(index > numSlides - 1) {
			return index - numSlides;
		} else  if(index < 0) {
			return numSlides + index;
		}
		return index;
	},
	_replaceCurrTotal = function(text, curr, total) {
		return text.replace('%curr%', curr + 1).replace('%total%', total);
	};

$.magnificPopup.registerModule('gallery', {

	options: {
		enabled: false,
		arrowMarkup: '<span class="lightbox-arrow lightbox-arrow-%dir%"></span>',
		preload: [0,2],
		navigateByImgClick: true,
		arrows: true,

		tCounter: '%curr% of %total%'
	},

	proto: {
		initGallery: function() {

			var gSt = lightbox.st.gallery,
				ns = '.lightbox-gallery',
				supportsFastClick = Boolean($.fn.lightboxFastClick);

			lightbox.direction = true; // true - next, false - prev
			
			if(!gSt || !gSt.enabled ) return false;

			_wrapClasses += ' lightbox-gallery';

			_lightboxOn(OPEN_EVENT+ns, function() {

				if(gSt.navigateByImgClick) {
					lightbox.wrap.on('click'+ns, '.lightbox-img', function() {
						lightbox.next();
						return false;
					});
				}

				_document.on('keydown'+ns, function(e) {
					if (e.keyCode === 37) {
						lightbox.prev();
					} else if (e.keyCode === 39) {
						lightbox.next();
					}
				});
			});

			_lightboxOn('UpdateStatus'+ns, function(e, data) {
				if(data.text) {
					data.text = _replaceCurrTotal(data.text, lightbox.currItem.index, lightbox.items.length);
				}
			});

			_lightboxOn(MARKUP_PARSE_EVENT+ns, function(e, element, values, item) {
				var l = lightbox.items.length;
				values.counter = l ? _replaceCurrTotal(gSt.tCounter, item.index, l) : '';
			});

			_lightboxOn(CHANGE_EVENT+ns, function() {

				if(lightbox._preloadTimeout) clearTimeout(lightbox._preloadTimeout);

				lightbox._preloadTimeout = setTimeout(function() {
					lightbox.preloadNearbyImages();
					lightbox._preloadTimeout = null;
				}, 16);		

				if(gSt.arrows && !lightbox.arrowLeft) {

					var markup = gSt.arrowMarkup,
						arrowLeft = lightbox.arrowLeft = $( markup.replace('%dir%', 'left') ).addClass(PREVENT_CLOSE_CLASS),			
						arrowRight = lightbox.arrowRight = $( markup.replace('%dir%', 'right') ).addClass(PREVENT_CLOSE_CLASS);

					var eName = supportsFastClick ? 'lightboxFastClick' : 'click';
					arrowLeft[eName](function() {
						lightbox.prev();
					});			
					arrowRight[eName](function() {
						lightbox.next();
					});	

					// Polyfill for :before and :after (adds elements with classes lightbox-a and lightbox-b)
					if(lightbox.isIE7) {
						_getEl('b', arrowLeft[0], false, true);
						_getEl('a', arrowLeft[0], false, true);
						_getEl('b', arrowRight[0], false, true);
						_getEl('a', arrowRight[0], false, true);
					}

					lightbox.container.find('.lightbox-content').append(arrowLeft.add(arrowRight));
				}
			});


			_lightboxOn(CLOSE_EVENT+ns, function() {
				_document.off(ns);
				lightbox.wrap.off('click'+ns);
			
				if(supportsFastClick) {
					lightbox.arrowLeft.add(lightbox.arrowRight).destroylightboxFastClick();
				}
				lightbox.arrowRight = lightbox.arrowLeft = null;
			});

		}, 
		next: function() {
			lightbox.direction = true;
			lightbox.index = _getLoopedId(lightbox.index + 1);
			lightbox.updateItemHTML();
		},
		prev: function() {
			lightbox.direction = false;
			lightbox.index = _getLoopedId(lightbox.index - 1);
			lightbox.updateItemHTML();
		},
		preloadNearbyImages: function() {
			var p = lightbox.st.gallery.preload,
				preloadBefore = Math.min(p[0], lightbox.items.length),
				preloadAfter = Math.min(p[1], lightbox.items.length),
				i;

			for(i = 1; i <= (lightbox.direction ? preloadAfter : preloadBefore); i++) {
				lightbox._preloadItem(lightbox.index+i);
			}
			for(i = 1; i <= (lightbox.direction ? preloadBefore : preloadAfter); i++) {
				lightbox._preloadItem(lightbox.index-i);
			}
		},
		_preloadItem: function(index) {
			index = _getLoopedId(index);

			if(lightbox.items[index].preloaded) {
				return;
			}

			var item = lightbox.items[index];
			if(!item.parsed) {
				item = lightbox.parseEl( index );
			}

			_lightboxTrigger('LazyLoad', item);

			if(item.type === 'image') {
				item.img = $('<img class="lightbox-img" />').on('load.lightboxloader', function() {
					item.hasSize = true;
				}).on('error.lightboxloader', function() {
					item.hasSize = true;
					item.loadError = true;
				}).attr('src', item.src);
			}


			item.preloaded = true;
		}
	}
});

/*
Touch Support that might be implemented some day

addSwipeGesture: function() {
	var startX,
		moved,
		multipleTouches;

		return;

	var namespace = '.lightbox',
		addEventNames = function(pref, down, move, up, cancel) {
			lightbox._tStart = pref + down + namespace;
			lightbox._tMove = pref + move + namespace;
			lightbox._tEnd = pref + up + namespace;
			lightbox._tCancel = pref + cancel + namespace;
		};

	if(window.navigator.msPointerEnabled) {
		addEventNames('MSPointer', 'Down', 'Move', 'Up', 'Cancel');
	} else if('ontouchstart' in window) {
		addEventNames('touch', 'start', 'move', 'end', 'cancel');
	} else {
		return;
	}
	_window.on(lightbox._tStart, function(e) {
		var oE = e.originalEvent;
		multipleTouches = moved = false;
		startX = oE.pageX || oE.changedTouches[0].pageX;
	}).on(lightbox._tMove, function(e) {
		if(e.originalEvent.touches.length > 1) {
			multipleTouches = e.originalEvent.touches.length;
		} else {
			//e.preventDefault();
			moved = true;
		}
	}).on(lightbox._tEnd + ' ' + lightbox._tCancel, function(e) {
		if(moved && !multipleTouches) {
			var oE = e.originalEvent,
				diff = startX - (oE.pageX || oE.changedTouches[0].pageX);

			if(diff > 20) {
				lightbox.next();
			} else if(diff < -20) {
				lightbox.prev();
			}
		}
	});
},
*/


/*>>gallery*/

/*>>retina*/

var RETINA_NS = 'retina';

$.magnificPopup.registerModule(RETINA_NS, {
	options: {
		replaceSrc: function(item) {
			return item.src.replace(/\.\w+$/, function(m) { return '@2x' + m; });
		},
		ratio: 1 // Function or number.  Set to 1 to disable.
	},
	proto: {
		initRetina: function() {
			if(window.devicePixelRatio > 1) {

				var st = lightbox.st.retina,
					ratio = st.ratio;

				ratio = !isNaN(ratio) ? ratio : ratio();

				if(ratio > 1) {
					_lightboxOn('ImageHasSize' + '.' + RETINA_NS, function(e, item) {
						item.img.css({
							'max-width': item.img[0].naturalWidth / ratio,
							'width': '100%'
						});
					});
					_lightboxOn('ElementParse' + '.' + RETINA_NS, function(e, item) {
						item.src = st.replaceSrc(item, ratio);
					});
				}
			}

		}
	}
});

/*>>retina*/

/*>>fastclick*/
/**
 * FastClick event implementation. (removes 300ms delay on touch devices)
 * Based on https://developers.google.com/mobile/articles/fast_buttons
 *
 * You may use it outside the Magnific Popup by calling just:
 *
 * $('.your-el').lightboxFastClick(function() {
 *	 console.log('Clicked!');
 * });
 *
 * To unbind:
 * $('.your-el').destroylightboxFastClick();
 * 
 * 
 * Note that it's a very basic and simple implementation, it blocks ghost click on the same element where it was bound.
 * If you need something more advanced, use plugin by FT Labs https://github.com/ftlabs/fastclick
 * 
 */

(function() {
	var ghostClickDelay = 1000,
		supportsTouch = 'ontouchstart' in window,
		unbindTouchMove = function() {
			_window.off('touchmove'+ns+' touchend'+ns);
		},
		eName = 'lightboxFastClick',
		ns = '.'+eName;


	// As Zepto.js doesn't have an easy way to add custom events (like jQuery), so we implement it in this way
	$.fn.lightboxFastClick = function(callback) {

		return $(this).each(function() {

			var elem = $(this),
				lock;

			if( supportsTouch ) {

				var timeout,
					startX,
					startY,
					pointerMoved,
					point,
					numPointers;

				elem.on('touchstart' + ns, function(e) {
					pointerMoved = false;
					numPointers = 1;

					point = e.originalEvent ? e.originalEvent.touches[0] : e.touches[0];
					startX = point.clientX;
					startY = point.clientY;

					_window.on('touchmove'+ns, function(e) {
						point = e.originalEvent ? e.originalEvent.touches : e.touches;
						numPointers = point.length;
						point = point[0];
						if (Math.abs(point.clientX - startX) > 10 ||
							Math.abs(point.clientY - startY) > 10) {
							pointerMoved = true;
							unbindTouchMove();
						}
					}).on('touchend'+ns, function(e) {
						unbindTouchMove();
						if(pointerMoved || numPointers > 1) {
							return;
						}
						lock = true;
						e.preventDefault();
						clearTimeout(timeout);
						timeout = setTimeout(function() {
							lock = false;
						}, ghostClickDelay);
						callback();
					});
				});

			}

			elem.on('click' + ns, function() {
				if(!lock) {
					callback();
				}
			});
		});
	};

	$.fn.destroylightboxFastClick = function() {
		$(this).off('touchstart' + ns + ' click' + ns);
		if(supportsTouch) _window.off('touchmove'+ns+' touchend'+ns);
	};
})();

/*>>fastclick*/
})(window.jQuery || window.Zepto);