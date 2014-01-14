<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $article_url
 * @property string $title
 * @property integer $pushed
 * @property integer $number_pushed_to
 * @property string $time_created
 */
class Article extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{article}}';
	}
        

        public function getUrl()
         {
             return Yii::app()->createUrl('article/view', array(
                 'id'=>$this->id,
                 'title'=>$this->title,
             ));
         }
         
         
        protected function afterFind()
        {
            
            $this->time_created = date('Y-m-d H:i:s', $this->time_created);
            
            parent::afterFind();
        }
    

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_url, title', 'required'),
			array('user_id, pushed, number_pushed_to', 'numerical', 'integerOnly'=>true),
			array('article_url', 'length', 'max'=>150),
                        array('title', 'length', 'max'=>80),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('article_url, title', 'safe', 'on'=>'search'),
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
                    'pusher' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'article_url' => 'Article Url',
                        'title' => 'Title',
			'pushed' => 'Pushed',
			'number_pushed_to' => 'Number Pushed To',
			'time_created' => 'Time Created',
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

		$criteria->compare('title',$this->title,true);
		$criteria->compare('article_url',$this->article_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        

        protected function beforeSave()
        {
            $test = 10;
            
            if(parent::beforeSave())
            {
                $this->time_created=time();
                $this->user_id=Yii::app()->user->id;
   
                return true;
            }
            else
                return false;
        }
        
        /*
         * This is where we will make the curl call
         */
        
        protected function afterSave()
        {
            
            $user = User::model()->findByPk(Yii::app()->user->id);
            //'project_title' => $user->username
            $project = Project::model()->find(array('condition' => "project_title = '{$user->username}'"));
            
            $devices = Device::model()->findAll(array('condition' => "project_title = '{$user->username}'"));
            
            
            foreach ($devices as $device){
                $registrationId[] = $device->reg_id;
            }
    
            $message      = $this->title;
            $tickerText   = "ticker text message";
            $contentTitle = "content title";
            $contentText  = "content body";

            $response = $this->sendNotification( 
                            $project->api_key, 
                            $registrationId, 
                            array(
                                'message' => $message, 
                                'tickerText' => $tickerText, 
                                'contentTitle' => $contentTitle, 
                                "contentText" => $contentText) );

            $response = json_decode($response);

            if($response->success>0){
                //it's been pushed so set pushed to 1 (its automatically 0)
                $pushed = 1;
            }
            
            mail('info@webintelligence.ie', 'response', var_export($response, true));
            
            $this->updateByPk($this->id, array(
                'pushed' => $pushed,
                'number_pushed_to' => $response->success
            ));
            
        }
        

        private function sendNotification( $apiKey, $registrationIdsArray, $messageData )
        {   

            $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . $apiKey);

            $data = array(
                'data' => $messageData,
                'registration_ids' => $registrationIdsArray
            );

            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers ); 
            curl_setopt( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }
 
}
