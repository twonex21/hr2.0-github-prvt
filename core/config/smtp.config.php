<?php

//Global Smtp config

//SMTP mail server host
define('BASE_SMTP_HOST','localhost');

//define is authentication enabled for this smtp server or not
define('BASE_SMTP_AUTH','false');

//username for authenticating to mail server, in case of BASE_SMTP_USERNAME set to true
define('BASE_SMTP_USERNAME','');

//password for authenticating to mail server, in case of BASE_SMTP_PASSWORD set to true
define('BASE_SMTP_PASSWORD','');

//from address for notification emails
define('BASE_SMTP_FROM_ADDRESS','info@rec19.com');

//from name to display for notification email
define('BASE_SMTP_FROM_NAME','Favecast Local');

?>