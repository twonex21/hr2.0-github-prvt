{literal}
<script>	
	addCssFile('mCustomScrollbar');
	loadScript('jquery.mCustomScrollbar.min', 'initSearchScroller');	
</script>
{/literal}

<div class="loaded">
	{if !$_searchResults}
		<div class="no-result" style="height:80px;">No result found. Try another keyword please.</div>
	{else}
		{foreach from=$_searchResults item=vacancy}
		<a class="search-item" href="/vacancy/view/vid/{$vacancy.vacancyId}/t/">
			<div class="grid grid400">
                <h3>{$vacancy.title}</h3>
            	<p>{$vacancy.companyName}</p>
            </div>
			<div class="grid grid550">
			{if !$_HR_SESSION.USER}
                <p class="login-txt" style="width:400px;">You have to log in to see how your skills match this vacancy</p>
            {* TODO: Get profile completed status and place here in the place of the bullshit below *}
			{elseif $vacancy.matching.skills == 0}
				<p class="login-txt" style="width:400px;">You have to complete your profile to see how your skills match this vacancy</p>
            {else}
                <p class="grid" style="width:70px">
                	<object type="image/svg+xml" data="/images/small-circle.svg" width="55" height="55">
						<param name="matching" value="{$vacancy.matching.total}">													
					</object>
                </p>
                <h3><strong>{$vacancy.matching.skillsLevel.level}</strong> matching skills</h3>
            	<p>{$vacancy.matching.skillsLevel.text}</p>
            {/if}                
    		</div> 
        </a>
		{/foreach}
    {/if}
</div>