<?php
$this->breadcrumbs=array(
    MahimaType::$heading[$model->type]=>array('index','type'=>$model->type),
    $model->title,
);
$this->setPageTitle($model->title);
Yii::app()->clientScript->registerMetaTag('See what are the experiences of people with ganesha and share your experince also. Make a wish to ganesha and make your wish come true');
?>


   <div style="text-align:center;">
<div class="btm-border">
	<h2 class="page-head"><?php echo $model->title;?></h2>
</div>
</div>
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
    <div class="blog-content">
        <div><?php echo html_entity_decode($model->text, ENT_COMPAT, "UTF-8"); ?></div>

    </div>
	
	<div class="mt20"><a href="<?php echo Yii::app()->createUrl('experience/index',array('type'=>$model->type));?>">Back to  All</a></div>
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


