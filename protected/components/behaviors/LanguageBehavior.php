<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LanguageBehavior extends CBehavior
{

        public function attach($owner)
        {
                $owner->attachEventHandler('onBeginRequest', array($this, 'handleLanguageBehavior'));
        }

        public function handleLanguageBehavior($event)
        {
                $app  = Yii::app();
                $user = $app->user;
                
                // If there is a post-request, redirect the application to the provided url of the selected language 
                if(isset($_POST['language'])) {
                        $lang = $_POST['language'];
                        $MultilangReturnUrl = $_POST[$lang];
                        $app->request->redirect($MultilangReturnUrl);
                }        

                if (isset($_GET['language']))
                {
                        $app->language = $_GET['language'];
                        $user->setState('language', $_GET['language']);
                        $cookie = new CHttpCookie('language', $_GET['language']);
                        $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
                        $app->request->cookies['language'] = $cookie;
                        /*
                        * other code, such as updating the cache of some components that change when you change the language
                        */
                }
                else if ($user->hasState('language'))
                        $app->language = $user->getState('language');
                else if (isset($app->request->cookies['language']))
                        $app->language = $app->request->cookies['language']->value;
        }       
}