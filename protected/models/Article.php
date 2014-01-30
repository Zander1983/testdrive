<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $title
 * @property string $apple_response
 * @property integer $android_response
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
         
         
         /*
        protected function afterFind()
        {
            $test = $this->scenario;
            
            $this->time_created = date('Y-m-d H:i:s', $this->time_created);
            
            parent::afterFind();
        }*/
    

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, title', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>2000),
                        array('apple_response', 'length', 'max'=>200),
                        array('android_response', 'length', 'max'=>200),
                        array('title', 'length', 'max'=>80),
                       // array('time_created', 'checkIfHoursPassed'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('content, title', 'safe', 'on'=>'search'),
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
			'content' => 'Content',
                        'title' => 'Title',
			'apple_response' => 'Apple Response',
			'android_response' => 'Android Response',
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
		$criteria->compare('content',$this->content,true);

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
            //get the last time article created. if more than an hour -> send        
            /*$lastArticle = Article::model()->find(array('order' => 'time_created DESC', 'limit' => '1'));
          
            if($lastArticle){
                if(!$this->checkIfHoursPassed($lastArticle->time_created)){
                    return false;
                }
            }*/
      
            if(parent::beforeSave())
            {
                $this->time_created=time();
                $this->user_id=Yii::app()->user->id;
   
                return true;
            }
            else
                return false;
        }
        
        public function checkIfHoursPassed($attribute){
            
            $lastArticle = Article::model()->find(array('order' => 'time_created DESC', 'limit' => '1'));
          
            if($lastArticle){
                
                $test = time()-$lastArticle->$attribute;

                if((time()-$lastArticle->$attribute)<3600){
                    $this->addError($attribute, 'You must wait at least 1 hour before sending a new message');
                }                
            }
        }
        
        /*
         * This is where we will make the curl call
         */
        
        protected function afterSave()
        {
            
            $user = User::model()->findByPk(Yii::app()->user->id);
            
            //'project_title' => $user->username
            $project = Project::model()->find(array('condition' => "project_title = '{$user->username}'"));
            
            //get android devices
            $devices = Device::model()->findAll(array('condition' => "project_title = '{$user->username}'
                                                        AND (platform = 'android' Or platform = 'Android')", 
                                                        'group' => 'reg_id'
                                                        ));
            $this->setupAndroidNotification($devices, $project);
             
            
            //get apple devices
            $devices = Device::model()->findAll(array('condition' => "project_title = '{$user->username}'
                                                        AND (platform = 'ios' Or platform = 'iOS')", 
                                                        'group' => 'reg_id'
                                                        ));
            $this->setupAppleNotification($devices);
           
        }
        
      
        
        private function getRegIdsArray($devices){
            
            foreach ($devices as $device){
                $registrationId[] = $device->reg_id;
            }
            return $registrationId;
        }
        
        private function setupAndroidNotification( $devices, $project )
        {

            $registrationIds = $this->getRegIdsArray($devices);

            $response = $this->sendAndroidNotification( 
                            $project->api_key, 
                            $registrationIds, 
                            array(
                                'title' => $this->title,
                                'message' => substr($this->content, 0, 22)."..", 
                                'article_id' => $this->id
                                    ));

            $response = json_decode($response);

            $this->recordAndroidResponse($response);
            
        }
        
        private function recordAndroidResponse($response){
            
            if($response->failure=="0"){
                $string = "Successully sent. Response: ";
            }
            else{
                $string = "Problem Sending to all devices. Response: ";
            }
            
            $string .= "Success is {$response->success}, Failure is {$response->failure}";
            
            if(is_array($response->results)){
                $string .= " and message id is ".$response->results[0]->message_id;
            }
                
            $this->updateByPk($this->id, array(
            'android_response' => $string
            ));
            
        }
        
        
        private function recordAppleResponse($error, $error_string){
            
           
            if($error!="0"){
                $string = "Error is $error and errorString is $error_string";
            }
            else{
                $string = "Successfully sent";                
            }
            
            mail('info@webintelligence.ie', 'apple string is ', $string);
            
              
            $this->updateByPk($this->id, array(
            'apple_response' => $string
            ));
            
        }
 

        private function sendAndroidNotification( $apiKey, $registrationIdsArray, $messageData )
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
        
        
        private function setupAppleNotification( $devices ){
                
                $development = true;//change it to true if in development
                $passphrase='mountmercy543';//pass phrase of the pem file
                
                $device_tokens = $this->getRegIdsArray($devices);

                $payload = array();
                //$payload['aps'] = array('alert' => $msg_text, 'badge' => intval($badge), 'sound' => $sound);
                
                
                $payload['aps'] = array('alert' => $this->title, 
                                         'badge' => 1, 'sound' => 'default',
                                         'article_id'=> $this->id,	
                                            );
                
                
                $payload = json_encode($payload);

                $apns_url = NULL;
                $apns_cert = NULL;
                $apns_port = 2195;

                if($development)
                {
                    $apns_url = 'gateway.sandbox.push.apple.com';
                    $apns_cert = dirname(Yii::app()->request->scriptFile).'/pems/mountmercy/MountMercy-dev.pem';
                }
                else
                {
                    $apns_url = 'gateway.push.apple.com';
                    $apns_cert = dirname(Yii::app()->request->scriptFile).'/pems/mountmercy/MountMercy-dev.pem';
                }
                $stream_context = stream_context_create();
                stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
                stream_context_set_option($stream_context, 'ssl', 'passphrase', $passphrase);

                $apns = stream_socket_client('ssl://' . $apns_url . ':' . $apns_port, $error, $error_string, 2, STREAM_CLIENT_CONNECT, $stream_context);            
                //$device_tokens= "b2333b31cfd8d81c58b1453dee9306429c3a3a90e36a361ec60a8a700e4ed433";

                foreach ($device_tokens as $key => $token) {          
                    $apns_message = chr(0) . chr(0) . chr(32) . pack('H*', $token ) . chr(0) . chr(strlen($payload)) . $payload;
                    fwrite($apns, $apns_message);                 
                }

                @socket_close($apns);
                @fclose($apns);
   
                $this->recordAppleResponse($error, $error_string);
                
        }
        
     
 
}
