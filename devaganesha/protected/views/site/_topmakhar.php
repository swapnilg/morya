
<div class="scroll-pane" style="height:320px">
<div style="padding:10px;">
<?php foreach($photos as $photo){?>
<div class="each-ent">
	<div class="fl"><a href="<?php echo Yii::app()->createUrl('photo/view',array('slug'=>$photo->slug)) ?>" ><img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Mini] . $photo['file_name'];?>" class="each-img" height="65" width="65"/></a></div>
	<div class="fl" style="width:180px;">
		<div class="head-cont"><a href="<?php echo Yii::app()->createUrl('photo/view',array('slug'=>$photo->slug)) ?>" ><?php echo $photo['caption']; ?></a></div>
		<div class="addr-cont"></div>
		<div><a href="<?php echo Yii::app()->createUrl('photo/view',array('slug'=>$photo['slug'])); ?>" class="view-photo">view photos &raquo;</a></div>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>
</div>
</div>

<script>
$('.scroll-pane').jScrollPane();
</script>