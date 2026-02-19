<?php

class BookController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     */
    public function filterAccessControl($filterChain)
    {
        $access = new AccessControl();
        $access->filter($filterChain);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Book;

        if (isset($_POST['Book'])) {
            $model->attributes = $_POST['Book'];
            
            // Handle file upload
            if ($uploadedFile = CUploadedFile::getInstance($model, 'cover_image')) {
                $fileName = time() . '_' . $uploadedFile->getName();
                $uploadPath = Yii::getPathOfAlias('webroot') . '/images/covers/';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                if ($uploadedFile->saveAs($uploadPath . $fileName)) {
                    $model->cover_image = $fileName;
                }
            }
            
            if ($model->save()) {
                // Notify subscribers about new book
                BookObserver::notifySubscribers($model);
                
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $oldCover = $model->cover_image;

        if (isset($_POST['Book'])) {
            $model->attributes = $_POST['Book'];
            
            // Handle file upload
            if ($uploadedFile = CUploadedFile::getInstance($model, 'cover_image')) {
                $fileName = time() . '_' . $uploadedFile->getName();
                $uploadPath = Yii::getPathOfAlias('webroot') . '/images/covers/';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Delete old file
                if ($oldCover && file_exists($uploadPath . $oldCover)) {
                    unlink($uploadPath . $oldCover);
                }
                
                if ($uploadedFile->saveAs($uploadPath . $fileName)) {
                    $model->cover_image = $fileName;
                }
            } else {
                $model->cover_image = $oldCover;
            }
            
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            
            // Delete cover image
            if ($model->cover_image) {
                $uploadPath = Yii::getPathOfAlias('webroot') . '/images/covers/';
                if (file_exists($uploadPath . $model->cover_image)) {
                    unlink($uploadPath . $model->cover_image);
                }
            }
            
            $model->delete();

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Book', array(
            'criteria' => array(
                'with' => 'authors',
            ),
            'sort' => array(
                'attributes' => array(
                    'title',
                    'year',
                    'isbn',
                    'authors' => array(
                        'asc' => 'authors.full_name',
                        'desc' => 'authors.full_name DESC',
                    ),
                ),
            ),
        ));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Book('search');
        $model->unsetAttributes();
        if (isset($_GET['Book'])) {
            $model->attributes = $_GET['Book'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * @param integer $id the ID of the model to be loaded
     * @return Book the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Book::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Book $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'book-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
