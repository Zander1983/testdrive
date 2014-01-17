<?php
/* @var $this DeviceController */

$this->breadcrumbs=array(
	'Device',
);

?>
<?php 

    if(Yii::app()->user->isAdmin()){
        echo "<h3>Total number of users of Schoolspace apps: $total </h3>";
        echo "<h3>Total number of devices in all projects with notification turned on: $notifcation_on </h3>";
    }
    else{
        echo "<h3>Total number of users to have used the app: $total </h3>";
        echo "<h3>Number of devices with notification turned on: $notifcation_on </h3>";
        
    }

    ?>
</h3>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
