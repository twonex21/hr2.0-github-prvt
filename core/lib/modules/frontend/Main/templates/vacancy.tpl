<div class="vac-wrapper">
	<h2>{$_vacancy.info.title}</h2>
	<time class="deadline">Deadline: <strong>{$_vacancy.info.deadline}</strong></time>
    <a href="/company/profile/cid/{$_vacancy.info.companyIdHash}/t/" class="vac-logo">
    	{if $_vacancy.info.logoKey}<img src="/support/resizeimage/tp/2/s/3/dim/h/gs/1/key/{$_vacancy.info.logoKey}/t/">{/if}
    </a>
    <p class="vac-info">{$_vacancy.info.additionalInfo|escape|nl2br|truncate:300:"...":false}</p>
    <div class="vac-skills">
    {foreach from=$_vacancy.skills item=skill}
		<div class="skill">
	    	<div class="skill-name"><span {if $skill.name|strlen > 12}style="font-size:1.2rem;"{/if}>{$skill.name|truncate:20:".."}</span></div>
	        	<div style="height:110px;">
					<object type="image/svg+xml" data="/images/large-circle.svg?v=3" width="111" height="111">								
						<param name="matching" value="{$skill.map.score}">																				
						<param name="text" value="{$skill.map.text}">																				
					</object>
				</div>
			<div class="skill-years">{$skill.years} years</div>
		</div>
	{/foreach}                    	
    </div>
	<div class="clear"></div>
	<a href="/vacancy/view/vid/{$_vacancy.info.vacancyId}/t/" class="button large">Show full information</a>
</div>