{literal}
<script>
	addCssFile('glDatePicker.flatwhite.css');
	addScriptFile('ajax.js', true);
</script>
{/literal}

<h1 class="page-title"><span>Company page creaton</span></h1>
    	<div class="block-title block-title-nobgr">General information</div>
       	<form name="hrfrom" class="hr-form" action="file:///C:/Users/aaa/Desktop/hr_layout_1.0/company_page_creaton.html#" method="post" enctype="multipart/form-data">
           
            <div class="input-group-border padding28">
               	
                <fieldset class="grid grid230">
                  <section class="filefield"> 
                    <div id="messages"></div>
                    <div id="filedrag" class="grid" style="display: block;"></div>
                    <div id="fileaddbtn" class="fileaddbtn">Add logo</div>
                    <input type="file" id="upload_file" name="upload-file" class="fileselect">
                    {literal}
                    <script language="javascript">
                    	document.querySelector('#fileaddbtn').addEventListener('click', function(e) {
						  var fileInput = document.querySelector('#fileselect');
						  fileInput.click();
						}, false);
                    </script>
                    {/literal}
                  </section>   
                </fieldset>
                
               
                <fieldset class="grid grid670">
                    <section>
                        <label for="comp_title" class="input marginbottom25"><span>Title</span>
                            <input id="comp_title" name="comp_title" type="text" value="">
                            <b class="tooltip tooltip-bottom-left">Company officially registered name</b>
                        </label>
                        <label for="comp_ad_info" class="textarea "><span>Some additional information</span>
                            <textarea id="comp_ad_info" name="comp_ad_info"></textarea>
                            <b class="tooltip tooltip-bottom-left">About company</b>
                        </label>
                    </section>
                </fieldset>

	            <div class="clear"></div>
            </div>
            
                        
            <!-- START slider-->
            
            <!-- END slider-->
            
            <!--START Offices, Experience, subscription-->
            <div>
            	<div class="grid grid290 marginright40">
                   	<div class="block-title block-title-nobgr">Offices</div>
                                                
                                                
					<fieldset class="grid grid290">
                        <section>                         
                            <label class="select">
                                <select name="comp_offices[]">
                                    <option value="">Select the town</option>
                                    <option value="New York, United States">New York, United States</option>
                                    <option value="Yerevan, Armenia">Yerevan, Armenia</option>
                                    <option value="Barselona, Spain" selected="">Barselona, Spain</option>
								</select>
                                <i></i>
                            </label>
     					</section>
	                </fieldset>                            
					
       	            <div class="clear"></div>
					<div id="addlocation-btn" class="form-add-thin-btn">Add location</div>
                </div>
                
                
                <div class="grid grid290 marginright40">
                   	<div class="block-title block-title-nobgr">Experience</div>
                    <fieldset class="grid grid290">
                        <section>
                            <label for="comp_email" class="input marginbottom25">Email to contact
                                <input id="comp_email" name="comp_email" type="text" value="" placeholder="email@company.com">
                                <b class="tooltip tooltip-bottom-left">Company email address</b>
                            </label>
                            <label for="comp_linked" class="input marginbottom25">Linked in link
                                <input id="comp_linked" name="comp_linked" type="text" value="" placeholder="http://linkedin.com">
                                <b class="tooltip tooltip-bottom-left">Company Linked in link</b>
                            </label>
                            <label for="comp_face" class="input marginbottom25">Facebook link
                                <input id="comp_face" name="comp_face" type="text" value="" placeholder="http://facebook.com">
                                <b class="tooltip tooltip-bottom-left">Company facebook link</b>
                            </label>
                            <label for="comp_twitter" class="input marginbottom25">twitter link
                                <input id="comp_twitter" name="comp_twitter" type="text" value="" placeholder="http://twitter.com">
                                <b class="tooltip tooltip-bottom-left">Company twitter link</b>
                            </label>                        
                        </section>
	                </fieldset>
                </div>
                
                
                <div class="grid grid300">
                   	<div class="block-title block-title-nobgr">subscription</div>
                    <fieldset class="grid grid300 marginbottom25 margintop20 paddingright40">
                       <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Allow users to subscribe for new vacancies</label>
                    </fieldset>
                    <fieldset class="grid grid300 marginbottom25 paddingright40">
                        <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Allow users to subscribe for news</label>
                    </fieldset>
                </div>   

	            <div class="clear"></div>
            </div>
            <!--END Offices, Experience, subscription-->

			<!-- START Company statistics-->
            <div>
               	<div class="block-title block-title-nobgr">Company statistics</div>
            	<div class="grid grid290 marginright40">
                    <fieldset class="grid grid290">
                        <section>
                            <label class="select">amount of emploees
                                <select name="comp_emp_count">
                                    <option value="">Select emploees count</option>
                                    <option value="10+" selected="">10+</option>
                                    <option value="100+">100+</option>
                                    <option value="1,000+">1,000+</option>
                                    <option value="10,000+">10,000+</option>
                                    <option value="100,000+">100,000+</option>
                                    <option value="200,000+">200,000+</option>
								</select>
                                <i></i>
                            </label>
     					</section>
	                </fieldset> 
                </div>  
                <div class="grid grid290 marginright40">
                    <fieldset class="grid grid300 marginbottom25 margintop30 paddingright40">
                       <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Show amount of views of the company page</label>
                    </fieldset>
                </div>
                <div class="grid grid300">
                    <fieldset class="grid grid300 marginbottom25 margintop30 paddingright40">
                       <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>show amount users who applied for the positions of the company</label>
                    </fieldset>
                </div>                
   	            <div class="clear"></div>
            </div>
            <!-- END Company statistics-->

			<!-- START Benefits-->
            <div>
               	<div class="block-title block-title-nobgr">Benefits</div>
            	<div class="grid">
                    <fieldset class="benefitsitem grid grid290">
                        <section>
                            <label class="select">
                                <select name="comp_benefits">
                                    <option value="business trips" selected="">business trips</option>
                                    <option value="insurance">insurance</option>
								</select>
                                <i></i>
                            </label>
     					</section>
	                </fieldset> 
                    
   					<fieldset class="benefitsitem grid grid290">
                        <section>
                            <label class="select">
                                <select name="comp_benefits">
                                    <option value="business trips" selected="">business trips</option>
                                    <option value="insurance">insurance</option>
								</select>
                                <i></i>
                            </label>
     					</section>
	                </fieldset><fieldset class="benefitsitem grid grid290">
                        <section>
                            <label class="select">
                                <select name="comp_benefits">
                                    <option value="business trips" selected="">business trips</option>
                                    <option value="insurance">insurance</option>
								</select>
                                <i></i>
                            </label>
     					</section>
	                </fieldset><div id="add-benefit-btn" class="form-add-thin-btn grid grid290">Add benefit</div>

                </div>  
   	            <div class="clear"></div>
            </div>
            <!-- END Benefits-->                    

            
            <div>
                <div style="padding:60px 0 150px 0"><a href="javascript:document.forms.hrfrom.submit()" class="profbutton sbmbutton" style="width:100%; display:block">save and post company page</a></div>
            </div>
            
            
    	</form>