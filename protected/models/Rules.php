<?php

/**
 * This is the model class for table "rules".
 *
 * The followings are the available columns in table 'rules':
 * @property integer $rule_id
 * @property string $rule_name
 * @property string $access_list
 * @property string $created_date
 * @property string $modified_date
 * @property integer $rule_status
 *
 * The followings are the available model relations:
 * @property Users[] $users
 */
class Rules extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Rules the static model class
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
		return 'rules';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rule_status', 'numerical', 'integerOnly'=>true),
			array('rule_name', 'length', 'max'=>100),
			array('access_list', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('rule_id, rule_name, access_list, created_date, modified_date, rule_status', 'safe', 'on'=>'search'),
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
			'users' => array(self::HAS_MANY, 'Users', 'rule_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rule_id' => 'Rule',
			'rule_name' => 'Rule Name',
			'access_list' => 'Access List',
			'created_date' => 'Created Date',
			'modified_date' => 'Modified Date',
			'rule_status' => 'Rule Status',
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

		$criteria->compare('rule_id',$this->rule_id);
		$criteria->compare('rule_name',$this->rule_name,true);
		$criteria->compare('access_list',$this->access_list,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('rule_status',$this->rule_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}