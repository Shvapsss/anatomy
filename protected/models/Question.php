<?php

/**
 * This is the model class for table "{{question}}".
 *
 * The followings are the available columns in table '{{question}}':
 * @property integer $id
 * @property integer $test_id
 * @property string $explanation
 * @property string $question
 * @property string $text
 *
 * The followings are the available model relations:
 * @property Answer[] $answers
 * @property Test $test
 */
class Question extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{question}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('test_id, title', 'required'),
            array('test_id, sorted, active', 'numerical', 'integerOnly' => true),
            array('explanation, title, filename', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, test_id, explanation, title, sorted, active, text1, text2', 'safe'),
        );
    }

    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => 'questions.active=1',
            //'order' => 'sorter',
            ),
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
            'answers' => array(self::HAS_MANY, 'Answer', 'question_id'),
            'test' => array(self::BELONGS_TO, 'Test', 'test_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'test_id' => 'Test ID',
            'explanation' => 'Explanation',
            'title' => 'Question',
            'sorted' => 'Sorted',
            'active' => 'Active',
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
        $criteria->compare('test_id', $this->test_id);
        $criteria->compare('explanation', $this->explanation, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('text1', $this->text1, true);
        $criteria->compare('text2', $this->text2, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Question the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
