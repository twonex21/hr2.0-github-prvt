{literal}
<script>
	$(function() {		
		showPage(1);	
	});
</script>
{/literal}

<div class="home-block-title"> Who wants to work here    
  </div>
  <table cellpadding="3" cellspacing="0" border="0" width="100%" id="work-table" class="tablesorter">
    <thead>
		<tr>
	        <th class="header" style="width:240px"><span>User</span></th>
	        <th class="header" style="width:300px"><span>Closest Vacancy</span></th>
	        <th class="header"><span>Matching</span></th>
	        <th class="header"><span>Date Applied</span></th>	        	        
		</tr>
    <thead>
    </thead>
    <tbody>
    	{foreach from=$_workers item=worker}      	 
		<tr>
	        <td><a href="/user/profile/uid/{$worker.idHash}/t/">{$worker.fullName}</a></td>
	        <td>{if $worker.matching.vacancyTitle}<a href="/vacancy/view/vid/{$worker.matching.vacancyId}/t/" alt="{$worker.matching.vacancyTitle}">{$worker.matching.vacancyTitle|truncate:30:"..":false}</a>{else}No Match{/if}</td>
	        <td>
	        	{if $worker.matching}
	        	<div style="display:none;">{$worker.matching.total}</div>
	        	<div class="circle">
					<object type="image/svg+xml" data="/images/small-circle.svg" width="55" height="55">								
						<param name="matching" value="{$worker.matching.total}">								
					</object>
				</div>
				{else}
				No Match
				{/if}
	        </td>
	        <td>
	        	<div>{$worker.appliedAt}</div>
	        </td>	        	        	      
		</tr>
		{/foreach}		
	</tbody>	
</table>

{if $_pagesCount > 1}
<div id="pager" class="pager">
	{section start=1 loop=$_pagesCount+1 name=pages}
		<a href="" data-page="{$smarty.section.pages.index}" {if $smarty.section.pages.index == 1}class="active"{/if}>{$smarty.section.pages.index}</a>	    
	{/section}		
</div>
{/if}