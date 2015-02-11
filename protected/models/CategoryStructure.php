<?php

/**
 * This is the model class for table "category_structure".
 *
 * The followings are the available columns in table 'category_structure':
 * @property integer $category_id
 * @property integer $parent_id
 * @property integer $category_order
 * @property string $created_date
 * @property integer $category_status
 *
 * The followings are the available model relations:
 * @property CategoryDetails[] $categoryDetails
 * @property CategoryFiles[] $categoryFiles
 * @property ProductStructure[] $productStructures
 */
class CategoryStructure extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CategoryStructure the static model class
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
		return 'category_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, category_order, category_status', 'required'),
			array('parent_id, category_order, category_status', 'numerical', 'integerOnly'=>true),
			array('created_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('category_id, parent_id, category_order, created_date, category_status', 'safe', 'on'=>'search'),
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
			'categoryDetails' => array(self::HAS_MANY, 'CategoryDetails', 'category_id'),
			'categoryFiles' => array(self::HAS_MANY, 'CategoryFiles', 'category_id'),
			'productStructures' => array(self::HAS_MANY, 'ProductStructure', 'category_id'),
			'getparent' => array(self::BELONGS_TO, 'CategoryStructure', 'parent_id'),
			'childs' => array(self::HAS_MANY, 'CategoryStructure', 'parent_id', 'order'=>'category_order ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'category_id' => 'Category',
			'parent_id' => 'Parent',
			'category_order' => 'Category Order',
			'created_date' => 'Created Date',
			'category_status' => 'Category Status',
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

		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('category_order',$this->category_order);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('category_status',$this->category_status);

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
				if ($child->category_status > 1)
					$subitems[] = $child->getStructure($lng);
			}
		}

		$c = new CDbCriteria();
		$c->together = true;
		$c->with = array('lng', 'category');
		$c->condition = 't.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
		$c->params = array(':catID'=>$this->category_id, ':lng'=>$lng, ':stat'=>1);
		$category = CategoryDetails::model()->find($c);

		if (is_object($category) && count($category)>0) {
			if (isset($category->category->categoryFiles[0]->file_name)) {
				$returnarray = array('label' => $category->category_name, 'url' => array('site/list', 'pid' => $this->category_id), 'pic'=>$category->category->categoryFiles[0]->file_name, 'status'=>$category->category->category_status);
			} else {
				$returnarray = array('label' => $category->category_name, 'url' => array('site/list', 'pid' => $this->category_id), 'status'=>$category->category->category_status);
			}
		}
		if($subitems != array()) {
			$returnarray = array_merge($returnarray, array('items' => $subitems));
		}
		return $returnarray;
	}

	/**
	 * Recursive private method for getting path from selected item up to main item.
	 *
	 * @param string lng code
	 * @return array of selected item's path up to first levele
	 */
	private function _getDepthPath($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;

		$parents = array();
		$returnarray = array();

		if ($this->getparent)
		{
// 			if ($this->getparent->category_status > 0)
				$parents = $this->getparent->_getDepthPath($lng);
// 			else
// 				return false;
		}

		$c = new CDbCriteria();
		$c->together = true;
		$c->with = array('lng', 'category');
		$c->condition = 't.category_id=:catID and lng.lng_code=:lng';
		$c->params = array(':catID'=>$this->category_id, ':lng'=>$lng);
		$category = CategoryDetails::model()->find($c);

		if ($category && is_object($category) && count($category)>0)
			$returnarray[$category->category_name] = array('site/list', 'pid' => $this->category_id, 'seo_slag'=>$category->seo_slag, 'page_title'=>$category->page_title, 'keywords'=>$category->keywords, 'description'=>$category->description, 'status'=>$category->category->category_status);

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
	
	/**	DEPRECATE
	 * Returns array of ids of categories which hasn't child subcategories. 
	 * @param string $lng Language code (en, hy, ru)
	 * @return array
	 */
	public function _getVerginStructure($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;
		$subitems = array();
		$returnarray = array();
		
		if ($this->childs!=array()) {
			foreach ( $this->childs as $child ) {
				$subitems[] = $child->getVerginStructure ( $lng );
			}
		}
		
		//Closure function for getting all parent categories
		$parentCategories = function () {
			
			$all_parents = array();
			$c = new CDbCriteria();
			$c->select = 'parent_id';
			$c->condition = 't.category_status>:stat';
			$c->params = array(':stat'=>1);
			$c->group = 'parent_id';
			$parents = CategoryStructure::model()->findAll($c);
			
			foreach ($parents as $category)
			{
				$all_parents[] = $category->parent_id;
			}
		return $all_parents;
		};

		$c = new CDbCriteria();
		$c->together = true;
		$c->with = array('lng', 'category');
		$c->select = 't.category_id, t.category_name';
		$c->condition = 'category.category_id=:catID and lng.lng_code=:lng and category.category_status>:stat';
		$c->params = array(':catID'=>$this->category_id, ':lng'=>$lng, ':stat'=>1);
		$c->addNotInCondition('category.category_id', $parentCategories());
		$category = CategoryDetails::model()->find($c);
		
		if (is_object($category) && count($category)>0){
			$returnarray[] = json_encode(array($category->category->category_id, $category->category_name));
		}
		
		if($subitems != array()) {
			foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($subitems)) as $k=>$v){
				$returnarray[] = $v;
			}
		}
		return $returnarray;
	}

	/**
	 * Returns array of ids of categories which hasn't child subcategories.
	 * @param string $lng Language code (en, hy, ru)
	 * @return array
	 */
	public function getVerginStructure($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;
		
		$lng_i = 2;
		if($lng=='en'){
			$lng_i = 1;
		} elseif ($lng=='hy') {
			$lng_i = 0;
		} elseif ($lng=='ru') {
			$lng_i = 2;
		}

		$subitems = array();
		$returnarray = array();
	
		if ($this->childs!=array()) {
			foreach ( $this->childs as $child ) {
				$subitems[] = $child->getVerginStructure ( $lng );
			}
		} else {
			if (is_object($this) && count($this)>0){
				$returnarray[] = json_encode(array($this->category_id, $this->categoryDetails[$lng_i]->category_name));
			}
		}
	
		if($subitems != array()) {
			foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($subitems)) as $k=>$v){
				$returnarray[] = $v;
			}
		}
		return $returnarray;
	}
		
	/**
	 * Returns array of all items derived from selected category
	 * @param string $lng Language code (en, hy, ru)
	 * @return array of items
	 */
	public function getOwnItems($lng='')
	{
		$lng = ($lng=='')?Yii::app()->language:$lng;
		$items = array();
		$returnarray = array();
		
		$virginCategories = $this->getVerginStructure($lng);

		foreach ($virginCategories as $cat_str)
		{
			$category = json_decode($cat_str);
			$items = array();
			$c = new CDbCriteria();
			$c->together = true;
			$c->with = array ('lng', 'product', 'product.productFiles');
			$c->condition = 'product.category_id=:catID and lng.lng_code=:lng and product.product_status>:stat';
			$c->params = array (':catID' => CHtml::encode ( trim($category [0]) ), ':lng' => $lng, ':stat' => 1 );
// 			$c->group = 't.product_id';
			$c->order = 'product.priority DESC, t.modified_date DESC';
			
			foreach (ProductDetails::model ()->findAll ( $c ) as $item)
			{
				$items[] = array(	'product_id'=>$item->product_id, 
									'product_name'=>$item->product_name, 
									'product_description'=>$item->full_description, 
									'file_name'=>$item->product->productFiles[1]->file_name, 
									'quantity'=>$item->product->quantity, 
									'priority'=>$item->product->priority );
			}
			
			$returnarray[] = array('category_id'=>trim($category[0]), 'category_name'=>trim($category[1]), 'items'=>$items);
		}
		return $returnarray;
	}
	
	/**
	 * Recursivly convert multidimentional array into PHP standart object
	 * @param array $array
	 * @return stdClass
	 */
	public function makeObjects($array) {
		$obj = new stdClass();
	    foreach ($array as $key => $val) {
	        $obj->$key = is_array($val) ? $this->makeObjects($val) : $val;
	    }
		return $obj;
	}
	
}
