{literal}
<script>
	$(function() {		
		initVacancyScroller();
		initCarousel();
		loadBriefVacancy(null);
	});	
</script>
{/literal}

<div class="grid paddingtop60">
	<div class="grid grid290 comp-logo">
		{if $_companyProfile.logoKey}
			<img src="/support/resizeimage/tp/2/s/1/key/{$_companyProfile.logoKey}/t/">
		{/if}
	</div>
	{if $_HR_SESSION.USER && !$_isWorker}
	<div class="grid paddingtop15">
		<a href="#" id="want_to_work" class="profbutton" data-id="{$_companyProfile.companyId}">I want to work here</a>
    </div>
    {/if}	    
</div>  

<div class="clear"></div>     
<div class="block-title">{$_companyProfile.name}</div>
<div class="text"><p>{$_companyProfile.additionalInfo|escape|nl2br}</p></div>

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
{if $_companyBenefits}    
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
{/if}

<div class="block-title">Openings</div>
<div class="comp-vac-cont">
	<div class="vac-slider">                
		<ul class="vac-nav">
	        {foreach from=$_companyVacancies item=vacancy name=loop}
			<li class="vac-item{if $smarty.foreach.loop.index == 0} vac-selected{/if}" id="vac_{$vacancy.vacancyId}" data-id="{$vacancy.vacancyId}">
       			<div class="vac-wrapper">
        			<h5>{$vacancy.title}</h5>					
					<p class="arrow"></p>
				</div>
       		</li>
			{/foreach}						
		</ul> 
		<div class="vac-content">
		</div>
	</div>
</div>


<div class="comp-btns">
	{if $_companyOffices}
	<div class="grid grid320">
    	<div class="block-title">Main offices</div>
		{foreach from=$_companyOffices item=companyOffice}
			 <a class="button loc">{$companyOffice.address}</a>
		{/foreach}  
    </div>
    {/if}
  	<div class="grid grid320">
    	<div class="block-title">Contacts</div>
        <a class="button mail" href="mailto:{$_companyProfile.mail}">Send e-mail</a>
        {if $_companyProfile.linkedIn}<a class="button linkedin" href="{$_companyProfile.linkedIn}">Follow on linkedin</a>{/if}
        {if $_companyProfile.facebook}<a class="button face" href="{$_companyProfile.facebook}">Follow on facebook</a>{/if}
        {if $_companyProfile.twitter}<a class="button twitter" href="{$_companyProfile.twitter}">Follow on twitter</a>{/if}                
    </div>
    {if $_HR_SESSION.USER}
	   	<div class="grid grid320">
	    	<div class="block-title">Subscribe</div>
	    	{if $_companyProfile.newVacancies && !$_isSubscriptionForOpenings}
	        	<a id="subscribe-for-openings-btn" class="button subscribe" data-id="{$_companyProfile.companyId}" >Subscribe for openings</a>
	        {/if}
	        {if $_companyProfile.subscribeForNews}
	        	<a class="button">SUBscribe for news</a>     
	        {/if}               
	    </div>
    {/if}
    <div class="clear"></div>         
</div>