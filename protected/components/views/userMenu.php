
<ul>
    <li><?php 
        if(!Yii::app()->user->isAdmin()){
            echo CHtml::link('Send New Message',Yii::app()->createAbsoluteUrl('article/create')); 
        }
    ?></li>
    
    <li><?php  echo CHtml::link('Sent Messages', Yii::app()->createAbsoluteUrl('articles')); ?></li>
    
    <li><?php  echo CHtml::link('Devices', Yii::app()->createAbsoluteUrl('devices')); ?></li>
   
    <?php 
            if(Yii::app()->user->isAdmin()){
             ?>
    <li><?php  echo CHtml::link('Projects', Yii::app()->createAbsoluteUrl('projects')); ?></li>
    
    <li><?php  echo CHtml::link('New Project', Yii::app()->createAbsoluteUrl('project/create')); ?></li>
    
    <li><?php  echo CHtml::link('Users', Yii::app()->createAbsoluteUrl('user')); ?></li>
    
    <li><?php  echo CHtml::link('New User', Yii::app()->createAbsoluteUrl('user/admin/create')); ?></li>
    
    <?php
            }
    ?>
    
    
    <li><?php echo CHtml::link('Logout',array('site/logout')); ?></li>
</ul>