<?php
$this->breadcrumbs=array(
	'Temples',
);

switch($templeType){
    case TempleType::Historic : $heading = "Historic Temples";$addhead = "Add historic Temple";break;
    case TempleType::Popular : $heading = "Most Popular Temples";$addhead = "Add Most popular Temple";break;
}

$this->menu=array(
    array('label'=>$addhead, 'url'=>array('create','templeType'=>$templeType)),
	array('label'=>'Historic', 'url'=>array('index','templeType'=>TempleType::Historic)),
	array('label'=>'Most Popular', 'url'=>array('index','templeType'=>TempleType::Popular)),
    array('label'=>'Ganesh Names', 'url'=>$this->createAbsoluteUrl('vedic/page', array('view' => 'ganesh_names'))),
    array('label'=>'Lalbaug cha Raja', 'url'=>$this->createAbsoluteUrl('temple/page', array('view' => 'lalbuag'))),
);


?>

<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); 

$this->menu=array(
	array('label'=>$addhead, 'url'=>array('create','templeType'=>$templeType)),
	//array('label'=>'Manage Temple', 'url'=>array('admin')),
);*/
?>
<div class="mid-region">
    <div class="tab">&nbsp;<?php echo $heading;?></div>

    <?php foreach($elementsList as $temple){
    //$filename = Photo::model()->findByPk($temple->primary_pic);
    //$filename1 = Photo::model()->findByPk($temple->secondary_pic1);
    //$filename2 = Photo::model()->findByPk($temple->secondary_pic2);
    //$filename3 = Photo::model()->findByPk($temple->secondary_pic3);
    //$filename4 = Photo::model()->findByPk($temple->secondary_pic4);
    ?>
    <div class="cont-disp">
        <div class="title_head"><a href="<?php echo Yii::app()->createUrl('temple/templeview',array('temple_name'=>$temple->slug))?>"><?php echo $temple->name;?></a></div>

        <p>
             <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Screen].$temple->main_pic->file_name; ?>" height="200px" width="200px" style="padding: 5px;float: left;"/>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $temple->description;?>

        </p>
        <div><b>Established In : </b>&nbsp;<?php echo $temple->established;?></div>
        <div><b>How to reach : </b>&nbsp;<?php echo $temple->how_to_go;?></div>
        <div><b>History : </b>&nbsp;<?php echo $temple->history;?></div>
        <div><b>Other Pics : </b></div>
        <div><?php
           if(isset($temple->pic1->file_name)){
           ?><img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Screen].$temple->pic1->file_name; ?>" height="100px" width="100px" style="padding: 5px" border="1px #000000"/>
            <?php }
            if(isset($temple->pic2->file_name)){?>
               <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Screen].$temple->pic2->file_name; ?>" height="100px" width="100px" style="padding: 5px" border="1px #000000"/>
            <?php } ?>
       <?php if(isset($temple->pic3->file_name)){?>
        <img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Screen].$temple->pic3->file_name; ?>" height="100px" width="100px" style="padding: 5px" border="1px #000000"/>
        <?php }
    if(isset($temple->pic4->file_name)){?><img src="<?php echo PhotoType::$relativeFolderName[PhotoType::Screen].$temple->pic4->file_name; ?>" height="100px" width="100px" style="padding: 5px" border="1px #000000"/>
    <?php } ?></div>
        <div class="clear"></div>
    </div>
    <?php } ?>
</div>