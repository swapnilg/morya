<?php
$this->breadcrumbs=array(
	'Competitions'=>array('index'),
	$model->title,
);

?>
<div style="text-align:center;">
<div class="btm-border">
	<h2 class="page-head"><?php echo $model->title;?></h2>
</div>
</div>
<div class="row-fluid mt10">
<div class="span12 each-comp">
<div class="span8">
<div><?php echo $model->description;?></div>
<div class="mt10"><strong>Instructions :</strong></div>
<div><?php echo $model->instructions;?></div>
<div class="mt10"><strong>Prizes :</strong></div>
<div><?php echo $model->prizes;?></div>
<div class="mt10"><strong>Last Date Of Submission :</strong></div>
<div class="lastdate"><?php echo date('d\<\s\u\p\>S\<\/\s\u\p\> M, Y',strtotime($model->end_date));?></div>
</div>
<div class="comp-img span4"></div>
</div>
</div>

