<?php

/**
 * This is the model class for table "{{chapter}}".
 *
 * The followings are the available columns in table '{{chapter}}':
 * @property integer $id
 * @property string $title
 * @property integer $first_page
 * @property integer $paid
 * @property string $file
 * @property integer $upload_date
 */
class Chapter extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chapter}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, first_page, paid', 'required'),
            array('first_page, paid, sorted', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, first_page, paid, upload_date, sorted', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        );
    }

    /**
     * @return array behaviors.
     */
    public function behaviors()
    {
        return array(
            'uploadableFile' => array(
                'class' => 'behaviors.UploadableFileBehavior',
                'attributeName' => 'file',
                'savePathAlias' => 'webroot.files.pdf',
                'fileTypes' => 'pdf',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Название',
            'first_page' => 'Номер первой страницы',
            'upload_date' => 'Дата загрузки главы',
            'paid' => 'Платный',
            'file' => 'PDF файл',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('first_page', $this->first_page);
        $criteria->compare('paid', $this->paid);
        $criteria->compare('sorted', $this->sorted);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'sorted ASC',
            ),
            'pagination' => array(
                'pageSize' => 150,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chapter the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if (($this->scenario == 'insert' || $this->scenario == 'update') && $file = CUploadedFile::getInstance($this, 'file')) {
            // echo time() ; die();
            $this->upload_date = time();
        }
        return parent::beforeSave();
    }

}
