<?php
/* @var $this DeviceController */

$this->breadcrumbs=array(
	'Device',
);

?>
<h3><?php 

    if(Yii::app()->user->isAdmin()){
        echo "Total number of devices in all projects with notification turned on: ";
    }
    else{
        echo "Number of devices with notification turned on: ";
    }
    echo $count; 
    ?>
</h3>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
