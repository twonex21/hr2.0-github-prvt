{literal}
<script>
	addCssFile('mCustomScrollbar');
	// Loading and initializing scroller
	loadScript('jquery.mCustomScrollbar.min', 'initVacancyScroller');
	// Loading and initializing carousel
	loadScript('jquery.jcarousel.min', 'initCarousel');
	// Loading ajax lib and showing first vacancy
	loadScript('ajax', 'loadBriefVacancy', null);	
</script>
{/literal}

<section class="home-vac">
    <div class="home-block-title">recent vacancies</div>
	<div class="home-vac-cont">
		<div class="vac-slider">                
	        <ul class="vac-nav">
	        	{foreach from=$_vacancies item=vacancy name=loop}
        		<li class="vac-item{if $smarty.foreach.loop.index == 0} vac-selected{/if}" id="vac_{$vacancy.vacancyId}" data-id="{$vacancy.vacancyId}">
        			<div class="vac-wrapper">
	        			<h5>{$vacancy.title}</h5>
						<span>{$vacancy.companyName}</span>
						<p class="arrow"></p>
					</div>
        		</li>
				{/foreach}						
			</ul> 
			<div class="vac-content">
			</div>
		</div>
	</div>
</section>
        
<section class="home-vac-count">
	<div class="home-vac-count-inner">
    	<div class="grid vacs-on-top-comp">
        	<p>Vacancies in top companies:</p>
			<h2>{$_topVacanciesCount}</h2>
		</div>
		<div class="grid vacs-on-top-comp-perc">
        	<div class="grid comp-perc-graph">				
				<object type="image/svg+xml" data="/images/middle-circle.svg" width="70" height="70">								
					<param name="matching" value="{$_randomIndustry.percentage}">	
					<param name="color" value="#FFF">							
				</object>                
			</div>
			<div class="grid comp-perc-text">{$_randomIndustry.name} represents {$_randomIndustry.percentage}% of all of the vacancies</div>
		</div>            
	</div>
</section>    
    <!--START top companies-->
    <section class="home-top-comp">
		<div class="home-block-title" style="padding-bottom:30px;">top companies</div>    
			<div class="jcarousel-wrapper">   		  
				<div class="jcarousel">
					<ul>
                		<li class="companies-block">
                		{foreach from=$_topCompanies item=company name=loop}
	                    	<div class="grid topcompitem">
		                    	<a href="#">
		                            <img src="static/companies/1.jpg" alt="{$company.name}"/>
		                            <h3>{$company.name}</h3>
		                            <p>{$company.vacanciesCount} vacancies ({$smarty.foreach.loop.index})</p>
		                        </a>
		                    </div>
		                    {if ($smarty.foreach.loop.index + 1) % 10 == 0}
		                    	</li>
		                    	<li class="companies-block">		                                        		                
		                    {/if}
	                    {/foreach}
						</li>   	                	                	
	                </ul>
            	</div>            	
            	<p class="jcarousel-pagination"></p>
			</div>
    </section>
    <!--END top companies-->