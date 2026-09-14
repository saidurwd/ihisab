<?php

/**
 * This is the model class for table "{{prizebond_draw_number}}".
 *
 * The followings are the available columns in table '{{prizebond_draw_number}}':
 * @property integer $id
 * @property integer $draw_id
 * @property string $winning_number
 * @property integer $prize_id
 */
class ImportBondNumber extends CActiveRecord {

    public $file_source;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{prizebond_draw_number}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('draw_id, winning_number', 'required'),
            array('draw_id, prize_id', 'numerical', 'integerOnly' => true),
            array('winning_number', 'length', 'max' => 10),
            array('file_source', 'file', 'types' => 'xls,xlsx', 'allowEmpty' => false, 'minSize' => 2, 'maxSize' => 1024 * 1024 * 50, 'tooLarge' => 'The file was larger than 50MB. Please upload a smaller file.', 'wrongType' => 'File format was not supported.', 'tooSmall' => 'File size was too small or empty.'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, draw_id, winning_number, prize_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'draw_id' => 'Draw',
            'winning_number' => 'Winning Number',
            'prize_id' => 'Prize',
            'file_source' => 'File Source',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('draw_id', $this->draw_id);
        $criteria->compare('winning_number', $this->winning_number, true);
        $criteria->compare('prize_id', $this->prize_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ImportBondNumber the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
