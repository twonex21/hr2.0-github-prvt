// Site function library


function loadScript(src) {	
	// Getting callback function (must be always passed as second argument)
	var callback = arguments[1];
	// Getting callback arguments
	var args = Array.prototype.slice.call(arguments, 2);
	if($('head script[src^="/js/' + src + '.js"]').size() == 0) {
		$.getScript( "/js/" + src + ".js", function(data, textStatus, jqxhr) {
			if(typeof callback != 'undefined') {
				$(window).load(function(){
					// Invoking callback ajax function with passed arguments
					window[callback].apply(undefined, args);
				});
			}
		});
	} else {
		// Script is already available on the page
		if(typeof callback != 'undefined') {
			$(window).ready(function() {				
				// Invoking callback ajax function with passed arguments
				window[callback].apply(undefined, args);
			});
		}
	}
}


function addCssFile(href) {
	if($('head link[href^="/css/' + href + '.css"]').size() == 0) {
		$('.loaded').hide();		
		
		var link = document.createElement('link');
		link.setAttribute('rel', 'stylesheet');
		link.setAttribute('href', '/css/' + href + '.css');
		link.setAttribute('id', href + '_css');
		
		if(typeof link != 'undefined') {
			var $lastLink = $('head link[rel=stylesheet]:last');
			$lastLink.after(link);
		}
	
	    $('#' + href.replace('.', '\\.') + '_css').on('load', function () {
	    	$('.loaded').show();
	    });		
	}
}

function initPopup() {
	$('.popup').magnificPopup({
		type: 'ajax',
		overflowY: 'auto',
		removalDelay: 100,
		showCloseBtn: true,
		closeBtnInside: false,
		closeOnContentClick: false,
		closeOnBgClick: false,
		preloader: true,
		mainClass: 'my-mfp-zoom-in',
		fixedContentPos: true,
		fixedBgPos: true,
		ajax: {
		  settings:  {cache:false},
		  tError: '<a href="%url%">The content</a> could not be loaded.' //  Error message, can contain %curr% and %total% tags if gallery is enabled
		}
		
	});	
}

function addBlock($btn) {
	// Getting first block
	var $fieldset = $btn.siblings('fieldset:not(.hidden)').last();
	var $firstSelect = $fieldset.find('select:first');
	
	// Checking if already has selected value before adding new block
	if($firstSelect.val() && $firstSelect.val() != 0) {
		// Creating clone
		$clone = $fieldset.clone();	
		// Clearing dynamically loaded data
		$clone.find('select[attr-dynamic]').html('').attr('disabled', true).parents('label').addClass('inactive');
		// Resetting all dropdown values
		if($clone.find('select option.empty').size() > 0) {
			$clone.find('select option.empty').attr('selected', true);
		} else {
			$clone.find('select option:first-child').attr('selected', true);
		}
		// Hiding remove block link		
		$clone.find('.remove-block').css('visibility', 'hidden');		
		// Adding new identical block
		$btn.before($clone);
		// Arranging blocks
		arrangeSkillBlocks();
	}
	
	//inpute case
	// Getting first block
	var $fieldset = $btn.siblings('fieldset:not(.hidden)').last();
	var $firstInput = $fieldset.find('input:first');
	
	// Checking if already has selected value before adding new block
	if($firstInput.val() && $firstInput.val() != 0) {
		// Creating clone
		$clone = $fieldset.clone();	
		//emptying
		$clone.find('input').val("");
		// Adding new identical block
		$btn.before($clone);
		//to breack line
		$btn.before('<div class="clear"></div>');
	}
}


function removeBlock($btn) {
	$parent = $btn.parents('fieldset');		
	if($parent.siblings('fieldset').size() == 0) {console.log(111);
		// The last block
		if($parent.find('select option.empty').size() > 0) {
			$parent.find('select option.empty').attr('selected', true);
		} else {
			$parent.find('select option:first-child').attr('selected', true);
		}
		$parent.find('select[attr-dynamic]').html('').attr('disabled', true).parent('label').addClass('inactive');
		$btn.css('visibility', 'hidden');
	} else {
		$parent.remove();
		arrangeSkillBlocks();
	}
}


function arrangeSkillBlocks() {
	$('#block_skills').find('fieldset:not(.hidden)').removeClass('marginright0').each(function(i) {
		if((i + 1) % 3 == 0) {
			$(this).addClass('marginright0');
		}
	});
}


function truncate(str, length) {
	if(str.length > length) {
		return str.substring(0, length) + "...";
	} else {
		return str;
	}
}


function loadAjaxLib(callback) {
	// Getting callback function (must be always passed as first parameter)
	var callback = arguments[0];
	// Getting callback arguments
	var args = Array.prototype.slice.call(arguments, 1);		
	$.getScript( "/js/ajax.js", function(data, textStatus, jqxhr) {
		// Invoking callback ajax function with passed arguments
		window[callback].apply(undefined, args);
	});
}


function initCarousel() {	
	$('.jcarousel').jcarousel({
		animation: {
	        duration: 800,		        
	    }
	});

    $('.jcarousel-control-prev')
    	.on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        }).on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
    	.jcarouselControl({
        	target: '-=1'
    	});

		$('.jcarousel-control-next')
        .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
        .jcarouselControl({
            target: '+=1'
        });
		
	$('.jcarousel-pagination')
        .on('jcarouselpagination:active', 'a', function() {
            $(this).addClass('active');
        })
        .on('jcarouselpagination:inactive', 'a', function() {
            $(this).removeClass('active');
        })
        .on('click', function(e) {
            e.preventDefault();
        })
        .jcarouselPagination({
            perPage: 1,
            item: function(page) {
                return '<a href="#' + page + '">' + page + '</a>';
            }
        });	
}


function initVacancyScroller() {
	$('.vac-nav').mCustomScrollbar({
		theme: 'dark',
		mouseWheel: true
	});
}


function initSearchScroller() {
	$('#search-result-inner').mCustomScrollbar({
		theme: 'search',
		mouseWheel: true
	});		
}


function showMessage() {	
	var $message = $('.message');
	var isFlash = $message.attr('attr-flash');
	
	// If there is a text, show the message
	if($message.children('span').size() > 0) {
		setTimeout(function() {
			$message.show().animate({'opacity' : 1}, 800, function() {
				if(isFlash) {
					setTimeout(function() { 				
						closeMessage();
					}, 5000)
				}
			});
		}, 400);
	}
}


function setMessage() {
	var type = arguments[0];
	var messageText = arguments[1];
	var isFlash = arguments[2];
	
	var $message = $('.message');
	$message.addClass(type).attr('attr-flash', isFlash);
	$message.children('span').remove()
	$span = $('<span/>').html(messageText);
	$message.prepend('<span>' + messageText + '</span>');
	
	if(isFlash) {
		$message.children('.close').hide();
	} else {
		$message.children('.close').show();
	}
	
	$("html, body").animate({scrollTop: 0}, 500);
	
	showMessage();
}


function closeMessage() {	
	$('.message').animate({'opacity' : 0}, 800, function() {
		$(this).css({'display' : 'none'});
	});
}


function showPage(page) {console.log(page);
	var perPage = 10;
	
	$('.pager a[data-page=' + page + ']').addClass('active').siblings().removeClass('active');
	$(".tablesorter tbody tr").each(function(i) {
		if(i < (page - 1) * perPage || i >= page * perPage) {
			$(this).hide();
		} else {
			$(this).show();
		}
	});
}
