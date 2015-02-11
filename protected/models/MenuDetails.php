<?php

/**
 * This is the model class for table "menu_details".
 *
 * The followings are the available columns in table 'menu_details':
 * @property integer $id
 * @property integer $menu_id
 * @property integer $lng_id
 * @property string $menu_name
 * @property string $page_content
 * @property string $seo_slag
 * @property string $page_title
 * @property string $keywords
 * @property string $description
 * @property string $modified_date
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property Languages $lng
 * @property MenuStructure $menu
 * @property Users $user
 */
class MenuDetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuDetails the static model class
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
		return 'menu_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_id, lng_id, seo_slag, page_title, keywords, description, modified_date', 'required'),
			array('menu_id, lng_id, user_id', 'numerical', 'integerOnly'=>true),
			array('menu_name', 'length', 'max'=>255),
			array('seo_slag, keywords, description', 'length', 'max'=>200),
			array('page_title', 'length', 'max'=>100),
			array('page_content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, menu_id, lng_id, menu_name, page_content, seo_slag, page_title, keywords, description, modified_date, user_id', 'safe', 'on'=>'search'),
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
			'lng' => array(self::BELONGS_TO, 'Languages', 'lng_id'),
			'menu' => array(self::BELONGS_TO, 'MenuStructure', 'menu_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'menu_id' => 'Menu',
			'lng_id' => 'Lng',
			'menu_name' => 'Menu Name',
			'page_content' => 'Page Content',
			'seo_slag' => 'Seo Slag',
			'page_title' => 'Page Title',
			'keywords' => 'Keywords',
			'description' => 'Description',
			'modified_date' => 'Modified Date',
			'user_id' => 'User',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('lng_id',$this->lng_id);
		$criteria->compare('menu_name',$this->menu_name,true);
		$criteria->compare('page_content',$this->page_content,true);
		$criteria->compare('seo_slag',$this->seo_slag,true);
		$criteria->compare('page_title',$this->page_title,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}