<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $pass
 * @property string $passChanged
 * @property string $role
 * @property integer $confirmed
 * @property integer $registred
 * @property integer $confirmed
 */
class User extends CActiveRecord
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_BANNED = 'banned';

    public $verifyCode;    

    public $passOld;
    public $passNew;
    public $passRepeat;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user}}';
    }

    public function behaviors(){
        return array(
            'hasEnumFields' => array(
                'class' => 'application.components.behaviors.EnumItemsBehavior',
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // Common rules.
            array('email, firstname', 'required'),
            array('email','email'),
            array('email','unique'),
            array('pass', 'length', 'max'=>255),
            array('role', 'length', 'max'=>5),
            array('confirmed', 'safe'),
            array('email, firstname, lastname', 'length', 'max'=>128),
            array('firstname', 'match', 'pattern' => '/^[A-Za-z0-9А-Яа-я\s,]+$/u', 'message' => 'Username contains invalid characters.'),

            // Edit user rules.
            array('passRepeat', 'compare', 'compareAttribute'=>'passNew', 'on'=>'edit_user'),
            array('passNew, passRepeat', 'safe', 'on' => 'edit_user'),

            // Search rules.
            // @todo Please remove those attributes that should not be searched.
            array('id, email, firstname, lastname, pass, role, registred, confirmed', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'pass' => 'Пароль',
            'passRepeat' => 'Подтверждение пароля',
            'passOld' => 'Старый пароль',
            'passNew' => 'Новый пароль',
            'role' => 'Роль',
            'registred' => 'Дата регистрации',
            'confirmed' => 'Подтвержден',
            'verifyCode'=>'Проверочный код',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('firstname',$this->firstname,true);
        $criteria->compare('lastname',$this->lastname,true);
        $criteria->compare('pass',$this->pass,true);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('registred',$this->registred);
        $criteria->compare('confirmed',$this->confirmed);

        $sort = new CSort();
        $sort->attributes = array(
            'id' => array(
                'asc'  => 'id',
                'desc' => 'id desc',
            ),
            'firstname' => array(
                'asc'  => 'firstname',
                'desc' => 'firstname desc',
            ),
            'lastname' => array(
                'asc'  => 'lastname',
                'desc' => 'lastname desc',
            ),
            'role' => array(
                'asc'  => 'role',
                'desc' => 'role desc',
            ),
            'registred' => array(
                'asc'  => 'registred',
                'desc' => 'registred desc',
            ),
            'confirmed' => array(
                'asc'  => 'confirmed',
                'desc' => 'confirmed desc',
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 5,
            ),
            'sort' => $sort,
        ));
    }

    /**
     * Before save operations
    */
    public function beforeSave()
    {
        if($this->isNewRecord)
        {
            $this->registred = time();
            $this->pass = $this->hashPassword($this->pass);
        }
        if ($this->passNew)
        {
            $this->pass = $this->hashPassword($this->passNew);
        }
        return parent::beforeSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password)
    {
        return CPasswordHelper::verifyPassword($password,$this->pass);
    }

    /**
     * Generates the password hash.
     * @param string password
     * @return string hash
     */
    public function hashPassword($password)
    {
        return CPasswordHelper::hashPassword($password);
    }
    /**
     * 
     * @param type $attribute
     * @param type $params
     * @return boolean
     */
    public function checkOldPassword($attribute, $params)
    {
        if($this->passNew)
        {
            if (!CPasswordHelper::verifyPassword($this->passOld, $this->pass))
                $this->addError('passOld', 'This is not your password.');
        }   
    }

    public function comparePasswods($attribute, $params)
    {
        print_r($this->attributes);
        die($this->passChanged);
        if ($this->passChanged != $this->pass2)
            $this->addError('passChanged', 'Passwords does not mutch.');
    }

    public function getRoles()
    {
        //return array(self::ROLE_ADMIN => self::ROLE_ADMIN, self::ROLE_USER => self::ROLE_USER,);
        return $this->getEnumItems('role');
    }

    public function getStatuses()
    {
        return array(0 => 'нет', 1 => 'да');
    }
}