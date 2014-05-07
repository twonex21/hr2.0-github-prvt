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
		{foreach from=$_searchResults item=user}
		<a class="search-item" href="/user/profile/uid/{$user.idHash}/t/">
			<div class="grid grid400">
				<p class="grid user-picture">
					<img src="{if $user.pictureKey}/support/resizeimage/tp/1/s/3/sq/1/key/{$user.pictureKey}/t/{else}/images/photo.png{/if}">
				</p>
                <h3>{$user.name}</h3>
            	<p>{$user.experience}</p>
            </div>
			<div class="grid grid550">
				{if $user.matching && $user.matching.total > 5}			
                <p class="grid" style="width:70px">
                	<object type="image/svg+xml" data="/images/small-circle.svg" width="55" height="55">
						<param name="matching" value="{$user.matching.total}">													
					</object>
                </p>
                <h3><strong>{$user.matching.skillsLevel.level}</strong> matching skills</h3>
                <p>with your vacancy '{$user.matching.vacancy}'</p>
                {else}
                <p class="login-txt" style="width:400px;padding-top:15px;">No matching with any of your vacancies</p>
                {/if}            	                           
    		</div> 
        </a>
		{/foreach}
    {/if}
</div>