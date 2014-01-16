<?php

/**
 * This is the model class for table "{{project}}".
 *
 * The followings are the available columns in table '{{project}}':
 * @property integer $id
 * @property string $project_title
 * @property string $project_number
 * @property string $api_key
 */



class Project extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{project}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            
                Yii::import("application.modules.user.UserModule", true); 
                
                
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_title, project_number, api_key', 'required'),
			array('project_title', 'length', 'max'=>30),
			array('project_number', 'length', 'max'=>50),
			array('api_key', 'length', 'max'=>100),
                        array('project_title', 'unique', 'message' => UserModule::t("This project title already exists.")),
                        array('project_number', 'unique', 'message' => UserModule::t("This project number already exists.")),
                        array('api_key', 'unique', 'message' => UserModule::t("This api key number already exists.")),
                        array('project_title', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
                        array('api_key', 'match', 'pattern' => '/^\S{6,}\z/', 'message' => UserModule::t("Incorrect symbols, no spaces allowed.")),
                        //array('project_number', 'match', 'pattern' => '/[^0-9]/', 'message' => UserModule::t("Must be numbers only.")),
                        array('project_number', 'numerical', 'integerOnly'=>true, 'min'=>0),
                        // 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, project_title, project_number, api_key', 'safe', 'on'=>'search'),
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
			'project_title' => 'Project Title',
			'project_number' => 'Project Number',
			'api_key' => 'Api Key',
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
		$criteria->compare('project_title',$this->project_title,true);
		$criteria->compare('project_number',$this->project_number,true);
		$criteria->compare('api_key',$this->api_key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
