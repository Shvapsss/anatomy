<?php
class MultiplyListBehavior extends CActiveRecordBehavior
{
    /**
    * @var string public property name
    */
    public $attribute;
    /**
    * @var string MANY_MANY relation name
    */
    public $relation;
    /**
    * @var string primary key name of related model
    */
    public $relationPk = 'id';

    protected $value;

    /**
    * @param CActiveRecord $owner
    */
    public function attach($owner)
    {
        $validator = new CSafeValidator;
        $validator->attributes = array($this->attribute);
        $owner->getValidatorList()->add($validator);
        parent::attach($owner);
    }

    public function canGetProperty($name)
    {
        return $this->validProperty($name);
    }

    public function canSetProperty($name)
    {
        return $this->validProperty($name);
    }

    public function __get($name)
    {
        if (!$this->validProperty($name))
            return null;

        if ($this->value === null)
            $this->value = CHtml::listData($this->getOwner()->{$this->relation}, $this->relationPk, $this->relationPk);

        return $this->value;
    }

    public function __set($name, $value)
    {
        if ($this->validProperty($name))
            $this->value = $value;
    }

    protected function validProperty($name)
    {
        if (empty($this->attribute))
            throw new CException(__CLASS__ . '::attribute is empty');
        if (empty($this->relation))
            throw new CException(__CLASS__ . '::relation is empty');
        if (empty($this->relationPk))
            throw new CException(__CLASS__ . '::relationPk is empty');

        return $name == $this->attribute;
    }
}