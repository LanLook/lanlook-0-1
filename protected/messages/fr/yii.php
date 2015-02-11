<?php 
//Core Message translation
$myown = array(
  '&lt; Previous' => '&lt; Précédent',
  '&lt;&lt; First' => '&lt;&lt; Premier',
  'Go to page: ' => 'Passer vers page: ',
  'Last &gt;&gt;' => 'Dernier &gt;&gt;',
  'Next &gt;' => 'Suivant &gt;',

);



$coreMessageFile = Yii::getFrameworkPath().DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.basename(dirname(__FILE__),'php').DIRECTORY_SEPARATOR.'yii.php';
if(is_file($coreMessageFile)){
	return CMap::mergeArray( require($coreMessageFile), $myown );
}else{
	return $myown;
}