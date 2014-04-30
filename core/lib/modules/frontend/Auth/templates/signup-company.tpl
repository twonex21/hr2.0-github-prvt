<form id="create-account-form" class="hr-form" method="POST" action="/auth/companysignup/">
	<div class="grid" style="">
		<label for="name" class="input label105">Company name
			<input id="name" name="name" type="text" value="" attr-validate="notEmpty">
		</label>
		<label for="email" class="input  label105">Company email
			<input id="email" name="email" type="text" value="" attr-validate="notEmpty, isEmailAddress, notAlreadyUsed">
		</label>
		<label for="password" class="input label105">Password
			<input id="password" name="password" type="password" value="" attr-validate="notEmpty, isPasswordLength">
		</label>		
	</div>
	<div class="grid">
		<label for="phone" class="input label105">Company Phone
			<input id="phone" name="phone" type="text" value="" attr-validate="notEmpty, isPhoneNumber">
		</label>
		<label for="person" class="input label105">Contact Person / Title
        	<input id="person" name="person" type="text" value="" attr-validate="notEmpty, isLatin">
		</label>		
		<label class="checkbox" style="margin-top:30px"><input id="has_agreed" name="has-agreed" type="checkbox" attr-validate="hasAgreedWithTerms"><i style="top:0"></i>Agree with terms<br></label>
	</div>
</form>
<div class="clear"></div>
<div class="connect-button">
	<a href="" class="sbutton">SIGN UP</a>
</div>