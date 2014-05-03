<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta property="og:title" content="HR.am - main page" />
		<meta property="og:url" content="http://hr.am/" />
		<meta property="og:description" content="desc" />
		<meta property="og:image" content="http://hr.am/image/image.png" />
		<meta itemprop="image" content="http://hr.am/image/image.png">
		<link rel="shortcut icon"    href="#">
		<link rel="apple-touch-icon" href="#" />
		<link rel="apple-touch-icon" sizes="72x72" href="#" />
		<link rel="apple-touch-icon" sizes="114x114" href="#" />
		<link rel="apple-touch-icon" sizes="144x144" href="#" />
		
		<!-- Stylesheets -->									
		<link rel="stylesheet" href="/css/main.css?v={$smarty.server.VERSION}" />		
		<link rel="stylesheet" href="/css/other.css?v={$smarty.server.VERSION}" />		
		<link rel="stylesheet" href="/css/form.css?v={$smarty.server.VERSION}" />
		<link rel="stylesheet" href="/css/magnific.css?v={$smarty.server.VERSION}" />		
		{if $_HR_STYLESHEETS}
			{foreach from=$_HR_STYLESHEETS item=stylesheet}
			<link rel="stylesheet" href="/css/{$stylesheet}.css?v={$smarty.server.VERSION}" />
			{/foreach}
		{/if}
		
		<!-- JS Includes -->
		<script src="/js/jquery-1.8.1.min.js"></script>			
		<script src="/js/functions.js?v={$smarty.server.VERSION}"></script>	
		<script src="/js/lib.js?v={$smarty.server.VERSION}"></script>
		<script src="/js/main.js?v={$smarty.server.VERSION}"></script>
		{if $_HR_SCRIPTS}
			{foreach from=$_HR_SCRIPTS item=script}
			<script src="/js/{$script}.js?v={$smarty.server.VERSION}"></script>
			{/foreach}
		{/if} 			
		
		<title>{$_PAGE_TITLE}</title>
	</head>	
	<body>
		<!--Start of Header-->
		<header>
			<div id="header-back">
				<div id="header-innner">
					<div class="logo grid grid100">
						<a href="/"><h1> Armenian HR Portal : Jobs in Armenia </h1></a>
					</div>
					<nav class="grid grid400"> 
						<a href="#">Contacts</a> 
						<a href="#">About</a> 
						<a href="#">Blog</a> 
						<a href="#">Expert</a> 
					</nav>
					<div class="grid account">
						<div>
							<a href="/auth/signup/" class="popup">create account</a> 
							<span>or</span> <a href="#">log in</a>
						</div>
					</div>
					<div class="grid searchbtn"></div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="search-input-back">
				<div id="search-input-inner">
					<input id="search-input" value="" maxlength="128"/>
				</div>
			</div>
		</header>
		<!--End of Header-->
		<section class="message {$_HR_MESSAGE.type}" attr-flash="{$_HR_MESSAGE.isFlash}">
			{if $_HR_MESSAGE.text}<span>{$_HR_MESSAGE.text}</span>{/if}
			{if !$_HR_MESSAGE.isFlash}<div class="close"></div>{/if}
		</section>
		<!-- Start of Content -->
		<section class="page-content">	
			{$CONTENT}
		</section>
		<!-- End of Content -->
		
		<!--Start of Footer-->
		<footer>
			<div id="footer-inner">
				<div class="logo grid"></div>
				<div class="footercopy grid">{$smarty.now|date_format:"%Y"}. HR.am. Jobs, Resumes and Careers in Armenia. © All Rights Reserved.</div>
				<div class="grid footermenu"> <a href="#">Facebook</a> <a href="#">Twitter</a> <a href="#">Blog</a> </div>
			</div>
		</footer>
		<!--End of Footer--> 
		
		<!-- Including external js files -->		
	</body>	
</html>