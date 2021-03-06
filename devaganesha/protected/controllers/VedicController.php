<?php

class VedicController extends AppController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/layout_3C';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD
		);
	}

	public function actions()
	{
		return array(

			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	 public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('vedic','vedicview','page'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('addvedic','create','update','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	} 

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
/*	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}  */
	

	
	public function actionVedicview($slug)
	{
		if($slug != '')
		{
			$model=Vedic::model()->with('node')->findByAttributes(array('slug'=>$slug));
            //$elements = Vedic::model()->findAll();
			$newComment = new Comment() ;
        	$newComment->node_id = $model->node_id ;
			$this->render('vedicview',array(
			'model'=>$model,
            // 'elements'=>$elements,
			'newComment'=>$newComment,
			));
		
		}
	
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model->text = html_entity_decode($model->text, ENT_COMPAT, "UTF-8");
		if(isset($_POST['Vedic']))
		{
			$model->attributes=$_POST['Vedic'];
            $model->text = htmlentities($model->text, ENT_COMPAT, "UTF-8");
			if($model->save())
				$this->redirect(array('vedicview','slug'=>$model->slug));
		}

		$this->render('update',array(
			'model'=>$model,
			'vedicType'=>$model->type,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('site/myganesha'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Vedic');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}  */

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vedic('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Vedic']))
			$model->attributes=$_GET['Vedic'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	
	
	public function actionVedic($vedicType = VedicType::Aarti)
	{	
		if(isset($_REQUEST['type'])){
		$vedicType = $_REQUEST['type'];
		}
		$criteria=new CDbCriteria;
		$criteria->limit = 25;
		$criteria->with = array('node');
		$criteria->order = 'node.created DESC';
		$criteria->compare('t.type',$vedicType); 

	   $pages=new CPagination(Vedic::model()->count($criteria));
	   $pages->applyLimit($criteria);
	   $pages->pageSize=25;

	   $elementsList=Vedic::model()->findAll($criteria);//->with('comments')
	   $this->render('vedic',array(
		  'elementsList'=>$elementsList,
		  'pages'=>$pages,
		  'vedicType'=>$vedicType,
	   ));
	}
	
	public function actionAddvedic($vedicType = VedicType::Aarti)
	{
		if(isset($_REQUEST['type'])){
		$vedicType = $_REQUEST['type'];
		}
		$model=new Vedic;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Vedic']))
		{
			$node = new Node ;
			$node->type = NodeType::Vedic;
		
			$model->attributes=$_POST['Vedic'];
            $model->text = htmlentities($model->text, ENT_COMPAT, "UTF-8");
			$model->slug = $this->behaviors();
			if($node->validate()){
			
			
			$transaction = Yii::app()->db->beginTransaction();
			  $success = $node->save(false);
			  $model->node_id = $node->id;
			
			  $success = $success ? $model->save(false) : $success;
			 if ($success)
			 {
				$transaction->commit();
				//add points for adding new aarti/mantra
				$user = User::model()->findByPk(Yii::app()->user->id);
				$points = 0;
				if($vedicType == VedicType::Aarti)
					$points = PointsType::AartiAdd ;
				elseif($vedicType == VedicType::Mantra)
					$points =  PointsType::MantraAdd ;
					
				$user->addPoints($points);	
				
				
				//Yii::app()->facebook->setFileUploadSupport(true);
				$url = $this->getUrlByNode($model->node_id);
				//$img = "www.";
			/*	Yii::app()->facebook->api(
				  '/514147705313075/feed',
				  'POST',
				  array(
					//'picture' => $img,
					'message' => $url,
					'description'=>'Get all the Aarti Mantra Shlokas for Ganesh Utsav. get the various pictures and wallpapers of Lord ganesh.',
					'link'=>$url,
					'access_token'=>urlencode('CAACEdEose0cBAKZAc7NxpvenkvAjtKWyiMZCgc2O1w7zytqPEiBULCulazwvmY8sWUsmmvNDBiE0MXgFWgwhdxJTNkG6Y2J5LQftSTf9GYaZBPrew4DjOJH4N2zZB6tTbwlfWgQTli4rMZBeNBqD2sz2iAXI7rBaJIdCCf54poduhRaP2dy1AqnQHSl8BDid5gEX79FalYQZDZD'),
				  )
				); */
				$this->redirect($this->createAbsoluteUrl('vedic',array('type'=>$model->type)));
			}else{
			$transaction->rollBack();
			}
				
			}
		}

		$this->render('addvedic',array(
			'model'=>$model,
			'vedicType'=>$vedicType,
		));
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Vedic::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vedic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
