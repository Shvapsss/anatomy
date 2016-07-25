<?php
class AliasBehavior extends CActiveRecordBehavior
{
    // Custom user alias
    public $c_alias;
    
    public $scenarios = array('insert','update');
    
    public function attach($owner){
        
        parent::attach($owner);
       
        $owner->getMetaData()->addRelation('alias', array(
            $owner::BELONGS_TO,
            'Alias',
            'alias_id',
        ));
        
        if(in_array($owner->scenario, $this->scenarios)){
            
            // Add alias validator   
            $alias_validator = CValidator::createValidator('application.components.validators.AliasValidate', $owner, 'c_alias', array('on'=>array('insert', 'update')));
            $owner->validatorList->add($alias_validator);
        }
        
    }
    
    /**
     * getEntityByAlias
     * @desc Returned entity data by alias
     * @param string $alias - entity alias 
     * @return mixed - return entity data or false
     */
    public function getEntityByAlias($alias)
    {
        $pData = false; 

        if($alias) {
            $aData = Alias::model()->find('alias = :alias', array('alias' => $alias));
            $pData = ($aData) ? $this->owner->find('alias_id = :alias_id', array(':alias_id' => $aData->id)) : false;
        }

        return $pData; 
    }
    
    public function beforeSave($event){
        if($this->owner->scenario == 'insert') {
            
            // save alias 
            $aliasModel = new Alias();
            $aliasModel->alias = ($this->owner->c_alias) ? Alias::aliasCreator($this->owner->c_alias) : Alias::aliasCreator($this->owner->title);
            $aliasModel->save();
            $this->owner->alias_id = $aliasModel->id;
        }
        elseif($this->owner->scenario == 'update') {
           
            // updated alias data 
            if($this->owner->c_alias)
                Alias::model()->updateByPk($this->owner->alias_id, array('alias' => Alias::aliasCreator($this->owner->c_alias)));
        }
    }
    
    public function beforeDelete($event){
        $id = Yii::app()->request->getQuery('id');
                
        if($id){
            // remove relaited alias 
            $pData = $this->owner->findByPk($id);
            if(isset($pData->alias_id)) 
                Alias::model()->deleteByPk($pData->alias_id);
        }       
    }
 
}