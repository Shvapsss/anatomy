<?php

/**
 * Description of TestController
 *
 * @author dev@dump.com.ua Андрій
 */
class TestController extends Controller
{

    public function actionIndex()
    {
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/testAdmin.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/testAdmin.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/sortable.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js/font-awesome-4.4.0/css/font-awesome.min.css');
        // Yii::app()->clientScript->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js/imperavi/redactor.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/imperavi/redactor.min.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/imperavi/lang/ru.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/imperavi/plugins/table/table.js');

        $this->render('index', array(
            'tests' => Test::model()->findAll(array('order' => 'sorted')),
            'results' => ResultsText::model()->findAll()
        ));
    }

    public function actionResultsText()
    {
        if (isset($_POST['till'])) {

            $conn = Yii::app()->db;
            $transaction = $conn->beginTransaction();
            try {

                $conn->createCommand("TRUNCATE TABLE {{results_text}}")->execute();
                $sql = "INSERT INTO {{results_text}}(`from`,`till`,`text`) VALUES ";
                foreach ($_POST['till'] as $i => $till) {
                    if (!empty($_POST['text'][$i])) {
                        $items[] = '(' . (int) $_POST['from'][$i] . ', ' . (int) $_POST['till'][$i] . ', "' . $_POST['text'][$i] . '")';
                    }
                }
                $conn->createCommand($sql . implode(',', $items))->execute();

                $transaction->commit();

                $this->renderPartial('li_results', array('results' => ResultsText::model()->findAll()));
            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
    }

    public function actionCreate()
    {
        $model = new Test();
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        if ($model->save()) {
            $this->send($this->renderPartial('li_test', array('model' => $model), true), $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false), false);
        }
    }

    public function actionEdit($id)
    {
        $model = Test::model()->findByPk($id);
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        if ($model->save()) {
            $this->send($model->title, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionActivate($id)
    {
        $model = Test::model()->findByPk($id);
        $model->active = empty($model->active) ? 1 : 0;
        if ($model->save()) {
            $this->send($model->active, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionPro($id)
    {
        $model = Test::model()->findByPk($id);
        $model->pro = empty($model->pro) ? 1 : 0;
        if ($model->save()) {
            $this->send($model->pro, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionSort()
    {
        // очерёдность
        $sql = "UPDATE {{test}}
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

    public function actionDelete($id)
    {
        $model = Test::model()->findByPk($id);
        if ($model && $model->delete()) {
            $this->send(true, true);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function send($html = '', $status = false)
    {
        echo CJSON::encode(array(
            'status' => $status ? $status : 'error',
            'html' => $html
        ));
        Yii::app()->end();
    }

}
