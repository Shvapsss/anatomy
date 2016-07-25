<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    
        private $_id;
        
	public function authenticate()
	{                
            $email = strtolower($this->username);
            $user = User::model()->find('LOWER(email)=?',array($email));
            if($user === null)
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            else if(!$user->validatePassword($this->password))
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else
            {
                $this->_id = $user->id;
                $this->username = $user->email;
                $this->errorCode = self::ERROR_NONE;
                $this->setState('role', $user->role);
                // set user login (email or full name)
                $this->setState('nickname', $this->getNickname($email, $user->firstname, $user->lastname));
            }
            return $this->errorCode==self::ERROR_NONE;
	}
        
        public function getId()
        {
            return $this->_id;
        }        
        
        public function getNickname($email, $firstname, $lastname) 
        {
                $nick = $email; 
                if($firstname) {
                        $nick = $firstname.(($lastname) ? ' '.$lastname : ''); 
                }
                return $nick; 
        }        
}