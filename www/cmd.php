<?php
// change the following paths if necessary
$yii = dirname(__FILE__).'/../../../usr/local/php5/libs/Yii/framework/yii.php';
$config = dirname(__FILE__).'/../protected/config/console.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);

// include the file of Yii framwork init
require_once($yii);

// create and init console application
Yii::createConsoleApplication($config)->run();
