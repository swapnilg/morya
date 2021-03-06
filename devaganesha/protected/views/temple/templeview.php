<?php
$this->breadcrumbs=array(
	TempleType::$heading[$model->type]=>array('index','type'=>$model->type),
	$model->name,
);
$this->setPageTitle($model->name);
Yii::app()->clientScript->registerMetaTag('Get the information about all the popular Temples and Mandals of lord ganesha all over the world. Ganesh mandals in mumbai, pune and all over maharashtra. Navsache ganpati wish full filling ganesh', 'description');
?>


	<div style="text-align:center;">
<div class="btm-border">
	<h2 class="page-head"><?php echo $model->name;?></h2>
</div>
</div>
	
	<div class="clear"></div>
		<div class="mt10">
		<div class="addthis_toolbox addthis_default_style addthis_32x32_style fl">
			<a class="addthis_button_facebook"></a>
		<a class="addthis_button_twitter"></a>
		<a class="addthis_button_pinterest_share"></a>
		<a class="addthis_button_email"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
	<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-517d3bd171dee465"></script>
		<div class="fr">
			<div><strong>Posted on : <?php echo  date('d M, Y',strtotime($model->node->created)); ?></strong></div>
			<div><strong>author : <?php echo $model->node->creator->name; ?></strong></div>
		</div>
		<div class="clear"></div>
		</div>
		<hr/>
        <div class="mt10" style="text-align:center;">
            <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Thumb].$model->main_pic->file_name; ?>" class="img-polaroid"/>
         </div>
		 <div class="mt10"><?php echo html_entity_decode($model->description, ENT_COMPAT, "UTF-8");?></div>
        <div class="mt10"><strong>Established In  : </strong> <?php echo $model->established ?></div>
        <div class="mt10"><strong> How to reach : </strong><?php echo html_entity_decode($model->how_to_go, ENT_COMPAT, "UTF-8");?></div>
        <div  class="mt10"><p><strong>History : </strong><?php echo html_entity_decode($model->history, ENT_COMPAT, "UTF-8");?></p></div>
     
        <div class="mt10"><?php
            if(isset($model->pic1->file_name)){
                ?><img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Thumb].$model->pic1->file_name; ?>" height="100px" width="100px" style="padding: 5px" class="img-polaroid"/>
                <?php }
            if(isset($model->pic2->file_name)){?>
                <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Thumb].$model->pic2->file_name; ?>" height="100px" width="100px" style="padding: 5px" class="img-polaroid"/>
                <?php } ?>
            <?php if(isset($model->pic3->file_name)){?>
                <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Thumb].$model->pic3->file_name; ?>" height="100px" width="100px" style="padding: 5px" class="img-polaroid"/>
                <?php }
            if(isset($model->pic4->file_name)){?><img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Thumb].$model->pic4->file_name; ?>" height="100px" width="100px" class="img-polaroid"/>
                <?php } ?>
		</div>
		
		<div class="mt10">
			<?php 
					$mapDesign = array('height'=>'350px','zoom'=>7); 
					$this->renderPartial('//site/showmap',array('maparr'=>$maparr,'map_design'=>$mapDesign)); 
					?>
		</div>
				
				<div class="mt20"><a href="<?php echo Yii::app()->createUrl('temple/index',array('type'=>$model->type));?>">Back to  All</a></div>
		<div id="comments">
			<div id="accordion" style="margin-bottom:10px !important;">
			
			<h3>Show comments</h3>
			<div class="single_comment">
			<?php
			if($model->node->comments){
				foreach($model->node->comments as $comment){
					$this->renderPartial('//comment/view',array('comment'=>$comment));
				}
			}else{
			echo "<p>Be the first one to give the comment.</p>";
			}
			?>
			</div>
			</div>
		<?php $this->renderPartial('//comment/create',array('comment'=>$newComment)); ?>
			
		</div>



