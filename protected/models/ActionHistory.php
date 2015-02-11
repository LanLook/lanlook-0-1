<?php

/**
 * This is the model class for table "action_history".
 *
 * The followings are the available columns in table 'action_history':
 * @property integer $id
 * @property integer $user_id
 * @property string $action
 * @property string $action_date
 * @property string $action_result
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class ActionHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActionHistory the static model class
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
		return 'action_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, action_date', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('action_result', 'length', 'max'=>150),
			array('action', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, action, action_date, action_result', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'action' => 'Action',
			'action_date' => 'Action Date',
			'action_result' => 'Action Result',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('action_result',$this->action_result,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}