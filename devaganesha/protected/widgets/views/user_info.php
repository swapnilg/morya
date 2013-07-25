<?php
/**
 * Author: swapnil
 * File : user_info.php
 * Date: 3/9/12
 * Time: 3:41 PM
 */
?>

<?php
if(Yii::app()->user->isGuest)
{
?>
      <a id="signup" class="reg-log fr" href="#authentication">
		<div class="in-log"><img src="<?php echo get_template_directory_uri(); ?>/img/darshan.png" />&nbsp;&nbsp;&nbsp;Login / Register </div>
	  </a>

<div style="display: none;">
    <div id="authentication">
        <div id="register">
            <h1>Register</h1>
            <?php $this->controller->renderPartial('//user/short_register',array('model'=>$register)); ?>
        </div>
        <div id="login">
            <h1>Login</h1>
            <?php $this->controller->renderPartial('//user/login',array('model'=>$login)); ?>
        </div>
		
    </div>
</div>



<?php
}else{
?>

<span class="user_space">
	<div class="fl mr10">
		<img src="<?php echo get_template_directory_uri(); ?>/img/prof.gif" alt="pp" wid/>
	</div>
	<div>
    <div><a href="" class="us_logged"><?php $splitIndex = strpos($user->name,' '); echo($splitIndex > 0 ? substr($user->name,0,$splitIndex) : $user->name) ; ?></a></div>
    <div class="us_details">
        <?php //echo CHtml::link('My Ganesha',Yii::app()->createUrl('photo/myganesha')); ?>
        <?php echo CHtml::link('Edit Profile',Yii::app()->createUrl('user/edit')); ?> &nbsp;|&nbsp; 
        <?php echo CHtml::link('logout',Yii::app()->createUrl('user/logout')); ?>
    </div>
	</div>
</span>

<?php
}
?>



<?php
/* Yii::app()->clientScript->registerScript('user-widget-script', <<<EOD
        $('.us_logged').hover(
            function(){
                //mouserenter
                //$(this).css('height','40');
                $('.us_details').show();
            },function(){
                //mouserexit
                //$(this).css('height','20');
                $('.us_details').hide();
            }
        );
EOD
); */
?>