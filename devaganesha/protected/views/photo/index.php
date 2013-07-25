<?php
$this->breadcrumbs=array(
	'Ganesh Photos',
);?>

        <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
        array(
            'id'=>'uploadFile',
            'config'=>array(
                'action'=>Yii::app()->createUrl('photo/postUpload',array('type'=>PhotoUploadCategory::Normal)),
                'allowedExtensions'=>array("jpg","jpeg","gif"),//array("jpg","jpeg","gif","exe","mov" and etc...
                'sizeLimit'=>10*1024*1024,// maximum file size in bytes
                'minSizeLimit'=>10,// minimum file size in bytes
                'onComplete'=>"js:function(id,filename,response){}",
                'uploadButtonText'=>'Drop Your Ganesha`s Photoes Here to Upload. ',
                'messages'=>array(
                    'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
                    'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
                    'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                    'emptyError'=>"{file} is empty, please select files again without it.",
                    'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                ),
                'showMessage'=>"js:function(message){ alert(message); }"
            )
        )); ?>

    <div class="row-fluid" >
      <div id="container">
    <?php
         foreach($elementsList as $photo){
            $this->renderPartial('//photo/_single',array('photo'=>$photo));
        }
    ?>
     </div> 
	</div>

 

<?php $this->beginClip('js-page-end'); ?>
		<script type="text/javascript">
           
            </script>
 <?php $this->endClip(); ?>