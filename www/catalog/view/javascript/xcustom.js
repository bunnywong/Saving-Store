$(document).ready(function(){$(".xpersonal").append($(".xpersonal tr").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$(".xpersonal").append($(".xpersonal xdiv").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$(".xpersonal1").append($(".xpersonal1 xdiv").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$( ".xaddress").append($(".xaddress tr").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$( ".xaddress1").append($(".xaddress1 tr").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$( ".xaddress2").append($(".xaddress2 tr").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$( ".xaddress").append($(".xaddress xdiv").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$( ".xaddress1").append($(".xaddress1 xdiv").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$(".xpassword").append($(".xpassword tr").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function(){$(".xpassword").append($(".xpassword xdiv").get().sort(function(a, b) {
    return parseInt($(a).attr("sort").match(/\d+/), 10)
         - parseInt($(b).attr("sort").match(/\d+/), 10);
}));
});
$(document).ready(function() {
	if ($.browser.msie && $.browser.version == 6) {
		$('.date').bgIframe();
	}
	$('.date').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
	    changeYear: true,
	    yearRange: "-100:+5"});
});

$(document).ready(function() {
	$(".numeric").keydown(function(event) {
		// Allow only backspace and delete
		if ( event.keyCode == 46 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 8 || event.keyCode == 16 || event.keyCode == 36 || event.keyCode == 35) {
			// let it happen, don't do anything
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.keyCode < 48 || event.keyCode > 57 ) {
				event.preventDefault();
			}
		}
	});
});
$(function() {
    $( "#radio-xtensions" ).buttonset();
    $( "#radio1-xtensions" ).buttonset();
    $( "#radio-shipping" ).buttonset();
    $( "#radio2" ).buttonset();

  });


jQuery(function($) {
    $(".telephone").mask("(99)9999-9999");
    $(".mobile").mask("(99)999999999");
    $(".cpf").mask("999.999.999-99");
    $(".cep").mask("99999-999");
    $(".cnpj").mask("99.999.999/9999-99");
    $(".postcode").mask("99999-999");
 });
$(function(){
  // My Script
   /* $("[xtitle]").mbTooltip({ // also $([domElement]).mbTooltip
      opacity : 1,       //opacity
      wait:400,           //before show
      cssClass:"default",  // default = default
      timePerWord:70,      //time to show in milliseconds per word
      hasArrow:false,                 // if you whant a little arrow on the corner
      hasShadow:true,
      imgPath:"catalog/view/javascript/",
      anchor:"parent", //or "parent" you can ancor the tooltip to the mouse  or to the element
      shadowColor:"black", //the color of the shadow
      mb_fade:200 //the time to fade-in
    });*/
});


$(document).ready(function(){
    $( document ).on( 'focus', '.mask', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });
});
$(document).ready(function(){
    $( document ).on( 'focus', '.numeric', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });
});