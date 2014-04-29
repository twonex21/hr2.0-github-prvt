{literal}
<script>
	$(function() {		
		$('.tab-switch a').click(function() {
			var userType = $(this).attr('title');

			$(this).addClass('sel').siblings('a').removeClass('sel');
			$('.create-account-body .tab').css('opacity', '0').load('/auth/' + userType + 'signup', function() {
				$(this).attr('id', userType).animate({'opacity' : 1}, 800);
			});
			
			return false;
		});

		$('.tab-switch a:first').trigger('click');
	});	
</script>
{/literal}

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