<?php

class SiteController extends AppController
{
	/**
	 * Declares class-based actions.
	 */
	    function init(){
        Yii::import('application.models.vm.user.*');
    }
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		 //$elementsList=Photo::model()->findAll($criteria);//->with('comments')

		$criteria=new CDbCriteria;
		$criteria->with = array('node');
		$criteria->order = 'node.created DESC';
		$criteria->compare('t.type',PhotoUploadCategory::Normal);
		$criteria->limit = 10;
		$photos = Photo::model()->findAll($criteria);
		$map = Map::model()->findAll();
		foreach($map as $cord){
			$maparr[] = array('lat'=>$cord->lat,'lng'=>$cord->long,'temple'=>array('name'=>$cord->temp->name,'photo'=>PhotoType::$relativeFolderName[PhotoType::Mini].$cord->temp->main_pic->file_name,'desc'=>html_entity_decode($cord->temp->description),'url'=>Yii::app()->createAbsoluteUrl('temple/templeview',array($cord->temp->slug))));
		}
		$maparr = json_encode($maparr);
		$this->render('index',array(
			'maparr'=>$maparr,
			'photos'=>$photos,
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
		public function actionMyganesha(){

		if(empty($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
			{
				$this->redirect(array('user/login'));
			}
			$chkid = Yii::app()->user->Id;
		}else{
			$chkid = $_GET['id'];
		}
		
		$user = User::model()->findByPk($chkid);
		//print_r($user);
		$criteria=new CDbCriteria;
		$criteria->with = array('node');
		$criteria->order = 'node.created DESC';
		$criteria->compare('node.user_id',$chkid);
		$criteria->compare('t.type',PhotoUploadCategory::Normal,'AND');
		$photos = Photo::model()->findAll($criteria);//->with('comments')
				
	   $this->render('myganesha',array(
		  'photos'=>$photos,
		  'user'=>$user,
	   ));
	}

	public function actionOauth(){
		$this->render('oauth');
	}
	
	public function actionShowmap(){
		$temples = Temple::model()->findAll();
		$map = Map::model()->findAll();
		foreach($map as $cord){
			$maparr[] = array('lat'=>$cord->lat,'lng'=>$cord->long,'temple'=>array('name'=>$cord->temp->name,'photo'=>PhotoType::$relativeFolderName[PhotoType::Mini].$cord->temp->main_pic->file_name,'desc'=>html_entity_decode($cord->temp->description),'url'=>Yii::app()->createAbsoluteUrl('temple/templeview',array($cord->temp->slug))));
		}
		$maparr = json_encode($maparr);
		$this->render('showmap',array('temples'=>$temples,'maparr'=>$maparr));
	}
	
	public function actionSavecord(){

			$map = new Map;
			$map->lat = $_POST['lat'];
			$map->long = $_POST['long'];
			$map->temp_id = $_POST['place'];
			if($map->save())
			{
				echo "success";
			}
				
	}
	
	public function actionAddtofav(){
		$nodeid = $_POST['node_id'];
		$userid = Yii::app()->user->id;
		
		if($fav = Favourite::model()->findByPk(array('node_id' => $nodeid , 'user_id' => $userid )))
		{
			$fav->delete();
			echo "deleted";
		}else
		{
			$fav = new Favourite;
			$fav->node_id = $nodeid;
			$fav->user_id = $userid;
			if($fav->validate())
			{
				$fav->save();
				echo "added";
			}else{
				echo "error";
			}
		}


		
	}
	
	public function actionReportabuse(){
		$nodeid = $_POST['node_id'];
		$userid = Yii::app()->user->id;
		
		if($ra = ReportAbuse::model()->findByPk(array('node_id' => $nodeid , 'user_id' => $userid )))
		{
			$ra->delete();
			echo "undone";
		}else
		{
			$ra = new ReportAbuse;
			$ra->node_id = $nodeid;
			$ra->user_id = $userid;
			if($ra->validate())
			{
				$ra->save();
				$cnt  = ReportAbuse::model()->count('node_id',$nodeid);
				if($cnt == 3)
				{
								$to = "mayuresh@itvedant.com";
								$subject = "Abuse report for node $nodeid";
								$body_plain = "3 users are rporting the following node as abuse ".$nodeid;
								$body_html = "<div>3 users are rporting the following node as abuse $nodeid;</div>";
								$priority = 10;
								$sent = 0;
								
								$email = New Email;
								$email->email_to = $to;
								$email->subject = $subject;
								$email->body_plain = $body_plain;
								$email->body_html = $body_html;
								$email->priority = $priority;
								$email->sent = $sent;
								$email->save();
				}
				echo "done";
			}else{
				echo "error";
			}
		}
 }
	
	public function actionLoadSlider()
	{
		$criteria=new CDbCriteria;
		//$criteria->with = array('photoes');
		$criteria->order = 't.created DESC';
        $criteria->limit = 20;
		
		$sliderarr = Node::model()->findAll();
		
		
		echo $resp = $this->renderPartial('_slidercontent',array(
		'sliderarr'=>$sliderarr,
		));
	}
	
	public function actionTopwinner()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('node','main_pic');
		$criteria->order = 'node.created DESC';
		$criteria->compare('t.type',TempleType::Mandal);
		$criteria->limit = 10;
		$photos = Temple::model()->findAll($criteria);
		echo $resp = $this->renderPartial('_topwinner',array('photos'=>$photos,));
	}
	
	public function actionGettemple()
	{
		 $criteria1=new CDbCriteria;
		 $criteria1->with = array('node','main_pic');
		 $criteria1->order = 'node.created DESC';
		 $criteria1->compare('t.type',TempleType::Temple);
		 $elementsList1=Temple::model()->findAll($criteria1);//->with('comments')
		echo $resp = $this->renderPartial('_templecor',array('elementsList1'=>$elementsList1));
	}
	
	public function actionGetvedic()
	{
		if(empty($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
			{
				$this->redirect(array('user/login'));
			}
			$chkid = Yii::app()->user->Id;
		}else{
			$chkid = $_GET['id'];
		}
		$criteria3=new CDbCriteria;
		$criteria3->with = array('node');
		$criteria3->order = 'node.created DESC';
		$criteria3->compare('node.user_id',$chkid);
		$vedics = Vedic::model()->findAll($criteria3);//->with('comments')
		echo $resp = $this->renderPartial('_getvedic',array('vedics'=>$vedics));
		
	}
	
	public function actionGetmytemple()
	{
		if(empty($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
			{
				$this->redirect(array('user/login'));
			}
			$chkid = Yii::app()->user->Id;
		}else{
			$chkid = $_GET['id'];
		}
		$criteria2=new CDbCriteria;
		$criteria2->with = array('node');
		$criteria2->order = 'node.created DESC';
		$criteria2->compare('node.user_id',$chkid);
		$temples = Temple::model()->findAll($criteria2);//->with('comments')
		echo $resp = $this->renderPartial('//temple/_getmytemple',array('temples'=>$temples));
		
	}
	
	public function actionGetrecipe()
	{
		if(empty($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
			{
				$this->redirect(array('user/login'));
			}
			$chkid = Yii::app()->user->Id;
		}else{
			$chkid = $_GET['id'];
		}
		$criteria1=new CDbCriteria;
		$criteria1->with = array('node');
		$criteria1->order = 'node.created DESC';
		$criteria1->compare('node.user_id',$chkid);
		$recipes = Recipe::model()->findAll($criteria1);//->with('comments')
		echo $resp = $this->renderPartial('//recipe/_getrecipes',array('recipes'=>$recipes));
		
	}
	
	public function actionGetexp()
	{
		if(empty($_GET['id']))
		{
			if(Yii::app()->user->isGuest)
			{
				$this->redirect(array('user/login'));
			}
			$chkid = Yii::app()->user->Id;
		}else{
			$chkid = $_GET['id'];
		}
		$criteria4=new CDbCriteria;
		$criteria4->with = array('node');
		$criteria4->order = 'node.created DESC';
		$criteria4->compare('node.user_id',$chkid);
		$experiences = Experience::model()->findAll($criteria4);//->with('comments')
		echo $resp = $this->renderPartial('_getexp',array('experiences'=>$experiences));
		
	}
	
	public function actionTopmakhar()
	{
		/* $criteria=new CDbCriteria;
		$criteria->select = "node_id, count( node_id ) AS cnt";
		$criteria->group = "node_id";
		$criteria->order = 'cnt DESC';
		$criteria->limit = 10;
		$photos = Visit::model()->findAll($criteria); */
		$photos = Yii::app()->db->createCommand()
			->select('a.node_id, count( a.node_id ) AS cnt, file_name, caption, slug,b.node_id')
			->from('visits a')
			->join('photoes b', 'a.node_id=b.node_id')
			->group('a.node_id')
			->order('cnt desc')
			->limit(10)
			->queryAll();
			//print_r($user);
		echo $resp = $this->renderPartial('_topmakhar',array('photos'=>$photos,));
	}
	
	public function actionNode($id){
		return 	$this->redirect($this->getUrlByNode($id));
	}
	
	public function actionEmail(){
		Yii::import('application.commands.*');
		$command = new EmailCommand("","");
		$command->run(null);
	}
}