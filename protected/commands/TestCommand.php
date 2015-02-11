<?php
/**
 * This command fetch exchange rates from: AMD from CBA.am and others from webservicesx.com sources.
 * Results are storing in the database table named curencies.
 * For cronJob: php /home/lanlook/www/cmd.php GetRatesFromWebService
 * Local Examlpe: Z:\usr\local\php5\php cmd.php TestCommand
 * @author armos Armen Bablanyan @2012
 *
 */
class TestCommand extends CConsoleCommand
{
	public function run($args)
	{
		if(!isset($args[0]) || $args[0]==''){
			Yii::log('Arguments of Test Command not given', 'warning',  'application.commands.Test');
			echo "\nPlease give aarguments\n";
			return;
		}

		echo "\nPlease wait...\n";
		
		$c = new CDbCriteria ();
		$c->with = array ('lng', 'category' );
		$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
		$c->params = array (':catID' => CHtml::encode ( $args[0] ), ':lng' => Yii::app ()->language, ':stat' => 1 );
		$category = CategoryDetails::model ()->find ( $c );

		echo "\n<pre>\n";
		var_dump($category->category->getOwnItems());
				
	}
	
}