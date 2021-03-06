<?php

class PhotoController extends AppController
{
    public $layout = 'main' ;

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','loadRelated'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('upload','postUpload','update','myganesha','rate','delete'),
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

	public function actionIndex($userSlug = null)
    {
		$criteria=new CDbCriteria;
        $criteria->with = array('node');
		$criteria->order = 'node.created DESC';
		$criteria->compare('t.type',PhotoUploadCategory::Normal);
        $criteria->limit = 20;
	
        $pages=new CPagination(Photo::model()->count($criteria));
		$pages->pageSize=20;
        $pages->applyLimit($criteria);
        

        $elementsList=Photo::model()->findAll($criteria);//->with('comments')
        $this->render('index',array(
            'elementsList'=>$elementsList,
            'pages'=>$pages
        ));
	}
	public function actionRate($node_id){
		if(Yii::app()->request->isPostRequest)
		{
			if($modak = Modak::model()->findByPk(array('node_id' => $node_id , 'user_id' => Yii::app()->user->id )))
			{
				$modak->rating = $_POST['rating'];
			}
			else
			{
				$modak = new Modak ;
				$modak->rating = $_POST['rating'];	
				$modak->node_id = $node_id ;
			}
			if($modak->save())
			{
				return $modak->rating ;
			}
		}
		return -1 ;
	}
	
	public function actionMyganesha(){
		$photo = new photo;
		$criteria=new CDbCriteria;
		$criteria->limit = 10;
		$criteria->compare('user_id',$photo->user_id);

	   $pages=new CPagination(Photo::model()->count($criteria));          
	   $pages->applyLimit($criteria);
	   $pages->pageSize=10;

	   $elementsList=Photo::model()->findAll($criteria);//->with('comments')
	   $this->render('index',array(
		  'elementsList'=>$elementsList,
		  'pages'=>$pages,
	   ));
	}
	
	public function actionUpload()
	{
		$this->render('upload');
	}
	public function actionPostUpload($type = PhotoUploadCategory::Normal){

			Yii::import("ext.EAjaxUpload.qqFileUploader");
			$folder = PhotoType::$folderName[PhotoType::Original];// folder for uploaded files
			$allowedExtensions = array("jpg","jpeg","gif");//array("jpg","jpeg","gif","exe","mov" and etc...
			$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes - 10mb
			$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
			$result = $uploader->handleUpload($folder);
			$fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
			$fileName=$result['filename'];//GETTING FILE NAME
			$this->resize($folder.$fileName);
			$lastId =$this->updateDb($type,$fileName,$uploader->file->getName(),$uploader->file->getSize());
			$result['id']=$lastId;
			$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			$user = User::model()->findByPk(Yii::app()->user->id);
			$user->addPoints(PointsType::PhotoAdd);			
			echo $return;// it's array
	}
	public function actionUpdate(){
		$photo = Photo::model()->findByPk((int)$_POST['id'])->with('node');
        if($photo->node->user_id === Yii::app()->user->id){
            $photo->caption = $_POST['caption'];
			$photo->slug = $this->behaviors();
            $photo->description = $_POST['description'];
			$photo->location = $_POST['location'];
            if($photo->save()){
				$rurl = Yii::app()->createUrl('photo/view',array('slug'=>$photo->slug));
				echo $rurl;
				return true;
            }else{
			 echo $photo->getErrors();
			}
        }
        return false;
	}
	private function updateDb($type,$fileName,$ogName,$size){
	
		$node = new Node ;
		$node->type = NodeType::Photo ;
		
		$photo = new Photo;
        $photo->type = $type ;
		$photo->caption = $fileName;
		$photo->original_name = $ogName;
		$photo->file_name = $fileName;
		$photo->file_type = 'image/jpeg';
		$photo->file_size = $size ;
		$photo->slug = $this->behaviors();
		if($node->validate()){
		$transaction = Yii::app()->db->beginTransaction();
		  $success = $node->save(false);
		  $photo->node_id = $node->id;
		  $success = $success ? $photo->save(false) : $success;
		 if ($success)
		 {
			$transaction->commit();
			//Yii::app()->facebook->setFileUploadSupport(true);
			$img = PhotoType::$relativeFolderName[PhotoType::Screen].$photo->file_name;
			/*Yii::app()->facebook->api(
			  '/me/photos',
			  'POST',
			  array(
				'source' => '@' . $img,
				'message' => 'Photo uploaded via the DevaGanesha.com'
			  )
			);*/
		}
		 else
			$transaction->rollBack();
		}
		else{
		return -1 ;
		}
		return $photo->id;
	}
	
	private function resize($filePath){
			$path_information = pathinfo($filePath);
			list($width, $height, $type, $attr) = getimagesize($filePath);

			$thumb=Yii::app()->phpThumb->create($filePath);
			$thumb->resize(PhotoType::$dimension[PhotoType::Screen]['width']);
			$thumb->save(PhotoType::$folderName[PhotoType::Screen].$path_information['basename']);
			
			$thumb->resize(PhotoType::$dimension[PhotoType::Thumb]['width']);
			$thumb->save(PhotoType::$folderName[PhotoType::Thumb].$path_information['basename']);
			
			$thumb->resize(PhotoType::$dimension[PhotoType::Mini]['width']);
			$thumb->save(PhotoType::$folderName[PhotoType::Mini].$path_information['basename']);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($slug)
	{
        //photoes with comments
        $photo = Photo::model()->findByAttributes(array('slug'=>$slug));
		
		if(isset(Yii::app()->user->id))
		{
			$modaks = Modak::model()->findByPk(array('node_id' => $photo->node_id,'user_id' => Yii::app()->user->id ));
			if($modaks == null)
			{
				$modaks = new Modak ;
			}
		}
		else
		{
			$modaks = new Modak ;
		}
        $newComment = new Comment() ;
        $newComment->node_id = $photo->node_id ;
		$criteria=new CDbCriteria;
        $criteria->with = array('node');
		$criteria->order = 'node.created DESC';
        $criteria->limit = 30;
		if(isset(Yii::app()->user->id))
		{
			$viewnum = Visit::model()->findByAttributes(array('user_id'=>Yii::app()->user->id,'node_id'=>$photo->node_id));
			
			if($viewnum == null){
			$visit = new Visit();
			
			$visit->node_id = $photo->node_id;
			$visit->user_id = Yii::app()->user->id;
			//var_dump($visit);
			$visit->created = date('Y-m-d h:i:s');
			$visit->save();
			
			}
		}
		$criteria1=new CDbCriteria;
		$criteria1->compare('node_id',$photo->node_id);
		$novisit = Visit::model()->count($criteria1);
		
		if(isset(Yii::app()->user->id)){
			if($fav = Favourite::model()->findByPk(array('node_id' => $photo->node_id , 'user_id' => Yii::app()->user->id)))
			{
				$classname = 'rem-frm-fav';
				$titlefav = "Remove from favourite";
			}else{
				$classname = 'add-to-fav';
				$titlefav = "Add to favourite";
			}
		}else{
				$classname = 'add-to-fav';
				$titlefav = "Add to favourite";
		}
		
			if(isset(Yii::app()->user->id)){
			if($ra = ReportAbuse::model()->findByPk(array('node_id' => $photo->node_id , 'user_id' => Yii::app()->user->id)))
			{
				$rtext = 'Undo';
				$titlera = "undo your action";
			}else{
				$rtext = 'Abuse Report';
				$titlera = "Report this as not a ganesh";
			}
		}else{
				$rtext = 'Abuse Report';
				$titlera = "Report this as not a ganesh";
		}
	
		$prev = $this->getNextOrPrevId("Photo",$photo->id, "next");
		
		$nxt = $this->getNextOrPrevId("Photo",$photo->id, "prev");
		
		$photo->counter = $photo->counter + 1 ;
		$photo->save();
		$this->render('view',array(
			'photo'=>$photo,
            'newComment'=>$newComment,
			'modaks' => $modaks,
			'novisit'=>$novisit,
			'classname'=>$classname,
			'titlefav'=>$titlefav,
			'rtext'=>$rtext,
			'titlera'=>$titlera,
			'prev'=>$prev,
			'nxt'=>$nxt,
		));
	}

	public function actionLoadRelated()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('type',PhotoUploadCategory::Normal);
		$criteria->order = 'id DESC';
        $criteria->limit = 30;
		$elementsList=Photo::model()->findAll($criteria);//->with('comments')
		
		
		echo $resp = $this->renderPartial('_related',array(
		'elementsList'=>$elementsList,
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
			
			$model = $this->loadModel($id);
			$nodeid = $model->node_id;
			$node = Node::model()->findByPk($nodeid);
			$node->status = 0;
			if($node->save()){
				$model->delete();
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('photo/index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Photo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Photo']))
			$model->attributes=$_GET['Photo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Photo::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='photo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * @param null $userId
     * if the userSlug is given then the particular user's uploaded photoes will be visible
     * else the logged in user's photo will be shown
     * if the user is not logged in then he will be redirected to loginpage
     */
    public function actionUserPhoto($userSlug = null){
        if($userSlug === null){
            if(($user = User::model()->findByAttribute(array('slug'=> $userSlug))) !== null )
            {
                $elementsList= $user->photoes ;
            }
        }
        elseif(!Yii::app()->user->isGuest){
            //user is logged in show him his uploaded photoes

        }
    }
}
