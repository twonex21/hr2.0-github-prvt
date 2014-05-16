{literal}
<script>
	addCssFile('account');

	$(function() {		
		initPopup();

		$('.face').click(function() {
			var fbUrl = $(this).attr('data-url');			
			var fbWindow = window.open(fbUrl, 'fbWindow', 'width=600, height=420');
			fbWindow.addEventListener("beforeunload", function(e) {				
			   	
			}, false);
		});


		
	});	
</script>
{/literal}

<div class="loaded">
	<section class="create-account-sec">
		<div class="create-account-header">
			<div class="account-title">Welcome to HR.am!</div>	        
			<div class="account-title-sub"><span>Don't have account yet?</span>&nbsp;&nbsp;<a href="/auth/signup/" class="popup">CREATE NEW ACCOUNT</a></div>
		</div>
	    <div class="signin-body">
	    	 <div class="social-login">
			    <div class="account-block-title">Connect your social account</div>
			    <a class="button linkedin" href="#">LOGIN VIA linkedin</a>
			    <a class="button face" href="#" data-url="{$_fbUrl}">LOGIN VIA facebook</a>
				<a class="button twitter" href="#">LOGIN VIA twitter</a>                
			</div>
			<div class="login-from">
			    <div class="account-block-title" style="margin-left:20px;">Connect with <a href="#">HR.AM</a> Credentials</div>
			    <div class="error">Incorrect login or password</div>
				<form id="signin-form" class="hr-form" method="POST" action="/auth/signin/">
					<div class="grid">
						<label for="email" class="input label105">E-mail
			            	<input id="si_email" name="email" type="text" value="">
			            </label>			            	        		
					</div>
					<div class="grid">
						<label for="password" class="input label105">Password
			            	<input id="si_password" name="password" type="password" value="">
			        	</label>
			        	<label class="checkbox"><input id="si_remember_me" name="remember-me" type="checkbox"><i style="top:0"></i>Remember me on this computer<br></label>			
					</div>    	
				</form>  
			</div>
			<div class="clear"></div>
			<div class="connect-button">
				<a href="" class="sbutton">CONNECT</a>
			</div>
	    </div>
	</section>
</div>