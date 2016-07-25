<?php

class AnswerController extends Controller
{

    public function actionIndex($id)
    {
        $html = $this->renderPartial('index', array(
            'models' => Answer::model()->findAll(array('condition' => 'question_id=' . $id, 'order' => 'id DESC'))
                ), true);
        $data = Question::model()->findByPk($id);
        $this->send($html, $id, $data->attributes);
    }

    public function actionCreate()
    {
        $model = new Answer();
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        $model->question_id = isset($_POST['question_id']) ? $_POST['question_id'] : '';
        if ($model->save()) {
            $this->send($this->renderPartial('li_answer', array('model' => $model), true), $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false), false);
        }
    }

    public function actionEdit($id)
    {
        $model = Answer::model()->findByPk($id);
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        if ($model->save()) {
            $this->send($model->title, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionActivate($id)
    {
        $model = Answer::model()->findByPk($id);
        $model->active = empty($model->active) ? 1 : 0;
        if ($model->save()) {
            $this->send($model->active, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionRight($id, $question_id)
    {
        Answer::model()->updateAll(array('right' => 0), 'question_id=' . $question_id);
        Answer::model()->updateByPk($id, array('right' => 1));
        $this->send(true, true);
    }

    public function actionDelete($id)
    {
        $model = Answer::model()->findByPk($id);
        if ($model && $model->delete()) {
            $this->send(true, true);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function send($html = '', $status = false, $data = array())
    {
        echo CJSON::encode(array(
            'status' => $status ? $status : 'error',
            'html' => $html,
            'data' => $data,
        ));
        Yii::app()->end();
    }

}
