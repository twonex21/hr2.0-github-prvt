{literal}
<script>
	addCssFile('glDatePicker.flatwhite');
	addScriptFile('ajax.js', true);
</script>
{/literal}

<h1 class="page-title"><span>Company page creaton</span></h1>
<div class="block-title block-title-nobgr">General information</div>
   	<form name="hr-form" class="hr-form" action="" method="post" enctype="multipart/form-data">
   
    <div class="input-group-border padding28">
       	
        <fieldset class="grid grid230">
          <section class="filefield"> 
			<div id="company_logo" class="grid photo company-logo">
				{if $_companyProfile.logoKey}
					<img src="/support/resizeimage/tp/2/s/1/key/{$_companyProfile.logoKey}/t/">
				{/if}
			</div>
            {if $_companyProfile.logoKey}
            	<div id="company_logo_addbtn" class="fileaddbtn logo-remove" attr-picture-key='{$_companyProfile.logoKey}'>Remove Photo</div>
            {else}
            	<div id="company_logo_addbtn" class="fileaddbtn">Add logo</div>
            {/if}
            <input type="file" id="upload_company_logo" name="upload-picture" class="fileselect">
            <input type="hidden" id="temp_picture" name="temp-picture" value="">
          </section>   
        </fieldset>
        
       
        <fieldset class="grid grid670">
            <section>
                <label for="comp_title" class="input marginbottom25"><span>Title</span>
                    <input id="comp_title" name="comp_title" type="text" value="{$_companyProfile.name}" attr-validate="notEmpty">
                    <b class="tooltip tooltip-bottom-left">Company officially registered name</b>
                </label>
                <label for="comp_ad_info" class="textarea "><span>Some additional information</span>
                    <textarea id="comp_ad_info" name="comp_ad_info" attr-validate="isLatin">{$_companyProfile.additionalInfo}</textarea>
                    <b class="tooltip tooltip-bottom-left">About company</b>
                </label>
            </section>
        </fieldset>

        <div class="clear"></div>
    </div>
    
                
    <!-- START slider-->
    
    <!-- END slider-->
    
    <!--START Offices, Experience, subscription-->
    <div>
    	<div class="grid grid290 marginright40">
           	<div class="block-title block-title-nobgr">Offices</div>
            {foreach from=$_companyOffices item=_companyOffice}
				<fieldset class="grid grid290">
                    <section>                         
                        <label class="input">
                        	<input id="comp_offices_{$_companyOffice.office_id}" name="comp_offices[]" type="text" value="{$_companyOffice.address}" attr-validate="isLatin">
                        </label>
 					</section>
                </fieldset>
				{/foreach}                            
   	            <div class="clear"></div>
			<div id="addlocation-btn" class="form-add-thin-btn">Add location</div>
        </div>
        
        
        <div class="grid grid290 marginright40">
           	<div class="block-title block-title-nobgr">Contacts</div>
            <fieldset class="grid grid290">
                <section>
                    <label for="comp_phone" class="input marginbottom25">Contact Phone
                        <input id="comp_phone" name="comp_phone" type="text" value="{$_companyProfile.phone}">
                        <b class="tooltip tooltip-bottom-left">Company contact phone</b>
                    </label>
                    <label for="comp_email" class="input marginbottom25">Email to contact
                        <input id="comp_email" name="comp_email" type="text" value="{$_companyProfile.mail}" attr-validate='isEmailAddress'>
                        <b class="tooltip tooltip-bottom-left">Company email address</b>
                    </label>
                    <label for="comp_linked" class="input marginbottom25">Linked in link
                        <input id="comp_linked" name="comp_linked" type="text" value="{$_companyProfile.linkedIn}" attr-validate='isLinkedIn'>
                        <b class="tooltip tooltip-bottom-left">Company Linked in link</b>
                    </label>
                    <label for="comp_face" class="input marginbottom25">Facebook link
                        <input id="comp_face" name="comp_face" type="text" value="{$_companyProfile.facebook}" attr-validate='isFacebook'>
                        <b class="tooltip tooltip-bottom-left">Company facebook link</b>
                    </label>
                    <label for="comp_twitter" class="input marginbottom25">twitter link
                        <input id="comp_twitter" name="comp_twitter" type="text" value="{$_companyProfile.twitter}" attr-validate='isTwitter'>
                        <b class="tooltip tooltip-bottom-left">Company twitter link</b>
                    </label>                        
                </section>
            </fieldset>
        </div>
        
        
        <div class="grid grid300">
           	<div class="block-title block-title-nobgr">subscription</div>
            <fieldset class="grid grid300 marginbottom25 margintop20 paddingright40">
               <label class="checkbox"><input type="checkbox" name="subscribe_for_new_vacancies" value="1" {if $_companyProfile.newVacancies}checked{/if}><i></i>Allow users to subscribe for new vacancies</label>
            </fieldset>
            <fieldset class="grid grid300 marginbottom25 paddingright40">
                <label class="checkbox"><input type="checkbox" name="subscribe_for_news" value="1"  {if $_companyProfile.subscribeForNews}checked{/if}><i></i>Allow users to subscribe for news</label>
            </fieldset>
        </div>   

        <div class="clear"></div>
    </div>
    <!--END Offices, Experience, subscription-->

	<!-- START Company statistics-->
    <div>
       	<div class="block-title block-title-nobgr">Company statistics</div>
    	<div class="grid grid290 marginright40">
            <fieldset class="grid grid290">
                <section>
                    <label class="select">amount of emploees
                        <select name="comp_emp_count">
                            <option value="">Select emploees count</option>
                            <option value="5+" {if $_companyProfile.employeesCount == "5+"}selected=""{/if}>5+</option>
                            <option value="10+" {if $_companyProfile.employeesCount ==  "10+"}selected=""{/if}>10+</option>
                            <option value="25+" {if $_companyProfile.employeesCount ==  "25+"}selected=""{/if}>25+</option>
                            <option value="50+" {if $_companyProfile.employeesCount ==  "50+"}selected=""{/if}>50+</option>
                            <option value="100+" {if $_companyProfile.employeesCount ==  "100+"}selected=""{/if}>100+</option>
						</select>
                        <i></i>
                    </label>
 					</section>
            </fieldset> 
        </div>  
        <div class="grid grid290 marginright40">
            <fieldset class="grid grid300 marginbottom25 margintop30 paddingright40">
               <label class="checkbox"><input type="checkbox" name="show_amount_of_views" value="1" {if $_companyProfile.showViewsCount}checked{/if} ><i></i>Show amount of views of the company page</label>
            </fieldset>
        </div>
        <div class="grid grid300">
            <fieldset class="grid grid300 marginbottom25 margintop30 paddingright40">
               <label class="checkbox"><input type="checkbox" name="show_amount_users_applied" value="1" {if $_companyProfile.showApplicantsCount}checked{/if} ><i></i>show amount users who applied for the positions of the company</label>
            </fieldset>
        </div>                
   	            <div class="clear"></div>
    </div>
    <!-- END Company statistics-->

	<!-- START Benefits-->
    <div>
       	<div class="block-title block-title-nobgr">Benefits</div>
    	<div class="grid">
    	
    		{foreach from=$_companyBenefits item=_companyBenefit}
				<fieldset class="benefitsitem grid grid290">
                    <section>
                        <label class="select">
                            <select name="comp_benefits[]">
                            	{foreach from=$_allBenefits item=_benefit}
                            		<option value="{$_benefit.benefitId}" {if $_companyBenefit.benefitId == $_benefit.benefitId}selected=""{/if} >
                            			{$_benefit.name}
                            		</option>
                            	{/foreach}   
							</select>
                            <i></i>
                        </label>
 					</section>
                </fieldset> 
			{/foreach}        
            
            <div id="add-benefit-btn" class="form-add-thin-btn grid grid290">Add benefit</div>

        </div>  
   	            <div class="clear"></div>
    </div>
    <!-- END Benefits-->                    

    
    <div>
        <div style="padding:60px 0 150px 0">
        	<a class="profbutton sbmbutton" style="width:100%; display:block">save and post company page</a>
        </div>
    </div>
    
    
</form>