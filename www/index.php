<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../../usr/local/php5/libs/Yii/framework/yii.php';
$config=dirname(__FILE__).'/../protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// mentioned constant define Yii error handling availability
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER',true);

// mentioned constant define Yii exception handling availability
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER',true);

require_once($yii);
Yii::createWebApplication($config)->run();