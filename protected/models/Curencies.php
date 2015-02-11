<?php

/**
 * This is the model class for table "curencies".
 *
 * The followings are the available columns in table 'curencies':
 * @property string $id
 * @property string $curency_id
 * @property string $lng_id
 * @property string $curency_code
 * @property string $curency_name
 * @property string $curency_value
 * @property string $modified_date
 * @property integer $curency_status
 */
class Curencies extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Curencies the static model class
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
		return 'curencies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('curency_id, lng_id, curency_code, curency_name', 'required'),
			array('curency_status', 'numerical', 'integerOnly'=>true),
			array('curency_id, curency_value', 'length', 'max'=>10),
			array('lng_id', 'length', 'max'=>5),
			array('curency_code', 'length', 'max'=>4),
			array('curency_name', 'length', 'max'=>80),
			array('modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, curency_id, lng_id, curency_code, curency_name, curency_value, modified_date, curency_status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'curency_id' => 'Curency',
			'lng_id' => 'Lng',
			'curency_code' => 'Curency Code',
			'curency_name' => 'Curency Name',
			'curency_value' => 'Curency Value',
			'modified_date' => 'Modified Date',
			'curency_status' => 'Curency Status',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('curency_id',$this->curency_id,true);
		$criteria->compare('lng_id',$this->lng_id,true);
		$criteria->compare('curency_code',$this->curency_code,true);
		$criteria->compare('curency_name',$this->curency_name,true);
		$criteria->compare('curency_value',$this->curency_value,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('curency_status',$this->curency_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}