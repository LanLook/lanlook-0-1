<?php
Yii::import('zii.widgets.CMenu');

class simpleMenu extends CMenu
{
	/**
     * to make menu vertical
     * @var bool
     */
	public $vertical = false;
	
	/**
	 * to make menu right to left vertical, just will be considered if $vertical set to true
	 * @var bool
	 */
	public $rtl = false;
	
	/**
	 * to make menu upward
	 * @var bool
	 */
	public $upward = false;
	
	public $firstItemCssClass = 'first';
	public $lastItemCssClass = 'last';
	public $dirCssClass = 'dir';
	
	/**
	 * to use a provided theme
	 * must be one of adobe, flikr, lwis, mtv, nvidia, vimeo and default
	 * @var string
	 */
	public $theme = 'default';
	
	public function init()
	{
		$class=array('dropdown');
        $cssFile='dropdown.css';
        
     	if($this->vertical) {
     		
            $class[] = 'dropdown-vertical';
            
            if($this->rtl){
                $class[] = 'dropdown-vertical-rtl';
//              $cssFile = 'dropdown.vertical.rtl.css';
            }
            else{
//             $cssFile = 'dropdown.vertical.css';
            }
        }
        elseif($this->upward){
            $class[] = 'dropdown-upward';
//          $cssFile = 'dropdown.upward.css';
        }
        else{
            $class[] = 'dropdown-horizontal';
//          $cssFile = 'dropdown.vertical.css';
        }

        $this->htmlOptions['class']=implode(' ', $class);
                        
        $basedir = dirname(__FILE__). '/assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($basedir);

        Yii::app()->getClientScript()->registerCSSFile($baseUrl.'/css/'.$cssFile, 'screen')
                                     ->registerCSSFile($baseUrl.'/css/'.$this->theme.'/'.$this->theme.'.ultimate.css', 'screen')
        ;
                                     
		//ToDo: these should added just for IE7, i don't know how to do this
        if (strtolower(Yii::app()->browser->getBrowser())=='internet explorer' && Yii::app()->browser->getVersion()<7.0)
		{
			Yii::app()->getClientScript()->registerCoreScript('jquery')
									 	 ->registerScriptFile($baseUrl.'/js/jquery.dropdown.js');
		}
		
		parent::init();
	}
	
	/**
     * Recursively renders the menu items.
     * @param array $items the menu items to be rendered recursively
     */
    protected function renderMenuRecursive($items)
    {
        $count=0;
        $n=count($items);
        foreach($items as $item)
        {
            if($item == array())
                continue;
            $count++;
            $options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
            $class=array();          
            
            if(isset($item['items']) && count($item['items']))
            {
            	$class[]=$this->dirCssClass;
            }
 			
 			if($class!==array())
 			{
 				if(empty($options['class']))
 					$options['class']=implode(' ',$class);
 				else
 					$options['class'].=' '.implode(' ',$class);
 			}else{
 				$options = array();
 			}
 			
 			echo CHtml::openTag('li')."\n";
 			$item['linkOptions'] = $options;

            $menu=$this->renderMenuItem($item);
            
            if(isset($this->itemTemplate) || isset($item['template']))
            {
                $template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
                echo strtr($template,array('{menu}'=>$menu));
            } else {
                echo $menu;
            }

            if(isset($item['items']) && count($item['items']))
            {
                echo "\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
                $this->renderMenuRecursive($item['items']);
                echo CHtml::closeTag('ul')."\n";
            }

            echo CHtml::closeTag('li')."\n";
        }
    }
}