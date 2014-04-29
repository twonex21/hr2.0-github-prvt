{literal}
<script>
	addCssFile('araks');	
	
	$(function() {		
		initCarousel();		
	});

</script>
{/literal}

<div class="grid profile-top-block">
    <img src="{if $_user.profile.pictureKey}/support/resizeimage/tp/1/s/1/key/{$_user.profile.pictureKey}/t/{else}/images/photo.png{/if}" alt="" class="grid user-photo">
    <div class="grid profile-top">
        <div class="grid profile-top-text">{$_user.profile.fullName},&nbsp;<span>{$_user.profile.age}</span></div> 
        <div class="grid profile-top-year">years<br /> old</div>
        <div class="clear"></div>   
        <div class="profile-social">
            <a class="button loc">{$_user.profile.location}</a>
            {if $_user.profile.linkedIn}<a class="button linkedin" href="{$_user.profile.linkedIn}" target="_blank">Show on linkedin</a>{/if}<br />
            <a class="button mail" href="mailto:{$_user.profile.mail}">Send e-mail</a>
            <a class="button share" href="mailto:mail@mail.com">Share this cv</a>
        </div>
    </div>
</div>
<div class="clear"></div>
{if $_user.experience}
<div class="req-skills-top">
	{foreach from=$_user.experience item=expItem}	
	<div class="profile-data"><h1>Scope of activity: <span>&nbsp;&nbsp;{$expItem.industryName}</span></h1></div>
	<div class="profile-spec"><span>Specialization:</span> {$expItem.specName} </div>
	<div class="profile-exp"><span>Total experience:</span>&nbsp;&nbsp;{$expItem.years} years </div>           
	<div class="separator"></div>
	{/foreach}
</div>
{/if}
{if $_user.skills}
<div class="req-skills">
	<h1>Skills</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_user.skills item=skillItem}	    
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
{if $_user.softSkills}
<div class="req-skills">
   <h1>Soft Skills</h1>
   <div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_user.softSkills item=softItem}	    	            
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
{if $_user.education}
<div class="req-skills">
	<h1>Education</h1>
    <div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_user.education item=eduItem}			
				<li class="edu-block"><div>{$eduItem.univerName}</div><img src="/images/edu.png" /><span>{$eduItem.degree} in {$eduItem.facultyName}</span></li>                 			
         	{/foreach}
         	</ul>
        </div>        
		<div class="jcarousel-control jcarousel-control-prev"></div>
		<div class="jcarousel-control jcarousel-control-next"></div>
    </div>
    <div class="clear"></div>      
</div>
{/if}
{if $_user.languages}
<div class="req-skills">
	<h1>Languages</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_user.languages item=langItem}	    
	            <li class="lang-block"><span>{$langItem.language}</span><br />
			       <img src="/images/langs/{$langItem.language|strtolower}.png" /><div class="lang-block-div" {if $langItem.level|strlen > 8}style="font-size: 1.4rem;"{/if}>{$langItem.level}</div>
			       <div class="clear"></div></li>
            {/foreach}
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>	
	<div class="clear"></div>      
</div>    
{/if}
{if $_user.profile.bio}
	<div class="block-title-red">some words about me</div>
	<div class="text"><p>{$_user.profile.bio|escape|nl2br}</p></div>	    
{/if}
        
<div class="grid margintop80">
	<div class="grid">
	<a href="#" class="profbutton">hire me</a>
    </div>
    <div class="grid wants-work">
        Wants to work in:
        <br />
         <a class="button prof-bot-links">samsung</a> 
         <a class="button prof-bot-links">apple</a>
         <a class="button prof-bot-links">microsoft</a>
         <a class="button prof-bot-links">htc</a>
         <a class="button prof-bot-links">Fantasy interactive</a>
    </div>
</div>
<div class="clear"></div>      
