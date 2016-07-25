<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LanguageSelector extends CWidget
{
        public function run()
        {
                $currentLang = Yii::app()->language;
                $languages = Yii::app()->params->languages;
                $this->render('languageSelector', array('currentLang' => $currentLang, 'languages'=>$languages));
        }
        
        public static function createMultilanguageReturnUrl($lang='')
        {
                $app  = Yii::app();
                
                if (count($_GET)>0) {
                        $arr = $_GET;
                        $arr['language']= $lang;
                }
                else $arr = array('language'=>$lang);
                
                $module = (isset($app->controller->module->id)) ? $module.'/' : '' ;
                $controller = $app->controller->id;
                $action = $app->controller->action->id;
                $url = $app->createUrl($module.$controller.'/'.$action, $arr);
                
                if($lang == $app->sourceLanguage) {
                        $arr['language'] = '';
                        $url = $app->createUrl($module.$controller.'/'.$action, $arr);
                        $url = '/'.trim($url, '\/');
                }
                
                return $url;
        }
}