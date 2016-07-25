<?php
class AliasValidate extends CValidator{
    
    /**
    * Validates the attribute of the object.
    * If there is any error, the error message is added to the object.
    * @param CModel $object the object being validated
    * @param string $attribute the attribute being validated
    */
     protected function validateAttribute($object, $attribute) {

        $aliasModel = new Alias(); 
        $aliasModel->alias = Alias::aliasCreator($object->c_alias);
        $aliasModel->validate();
        if($aliasModel->getErrors()) {
                $err = $aliasModel->getErrors(); 
                $this->addError($object, $attribute, $err['alias'][0]);
        }
    }
   
}