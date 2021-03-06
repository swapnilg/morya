<?php

class VedicController extends AppController
{
		function init(){
		Yii::import('application.models.vedic.*');
        Yii::import('application.models.user.*');
	}
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
				'actions'=>array('addvedic','create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
	

	
	public function actionVedicview()
	{
		if($_REQUEST['ved_title'] != '')
		{
			$model=Vedic::model()->findByAttributes(array('slug'=>$_REQUEST['ved_title']));
            $elements = Vedic::model()->findAll();
			$this->render('vedicview',array(
			'model'=>$model,
             'elements'=>$elements,
			));
		
		}
	
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Vedic;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Vedic']))
		{
			$model->attributes=$_POST['Vedic'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Vedic']))
		{
			$model->attributes=$_POST['Vedic'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
		
		//$dataProvider=new CActiveDataProvider('Vedic');
	/*	$dataProvider=new CActiveDataProvider('vedic', array(
			'criteria'=>array(
				'condition'=>'type='.$vedicType,
				'order'=>'id ASC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->render('vedic',array(
			'dataProvider'=>$dataProvider,
			'vedicType'=>$vedicType,
		));  */
	
		$criteria=new CDbCriteria;
		$criteria->limit = 10;
		$criteria->compare('type',$vedicType); 

	   $pages=new CPagination(Vedic::model()->count($criteria));
	   $pages->applyLimit($criteria);
	   $pages->pageSize=10;

	   $elementsList=Vedic::model()->findAll($criteria);//->with('comments')
	   $this->render('vedic',array(
		  'elementsList'=>$elementsList,
		  'pages'=>$pages,
		  'vedicType'=>$vedicType,
	   ));
	}
	
	public function actionAddvedic($vedicType)
	{
		$model=new Vedic;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Vedic']))
		{
			$model->attributes=$_POST['Vedic'];
            $model->text = nl2br($model->text);
			$model->slug = $this->behaviors();
			
			if($model->save())
				$this->redirect($this->createAbsoluteUrl('vedic',array('vedicType'=>$model->type)));
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
