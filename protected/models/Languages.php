<?php

/**
 * This is the model class for table "languages".
 *
 * The followings are the available columns in table 'languages':
 * @property integer $lng_id
 * @property string $lng_code
 * @property string $lng_name
 * @property string $created_date
 * @property string $modified_date
 * @property integer $lng_status
 *
 * The followings are the available model relations:
 * @property CategoryDetails[] $categoryDetails
 * @property MenuDetails[] $menuDetails
 * @property ProductDetails[] $productDetails
 */
class Languages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Languages the static model class
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
		return 'languages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lng_code', 'required'),
			array('lng_status', 'numerical', 'integerOnly'=>true),
			array('lng_code', 'length', 'max'=>7),
			array('lng_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lng_id, lng_code, lng_name, created_date, modified_date, lng_status', 'safe', 'on'=>'search'),
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
			'categoryDetails' => array(self::HAS_MANY, 'CategoryDetails', 'lng_id'),
			'menuDetails' => array(self::HAS_MANY, 'MenuDetails', 'lng_id'),
			'productDetails' => array(self::HAS_MANY, 'ProductDetails', 'lng_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lng_id' => 'Lng',
			'lng_code' => 'Lng Code',
			'lng_name' => 'Lng Name',
			'created_date' => 'Created Date',
			'modified_date' => 'Modified Date',
			'lng_status' => 'Lng Status',
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

		$criteria->compare('lng_id',$this->lng_id);
		$criteria->compare('lng_code',$this->lng_code,true);
		$criteria->compare('lng_name',$this->lng_name,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('lng_status',$this->lng_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}