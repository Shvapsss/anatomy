<?php

class ApiController extends Controller
{

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    private $model = null;
    private $errors = array();
    // default response error status
    private $response = array('errors' => 0);
    // global get parameters
    private $params = null;
    public $layout;

    public function actionIndex()
    {
        $this->layout = '/layouts/column1';
        $this->render('index');
    }

    /**
     * actionChapters
     * @get_param int id (optional) - chapter primary key
     * @get_param int limit (optional)
     * @get_param int offset (optional)
     * @desc return json array of chapters or json array of error codes
     */
    public function actionTests()
    {
        if ($this->response['errors'] == 0) {
            $this->response['data'] = array();

            if ($this->model['id']) {
                $data = Test::model()->active()->with('questions:active.answers:active')->findByPk($this->model['id']);
                /*
                  if ($data) {
                  $data->file = Yii::app()->createAbsoluteUrl('/files/pdf/' . $data->file);
                  $this->response['data'][] = $data->getAttributes();
                  }
                 */
            } else {

                $data = Test::model()->active()->with('questions:active.answers:active')->findAll(array(
                    'limit' => $this->model['limit'],
                    'offset' => $this->model['offset'],
                ));

                $tests = array();
                foreach ($data as $t => $test) {
                    $tests[$t]['test_id'] = $test->id;
                    $tests[$t]['pro_test'] = $test->pro;
                    $tests[$t]['test_index'] = $test->sorted;
                    $tests[$t]['test_name'] = $test->title;

                    $questions = array();
                    foreach ($test->questions as $i => $question) {
                        $questions[$i]['question_id'] = $question->id;
                        $questions[$i]['question_index'] = $question->sorted;
                        $questions[$i]['question_name'] = $question->title;
                        $questions[$i]['explanation'] = $question->explanation ? Yii::app()->createAbsoluteUrl($question->explanation) : false;
                        //$questions[$i]['question_text_before'] = $question->text1;
                        //$questions[$i]['question_text_after'] = $question->text2;
                        $questions[$i]['question'] = $question->text1 . ($question->question ? '<img src="' . Yii::app()->createAbsoluteUrl($question->question) . '" />' : '') . $question->text2;
                        $questions[$i]['right_answer'] = 0;

                        $answers = array();
                        foreach ($question->answers as $r => $answer) {
                            $answers[] = $answer->title;
                            if ($answer->right) {
                                $questions[$i]['right_answer'] = $r;
                            }
                        }
                        $questions[$i]['answers'] = $answers;
                    }
                    $tests[$t]['questions'] = $questions;
                }
                $this->response['data']['tests'] = $tests;

                $result_desc = array();
                foreach (ResultsText::model()->findAll() as $item) {
                    $result_desc[] = array(
                        'minimum_percentage' => $item->till,
                        'maximum_percentage' => $item->from,
                        'description' => $item->text
                    );
                }

                $this->response['data']['result_desc'] = $result_desc;
            }
        }
        $this->response['data'] = array($this->response['data']);

        $this->_sendResponse(200, CJSON::encode($this->response));
    }

    /**
     * actionChapters
     * @get_param int id (optional) - chapter primary key
     * @get_param int limit (optional)
     * @get_param int offset (optional)
     * @desc return json array of chapters or json array of error codes
     */
    public function actionChapters()
    {
        if ($this->response['errors'] == 0) {
            $this->response['data'] = array();

            if ($this->model['id']) {
                $data = Chapter::model()->findByPk($this->model['id']);
                if ($data) {
                    $data->file = Yii::app()->createAbsoluteUrl('/files/pdf/' . $data->file);
                    $this->response['data'][] = $data->getAttributes();
                }
            } else {

                $data = Chapter::model()->findAll(array('limit' => $this->model['limit'],
                    'offset' => $this->model['offset'],
                ));

                foreach ($data as $item) {
                    $item->file = Yii::app()->createAbsoluteUrl('/files/pdf/' . $item->file);
                    $this->response['data'][] = array_merge($item->getAttributes(), array('chapter_index' => $item->sorted));
                }
            }
        }

        $this->_sendResponse(200, CJSON::encode($this->response));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            $this->layout = '/layouts/column1';
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    protected function beforeAction($action)
    {
        $this->model = new Validate();

        // Set scenario based on action id
        $this->model->scenario = $action->getid();

        // Set model attributes
        $this->model->attributes = $_GET;

        $this->model->validate();
        $modelErrors = $this->model->getErrors();

        //print_r($modelErrors); die;

        $this->errors = (isset($modelErrors[0])) ? $modelErrors[0] : array();
        if (count($this->errors))
            $this->response['errors'] = array_values(array_unique($this->errors));

        return true;
    }

    public function filters()
    {

    }

    /**
     * _sendResponse
     * @desc outputs the response api data
     * @param int $status
     * @param string $body
     * @param string $content_type for examle 'text/html'
     */
    private function _sendResponse($status = 200, $body = '', $content_type = false)
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);

        // and the content type
        $content_type = ($content_type) ? $content_type : (($this->format) ? 'application/' . $this->format : 'text/html');

        // pages with body are easy
        if ($body != '') {
            header('Content-type: ' . $content_type . '; charset=utf-8');
            // send the body
            echo JSONHelper::json_fix_cyr($body);
        }
        // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            $this->renderPartial('default', array('message' => $message,
                'status' => $status,
                'statusCodeMessage' => $this->_getStatusCodeMessage($status),
                'signature' => $signature,
            ));
        }

        Yii::app()->end();
    }

    /**
     * _getStatusCodeMessage
     * @desc return status message by status code
     * @param int $status
     * @return string
     */
    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}
