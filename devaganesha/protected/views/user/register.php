<?php
$this->breadcrumbs=array(
	'Register',
);
?>
<div class="title-bar">Register to devaganesha</div>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'action'=>Yii::app()->createUrl('user/register'),
    'enableAjaxValidation'=>true,
    'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); ?>

    <div>
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
  <!--  <div>
        <?php echo $form->labelEx($model,'confirm_password'); ?>
        <?php echo $form->passwordField($model,'confirm_password',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'confirm_password'); ?>
    </div> -->


    <div>
        <?php echo $form->labelEx($model,'contact'); ?>
        <?php echo $form->textField($model,'contact'); ?>
        <?php echo $form->error($model,'contact'); ?>
    </div>

	<div>
        <?php echo $form->labelEx($model,'ganpati_pic'); ?>
        <?php echo $form->fileField($model,'ganpati_pic'); ?>
        <?php echo $form->error($model,'ganpati_pic'); ?>
    </div> 

    <div>
        <?php echo $form->labelEx($model,'add_line_1'); ?>
        <?php echo $form->textField($model,'add_line_1',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'add_line_1'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'add_line_2'); ?>
        <?php echo $form->textField($model,'add_line_2',array('class'=>'span8','maxlength'=>255)); ?>
        <?php echo $form->error($model,'add_line_2'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'city'); ?>
        <?php $htmlOptions = array('class'=>'span8','style'=>'margin-bottom:5px','empty' => "Select city");?>
		<?php //echo Chosen::dropDownList($model->city,$model->city,$cityarr, $htmlOptions); 
		$this->widget('ext.chosen.Chosen',array(
			'name' => 'User[city]', // input name
			//'value' => $model->city, // selection
			'data' =>$cityarr,
			'htmlOptions'=>$htmlOptions,
		));
		?>
        <?php echo $form->error($model,'city'); ?>
    </div>

    <div class="mt10">
        <?php echo CHtml::submitButton('Register',array('class'=>'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->