var loadValues = {};

$(document).ready(function() {
	
	showMessage();
	
	$('.message .close').click(function() {
		closeMessage();
	});
	
	/* Toggle search */
	$('.searchbtn').click(function(){
		if($(this).hasClass("sbtnactive")) 	$(this).removeClass('sbtnactive');	else  $('.searchbtn').addClass('sbtnactive');	
		$('#search-input-back').slideToggle(300, 'easeInOutCirc', function() {
			$('#search-input').val('').trigger('keyup');
			$('#search-input').focus();
		});
	});


	/* top comp slider */	
	$('#top-comp-slider').royalSlider({
		autoHeight: false,
		arrowsNav: false,
		fadeinLoadedSlide: true,
		controlNavigationSpacing: 0,
		controlNavigation: 'bullets',
		imageScaleMode: 'none',
		loop: false,
		loopRewind: true,
		numImagesToPreload: 1,
		keyboardNavEnabled: false,
		usePreloader: true
	});
	
	/* company page slider */	
	$('#comp-slider').royalSlider({
		autoHeight: false,
		arrowsNav: true,
		arrowsNavAutoHide: false,
		fadeinLoadedSlide: true,
		controlNavigationSpacing: 0,
		controlNavigation: 'bullets',
		imageScaleMode: 'none',
		loop: false,
		loopRewind: true,
		numImagesToPreload: 2,
		keyboardNavEnabled: true,
		usePreloader: true
	});	
	
	/* sign-in */	
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
	
	/* vacancy - table sorter */
	$(".tablesorter").tablesorter();
	
	/* vacancy slider */
	$('#vac-slider').royalSlider({
		arrowsNav: false,
		fadeinLoadedSlide: true,
		controlNavigationSpacing: 0,
		controlNavigation: 'thumbnails',
		thumbs: {
		  orientation: 'vertical',
		  spacing: 0,
		  paddingBottom: 0,
		  arrows: false
		},
		navigateByClick:false,
		keyboardNavEnabled: true,
		transitionType: 'fade',
		slidesSpacing: 0,
		loop: false,
		loopRewind: true,
		autoScaleSlider: true, 
		autoScaleSliderWidth: 960,     
		autoScaleSliderHeight: 635,
	

 	});
	

	/* education|experience add */
	$('#addeducation-btn, #addexperience-btn, #addskill-btn, #addlanguage-btn, #addlocation-btn, #addbenefit-btn').click(function() {
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
	$('#add-benefit-btn').click(function(){
		$(this).before(
			'<fieldset class="benefitsitem grid grid290">'+
			$(this).parent().find('fieldset').html()+
			'</fieldset>'
			);
	});	
	
	
	/*search*/
	$('#search-input').keyup(function(){
		if($(this).val()=='') {
			$('#search-result-back').slideUp(300, 'easeInOutCirc', function() {
				//$('#search-input').focus();
			});	
		}else {
			$('#search-result-back').slideDown(300, 'easeInOutCirc', function() {
				//$('#search-input').focus();
			});	
		}
	});
	
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
		ajaxRemovePicture(photoKey, 'user');
	});
	
	//company page
	$('#company_logo_addbtn:not(.photo-remove)').click(function(e) { 
		$('#upload_company_logo').trigger('click');
	});
	$('#company_logo_addbtn').click(function(e) { 
		var photoKey = $(this).attr('attr-picture-key');
		ajaxRemovePicture(photoKey, 'user');
	});
	
	$(document).on('change', '#upload_company_logo', function() {
		ajaxUploadCompanyLogo();
	});
	//
	
	
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
		ajaxValidateForm();
		e.preventDefault();
	});
	
	
	$('.vac-slider').royalSlider({
            autoHeight: false,
            arrowsNav: true,
            arrowsNavAutoHide: false,
            fadeinLoadedSlide: true,
            controlNavigationSpacing: 0,
            controlNavigation: 'none',
            imageScaleMode: 'none',
            loop: false,
            loopRewind: false,
            numImagesToPreload: 2,
            keyboardNavEnabled: true,
            usePreloader: true,
            sliderDrag: false
    });
    
    
    $('.vacbutton').click(function(e) {
    	var vacancyId = $(this).attr('attr-id');
    	applyToVacancy(vacancyId);
    	e.preventDefault();
    });
	
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