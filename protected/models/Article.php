<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $title
 * @property string $project_id
 * @property string $apple_response
 * @property integer $android_response
 * @property string $time_created
 */
class Article extends CActiveRecord
{
    
        private $project;
    
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
            if(Yii::app()->user->isAdmin()){
		return array(
			array('content, title, project_id', 'required'),
			array('user_id, project_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>2000),
                        array('apple_response', 'length', 'max'=>200),
                        array('android_response', 'length', 'max'=>200),
                        array('title', 'length', 'max'=>80),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('content, title', 'safe', 'on'=>'search'),
		);                
            }
            else{
		return array(
			array('content, title', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>2000),
                        array('apple_response', 'length', 'max'=>200),
                        array('android_response', 'length', 'max'=>200),
                        array('title', 'length', 'max'=>80),
                        //array('time_created', 'checkIfHoursPassed'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('content, title', 'safe', 'on'=>'search'),
		);
            }
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
                        'project_id' => 'Project',
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
        


        
        public function checkIfHoursPassed($attribute){
            
            $lastArticle = Article::model()->find(array('order' => 'time_created DESC', 'limit' => '1'));
          
            if($lastArticle){
                
                $test = time()-$lastArticle->$attribute;

                if((time()-$lastArticle->$attribute)<3600){
                    $this->addError($attribute, 'You must wait at least 1 hour before sending a new message');
                }                
            }
        }
        
  
        
        protected function beforeSave()
        {
      
            if(parent::beforeSave())
            {
                $this->time_created=time();
                $this->user_id=Yii::app()->user->id;
                
                if(!Yii::app()->user->isAdmin()){
                    $user = User::model()->findByPk(Yii::app()->user->id);
                    $this->project_id = $user->project_id;
                }
                
   
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
            
            if(Yii::app()->user->isAdmin()){
                
                //so only send to test devices
                $this->project = Project::model()->findByPk($this->project_id);

                //get android devices
                $devices = Device::model()->findAll(array('condition' => "project_title = '{$this->project->project_title}'
                                                            AND (platform = 'android' Or platform = 'Android')
                                                            AND notification = 1
                                                            AND test_device = 1
                                                            ", 
                                                            'group' => 'reg_id'
                                                            ));

                mail('info@webintelligence.ie', 'admin test: devices is ', var_export($devices, true));

                mail('info@webintelligence.ie', 'admin test: device count for android is '.count($devices), 'body');

                
                
                if(count($devices)>0){
                    
                    $response = $this->setupAndroidNotification($devices);       
                    $android_response = $this->parseAndroidResponse($response);
                    $this->recordAndroidResponse($android_response);
                    
                }
                else{
                    $this->recordAndroidResponse('no android devices found');
                }
                
                //get apple devices

                $devices = Device::model()->findAll(array('condition' => "project_title = '{$this->project->project_title}'
                                                            AND (platform = 'ios' Or platform = 'iOS')
                                                            AND notification = 1
                                                            AND test_device = 1
                                                            ", 
                                                            'group' => 'reg_id'
                                                            ));

                mail('info@webintelligence.ie', 'device count for iOS is '.count($devices), 'body');

                
                if(count($devices)>0){
                    $returns = $this->setupAppleNotification($devices);
                    $apple_response = $this->parseAppleResponse($returns['error'], $returns['error_string']);
                    $this->recordAppleResponse($apple_response);
                }
                else{
                    $this->recordAppleResponse('no apple devices found');
                }
            
            }
            else{
  
                $this->project = Project::model()->findByPk($this->project_id);
                
                //get android devices
                $devices = Device::model()->findAll(array('condition' => "project_title = '{$this->project->project_title}'
                                                            AND (platform = 'android' Or platform = 'Android')
                                                            AND notification = 1
                                                            ", 
                                                            'group' => 'reg_id'
                                                            ));

                mail('info@webintelligence.ie', 'devices is ', var_export($devices, true));

                mail('info@webintelligence.ie', 'device count for android is '.count($devices), 'body');

                if(count($devices)>0){
                    
                    $response = $this->setupAndroidNotification($devices);       
                    $android_response = $this->parseAndroidResponse($response);
                    $this->recordAndroidResponse($android_response);
                    
                }
                else{
                    $this->recordAndroidResponse('no android devices found');
                }


                //get apple devices
                $devices = Device::model()->findAll(array('condition' => "project_title = '{$this->project->project_title}'
                                                            AND (platform = 'ios' Or platform = 'iOS')
                                                            AND notification = 1
                                                            ", 
                                                            'group' => 'reg_id'
                                                            ));

                mail('info@webintelligence.ie', 'device count for iOS is '.count($devices), 'body');
                
                
                if(count($devices)>0){
                    $returns = $this->setupAppleNotification($devices);
                    $apple_response = $this->parseAppleResponse($returns['error'], $returns['error_string']);
                    $this->recordAppleResponse($apple_response);
                }
                else{
                    $this->recordAppleResponse('no apple devices found');
                }

            }
        }
        
      
        
        private function getRegIdsArray($devices){
            
            foreach ($devices as $device){
                $registrationId[] = $device->reg_id;
            }
            return $registrationId;
        }
        
        private function setupAndroidNotification( $devices )
        {

            $registrationIds = $this->getRegIdsArray($devices);

            $response = $this->sendAndroidNotification( 
                            $this->project->api_key, 
                            $registrationIds, 
                            array(
                                'title' => $this->title,
                                'message' => substr($this->content, 0, 22)."..", 
                                'article_id' => $this->id
                                    ));

            return json_decode($response);

            
            
        }
        
        private function parseAndroidResponse($response){
            
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
            
            return $string;
            
        }
        
        private function recordAndroidResponse($string){
                
            $this->updateByPk($this->id, array(
            'android_response' => $string
            ));
            
        }
    
        
        private function parseAppleResponse($error, $error_string){

            if($error!="0"){
                return "Error is $error and errorString is $error_string";
            }
            else{
                return "Successfully sent";                
            }
            
            
        }
        
        private function recordAppleResponse($string){
              
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
            
                mail('info@webintelligence.ie', 'in setupAppleNotification ', 'body');
            
                $development = false;//change it to true if in development
                $passphrase=$this->project->passphrase;//pass phrase of the pem file
                
                mail('info@webintelligence.ie', 'passphrase is '.$passphrase, 'body');
                
                $device_tokens = $this->getRegIdsArray($devices);

                $payload = array();
                
                
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
                    $apns_cert = dirname(Yii::app()->request->scriptFile)."/pems/{$this->project->project_title}/{$this->project->project_title}-dev.pem";
                }
                else
                {
                    $apns_url = 'gateway.push.apple.com';
                    /*Because i made an error with Athlone and called the production pem project_title-dev.pem 
                     * rather than project_title-prod.pem, need to check if its athlone and adjust accordingly
                     */
                    if($this->project->project_title=="athlonecc"){
                        $apns_cert = dirname(Yii::app()->request->scriptFile)."/pems/{$this->project->project_title}/prod/{$this->project->project_title}-dev.pem";
                    }
                    else{
                        $apns_cert = dirname(Yii::app()->request->scriptFile)."/pems/{$this->project->project_title}/{$this->project->project_title}-prod.pem";                
                    }
                }
                
                mail('info@webintelligence.ie', 'apns cert is ', $apns_cert);
                
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
   
                $returns = array();
                $returns['error'] = $error;
                $returns['error_string'] = $error_string;
                
                return $returns;
        }
        
     
 
}
