<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhpAuthManager
 *
 * @author dsurjikov
 */

class PhpAuthManager extends CPhpAuthManager {
    
        public function init()
        {
                // get a hierarchy of roles from auth.php file 
                if($this->authFile===null) {
                        $this->authFile=Yii::getPathOfAlias('application.config.auth').'.php';
                }
                
                parent::init();

                // For guests we have, and so the role of the default "guest"
                if(!Yii::app()->user->isGuest) { 
                        // Associate role defined in the database with the user ID
                        // return UserIdentity.getId()
                        $this->assign(Yii::app()->user->role, Yii::app()->user->id);
                }
        }
}
