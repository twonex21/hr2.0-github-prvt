{literal}
<script>
	addCssFile('araks.css');	
	
	$(function() {		
		initCarousel();		
	});

</script>
{/literal}

<div class="grid profile-top-block">
    <img src="{if $_userProfile.pictureKey}/support/resizeimage/tp/1/s/1/key/{$_userProfile.pictureKey}/t/{else}/images/photo.png{/if}" alt="" class="grid user-photo">
    <div class="grid profile-top">
        <div class="grid profile-top-text">{$_userProfile.fullName},&nbsp;<span>{$_userProfile.age}</span></div> 
        <div class="grid profile-top-year">years<br /> old</div>
        <div class="clear"></div>   
        <div class="profile-social">
            <a class="button loc">{$_userProfile.location}</a>
            {if $_userProfile.linkedIn}<a class="button linkedin" href="{$_userProfile.linkedIn}" target="_blank">Show on linkedin</a>{/if}<br />
            <a class="button mail" href="mailto:{$_userProfile.mail}">Send e-mail</a>
            <a class="button share" href="mailto:mail@mail.com">Share this cv</a>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="req-skills-top">
	{foreach from=$_userExperience item=expItem}	
	<div class="profile-data"><h1>Scope of activity: <span>&nbsp;&nbsp;{$expItem.industryName}</span></h1></div>
	<div class="profile-spec"><span>Specialization:</span> {$expItem.specName} </div>
	<div class="profile-exp"><span>Total experience:</span>&nbsp;&nbsp;{$expItem.years} years </div>           
	<div class="separator"></div>
	{/foreach}
</div>

<div class="req-skills">
	<h1>Skills</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_userSkills item=skillItem}	    
	            <li class="skills-block" {if $skillItem.name|strlen > 10}style="font-size: 1.3rem;"{/if}><div>{$skillItem.name}</div><img src="/images/levels/{$skillItem.years}.png" /><span>{$skillItem.years}&nbsp;years</span></li>	            
            {/foreach}
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>
	<div class="clear"></div>      
</div>    
<div class="req-skills">
   <h1>Soft Skills</h1>
   <div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_userSoftSkills item=softItem}	    	            
	            <li class="soft-block"><div {if $softItem.name|strlen > 26}style="font-size: 1.1rem;line-height:18px;"{/if}>{$softItem.name}</div><img src="/images/soft-levels/{$softItem.level|strtolower}.png" /></li>
            {/foreach}
			</ul>
		</div>
		<div class="jcarousel-control jcarousel-control-prev"></div>
	    <div class="jcarousel-control jcarousel-control-next"></div>
    </div>
	<div class="clear"></div>
</div>    
<div class="req-skills">
	<h1>Education</h1>
    <div class="jcarousel-wrapper">
		<div class="jcarousel">
		{foreach from=$_userEducation item=eduItem}
			<ul>
				<li class="edu-block"><div>{$eduItem.univerName}</div><img src="/images/edu.png" /><span>{$eduItem.degree} in {$eduItem.facultyName}</span></li>                 
			</ul>
         {/foreach}
        </div>        
		<div class="jcarousel-control jcarousel-control-prev"></div>
		<div class="jcarousel-control jcarousel-control-next"></div>
    </div>
    <div class="clear"></div>      
</div>
<div class="req-skills">
	<h1>Languages</h1>
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
			{foreach from=$_userLanguages item=langItem}	    
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
{if $_userProfile.bio}
	<div class="block-title-red">some words about me</div>
	<div class="text"><p>{$_userProfile.bio|escape|nl2br}</p></div>	    
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
