<?php
$this->breadcrumbs=array(
    VedicType::$heading[$vedicType] => array('vedic','type'=>$vedicType),
	'add'
);

?>

<div class="title-bar">Add your new <?php echo VedicType::$heading[$vedicType];?></div>

<?php echo $this->renderPartial('_form', array('model'=>$model ,'vedicType'=>$vedicType)); ?>