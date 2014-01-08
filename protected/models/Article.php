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
                var_dump('before save ');
                var_dump(time());
                
                
                
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
            
            /*
            $user = User::model()->findByPk(Yii::app()->user->id);

            $data = array('title' => $this->title, 'project_title' => $user->username);
                
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost/schoolspace/device_api/notify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
            //add headers to pass authorization
            curl_setopt($ch,CURLOPT_HTTPHEADER,array('device_id: 63843', 'api_key: hv7Vgd4jsbb'));
            
            file_put_contents('/var/www/my_logs/beforeCurl.log', "just before curl");
            
            $result = curl_exec($ch);

            file_put_contents('/var/www/my_logs/result.log', "$result");

            print_r($result);
            curl_close($ch);*/
            
            $user = User::model()->findByPk(Yii::app()->user->id);
            //'project_title' => $user->username
            $project = Project::model()->find(array('condition' => "project_title = '{$user->username}'"));
            
            $devices = Device::model()->findAll(array('condition' => "project_title = '{$user->username}'"));
            
            
            foreach ($devices as $device){
                $registrationId[] = $device->reg_id;
            }
    

            $response = sendNotification( 
                            $project->api_key, 
                            $registrationId, 
                            array(
                                'message' => $message, 
                                'tickerText' => $tickerText, 
                                'contentTitle' => $contentTitle, 
                                "contentText" => $contentText) );

            $response = json_decode($response);


            if($response->success==1){
                //record success
                $message = "The article with title $message and id of {$article->id}
                            from the website {$article->project_title}
                            has been successfully pushed
                            ";
                recordPushSuccess($message);

                //add the article
                if(!addArticle($article, $project->id)){
                    $message = "Could not add the article with title $message 
                            and id of {$article->id}
                            from the website {$article->project_title} to article table ";
                    recordPushFailure($message);
                }
            }
            else{
                $message = "An error  title $message and id of {$article->id}
                            from the website {$article->project_title}
                            could not be pushed
                            ";
                recordPushFailure($message);
            }
            
            file_put_contents('/var/www/my_logs/project.log', $project->project_number);
            
        }
        
        private function recordPushFailure($message){

                //send an email notifying of failure     
                mail('markkelly1983@yahoo.co.uk', 'A problem occurred pushing article', $message);
                //file_put_contents('/var/www/my_logs/failure.log', $message);
        }

        private function recordPushSuccess($message){

                $articleDevice = new ArticleDevice();
                $articleDevice->article_id = 8;
                $articleDevice->article_title = "blah";
                $articleDevice->save();
        }
 
}
