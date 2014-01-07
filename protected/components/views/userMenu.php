
<ul>
    <li><?php echo CHtml::link('Push New Article',array('article/create')); ?></li>
    
    <li><?php  echo CHtml::link('Your Articles', Yii::app()->createAbsoluteUrl('articles')); ?></li>
   
    
    <li><?php echo CHtml::link('Logout',array('site/logout')); ?></li>
</ul>