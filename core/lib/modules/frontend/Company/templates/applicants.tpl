{literal}
<script>
	$(function() {		
		showPage(1);	
	});
</script>
{/literal}

<div class="home-block-title"> Applied Jobs    
  </div>
  <table cellpadding="3" cellspacing="0" border="0" width="100%" id="appl-table" class="tablesorter">
    <thead>
		<tr>
	        <th class="header" style="width:300px"><span>Vacancy</span></th>
	        <th class="header" style="width:220px"><span>Applicant</span></th>
	        <th class="header"><span>Matching</span></th>
	        <th class="header"><span>Date Applied</span></th>	        	        
		</tr>
    <thead>
    </thead>
    <tbody>
    	{foreach from=$_applicants item=applicant}      	 
		<tr>
	        <td><a href="/vacancy/view/vid/{$applicant.vacancyId}/t/" title="{$applicant.vacancyTitle}">{$applicant.vacancyTitle|truncate:30:"..":false}</a></td>
	        <td><a href="/user/profile/uid/{$applicant.idHash}/t/">{$applicant.fullName}</a></td>
	        <td>
	        	<div style="display:none;">{$applicant.matching.total}</div>
	        	<div class="circle">
					<object type="image/svg+xml" data="/images/small-circle.svg" width="55" height="55">								
						<param name="matching" value="{$applicant.matching.total}">								
					</object>
				</div>
	        </td>
	        <td>
	        	<div>{$applicant.appliedAt}</div>
	        </td>
	        {*<td><span {if $applicant.vacancyStatus == "ACTIVE"}class="green"{else}class="red"{/if}>{$applicant.vacancyStatus}</span></td>*}	        	       
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