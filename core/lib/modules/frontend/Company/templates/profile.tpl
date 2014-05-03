{literal}
<script>
{/literal}
	var companyId = {$_companyProfile.companyId};
{literal}	
	// Loading ajax lib
	loadScript('ajax');		
	// Loading and initializing carousel
	loadScript('jquery.jcarousel.min', 'initCarousel');	
</script>
{/literal}


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
            {assign var="onePercent" value=$_companyProfile.pageViews*100}
        	<div class="vis-act" style="width:{ $onePercent/$_maxPageViews }%;"></div>
        </div>
    </div>
	{/if}
	
	{if $_companyProfile.showApplicantsCount == 1}
    <div class="grid comp-stat-block applied">
    	<h3>users applied for the positions:</h3>
        <p>{$_usersApplyedCount}</p>
        <div class="vis"></div>
    </div>
	{/if}
    
    <div class="clear"></div>      
</div>
    
<div class="block-title">Benefits</div>
<div class="comp-benifits">

	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
				{foreach from=$_companyBenefits item=companyBenefit}
					<li {if $companyBenefit.benefitName|strlen > 10}style="font-size: 1.3rem;"{/if}>
						<div class="grid comp-benifits-item">
							<img src="/images/benefits/{$companyBenefit.benefitId}.png" width="130" height="130">
							<h3>{$companyBenefit.benefitName}</h3>
						</div>
					</li>
				{/foreach} 
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>
	       
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
    {if $_HR_SESSION.USER}
	   	<div class="grid grid320">
	    	<div class="block-title">Subscribe</div>
	    	{if $_companyProfile.newVacancies}
	        	<a id="subscribe-for-openings-btn" class="button subscribe">Subscribe for openings</a>
	        {/if}
	        {if $_companyProfile.subscribeForNews}
	        	<a class="button">SUBscribe for news</a>     
	        {/if}               
	    </div>
    {/if}
    <div class="clear"></div>      
</div>