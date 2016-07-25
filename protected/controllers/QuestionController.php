<?php

/**
 * Description of QuestionController
 *
 * @author dev@dump.com.ua Андрій
 */
class QuestionController extends Controller
{

    public function actionIndex($id)
    {
        $html = $this->renderPartial('index', array(
            'models' => Question::model()->findAll(array('condition' => 'test_id=' . $id, 'order' => 'sorted'))
                ), true);
        $this->send($html, $id);
    }

    public function actionCreate()
    {
        $model = new Question();
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        $model->test_id = isset($_POST['test_id']) ? $_POST['test_id'] : '';
        if ($model->save()) {
            $this->send($this->renderPartial('li_question', array('model' => $model), true), $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false), false);
        }
    }

    public function actionRedactor()
    {
        $model = Question::model()->findByPk($_POST['id']);
        if ($model) {
            $model->text1 = $_POST['text1'];
            $model->text2 = $_POST['text2'];

            if ($model->save()) {
                $this->send('', $model->id);
            } else {
                $this->send(CHtml::errorSummary($model, false));
            }
        }
    }

    public function actionEdit($id)
    {
        $model = Question::model()->findByPk($id);
        $model->title = isset($_POST['title']) ? $_POST['title'] : '';
        if ($model->save()) {
            $this->send($model->title, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionActivate($id)
    {
        $model = Question::model()->findByPk($id);
        $model->active = empty($model->active) ? 1 : 0;
        if ($model->save()) {
            $this->send($model->active, $model->id);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionDelete($id)
    {
        $model = Question::model()->findByPk($id);
        if ($model && $model->delete()) {
            $this->send(true, true);
        } else {
            $this->send(CHtml::errorSummary($model, false));
        }
    }

    public function actionSort()
    {
        // очерёдность
        $sql = "UPDATE {{question}}
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

    public function actionUnlink()
    {
        if (isset($_POST['id']) && isset($_POST['src'])) {
            $id = (int) $_POST['id'];
            $src = $_POST['src'];
            if (in_array($src, array('question', 'explanation'))) {
                $model = Question::model()->findByPk($id);
                if ($model) {
                    $file = $_SERVER['DOCUMENT_ROOT'] . $model->$src;
                    $model->$src = '';
                    if ($model->save()) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                        $this->send(true, true);
                    } else {
                        Debug::dump(array($_POST, $model, $file));
                        $this->send(CHtml::errorSummary($model, false));
                    }
                }
            }
        }
        $this->send('Произошла ошибка. Удаление файла не было произведено.');
    }

    public function actionUpload($type, $id)
    {
        $filepath = urldecode(@$_SERVER['HTTP_X_FILE_NAME']);
        $uploaded = $this->utfPathinfo($filepath);
        $file = file_get_contents("php://input");
        $name = '/files/' . $type . '/' . md5($uploaded['basename'] . time()) . '.' . $uploaded['extension'];
        if (in_array($type, array('question', 'explanation'))) {
            $ok = file_put_contents($_SERVER['DOCUMENT_ROOT'] . $name, $file);
            $update[$type] = $name;
            if (isset($_GET['filename'])) {
                $update['filename'] = $_GET['filename'];
            }
            if ($ok && Question::model()->updateByPk($id, $update)) {
                $this->send($name, $ok, $update);
            }
        }
        $this->send('Произошла неизвестная ошибка. Обратитесь к администратору.');
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

    /**
     * http://php.net/manual/en/function.pathinfo.php#107461
     *
     * Use this function in place of pathinfo to make it work with UTF-8 encoded file names too
     * @param type $filepath
     * @return type
     */
    public function utfPathinfo($filepath)
    {
        $upload = array();
        preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
        // if($m[1]) $ret['dirname']=$m[1];
        if ($m[2]) {
            $upload['basename'] = $m[2];
        } // имя файла с раширением
        if ($m[5]) {
            $upload['extension'] = strtolower($m[5]);
        } // расширение
        if ($m[3]) {
            $upload['filename'] = $m[3];
        } // имя файла
        return $upload;
    }

}
