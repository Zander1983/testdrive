<?php

/**
 * This is the model class for table "{{device}}".
 *
 * The followings are the available columns in table '{{device}}':
 * @property integer $id
 * @property string $platform
 * @property string $reg_id
 * @property string $project_title
 * @property string $api_key
 * @property string $notification_on
 * @property string $notification
 */
class Device extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{device}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('platform, reg_id, project_title, api_key, notification_on', 'required'),
			array('platform', 'length', 'max'=>12),
			array('reg_id', 'length', 'max'=>200),
			array('project_title', 'length', 'max'=>30),
			array('api_key', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, platform, reg_id, project_title, api_key, notification_on', 'safe', 'on'=>'search'),
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
			'platform' => 'Platform',
			'reg_id' => 'Reg',
			'project_title' => 'Project Title',
			'api_key' => 'Api Key',
			'notification_on' => 'Notification On Since',
                        'notification_on' => 'Notify User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('platform',$this->platform,true);
		$criteria->compare('reg_id',$this->reg_id,true);
		$criteria->compare('project_title',$this->project_title,true);
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('notification_on',$this->notification_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Device the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
