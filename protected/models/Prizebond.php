<?php

/**
 * This is the model class for table "{{prizebond}}".
 *
 * The followings are the available columns in table '{{prizebond}}':
 * @property integer $id
 * @property integer $user
 * @property integer $series
 * @property string $sl_number
 * @property string $purchase_date
 * @property integer $status
 */
class Prizebond extends CActiveRecord {

    public $file_source;
    public $draw_id;
    public $winning_number;
    public $prize_id;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{prizebond}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, sl_number', 'required'),
            array('user, series, status', 'numerical', 'integerOnly' => true),
            array('sl_number', 'length', 'max' => 10),
            array('purchase_date', 'safe'),
            //array('file_source', 'file', 'types' => 'xls,xlsx,csv', 'allowEmpty' => false, 'minSize' => 2, 'maxSize' => 1024 * 1024 * 50, 'tooLarge' => 'The file was larger than 50MB. Please upload a smaller file.', 'wrongType' => 'File format was not supported.', 'tooSmall' => 'File size was too small or empty.'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, series, sl_number, purchase_date, status', 'safe', 'on' => 'search'),
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
            'user' => 'User',
            'series' => 'Series',
            'sl_number' => 'Number',
            'purchase_date' => 'Purchase Date',
            'status' => 'Status',
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
        $criteria->alias = 't';
        $criteria->condition = 't.user=' . Yii::app()->user->id;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.series', $this->series);
        $criteria->compare('t.sl_number', $this->sl_number, true);
        $criteria->compare('t.purchase_date', $this->purchase_date, true);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.sl_number'),
        ));
    }

    public function search_match() {
        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->select = 't.sl_number, t.purchase_date, d.draw_id AS draw_id, d.winning_number AS winning_number, d.prize_id AS prize_id';
        $criteria->condition = 't.user=' . Yii::app()->user->id;
        $criteria->join = "INNER JOIN {{prizebond_draw_number}} AS d ON(t.sl_number=d.winning_number)";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 't.sl_number'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Prizebond the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function checkUser($id) {
        if (($model = Prizebond::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/prizebond/admin'));
        } else {
            return true;
        }
    }

}
