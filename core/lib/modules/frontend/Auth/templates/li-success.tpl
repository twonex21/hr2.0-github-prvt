{literal}
<style>
@font-face {
    font-family: 'gothammedium';
    src: url('../fonts/gotham-medium-webfont.eot');
    src: url('../fonts/gotham-medium-webfont.eot?#iefix') format('embedded-opentype'),
         url('../fonts/gotham-medium-webfont.woff') format('woff'),
         url('../fonts/gotham-medium-webfont.ttf') format('truetype'),
         url('../fonts/gotham-medium-webfont.svg#gothammedium') format('svg');
    font-weight: normal;
    font-style: normal;
	-webkit-font-smoothing: antialiased;	
}
.msg {font-family: 'gothammedium';width: 95%;margin: 10px auto -20px;padding: 10px 0;text-align: center;font-size: 17px;color: #FFF;-webkit-border-radius:7px;	-moz-border-radius:7px;border-radius:7px;}
.success {background-color: #34AB00;}
.error {background-color: #E22301;}
</style>
{/literal}

{if $_success}
	<div class="msg success">Successful authorization! Redirecting...</div>
{else}
	<div class="msg error">Ooops, there was some error connecting LinkedIn. Please try again later.</div>
{/if}	

{literal}
<script>	
	var success = '{/literal}{$_success}{literal}';
	var url = '{/literal}{$_redirectUrl}{literal}';

	if(success == '1') {
		setTimeout(function() {
			if(url != '') {
				window.opener.location.href = url;
			} else {
				window.opener.location.reload();
			}
			window.close();
		}, 1500);
	}
</script>
{/literal}