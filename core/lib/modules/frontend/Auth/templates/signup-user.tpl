<div class="social-login">
    <div class="account-block-title">Connect your social account</div>
    <a class="button linkedin" href="#">LOGIN VIA linkedin</a>
    <a class="button face" href="#" data-url="{$_fbUrl}">LOGIN VIA facebook</a>
	<a class="button twitter" href="#">LOGIN VIA twitter</a>                
</div>
<div class="login-from">
    <div class="account-block-title" style="margin-left:20px;">Create an <a href="#">HR.AM</a> Account</div>
	<form id="create-account-form" class="hr-form" method="POST" action="/auth/usersignup/">
		<div class="grid">
			<label for="email" class="input label105">Your email
            	<input id="email" name="email" type="text" value="" attr-validate="notEmpty, isEmailAddress, notAlreadyUsed">
            </label>
            <label for="password" class="input label105">Password
            	<input id="password" name="password" type="password" value="" attr-validate="notEmpty, isPasswordLength">
        	</label>	        		
		</div>
		<div class="grid">
			<label for="firstname" class="input label105">First name
            	<input id="firstname" name="firstname" type="text" value="" attr-validate="notEmpty, isLatin">
            </label>
			<label for="lastname" class="input label105">Last name
            	<input id="lastname" name="lastname" type="text" value="" attr-validate="notEmpty, isLatin">
            </label>
            <label class="checkbox"><input id="has_agreed" name="has-agreed" type="checkbox" attr-validate="hasAgreedWithTerms"><i style="top:0"></i>Agree with terms<br></label>			
		</div>    	
	</form>  
</div>
<div class="clear"></div>
<div class="connect-button">
	<a href="" class="sbutton">SIGN UP</a>
</div>