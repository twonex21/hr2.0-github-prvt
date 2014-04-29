{literal}
<script>
	addCssFile('araks');	
	addCssFile('account');
	
	addScriptFile('ajax.js', true);	
	
	$(function() {		
		initCarousel();		
	});

	
</script>
{/literal}

<section class="vacancy-top-section">
	<div class="vacancy-top">
		<div class="vacancy-top-text">{$_vacancy.info.title}, <div>{$_vacancy.info.location}</div></div>
		<div><i>Deadline:</i> {$_vacancy.info.deadline}</div>
	</div>		
	<div class="clear margintop35"></div>      
	<img height="68" width="200" alt="" src="/images/samsung.png">
</section>
<div class="clear"></div>
<div class="req-skills-top">
	<h1>Required skills</h1>
	{if !$_HR_SESSION.user}
	<div class="login-txt">You have to <a href="#">log in</a> to see how your skills match this vacancy</div>
	{elseif $_matching.skillsLevel.level > 0}
	<div class="login-txt">You have to <a href="/user/create/">complete your profile</a> to see how your skills match this vacancy</div>		
	{else}
	<div class="grid req-skills-top-block">
		<div class="circle">
			<object type="image/svg+xml" data="/images/small-circle.svg" width="55" height="55">								
				<param name="matching" value="{$_matching.skills|round}">	
				<param name="color" value="#34AB00">							
			</object>
		</div>
		<div class="email-blocks-text"><div class="uppercase-height"><strong>{$_matching.skillsLevel.level}</strong> matching skills</div><span>{$_matching.skillsLevel.text}</span></div>
	</div>	
	{/if}
	{if $_requiredExperience}
	<div class="grid req-skills-top-block-flr">
		<div class="screw">{$_requiredExperience}</div>
		<div class="exp-years">years of <br>experience</div>
	</div>
	{/if}
	<div class="clear"></div>      
</div>
{if $_vacancy.experience}
<div class="req-skills-top">
	<h1 class='title'>Required Experience</h1>	
	{foreach from=$_vacancy.experience item=expItem}	
	<div class="profile-data"><h1>Scope of activity: <span>&nbsp;&nbsp;{$expItem.industryName}</span></h1></div>
	<div class="profile-spec"><span>Specialization:</span> {$expItem.specName} </div>
	<div class="profile-exp"><span>Total experience:</span>&nbsp;&nbsp;{$expItem.years} years </div>           
	<div class="separator"></div>
	{/foreach}     
</div>
{/if}
{if $_vacancy.skills}
<div class="req-skills">
	<h1>Required Skills</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_vacancy.skills item=skillItem}	    
	            <li class="skills-block" {if $skillItem.name|strlen > 10}style="font-size: 1.3rem;"{/if}><div>{$skillItem.name}</div><img src="/images/levels/{$skillItem.years}.png" /><span>{$skillItem.years}&nbsp;years</span></li>	            
            {/foreach}
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>
	<div class="clear"></div>      
</div>   
{/if} 
{if $_vacancy.softSkills}
<div class="req-skills">
   <h1>Required Soft Skills</h1>
   <div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_vacancy.softSkills item=softItem}	    	            
	            <li class="soft-block"><div {if $softItem.name|strlen > 26}style="font-size: 1.1rem;line-height:18px;"{/if}>{$softItem.name}</div><img src="/images/soft-levels/{$softItem.level|strtolower}.png" /></li>
            {/foreach}
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>
	<div class="clear"></div>
</div>    
{/if}
{if $_vacancy.education}
<div class="req-skills">
	<h1>Required Education</h1>
    <div class="jcarousel-wrapper">
		<div class="jcarousel">		
			<ul>
			{foreach from=$_vacancy.education item=eduItem}
				<li class="edu-block"><img src="/images/edu.png" /><span>{$eduItem.degree} in {$eduItem.industryName}</span></li>
			{/foreach}                 
			</ul>         
        </div>        
		<div class="jcarousel-control jcarousel-control-prev"></div>
		<div class="jcarousel-control jcarousel-control-next"></div>
    </div>
    <div class="clear"></div>      
</div>
{/if}
{if $_vacancy.languages}
<div class="req-skills">
	<h1>Required Languages</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_vacancy.languages item=langItem}	    
	            <li class="lang-block"><span>{$langItem.language}</span><br />
			       <img src="/images/langs/{$langItem.language|strtolower}.png" /><div class="lang-block-div" {if $langItem.level|strlen > 8}style="font-size: 1.4rem;"{/if}>{$langItem.level}</div>
			       <div class="clear"></div>
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
{if $_vacancy.benefits}
<div class="req-skills">
	<h1>Position Benefits</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_vacancy.benefits item=benefitItem}	    
	            <li class="benefit-block">
			       <img src="/images/benefits/{$benefitItem.benefitId}.png" /><div {if $benefitItem.name|strlen > 8}style="font-size: 1.4rem;"{/if}>{$benefitItem.name}</div>
			       <div class="clear"></div>
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
{if $_vacancy.info.additionalInfo}
	<div class="block-title-red">additional info</div>
	<div class="text"><p>{$_vacancy.info.additionalInfo|escape|nl2br}</p></div>	    
{/if}
     
{if $_vacancy.info.showApplicantsCount || $_vacancy.info.showViewersCount || $_vacancy.info.showWantToWorkCount}
<div class="vac-bot-block">
	<div class="grid">
		{if $_matching.total > 0}
		<div class="grid vac-bot matching">
			<object type="image/svg+xml" data="/images/large-circle.svg" width="111" height="111">								
				<param name="matching" value="{$_matching.total}">																				
			</object>
			<div class="vac-bot-center"><p>Matches your background for about {$_matching.total}%.</p></div>
		</div>
		{/if}    		
		{if $_vacancy.info.showViewersCount}
		<div class="grid vac-bot views">
			<div class="vac-bot-center"><p>Views: {$_vacancy.info.views}</p></div>
		</div>    
		{/if}
		{if $_vacancy.info.showApplicantsCount}
		<div class="grid vac-bot applications">
			<div class="vac-bot-center"><p>Applications: {$_vacancy.info.applicantsCount}</p></div>
		</div> 
		{/if}   
	</div>
	<div class="clear"></div>      
</div>
{/if}
{if $_canApply}
<div class="connect-button">
	<a class="vacbutton" href="" attr-id="{$_vacancy.info.vacancyId}">apply to  this vacancy</a>
</div>    
{/if}

<div class="vac-social">
	<div data-share="false" data-show-faces="false" data-action="like" data-layout="button_count" data-href="https://developers.facebook.com/docs/plugins/" class="fb-like"></div>
    &nbsp;&nbsp;
	<iframe frameborder="0" scrolling="no" id="twitter-widget-0" allowtransparency="true" src="https://platform.twitter.com/widgets/tweet_button.1397165098.html#_=1398454637518&amp;count=horizontal&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=file%3A%2F%2F%2FC%3A%2FDocuments%2520and%2520Settings%2FAdministrator%2FDesktop%2FHR%2Fhr_layout_1.0%2Fvacancy_logged_in.html&amp;size=m&amp;text=HR.am%20-%20vacancy%20logged%20in&amp;url=file%3A%2F%2F%2FC%3A%2FDocuments%252520and%252520Settings%2FAdministrator%2FDesktop%2FHR%2Fhr_layout_1.0%2Fvacancy_logged_in.html" class="twitter-share-button twitter-tweet-button twitter-count-horizontal" title="Twitter Tweet Button" data-twttr-rendered="true" style="width: 109px; height: 20px;"></iframe>
</div>