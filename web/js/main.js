var loadValues = {};

$(document).ready(function() {
	
	showMessage();
	
	$('.message .close').click(function() {
		closeMessage();
	});
	
	/* Toggle search */
	$('.searchbtn').click(function(){
		if($(this).hasClass("sbtnactive")) 	$(this).removeClass('sbtnactive');	else  $('.searchbtn').addClass('sbtnactive');
		
		$('#search-input').val('').trigger('keypress');
		$('#search-input-back').slideToggle(300, 'easeInOutCirc', function() {			
			$('#search-input').focus();
		});
	});	
	
	/* sign-in */
	initPopup();
	
	/* Sign-up tab switch */
	$(document).on('click', '.tab-switch a', function() {
		var userType = $(this).attr('title');

		$(this).addClass('sel').siblings('a').removeClass('sel');
		$('.create-account-body .tab').css('opacity', '0').load('/auth/' + userType + 'signup', function() {
			$(this).attr('id', userType).animate({'opacity' : 1}, 800);
		});
		
		return false;
	});	
	
	/* vacancy - table sorter */
	$(".tablesorter").tablesorter().bind("sortEnd",function() { 
		showPage(1); 
    });	
	
	$('#pager a').click(function() {
		var page = parseInt($(this).text());
		
		showPage(page);
		return false;
	});
		
	
	$('.vac-slider .vac-item').click(function() {			
		var containerTop = $('.vac-nav').offset().top;	
		var containerHeight = $('.vac-nav').height();
		var itemPositionTop = $(this).position().top;
		var itemOffsetTop = $(this).offset().top - containerTop;
		var itemHeight = $(this).height();
		
		var scrollTo;
		if(itemOffsetTop < 0) {
			scrollTo = itemPositionTop;			
		} else if(itemOffsetTop < itemHeight) {
			scrollTo = itemPositionTop - itemHeight;	
		} else if(containerHeight - itemOffsetTop < itemHeight) {
			scrollTo = itemHeight + itemPositionTop - containerHeight;
		} else if(containerHeight - itemOffsetTop < 2 * itemHeight) {
			scrollTo = 2 * itemHeight + itemPositionTop - containerHeight;
		}
		
		if(typeof scrollTo != 'undefined') {
			$('.vac-nav').mCustomScrollbar('scrollTo', scrollTo);
		}		
		
		var vacancyId = $(this).attr('data-id');
		$(this).addClass('vac-selected').siblings('.vac-item').removeClass('vac-selected');
		loadBriefVacancy(vacancyId);
	});
				
	/* education|experience add */
	$('#addeducation-btn, #addexperience-btn, #addskill-btn, #addlanguage-btn, #addlocation-btn, #addbenefit-btn, #add-benefit-btn').click(function() {
		addBlock($(this));
	});
	
	
	$(document).on('click', '.remove-block', function() {
		removeBlock($(this));
	});	
	
	/*slide add*/
	$('#add-slide-btn').click(function(){
		$(this).before(
			'<fieldset class="skillitem grid grid310 input-group-border padding20 boxsizing">'+
			$(this).parent().find('fieldset').html()+
			'</fieldset>'
			);
	});	
	
	/* benefit add */
	/*$('#add-benefit-btn').click(function(){
		$(this).before(
			'<fieldset class="benefitsitem grid grid290">'+
			$(this).parent().find('fieldset').html()+
			'</fieldset>'
			);
	});	*/
	
		
	$('#fileaddbtn:not(.file-remove)').click(function(e) {
		$('#upload_file').trigger('click');
	});

	$(document).on('change', '#upload_file', function() {
		ajaxUploadFile();
	});
	
	$('#fileaddbtn.file-remove').click(function(e) {
		var fileKey = $(this).attr('attr-file-key');
		var type = $(this).attr('attr-type');
		ajaxRemoveFile(fileKey, type);
	});
	
	$('#photoaddbtn:not(.photo-remove)').click(function(e) {
		$('#upload_picture').trigger('click');
	});
	
	$(document).on('change', '#upload_picture', function() {
		ajaxUploadPicture();
	});
	
	$('#photoaddbtn.photo-remove').click(function(e) {
		var photoKey = $(this).attr('attr-picture-key');
		
		if($(this).hasClass('company_logo')){
			ajaxRemovePicture(photoKey, 'company');
		}else{
			ajaxRemovePicture(photoKey, 'user');
		}
	});	
	
	/* Loading dropdown menu contents */
	$(document).on('change', '.hr-form select', function() {
		var $menu = $(this).parents('.select').next('.select').children('select');
		var parentId = $(this).val();
		var type = $(this).attr('attr-load');
		
		if(parentId != 0) {			
			$(this).closest('section').find('.remove-block').css('visibility', 'visible');
		}

		if(typeof type != 'undefined') {
			loadDropdown($menu, [parentId], type);				
		}
	});
	
	
	$(document).on('change', '[name="exp-industry-specs[]"]', function() {
		loadValues = {};
		$('[name^="exp-ind"]').each(function() {
			var type = $(this).attr('attr-type');
			if(!loadValues.hasOwnProperty(type)) {
				loadValues[type] = [];
			}
			if($(this).val() != null && $(this).val() != 0) {
				loadValues[type].push($(this).val());
			}
		});
		
		loadSkillsDropdown(loadValues);
	});		
	
	$(document).on('click', '.sbutton, .profbutton', function(e) {
		if($(this).parents('.signin-body').size() == 0) {
			ajaxValidateForm();
		}
		e.preventDefault();
	});	
	
	$(document).on('click', '.signin-body .sbutton', function(e) {
		ajaxLogin();
	});
	
    $('.vacbutton').click(function(e) {
    	var vacancyId = $(this).attr('attr-id');
    	applyToVacancy(vacancyId);
    	e.preventDefault();
    });
    
    
    $('#want_to_work').click(function(){ 
    	addWantToWork(this);
    	return false;
    });
    
    $('#hire_me').click(function(){ 
    	addHiring(this); 
    	return false;
    });
    
    $('#subscribe-for-openings-btn').click(function(){ 
    	subscribeForOpenings(this); 
    });
    
        
    
    /* Search */    
	var timeout;
	var action;
	var searchKeyword;
	var scrollLoaded = false;
	$('#search-input').bind("keypress", function(){
		clearTimeout(timeout);
		
		timeout = setTimeout(function() {
			if($.trim($('#search-input').val()) != searchKeyword) {
				action = $('#search-input').attr('data-search-type');
				searchKeyword = $.trim($('#search-input').val());
				
				if(searchKeyword.length == '') {
					$('#search-result-back').slideUp(300, 'easeInOutCirc', function() {
						//$('#search-input').focus();
						$('#search-result-inner').html('');
					});	
				} else if(searchKeyword.length > 2) {
					$('#search-result-back').slideDown(300, 'easeInOutCirc', function() {						
						$('#search-result-inner').load('/search/' + action + '/', {'keyword' : searchKeyword});						
					});
				}				
			}
		}, 400);
			
	});
	
	
	$('#vac-table tbody tr').click(function() {
		var vacancyId = $(this).attr('data-id');
		location.href = '/vacancy/view/vid/' + vacancyId + '/t/';
	});

	$('#vac-table .delete').click(function(e) {		
		var vacancyId = $(this).parents('tr').attr('data-id');
		if(confirm('Are you sure you want to delete this vacancy?')) {
			deleteVacancy(vacancyId);
		}
		return false;
	});
	
	$('#appl-table .delete').click(function(e) {		
		var applId = $(this).parents('tr').attr('data-id');
		if(confirm('Are you sure you want to delete this vacancy?')) {
			deleteVacancy(vacancyId);
		}
		return false;
	});
	
	/*
	$('#vac-table th').click(function() {
		var columnIdx = $(this).index() + 1;
		var sortOrder = 'asc';
				
		var $row;		
		var ultimate;
		var $ultimateRow;
		
		if(sortOrder == 'asc') {			
			for(var idx = 0; idx < $('#vac-table tbody tr').size() - 1; idx++) {				
				$ultimateRow = $('#vac-table tbody tr:nth-child(' + (idx + 1) + ')');
				ultimate = $ultimateRow.find('td:nth-child(' + columnIdx + ')').text();
				
				$('#vac-table tbody tr').each(function(i) {
					if(i >= idx) {
						$row = $(this);
						value = $row.find('td:nth-child(' + columnIdx + ')').text();
						console.log(i + '.)' + value);
						if(value < ultimate) {							
							ultimate = value;
							$ultimateRow = $row;
						}
					}					
				});
				
				if($('#vac-table tbody tr:nth-child(' + (idx + 1) + ')').attr('data-id') != $ultimateRow.attr('data-id')) {
					$('#vac-table tbody tr:nth-child(' + (idx + 1) + ')').before($ultimateRow);					
				}
			}			
		} else if(sortOrder == 'desc') {
			
		}				
	});
	*/
	
});


$(window).load(function() {
	/*datepicker*/
	//http://glad.github.io/glDatePicker/#features
	var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];;
	$('#deadline, #birth_date').glDatePicker({
		showAlways: false,
		cssName: 'flatwhite',
		onClick: function(target, cell, date, data) {
	        target.val( date.getDate() + ' ' +
	                    monthNames[date.getMonth()] + ', ' +
	                    date.getFullYear());	        
	    }	    
	});	
});