// Site function library


function addScriptFile(src, async) {
	var script = document.createElement('script');
	script.setAttribute('async', async);
	script.setAttribute("src", '/js/' + src)
	
	if (typeof script != 'undefined')
		document.getElementsByTagName('body')[0].appendChild(script);
}


function addCssFile(href) {
	$('html, body').hide();		
	
	var link = document.createElement('link');
	link.setAttribute('rel', 'stylesheet');
	link.setAttribute('href', '/css/' + href + '.css');
	link.setAttribute('id', href + '_css');
	
	if(typeof link != 'undefined') {
		var $lastLink = $('head link[rel=stylesheet]:last');
		$lastLink.after(link);
	}

    $('#' + href.replace('.', '\\.') + '_css').on('load', function () {
    	$('html, body').show();	        
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

function initCarousel() {
	$.getScript( "/js/jquery.jcarousel.min.js", function(data, textStatus, jqxhr) {
		$('.jcarousel').jcarousel();
	
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


function closeMessage() {	
	$('.message').animate({'opacity' : 0}, 800, function() {
		$(this).css({'display' : 'none'});
	});
}