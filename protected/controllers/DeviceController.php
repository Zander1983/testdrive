<?php

class DeviceController extends Controller
{
    
        public $layout='//layouts/column2';
    
    
	public function actionIndex()
	{
            
            $user = User::model()->findByPk(Yii::app()->user->id);
                   
            if(Yii::app()->user->isAdmin()){

                //So is the super user, show all articles
                
                $total_records = Device::model()->count();
                
                $total = Device::model()->count(array(
                    'group' => 'reg_id'
                ));
                
                $notifcation_on = Device::model()->count(array(
                    'condition'=>"notification = 1",
                    'group' => 'reg_id'
                ));
                
                $criteria=new CDbCriteria(array(
                    'group' => 'reg_id'
                ));
                
                $dataProvider=new CActiveDataProvider('Device', array(
                    'pagination'=>array(
                        'pageSize'=>5,
                    ), 
                    'criteria'=>$criteria,
                ));   
                
                $this->render('index',array(
                        'dataProvider'=>$dataProvider,
                        'total' => $total,
                        'notifcation_on' => $notifcation_on,
                        'total_records' => $total_records
                ));
       
            }
            else{
                
                $criteria=new CDbCriteria(array(
                    'condition'=>"project_title = '{$user->username}'",
                    'group' => 'reg_id'
                ));
                   
                $total = Device::model()->count(array(
                    'condition'=>"project_title = '{$user->username}'",
                    'group' => 'reg_id'
                ));
                    
                $notifcation_on = Device::model()->count(array(
                    'condition'=>"project_title = '{$user->username}' AND notification = 1",
                    'group' => 'reg_id'
                ));
  
                $dataProvider=new CActiveDataProvider('Device', array(
                    'pagination'=>array(
                        'pageSize'=>5,
                    ),
                    'criteria'=>$criteria,
                ));    
                
                $this->render('index',array(
                        'dataProvider'=>$dataProvider,
                        'total' => $total,
                        'notifcation_on' => $notifcation_on
                ));
            }
           
            
            //$dataProvider=new CActiveDataProvider('Device');

	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}