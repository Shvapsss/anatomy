<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $email;
	public $pass;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email', 'required' , 'message'=>'Email cannot be blank.'),
			array('pass', 'required' , 'message'=>'Password cannot be blank.'),
                        // added validation by email 
                        array('email', 'email' , 'message'=>'Please enter a valid email.'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('pass', 'authenticate'),
                    
                        // check confirm email user or not
			array('password', 'confirmed', 'on' => 'user_login'),                    
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
                        'email' => 'Email', 
			'rememberMe' => 'Запомнить меня в следующий раз',
                        'pass' => 'Пароль',
		);
	}
        
        /**
	 * Checking confirmed email user or not.
	 * This is the 'confirmed' validator as declared in rules().
	 */        
        public function  confirmed($attribute, $params)
        {
                //get user data by email value 
                $uData = User::model()->find("email = :email ", array(':email' => $this->email));
                
                if($uData && ($uData['confirmed']==0)) {
                        $this->addError('email', '');                    
                        $this->addError('pass','Incorrect username or password.');
                }
        }        

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{ 
			$this->_identity=new UserIdentity($this->email,$this->pass); 
                        if(!$this->_identity->authenticate()) {
				$this->addError('email', '');
                                $this->addError('pass', 'Incorrect username or password.');
                        }      
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email, $this->pass);
			$this->_identity->authenticate();
		}
                
//		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
//		{
//			$duration = $this->rememberMe ? 3600*24*30 : 0; // 30 days
//			Yii::app()->user->login($this->_identity, $duration);
//			return true;
//		}
//		else
//			return false;
                
                $isAuth = false; 
                
                switch($this->_identity->errorCode) 
                {
                    case UserIdentity::ERROR_NONE:
                        
			$duration = $this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			$isAuth = true;
                        
                        break;
                    
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $this->addError('email','Email address is incorrect.');
                        break;
                    
                    default: // UserIdentity::ERROR_PASSWORD_INVALID
                        $this->addError('password','Password is incorrect.');
                        break;
                }
                
                return $isAuth; 
	}
}
