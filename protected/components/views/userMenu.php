
<ul>
    <li><?php echo CHtml::link('Push New Article',Yii::app()->createAbsoluteUrl('article/create')); ?></li>
    
    <li><?php  echo CHtml::link('Your Articles', Yii::app()->createAbsoluteUrl('articles')); ?></li>
   
    <?php
            $user = User::model()->findByPk(Yii::app()->user->id);    
            if($user->superuser){
             ?>
    <li><?php  echo CHtml::link('New Project', Yii::app()->createAbsoluteUrl('project/create')); ?></li>
      
    <li><?php  echo CHtml::link('Projects', Yii::app()->createAbsoluteUrl('projects')); ?></li>
    
    <?php
            }
    ?>
    
    
    <li><?php echo CHtml::link('Logout',array('site/logout')); ?></li>
</ul>