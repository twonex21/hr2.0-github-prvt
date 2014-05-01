// Site Ajax calls

	
function loadDropdown($menu, parentIds, type) {
	jQuery.ajax({
        dataType: 'json',
        url: '/support/loadmenu/',
        type: 'POST',
        data: {'parentIds' : parentIds, 'type' : type},

        success: function(json) {
            if(json.status == 'SUCCESS' && typeof json.options == 'object') {
        		$menu.html('').attr('disabled', false).parents('.select').removeClass('inactive');
        		var optionsHtml = '';
            	for(var id in json.options) {
            		if(json.options.hasOwnProperty(id)) {
	            		optionsHtml += '<option value="' + id + '">' + json.options[id].name + '</option>';	            		
            		} else {
            			$menu.html('').attr('disabled', true).parents('.select').addClass('inactive');
            			return;
            		}
            	}
            	
            	$menu.html(optionsHtml).trigger('change');
            } else {
            	$menu.html('').attr('disabled', true).parents('.select').addClass('inactive');
            }
        },
        error: function(json){} 
    });
}


function loadSkillsDropdown(expValues) {
	jQuery.ajax({
        dataType: 'json',
        url: '/support/loadmenu/',
        type: 'POST',
        data: {'parentIds' : expValues, 'type' : 'skills'},

        success: function(json) {
            if(json.status == 'SUCCESS') {
            	var currentLabels = [];
            	var currentLabel;
            	var hideBlock;
            	
        		$('[name="skills[]"]').each(function() {
        			hideBlock = true;
        			currentLabel = $(this).find('option[value="' + $(this).val() + '"]').text();
        			// Looping through new skills        			
        			for(var idx = 0; idx < json.options.length; idx++) {
        				// Hiding already set skills that do not correspond to new parents (industries, specs)
        				if(currentLabel == json.options[idx]['name']) {
        					hideBlock = false;
        					break;
        				}
        			}

        			if(hideBlock && $('[name="skills[]"]').size() > 1) {        				        					
    					// Hiding block
    					$(this).closest('fieldset').addClass('hidden').find('select').attr('disabled', true);        				
        			} else {
        				// Showing block
        				$(this).closest('fieldset').removeClass('hidden').find('select').attr('disabled', false);
	        			$(this).attr('disabled', false).parents('.select').removeClass('inactive');
	        			currentLabels.push(currentLabel);
        			}
        		});
        		
        		// Constructing new options list for skill dropdowns
        		// Default value
            	var optionsHtml = '<option value="0" class="empty">-- Not Set --</option>';
            	var currentParent = '';
            	for(var idx = 0; idx < json.options.length; idx++) { 
            		// Constructing industry optgroup           		
        			if(currentParent != json.options[idx]['parentName']) {
        				if(currentParent != '') {
        					optionsHtml += '</optgroup>';
        				}
        				currentParent = json.options[idx]['parentName'];
        				optionsHtml += '<optgroup label="' + currentParent + '">';            				
        			}
        			// Appending current option
            		optionsHtml += '<option value="' + json.options[idx]['skillId'] + '">' + json.options[idx]['name'] + '</option>';            		
            	}
            	
            	// Looping through all dropdowns
            	$('fieldset:not(.hidden) [name="skills[]"]').each(function(i) {
            		// Appending new options
	            	$(this).html(optionsHtml);
	            	// Setting preserved values
	            	if(currentLabels.length > i) {
	            		$(this).find('option').attr('selected', false).filter(function() { 
	            			return $(this).html() == currentLabels[i]; 
	            		}).attr('selected', true);
	            	}
            	});
            	
            	arrangeSkillBlocks();            	
            } else {
            	
            }
        },
        error: function(json){} 
    });
}


function ajaxUploadPicture() {	
	jQuery.ajaxFileUpload ({
        url: '/support/ajaxupload/',
        secureuri: false,
        fileElementId: 'upload_picture',
        dataType: 'json',
        success: function (json)
        {
        	$('#upload_picture').parents('fieldset').find('.file-error').remove();
        	
            if(json.status == 'SUCCESS') {
            	$('#temp_picture').val(json.tempFile);
            	// Showing temp picture
            	$('.photo').html('<img src="/support/resizeimage/tmp/1/s/1/key/' + json.tempFile + '/t/">');
            	// Chaging button
            	$('#photoaddbtn').html('remove photo').addClass('photo-remove').unbind('click').bind('click', function() {
            		ajaxRemovePicture(json.tempFile, 'temp');
            	});
            } else {
            	if(json.status == "FAIL") {            		
            		errorElement = document.createElement('div');
            		errorElement.innerHTML = json.message;
            		errorElement.className = 'file-error';
            		
            		$('#upload_picture').parents('fieldset').append(errorElement);
            	}
            }
        },
        error: function (json)
        {
         
        }
    })
    
    return false;
}


function ajaxUploadFile() {
	jQuery.ajaxFileUpload ({
        url: '/support/ajaxupload/',
        secureuri: false,
        fileElementId: 'upload_file',
        dataType: 'json',        
        success: function (json)
        {
        	$('#upload_file').parents('fieldset').find('.file-error').remove();
        	
            if(json.status == 'SUCCESS') {
            	$('#temp_file').val(json.tempFile);
            	
            	var realFileName = json.tempFile.substring(json.tempFile.indexOf('_') + 1);            	
            	$('#attached_file').addClass('attach-file').html(truncate(realFileName, 18)).attr('title', realFileName);
            	$('#fileaddbtn').html('remove file').addClass('file-remove').unbind('click').bind('click', function() {
            		ajaxRemoveFile(json.tempFile, 'temp');
            	});
            } else {
            	if(json.status == "FAIL") {            		
            		errorElement = document.createElement('div');
            		errorElement.innerHTML = json.message;
            		errorElement.className = 'file-error paddingright0';            		
            		$('#upload_file').parents('fieldset').append(errorElement);            		            		
            	}
            }
        },
        error: function (json)
        {
         
        }
    })
    
    return false;
}


function ajaxRemovePicture(fileName, type) {
	// TODO : Add confirmation dialog
	jQuery.ajax({
        dataType: 'json',
        url: '/support/ajaxdelete/',
        type: 'POST',
        data: {'key' : fileName, 'type' : type},

        success: function(json) {
            if(json.status == 'SUCCESS') {
            	// Clearing hidden value
            	$('#temp_picture').val('');
            	// Removing photo
            	$('.photo > img').remove();
            	// Changing button
            	$('#photoaddbtn').html('add photo').removeClass('photo-remove').unbind('click').bind('click', function() {
            		$('#upload_picture').trigger('click');
            	});            	
            } else {
            	
            }
        },
        error: function(json){} 
    });
}

function ajaxRemoveFile(fileName, type) {
	// TODO : Add confirmation dialog
	jQuery.ajax({
        dataType: 'json',
        url: '/support/ajaxdelete/',
        type: 'POST',
        data: {'key' : fileName, 'type' : type},

        success: function(json) {
            if(json.status == 'SUCCESS') {
            	var fileType = $('#fileaddbtn').attr('attr-type');
            	var attachText = '';
            	var uploadLabel = '';
            	
            	if(fileType == 'resume') {
            		attachText = 'You can attach your resume if you already have one.';
            		uploadLabel = 'upload resume';
            	} else if(fileType == 'vacancy') {
            		attachText = 'Attach additional file to the vacancy.';
            		uploadLabel = 'upload file';
            	}

            	// Clearing hidden value
            	$('#temp_file').val('');
            	// Removing file name
            	$('#attached_file').removeClass('attach-file').html(attachText).attr('title', '');
            	// Changing button
            	$('#fileaddbtn').html(uploadLabel).removeClass('file-remove').unbind('click').bind('click', function() {
            		$('#upload_file').trigger('click');
            	});
            } else {
            	errorElement = document.createElement('div');
        		errorElement.innerHTML = 'Error occured removing file';
        		errorElement.className = 'file-error paddingright0';
        		
        		$('#upload_file').parents('fieldset').append(errorElement);
            }
        },
        error: function(json){} 
    });
}


function ajaxValidateForm() {
	var params = {'fields' : []};
	var currentField = {};
	var criterias = [];
	// Getting all fields to validate
	var $fields = $('form').find('[attr-validate]');
	// Looping through fields
	$fields.each(function(i) {
		currentField = {};
		currentField.id = $(this).attr('id');
		if($(this).attr('type') == 'checkbox') {
			currentField.value = $(this).is(':checked');
		} else {
			currentField.value = $(this).val();
		}
		// Validation criterias
		criterias = $(this).attr('attr-validate').split(',');
		criterias = criterias.map(jQuery.trim);
		currentField.criterias = criterias;
		
		params.fields.push(currentField);
	});
	
	if(params.fields.length > 0) {
		// Performing server side validation
		jQuery.ajax({
	        dataType: "json",
	        url: "/support/validate/",
	        type: "POST",
	        data: params,
	            
	        success: function(json) {
	            if(json.status == 'SUCCESS') {
	            	// Successful validation, submitting the form
	            	$('.state-error').removeClass('state-error').find('span').remove();
	            	var form = $('form').get(0);
	            	form.submit();
	            } else {
	            	// Form failed to validate
	            	$('.state-error').removeClass('state-error').find('span').remove();
	            	if(json.messages.length !== null) {
	            		var errorElement;
	                	for(var id in json.messages) {
	                		// Appending errors to respective element container
	                		errorElement = document.createElement('span');
	                		errorElement.innerHTML = json.messages[id];
	                			                		
	                		$('#' + id).parents('label').addClass('state-error').append(errorElement);
	                	}
	                }
	            }
	        },
	        error: function(json){} 
	    });
	}
}



function applyToVacancy(vacancyId) {
	jQuery.ajax({
        dataType: 'json',
        url: '/vacancy/apply/',
        type: 'POST',
        data: {'vacancyId' : vacancyId},

        success: function(json) {
            if(json.status == 'SUCCESS') {
            	document.location.href = document.location.href;
            } else {
            	
            }
        },
        error: function(json){} 
    });
}
