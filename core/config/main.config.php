<?php

define('ROOT_DIR',     getcwd());
define('PATH_DIR',     ROOT_DIR . '/core/');
define('LIB_DIR',      PATH_DIR . '/lib/');
define('MEDIA_DIR',    ROOT_DIR . '/media/');
define('LOG_DIR',      MEDIA_DIR . '/logs/');
define('TEMP_DIR',     MEDIA_DIR . '/temp_files/');
define('CORE_DIR',     LIB_DIR . '/modules/core/');
define('FRONTEND_DIR', LIB_DIR . '/modules/frontend/');
define('FB_SDK_DIR',   LIB_DIR . '/facebook/src/');
define('LI_SDK_DIR',   LIB_DIR . '/LinkedIn/');

//Database access
define('BASE_DB_HOST', '127.0.0.1');
define('BASE_DB_USER', 'root');
define('BASE_DB_PASS', 'root');
define('BASE_DB_DB',   'hr_new');

?>