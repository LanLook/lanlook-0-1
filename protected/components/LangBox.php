<?php
class LangBox extends CWidget
{

    public function run()
    {
        $currentLang = Yii::app()->language;
        $lang_arr = array('en'=>'English');
        $c = new CDbCriteria();
        $c->condition = 'lng_status>:stat';
        $c->params = array(':stat'=>1);
        $langs = Languages::model()->findAll($c);
        
        if (is_array($langs) && count($langs)>0)
        {
	        foreach ($langs as $lng)
	        	$lang_arr[$lng->lng_code] = $lng->lng_name;
        }
        $this->render('langBox', array('currentLang' => $currentLang, 'languages'=>$lang_arr));
    }
}
?>