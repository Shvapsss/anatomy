<?php

/**
 * @property string $savePath
 */
class UploadableFileBehavior extends CActiveRecordBehavior{

    public $attributeName = 'file';
    
    public $savePathAlias = 'webroot.files';

    public $scenarios = array('insert','update');

    public $fileTypes = 'doc,docx,xls,xlsx,odt,pdf';
    
    public $maxFileSize = 20971520; // 20 megabites
 
    /**
     * Shortoce for Yii::getPathOfAlias($this->savePathAlias).DIRECTORY_SEPARATOR.
     * @return string file upload path
     */
    public function getSavePath(){
        return Yii::getPathOfAlias($this->savePathAlias).DIRECTORY_SEPARATOR;
    }
 
    public function attach($owner){
        
        parent::attach($owner);
       
        if(in_array($owner->scenario, $this->scenarios)){
           
            // Add file valitator
            $fileValidator=CValidator::createValidator('file',$owner,$this->attributeName,
                array('types' => $this->fileTypes, 'allowEmpty' => true, 'maxSize' => $this->maxFileSize));
            $owner->validatorList->add($fileValidator);
        }
    }
 
    public function beforeSave($event){
        if(in_array($this->owner->scenario, $this->scenarios) && ($file=CUploadedFile::getInstance($this->owner, $this->attributeName))){
            // Delete the old file
            $this->deleteFile();
 
            $this->owner->setAttribute($this->attributeName, $file->name);
            $file->saveAs($this->savePath.$file->name);
        }
    }
 
    // TODO Rename to after delete
    public function beforeDelete($event){
        $this->deleteFile();
    }
 
    public function deleteFile($filePath = null){
        $filePath = ($filePath)? $filePath : $this->savePath.$this->owner->getAttribute($this->attributeName);
        if(@is_file($filePath))
            @unlink($filePath);
    }
}