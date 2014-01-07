<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//Yii::app()->user->isGuest
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>The control panel allows school administrators to notify app users of a latest news article from the school.</p>

<?php
    if(Yii::app()->user->isGuest){
?>
    <p>Click login to proceed further</p>

<?php
    }
    else{
?>
    <p>Click "Push New Article" in the right sidebar to notify app users of a new news story.</p>
    
<?php
    }
?>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>


