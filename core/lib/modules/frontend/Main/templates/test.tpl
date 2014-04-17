{literal}
<script>
// Here goes our internal js code
</script>
{/literal}

{* This is smarty comment *}
{foreach from=$_data item=user}
	{if $user.tid == 1}
		<h1>{$_HR_SESSION[$smarty.const.TEST]}</h1>
		<strong>{$user.name|truncate:10:"...":true}</strong><br />
		<i>Encoded number is: {$_encodedNumber}</i>
	{/if}
{/foreach}
