<?php 
//Core Message translation
$myown = array(
  '&lt; Previous' => '&lt; Precedente',
  '&lt;&lt; First' => '&lt;&lt; Primero',
  'Go to page: ' => 'Ir a la página: ',
  'Last &gt;&gt;' => 'Última &gt;&gt;',
  'Next &gt;' => 'Siguiente &gt;',

);




$coreMessageFile = Yii::getFrameworkPath().DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.basename(dirname(__FILE__),'php').DIRECTORY_SEPARATOR.'yii.php';
if(is_file($coreMessageFile)){
	return CMap::mergeArray( require($coreMessageFile), $myown );
}else{
	return $myown;
}