<?php
$this->breadcrumbs=array(
	'Temples'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Temple', 'url'=>array('index')),
	array('label'=>'Manage Temple', 'url'=>array('admin')),
);
?>

<h1>Create Temple</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>