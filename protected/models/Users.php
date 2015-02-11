<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $fname
 * @property string $lname
 * @property integer $country_id
 * @property integer $state_id
 * @property string $city
 * @property string $phone_number
 * @property string $address
 * @property string $creation_date
 * @property string $activation_date
 * @property string $deactivation_date
 * @property string $activation_code
 * @property integer $rule_id
 * @property integer $user_status
 *
 * The followings are the available model relations:
 * @property ActionHistory[] $actionHistories
 * @property CategoryDetails[] $categoryDetails
 * @property MenuDetails[] $menuDetails
 * @property ProductDetails[] $productDetails
 * @property UserFiles[] $userFiles
 * @property Rules $rule
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, country_id, state_id, rule_id, user_status', 'required'),
			array('country_id, state_id, rule_id, user_status', 'numerical', 'integerOnly'=>true),
			array('username, password', 'length', 'max'=>32),
			array('email', 'length', 'max'=>100),
			array('fname, lname, city, address', 'length', 'max'=>150),
			array('phone_number, activation_code', 'length', 'max'=>10),
			array('creation_date, activation_date, deactivation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, username, password, email, fname, lname, country_id, state_id, city, phone_number, address, creation_date, activation_date, deactivation_date, activation_code, rule_id, user_status', 'safe', 'on'=>'search'),
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
			'actionHistories' => array(self::HAS_MANY, 'ActionHistory', 'user_id'),
			'categoryDetails' => array(self::HAS_MANY, 'CategoryDetails', 'user_id'),
			'menuDetails' => array(self::HAS_MANY, 'MenuDetails', 'user_id'),
			'productDetails' => array(self::HAS_MANY, 'ProductDetails', 'user_id'),
			'userFiles' => array(self::HAS_MANY, 'UserFiles', 'user_id'),
			'rule' => array(self::BELONGS_TO, 'Rules', 'rule_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'fname' => 'Fname',
			'lname' => 'Lname',
			'country_id' => 'Country',
			'state_id' => 'State',
			'city' => 'City',
			'phone_number' => 'Phone Number',
			'address' => 'Address',
			'creation_date' => 'Creation Date',
			'activation_date' => 'Activation Date',
			'deactivation_date' => 'Deactivation Date',
			'activation_code' => 'Activation Code',
			'rule_id' => 'Rule',
			'user_status' => 'User Status',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('activation_date',$this->activation_date,true);
		$criteria->compare('deactivation_date',$this->deactivation_date,true);
		$criteria->compare('activation_code',$this->activation_code,true);
		$criteria->compare('rule_id',$this->rule_id);
		$criteria->compare('user_status',$this->user_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}