<?php
class EnumItemsBehavior extends CActiveRecordBehavior
{
    /**
     * getEnumItems
     * @desc returned array of enum types of attribute
     * @param obj $model
     * @param string $attr
     * @return array
     */
    protected function getEnumItems($attr)
    {
        preg_match('/\((.*)\)/', $this->owner->tableSchema->columns[$attr]->dbType, $matches);

        foreach(explode(',', $matches[1]) as $value) {
            $value=str_replace("'",null,$value);
            $values[$value]=Yii::t('enumItem',$value);
        }

        return $values;
    }
}