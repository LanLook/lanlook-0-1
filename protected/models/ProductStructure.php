<?php

/**
 * This is the model class for table "product_structure".
 *
 * The followings are the available columns in table 'product_structure':
 * @property integer $product_id
 * @property integer $category_id
 * @property integer $quantity
 * @property integer $priority
 * @property string $created_date
 * @property string $expire_date
 * @property integer $product_status
 *
 * The followings are the available model relations:
 * @property ProductDetails[] $productDetails
 * @property ProductFiles[] $productFiles
 * @property CategoryStructure $category
 */
class ProductStructure extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductStructure the static model class
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
		return 'product_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, quantity, created_date', 'required'),
			array('category_id, quantity, priority, product_status', 'numerical', 'integerOnly'=>true),
			array('expire_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, category_id, quantity, priority, created_date, expire_date, product_status', 'safe', 'on'=>'search'),
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
			'productDetails' => array(self::HAS_MANY, 'ProductDetails', 'product_id'),
			'productFiles' => array(self::HAS_MANY, 'ProductFiles', 'product_id'),
			'category' => array(self::BELONGS_TO, 'CategoryStructure', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'category_id' => 'Category',
			'quantity' => 'Quantity',
			'priority' => 'Priority',
			'created_date' => 'Created Date',
			'expire_date' => 'Expire Date',
			'product_status' => 'Product Status',
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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('product_status',$this->product_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}