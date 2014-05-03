{literal}
<script>
	addCssFile('account');

	$(function() {		
		$('.tab-switch a:first').trigger('click');	
	});	
</script>
{/literal}

<div class="loaded">
	<section class="create-account-sec">
		<div class="create-account-header">
			<div class="account-title">Welcome to HR.am!</div>
	        <div class="account-title-sub"><span>Already have an account?</span>&nbsp;&nbsp;<a href="#">CLICK HERE TO LOG IN</a></div>
		</div>
	    <div class="create-account-body">
	    	<div class="tab-switch">
	        	<a class="sel" title="user">USER</a>
	            <a title="company">COMPANY</a>
	        </div>
			<div class="tab"></div>        
	    </div>
	</section>
</div>