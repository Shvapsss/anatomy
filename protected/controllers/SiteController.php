<?php

class SiteController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '/layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionSort()
    {
        // очерёдность
        $sql = "UPDATE {{chapter}}
    SET sorted = CASE
        id ";
        $i = 0;
        foreach ($_POST['sorted'] as $id) {
            $sql .= sprintf("
            WHEN %d THEN %d ", $id, $i);
            $i++;
        }
        $sql .= "END
    WHERE id IN (" . implode(',', $_POST['sorted']) . ")";
        Yii::app()->db->createCommand($sql)->query();
    }

    /**
     * Manages all chapters.
     */
    public function actionIndex()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sortable.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/chapterAdmin.js', CClientScript::POS_END);
        $model = new Chapter('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Chapter'])) {
            $model->attributes = $_GET['Chapter'];
        }

        $this->render('manage', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Chapter;

        $this->performAjaxValidation($model);

        if (isset($_POST['Chapter'])) {
            $model->attributes = $_POST['Chapter'];
            if ($model->save()) {
                $this->redirect(array('/site'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if (isset($_POST['Chapter'])) {
            $model->attributes = $_POST['Chapter'];
            if ($model->save()) {
                $this->redirect(array('/site'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (Yii::app()->user->isGuest) {

            $this->layout = 'login';
            $model = new LoginForm();

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];

                // validate user input and redirect to default admin page if valid
                if ($model->validate() && $model->login()) {
                    // redirect autorized user to default admin page
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
            // display the login form
            $this->render('login', array('model' => $model));
        } else {
            if (Yii::app()->user->role == 'admin') {
                // redirect to "index" action of "default" controller of current admin module
                $this->redirect(Yii::app()->homeUrl);
            }
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Chapter the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Chapter::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Chapter $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'chapter-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
