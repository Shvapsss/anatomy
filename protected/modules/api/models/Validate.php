<?php

/**
 * Validate class
 */
class Validate extends CModel
{
    public $id = false;
    public $limit = '100';
    public $offset = '0';
    
    /**
     * global errors array of Validate model 
     * @var array 
     */
    public $errors = array();

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('id', 'integerAttribute'),
            array('limit', 'integerAttribute'),
            array('offset', 'integerAttribute'),
        );
    }
    
    public function requiredAttribute($attribute)
    {
        if($this->$attribute === FALSE)
            $this->errors[] = 2;
        
        if(count($this->errors)) $this->addErrors($this->errors);   
    }
    
    public function integerAttribute($attribute)
    {
        if($this->$attribute && !ctype_digit($this->$attribute))
            $this->errors[] = 1;
        
        if(count($this->errors)) $this->addErrors($this->errors);
    }
    
   /**
    * Returns the list of attribute names.
    * By default, this method returns all public properties of the class.
    * You may override this method to change the default.
    * @return array list of attribute names. Defaults to all public properties of the class.
    */
   public function attributeNames()
   {
        $className=get_class($this);
        if(!isset(self::$_names[$className]))
        {
            $class=new ReflectionClass(get_class($this));
            $names=array();
            foreach($class->getProperties() as $property)
            {
                $name=$property->getName();
                if($property->isPublic() && !$property->isStatic())
                    $names[]=$name;
            }
            return self::$_names[$className]=$names;
        }
        else
            return self::$_names[$className];
   }

}