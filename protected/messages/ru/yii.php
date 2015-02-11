<?php 
//Core Message translation
$myown = array(


);




$coreMessageFile = Yii::getFrameworkPath().DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.basename(dirname(__FILE__),'php').DIRECTORY_SEPARATOR.'yii.php';
if(is_file($coreMessageFile)){
	return CMap::mergeArray( require($coreMessageFile), $myown );
}else{
	return $myown;
}