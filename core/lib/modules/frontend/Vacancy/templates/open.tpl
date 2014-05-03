{literal}
<script>
	addCssFile('glDatePicker.flatwhite');
	loadScript('ajax');
	
	$(function() {		
		arrangeSkillBlocks();	
	});

</script>
{/literal}

<h1 class="page-title"><span>vacancy creation</span></h1>
<form name="hr-form" class="hr-form" action="" method="post"  enctype="multipart/form-data">
    <div id="block_general_info">
    	<div class="block-title block-title-nobgr grid grid400">General information</div>        
        <div class="grid grid440 marginleft120">
        	<div class="paddingtop60">
        		{if $_vacancy.info.fileKey}
	           		<div class="note paddingleft40 attach-ico grid grid180 margintop15 paddingright20 attach-file" id="attached_file" title="{$_vacancy.info.fileKey}">{$_vacancy.info.fileKey|substr:5|truncate:20:"...":true}</div>
	                <fieldset class="grid grid200">
	                    <div class="margintop10 file-remove" id="fileaddbtn" attr-file-key="{$_vacancy.info.fileKey}" attr-type="vacancy">Remove File</div>
	                	<input type="file" id="upload_file" name="upload-file" class="fileselect">
	                    <input type="hidden" id="temp_file" name="temp-file" value="">
	               	</fieldset>
               	{else}
               		<div class="note paddingleft40 attach-ico grid grid180 margintop15 paddingright20" id="attached_file">Attach additional file to the vacancy.</div>
	                <fieldset class="grid grid200">
	                    <div class="margintop10" id="fileaddbtn" attr-type="vacancy">Upload File</div>                    
	                	<input type="file" id="upload_file" name="upload-file" class="fileselect">
	                    <input type="hidden" id="temp_file" name="temp-file" value="">                    
	               	</fieldset>
               	{/if}
        	</div>
        </div>
        <div class="clear"></div>
            
	    <div class="note paddingbottom10">Fields denoted with * are required</div>   
	    <div class="input-group-border padding28"> 
	    	<fieldset class="grid grid230">	
				<section class="filefield">
					<div id="profile_photo" class="grid photo profile-photo">
						
					</div>
					<div id="photoaddbtn">Add Photo</div>
					<input type="file" id="upload_picture" class="fileselect" name="upload-picture">
					<input type="hidden" id="temp_picture" name="temp-picture" value="">
				</section>
	        </fieldset>   	       
	        <fieldset class="grid grid290 marginright30">
	            <section>
	                <label for="fullname" class="input label105">Vacancy Title *
	                    <input id="title" name="title" type="text" value="{$_vacancy.info.title}" attr-validate="notEmpty">
	                </label>                                
	                <label for="location" class="input label105">Location *
	              		<input id="location" name="location" type="text" value="{$_vacancy.info.location}" attr-validate="notEmpty, isLatin">
	                    <b class="tooltip tooltip-bottom-left for-input">City, Country</b>
	                </label>
	            </section>
	        </fieldset>       
	        <fieldset class="grid grid290">
	        	<section>
	                <label for="status" class="select label105">Status
						<select name="status">
	                        <option value="Active">Active</option>
	                        <option value="Inactive" {if $_vacancy.info.status == 'INACTIVE'}selected{/if}>Not Active</option>                        
	                    </select>
	                    <i></i>
	                </label>
	                <label for="deadline" class="input label105">Deadline *
	                    <i class="icon-append icon-calendar"></i>
	              		<input id="deadline" name="deadline" type="text" value="{$_vacancy.info.deadline}" attr-validate="notEmpty, isDate, isFutureDate">
	                </label>
				</section>
	        </fieldset>
	        <div class="clear"></div>
	    </div>
    </div>
    <div id="block_education">    	
       	<div class="block-title block-title-nobgr">Education</div>
       	{foreach from=$_vacancy.education item=eduItem}
	        <fieldset class="block-item grid grid310 input-group-border padding20 boxsizing">
	            <section>
	                <label class="select marginbottom30">Industry
	                    <select name="edu-industries[]">
	                        <option value="0" class="empty">-- Not set --</option>
	                        {foreach from=$_data.industries item=industry}
	                        	<option value="{$industry.industryId}" {if $eduItem && $industry.industryId == $eduItem.industryId}selected{/if}>{$industry.name}</option>
	                    	{/foreach}
	                    </select>
	                    <i></i>
	                </label>                            
	                <label class="select">Degree
	                    <select name="edu-degrees[]">
	                        {foreach from=$_data.univerDegrees item=degree}
	                        	<option value="{$degree}" {if $eduItem && $degree == $eduItem.degree}selected{/if}>{$degree}</option>
	                    	{/foreach}
	                    </select>	                    
	                    <i></i>
	                </label>	 
	                <a class="remove-block down" {if !$eduItem}style="visibility:hidden;"{/if}>X Remove this University</a>
				</section>
	        </fieldset>        
        {/foreach}
        <div id="addeducation-btn" class="form-add-btn middle grid">Add Education</div>
    	<div class="clear"></div>        
    </div>
    <div id="block_experience">
       	<div class="block-title block-title-nobgr">Experience</div>
       	{foreach from=$_vacancy.experience item=expItem}
	        <fieldset class="block-item grid grid310 input-group-border padding20 paddingbottom0 boxsizing">
	            <section>
	                <label class="select marginbottom30">Scope of activity
	                    <select name="exp-industries[]" attr-load="specializations" attr-type="industry">
	                        <option value="0" class="empty">-- Not set --</option>
	                        {foreach from=$_data.industries item=industry}
	                        	<option value="{$industry.industryId}" {if $expItem && $industry.industryId == $expItem.industryId}selected{/if}>{$industry.name}</option>
	                    	{/foreach}
	                    </select>
	                    <i></i>
	                </label>                           
	                <label class="select marginbottom30 {if !$expItem}inactive{/if}">Specialization
	                    <select name="exp-industry-specs[]" attr-dynamic attr-type="spec" {if !$expItem}disabled{/if}>
	                    	{if $expItem}
		                    	{foreach from=$expItem.specs item=spec}
		                    		<option value="{$spec.specId}" {if $expItem && $spec.specId == $expItem.specId}selected{/if}>{$spec.name}</option>
		                    	{/foreach}
	                    	{/if}
	                    </select>
	                    <i></i>
	                </label> 
	                <label class="select marginbottom30">Total experience <span>(years)</span>
	                    <select name="exp-years[]">
	                        {section start=1 loop=5 name=numbers}
							    <option value="{$smarty.section.numbers.index}" {if $expItem && $smarty.section.numbers.index == $expItem.years}selected{/if}>{$smarty.section.numbers.index}</option>
							{/section}              
							<option value="5+" {if $expItem && $expItem.years == "5+"}selected{/if}>5+</option>                                                         
	                    </select>
	                    <i></i>
	                </label>                             
	                <a class="remove-block" {if !$expItem}style="visibility:hidden;"{/if}>X Remove this Position</a>
				</section>
	        </fieldset>
        {/foreach}
        <div id="addexperience-btn" class="form-add-btn thick grid">Add Experience</div>
		<div class="clear"></div>
    </div> 
    <div id="block_data.languages">
    	<!-- Wrapped in additional div to have only one child before the fieldset (to make 3n+1 logic work) -->
    	<div>
	       	<div class="block-title block-title-nobgr">Languages</div>
	        <div class="note paddingbottom10">Choose languages you know and estimate your level of knowledge</div>
        </div>
        {foreach from=$_vacancy.languages item=langItem}
	        <fieldset class="block-item grid grid310 input-group-border padding10 paddingtop0 boxsizing">
	            <section>
	                <label class="select col col-55p marginright9">
	                    <select name="langs[]">
	                    	<option value="" class="empty">-- Not set --</option>
	                   		{foreach from=$_data.languages item=lang}
	                   			<option value="{$lang}" {if $langItem && $lang == $langItem.language}selected{/if}>{$lang}</option>
	                   		{/foreach}
	                    </select>
	                    <i></i>
	                </label>
	                <label class="select col col-5">
	                    <select name="lang-levels[]">
	                        {foreach from=$_data.languageLevels item=level}
	                   			<option value="{$level}" {if $langItem && $level == $langItem.level}selected{/if}>{$level}</option>
	                   		{/foreach}
	                    </select>
	                    <i></i>
	                </label>	                	                
				</section>
	        </fieldset>                                
        {/foreach}
        <div id="addlanguage-btn" class="form-add-btn thin grid paddingleft20 boxsizing">Add language</div>
        <div class="clear"></div>
    </div>    
    <!--Skills-->
    <div id="block_skills">
    	<div class="block-title block-title-nobgr">Skills</div>
    	{foreach from=$_vacancy.skills.vacancySkills item=skillItem}
	        <fieldset class="skill-item grid grid310 input-group-border padding20 boxsizing">
	        	<section>
					<label class="select marginbottom30 {if !$skillItem}inactive{/if}">Choose skill
	                    <select name="skills[]" {if !$skillItem}disabled{/if}>
	                    	{assign var=parentName value=``}	                    		                    
	                    	{foreach from=$_vacancy.skills.allSkills item=skill}
		                    	{if $parentName != $skill.parentName}
			        				{if parentName != ''}</optgroup>{/if}
			        				{assign var=parentName value=$skill.parentName}
			        				<optgroup label="{$parentName}">
			        			{/if}			        			
	                   			<option value="{$skill.skillId}" {if $skillItem && $skill.name == $skillItem.name}selected{/if}>{$skill.name}</option>
	                   		{/foreach}
	                    </select>
						<i></i>
					</label>
	              	<label class="select">Years of experience
	                    <select name="skill-years[]">
	                    	{section start=1 loop=5 name=numbers}
							    <option value="{$smarty.section.numbers.index}" {if $skillItem && $smarty.section.numbers.index == $skillItem.years}selected{/if}>{$smarty.section.numbers.index}</option>
							{/section}
							<option value="5+" {if $skillItem && $skillItem.years == "5+"}selected{/if}>5+</option>
	                 	</select>
						<i></i>
					</label>
					<a class="remove-block down" {if !$skillItem}style="visibility:hidden;"{/if}>X Remove this Skill</a>
	            </section>
	        </fieldset>                
        {/foreach}
		<div id="addskill-btn" class="form-add-btn middle grid">Add Skill</div>
        <div class="clear"></div>
    </div>        
    <!-- Soft Skills -->
    <div id="block_soft_skills">
    	<!-- Wrapped in additional div to have only one child before the fieldset (to make 3n+1 logic work) -->
    	<div>
	       	<div class="block-title block-title-nobgr">Soft Skills</div>
	        <div class="note paddingbottom10">Choose soft skills that describe you the best</div>
        </div>
        {foreach from=$_vacancy.softSkills item=softItem}
	        <fieldset class="block-item grid grid310 input-group-border padding10 paddingtop0 boxsizing">
	            <section>
	                <label class="select col col-55p marginright9">
	                    <select name="soft-skills[]">
	                    	<option value="0" class="empty">-- Not set --</option>
	                   		{foreach from=$_data.softSkills item=skill}
	                   			<option value="{$skill.softId}" {if $softItem && $skill.softId == $softItem.softId}selected{/if}>{$skill.name}</option>
	                   		{/foreach}
	                    </select>
	                    <i></i>
	                </label>
	                <label class="select col col-5">
	                    <select name="soft-levels[]">
	                        {foreach from=$_data.softSkillLevels item=level}
	                   			<option value="{$level}" {if $softItem && $level == $softItem.level}selected{/if}>{$level}</option>
	                   		{/foreach}
	                    </select>
	                    <i></i>
	                </label>	                	                
				</section>
	        </fieldset>                                
        {/foreach}
        <div id="addlanguage-btn" class="form-add-btn thin grid paddingleft20 boxsizing">Add soft skill</div>
        <div class="clear"></div>
    </div>
    <div>
		<div class="block-title block-title-nobgr">Benefits</div>
		{foreach from=$_vacancy.benefits item=benefitItem}
		<fieldset class="block-item grid grid310 input-group-border padding10 paddingtop0 boxsizing">
			<section>
				<label class="select">
					<select name="benefits[]">
						<option value="0" class="empty">-- Not set --</option>
					{foreach from=$_data.benefits item=benefit}
                           <option value="{$benefit.benefitId}" {if $benefitItem && $benefit.benefitId == $benefitItem.benefitId}selected{/if}>{$benefit.name}</option>
					{/foreach}
					</select>
                   	<i></i>
               	</label>
			</section>
	    </fieldset>
	    {/foreach}                     
		<div id="addbenefit-btn" class="form-add-btn thin grid paddingleft20 boxsizing">Add benefit</div>
   		<div class="clear"></div>
    </div>
    <div>
       	<div class="block-title block-title-nobgr">additional information</div>       	
       	<fieldset>
	       	<section>
		       	<label for="vac_title" class="textarea marginbottom30">
			       	<textarea id="additional_info" name="additional-info" style="height:150px"/>{$_vacancy.info.additionalInfo}</textarea>			       	
		       	</label>
	       	</section>
       	</fieldset>                
		<fieldset class="grid grid300 paddingright20">
			<label class="checkbox"><input type="checkbox" name="show-applicant-count" {if $_vacancy.info.showApplicantsCount}checked{/if}><i></i>Show amount of people who applied to this vacancy</label>
       	</fieldset>
       	<fieldset class="grid grid300 paddingright20">
       		<label class="checkbox"><input type="checkbox" name="show-viewer-count" {if $_vacancy.info.showViewersCount}checked{/if}><i></i>Show amount of people who viewed this vacancy</label>
       	</fieldset>
       	<fieldset class="grid grid300 paddingright20">
       		<label class="checkbox"><input type="checkbox" name="show-wanttowork-count" {if $_vacancy.info.showWantToWorkCount}checked{/if}><i></i>Show amount of people who wants to work in your company</label>
		</fieldset>
   	    <div class="clear"></div>
        <div style="padding:60px 0 150px 0"><a class="profbutton" style="width:100%; display:block">save and post vacancy</a></div>
	</div>
</form>