<?php

/**
 * This is the model class for table "menu_structure".
 *
 * The followings are the available columns in table 'menu_structure':
 * @property integer $menu_id
 * @property string $menu_action
 * @property integer $parent_id
 * @property integer $menu_order
 * @property string $created_date
 * @property integer $menu_status
 *
 * The followings are the available model relations:
 * @property MenuDetails[] $menuDetails
 */
class MenuStructure extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuStructure the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_date', 'required'),
			array('parent_id, menu_order, menu_status', 'numerical', 'integerOnly'=>true),
			array('menu_action', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('menu_id, menu_action, parent_id, menu_order, created_date, menu_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menuDetails' => array(self::HAS_MANY, 'MenuDetails', 'menu_id'),
			'getparent' => array(self::BELONGS_TO, 'MenuStructure', 'parent_id'),
			'childs' => array(self::HAS_MANY, 'MenuStructure', 'parent_id', 'order'=>'menu_order ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'menu_id' => 'Menu',
			'menu_action' => 'Menu Action',
			'parent_id' => 'Parent',
			'menu_order' => 'Menu Order',
			'created_date' => 'Created Date',
			'menu_status' => 'Menu Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('menu_action',$this->menu_action,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('menu_order',$this->menu_order);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('menu_status',$this->menu_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Recursive method for getting hierarchy data from database.
	 * Please use this method after calling ->model()->find*() construction.
	 * Used for getting multidimensional item structure, Call as ->model()->find()->getStructure($lng).
	 * @param string lng code
	 * @return array of item's structure 
	 */
	public function getStructure($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;
		
		$subitems = array();
		$returnarray = array();
		
		if($this->childs)
		{
			foreach($this->childs as $child) {
				if ($child->menu_status > 1)
					$subitems[] = $child->getStructure($lng);
			}
		}
		
		$c = new CDbCriteria();
		$c->together = true;
		$c->with = array('lng', 'menu');
		$c->condition = 't.menu_id=:menuID and lng.lng_code=:lng and menu.menu_status>:stat';
		$c->params = array(':menuID'=>$this->menu_id, ':lng'=>$lng, ':stat'=>1);
		$menu = MenuDetails::model()->find($c);
	
		if (is_object($menu) && count($menu)>0)
			$returnarray = array('label' => $menu->menu_name, 'url' => array('site/routing', 'pid' => $this->menu_id), 'status'=>$menu->menu->menu_status);
	
		if($subitems != array())
			$returnarray = array_merge($returnarray, array('items' => $subitems));
	
		return $returnarray;
	}
	
	/**
	 * Recursive method for getting path from selected item up to main item.
	 * 
	 * @param string lng code
	 * @return array of selected item's path
	 */
	private function _getDepthPath($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;
		
		$parents = array();
		$returnarray = array();
		
		if ($this->getparent)
		{
// 			if ($this->getparent->menu_status > 1)
				$parents = $this->getparent->_getDepthPath($lng);
// 			else
// 				return false;
		}
		
		$c = new CDbCriteria();
		$c->together = true;
		$c->with = array('lng', 'menu');
		$c->condition = 't.menu_id=:menuID and lng.lng_code=:lng';
		$c->params = array(':menuID'=>$this->menu_id, ':lng'=>$lng);
		$menu = MenuDetails::model()->find($c);
	
		if (is_object($menu) && count($menu)>0)
			$returnarray[$menu->menu_name] = array('site/routing', 'pid' => $this->menu_id, 'seo_slag'=>$menu->seo_slag, 'page_title'=>$menu->page_title, 'keywords'=>$menu->keywords, 'description'=>$menu->description, 'status'=>$menu->menu->menu_status);
	
		if($parents != array())
			$returnarray = array_merge($parents, $returnarray);
	
		return $returnarray;
	}

	/**
	 * Recursive method for getting path from selected item up to main item.
	 * Please use this method after calling ->model()->find*() construction.
	 * Without attributes return for breadcrumbs. Call as ->model()->find()->getDepthPath($lng).
	 * @param bool $is_full if there is need to get all data
	 * @param string $lng language code (xx)
	 */
	public function getDepthPath($is_full=false, $lng='')
	{
		$path_arr = $this->_getDepthPath($lng);
		if ($is_full) return $path_arr;
		$res = array();
		foreach ($path_arr as $name=>$details)
		{
			if ($details['status']>1)
			{
				unset($details['seo_slag']);
				unset($details['page_title']);
				unset($details['keywords']);
				unset($details['description']);
				unset($details['status']);
				$res[$name] = $details;
			} else {
				$res = false;
				break;
			}
		}
		return $res;
	}
}