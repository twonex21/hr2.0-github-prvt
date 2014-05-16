{literal}
<script>
	$(function() {		
		showPage(1);	
	});
</script>
{/literal}

<div class="home-block-title"> vacancy manager    
  </div>
  <table cellpadding="3" cellspacing="0" border="0" width="100%" id="vac-table" class="tablesorter">
    <thead>
		<tr>
	        <th class="header" style="width:300px"><span>Title</span></th>
	        <th class="header"><span>Opened</span></th>
	        <th class="header"><span>Closed</span></th>
	        <th class="header"><span>Deadline</span></th>
	        <th class="header"><span>Status</span></th>
		</tr>
    <thead>
    </thead>
    <tbody>
    	{foreach from=$_vacancies item=vacancy}      	 
		<tr data-id="{$vacancy.vacancyId}">
	        <td><div class="grid">{$vacancy.title}</div><a href="/vacancy/open/vid/{$vacancy.vacancyId}/t/"><img src="/images/edit.png" class="grid edit" title="Edit Vacancy"></a></td>
	        <td>{if $vacancy.openedAt}{$vacancy.openedAt}{else}-{/if}</td>
	        <td>{if $vacancy.closedAt}{$vacancy.closedAt}{else}-{/if}</td>
	        <td>{$vacancy.deadlineShort}</td>
	        <td><span {if $vacancy.status == "ACTIVE"}class="green"{else}class="red"{/if}>{$vacancy.status}</span></td>
	        <td class="delete">x</td>
	        </li>
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