
<div class="detail_page_company_logo">
	{if $_companyProfile.logoKey}
		<img src="/support/resizeimage/tp/2/s/1/key/{$_companyProfile.logoKey}/t/">
	{/if}
</div>
<div class="block-title">{$_companyProfile.name}</div>
<div class="text"><p>{$_companyProfile.additionalInfo}</p></div>

<div class="block-title">Company statistics</div>
<div class="comp-stat">
	<div class="grid comp-stat-block emps">
    	<h3>employees: </h3>
        <p>more than {$_companyProfile.employeesCount|replace:'+':''}</p>
        <div class="vis">
        	<div class="vis-act_{$_companyProfile.employeesCount|replace:'+':''}"></div>
        </div>
    </div>
    
    
    {if $_companyProfile.showViewsCount == 1}
    <div class="grid comp-stat-block views">
    	<h3>page views: </h3>
        <p>{$_companyProfile.pageViews}</p>
        <div class="vis">
        	<div class="vis-act" style="width:180px;"></div>
        </div>
    </div>
	{/if}
	
	{if $_companyProfile.showApplicantsCount == 1}
    <div class="grid comp-stat-block applied">
    	<h3>users applied for the positions:</h3>
        <p>354 - FIX</p>
        <div class="vis"></div>
    </div>
	{/if}
    
    <div class="clear"></div>      
</div>
    
<div class="block-title">Benefits</div>
<div class="comp-benifits">

	{foreach from=$_companyBenefits item=companyBenefit}
		<div class="grid comp-benifits-item">
			<img src="/images/benefits/{$companyBenefit.benefitId}.png" width="130" height="130">
			<h3>{$companyBenefit.name}</h3>
		</div>
	{/foreach} 
	       
    <div class="clear"></div>      
</div>


<div class="block-title">Openings</div>
<!-- TODO implement -->

<div class="comp-btns">
	<div class="grid grid320">
    	<div class="block-title">Main offices</div>
		{foreach from=$_companyOffices item=companyOffice}
			 <a class="button loc">{$companyOffice.address}</a>
		{/foreach}  
    </div>
  	<div class="grid grid320">
    	<div class="block-title">Contact</div>
        <a class="button mail" href="mailto:{$_companyProfile.mail}">Send e-mail</a>
        <a class="button linkedin" href="{$_companyProfile.linkedIn}">Follow on linkedin</a>
        <a class="button face" href="{$_companyProfile.facebook}">Follow on facebook</a>
        <a class="button twitter" href="{$_companyProfile.twitter}">Follow on twitter</a>                
    </div>
   	<div class="grid grid320">
    	<div class="block-title">Subscribe</div>
    	{if $_companyProfile.newVacancies}
        	<a class="button subscribe">Subscribe for openings</a>
        {/if}
        {if $_companyProfile.subscribeForNews}
        	<a class="button">SUBscribe for news</a>     
        {/if}               
    </div>
    <div class="clear"></div>      
</div>