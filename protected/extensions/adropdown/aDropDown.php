<?php
class aDropDown extends CWidget
{
	/**
	 * 
	 * @var string
	 */
	public $cssFile = 'style.css';
	
	/**
	 * 
	 * @var array
	 */
	public $items = array();
	

	public function init()
	{
				
		$basedir = dirname(__FILE__). '/assets';
		$baseUrl = Yii::app()->getAssetManager()->publish($basedir);
		
		
		Yii::app()->getClientScript()->registerCSSFile($baseUrl.'/css/'.$this->cssFile)
									 ->registerCss('cssInit', '.qmfv{visibility:visible !important;} .qmfh{visibility:hidden !important;}')
									 ;
									 
		Yii::app()->getClientScript()->registerScript('coreObj', 'var qmad = new Object(); qmad.bvis=""; qmad.bhide="";', CClientScript::POS_HEAD)
									 // Sub Menu Fade Animation 
									 ->registerScript('fade', 'var a=qmad.qm0=new Object();a.fade_in_frames=100;a.fade_out_frames=100;', CClientScript::POS_HEAD)
									 // Item Bullets (CSS - Imageless)
									 ->registerScript('bullets', 'a.ibcss_apply_to = "parent";a.ibcss_main_type = "arrow";a.ibcss_main_direction = "down";a.ibcss_main_size = 5;a.ibcss_main_bg_color = "#bbbbbb";a.ibcss_main_bg_color_hover = "#ffffff";a.ibcss_main_bg_color_active = "#F3F3F3";a.ibcss_main_border_color_active = "#BFBEBE";a.ibcss_main_position_x = -16;a.ibcss_main_position_y = -5;a.ibcss_main_align_x = "right";a.ibcss_main_align_y = "middle";a.ibcss_sub_type = "arrow-v";a.ibcss_sub_direction = "down";a.ibcss_sub_size = 3;a.ibcss_sub_border_color = "#797979";a.ibcss_sub_border_color_hover = "#C72828";a.ibcss_sub_border_color_active = "#AE2323";a.ibcss_sub_position_x = -7;a.ibcss_sub_position_y = -4;a.ibcss_sub_align_x = "left";a.ibcss_sub_align_y = "middle";', CClientScript::POS_HEAD)
									 // Tree Menu
									 ->registerScript('tree', 'a.tree_enabled = true;a.tree_sub_sub_indent = 15;a.tree_hide_focus_box = true;a.tree_auto_collapse = false;a.tree_expand_animation = 2;a.tree_expand_step_size = 8;a.tree_collapse_animation = 3;a.tree_collapse_step_size = 15;', CClientScript::POS_HEAD)
									 // Persistent States With Auto Open Subs Option
									 ->registerScript('pers', 'a.sopen_auto_enabled = true;a.sopen_auto_show_subs = true;', CClientScript::POS_HEAD)
									 ;
		Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/coreAddon.js', CClientScript::POS_END)
									 ->registerScriptFile($baseUrl.'/js/treeMenu.js', CClientScript::POS_END)
									 ->registerScriptFile($baseUrl.'/js/itemBulets.js', CClientScript::POS_END)
									 ->registerScriptFile($baseUrl.'/js/fadeAnim.js', CClientScript::POS_END)
									 ->registerScriptFile($baseUrl.'/js/itemBulets.js', CClientScript::POS_END)
									 ->registerScriptFile($baseUrl.'/js/persist.js', CClientScript::POS_END)
									 // Create Menu Settings: (Menu ID, Is Vertical, Show Timer, Hide Timer, On Click ('all' or 'lev2'), Right to Left, Horizontal Subs, Flush Left, Flush Top)
									 ->registerScript('create', 'qm_create(0,false,0,500,\'all\',false,true,false,false);', CClientScript::POS_END)
		;

	}
	
	public function run()
	{
		echo CHtml::openTag('ul', array('id'=>'qm0', 'class'=>'qmmc'));
		$this->renderMenuRecursive($this->items);
		echo CHtml::closeTag('ul');
		
	}
	
	
	/**
	 * Recursively renders the menu items.
	 * @param array $items the menu items to be rendered recursively
	 */
	protected function renderMenuRecursive($items)
	{
		
		if (!is_array($items) || count($items)==0)
			return false;
		
		foreach($items as $item)
		{
			if($item == array())
				continue;
			
			$options = array();
			
			echo CHtml::openTag('li')."\n";
			
			if(isset($item['items']) && count($item['items'])>0)
			{
				if ($this->isActive($item[url]))
					$options = array('class'=>'qmparent qmactive');
				else
					$options = array('class'=>'qmparent');
				
// 				echo CHtml::link($item['label'], 'javascript:void(0)',  $options);
				echo CHtml::link($item['label'], $this->getOwner()->createUrl($item['url'][0], array('pid'=>$item['url']['pid'])),  $options);
				echo "\n".CHtml::openTag('ul')."\n";
				$this->renderMenuRecursive($item['items']);
				echo CHtml::closeTag('ul')."\n";
			} else {
				echo CHtml::link($item['label'], $this->getOwner()->createUrl($item['url'][0], array('pid'=>$item['url']['pid'])),  $options);
			}
	
			echo CHtml::closeTag('li')."\n";
		}
	}
	
	protected function isActive($url)
	{
		$route = $this->getController()->currentRoute;
		$params = $this->getController()->getActionParams();
		
		if (isset($url[0]) && $url[0]==$route && isset($url['pid']) && $url['pid']==$params['pid'])
		{
			return true;
		} else {
			return false;
		}
	}
}