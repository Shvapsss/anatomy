<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mailer
 * @author Dmitry Surzhikov http://dmitry-devstyle.pp.ua
 */

class Mailer
{
        private $_mail = null;
        
        public static function init()
        {
                static $initObj = null;
                
                if ( $initObj == null )
                        $initObj = new Mailer();
                
                return $initObj;
        }
        
        private function __construct()
        {
                Yii::import('application.extensions.*');
                require 'Zend/Mail.php';

                $this->_mail = new Zend_Mail('utf-8'); 
                $this->_mail->setHeaderEncoding(Zend_Mime::ENCODING_QUOTEDPRINTABLE); 
        }
   
        public function handle()
        {
                return $this->_mail;
        }
        
        /**
         * sendMail
         * @desc send mail 
         * @param string $from - who was sent email
         * @param string $to - who has been sent to email
         * @param string $subject - sbject of email
         * @param string $body - body of email
         * @param boolean $isHtml - if true bady have html, if false - only text
         * @param string $toName - name who has been sent to email
         */
        public static function sendMail($from, $to, $subject, $body, $isHtml = false, $toName = '') 
        {
                // get mailer instance
                $zendMail = self::init()->handle();
                
                /* clear attributes */
                $zendMail->clearSubject();
                $zendMail->clearDefaultFrom();
                $zendMail->clearFrom();
                $zendMail->clearDefaultReplyTo();
                $zendMail->clearReplyTo();
                $zendMail->clearRecipients();
                $zendMail->clearDefaultTransport();
                $zendMail->clearReturnPath();
                $zendMail->clearMessageId();
                
                
                $zendMail->addTo($to, $toName);
                $zendMail->setFrom($from);
                $zendMail->setSubject($subject);

                if($isHtml)
                        $zendMail->setBodyHtml($body);
                else $zendMail->setBodyText($body);

                $zendMail->send();
        }
        
        /**
         * emailReplacer
         * @desc replace placeholders in the email content
         * @param string $content - email data (subject or email body)
         * @param array $replaceArr - array with replace value for placeholder. For example array('{FIRSTNAME}'=>'Jhon','{LASTNAME}'=>'Karter'); 
         * @return type
         */
        public static function emailReplacer($content, $replaceArr)
        {
                if(count(Yii::app()->params->placeholders)) 
                {
                        foreach(Yii::app()->params->placeholders as $placeholder => $desc)
                                if(isset($replaceArr[$placeholder]))
                                        $content = str_replace($placeholder, $replaceArr[$placeholder], $content);
                }
                
                return $content;
        }
}

?>
