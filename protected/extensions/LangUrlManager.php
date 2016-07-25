<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LangUrlManager
 *
 * @author Ekstazi
 * @ver 1.2
 */
class LangUrlManager extends CUrlManager
{
        /**
         * @vars (array)languages, (string)$langParam and  (array)$excludeModules - init form main config.
         */
        public $languages;
        
        public $langParam;
        
        public $excludeModules;

        public function parsePathInfo($pathInfo)
        {
                parent::parsePathInfo($pathInfo);
                
                $userLang=Yii::app()->getRequest()->getPreferredLanguage();                
                
                // set default language (like default source language)
                if(!isset($_GET[$this->langParam])) $_GET[$this->langParam] = Yii::app()->sourceLanguage;
                
                //if language pass via url use it
                if(isset($_GET[$this->langParam]) && in_array($_GET[$this->langParam], $this->languages)) 
                {
                        Yii::app()->language = $_GET[$this->langParam];
                //else if preffered language is allowed
                } 
                elseif(in_array($userLang,$this->languages)) {
                        Yii::app()->language = $userLang;
                //else use the first language from the list
                } 
                else  Yii::app()->language = $this->languages[0];
        }
        
        public function createUrl($route, $params=array(), $ampersand='&')
        {
                $module = (isset(Yii::app()->controller->module->id)) ? 
                                Yii::app()->controller->module->id : false;
            
                if(!in_array($module, $this->excludeModules))
                        if(!isset($params[$this->langParam]) && 
                                Yii::app()->language !== Yii::app()->sourceLanguage)
                            $params[$this->langParam] = Yii::app()->language;
                
                return parent::createUrl($route, $params, $ampersand);
        }
}