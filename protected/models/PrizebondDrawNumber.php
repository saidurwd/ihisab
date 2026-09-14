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
class PrizebondDrawNumber extends CActiveRecord {    
    
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
            array('draw_id, winning_number, prize_id', 'required'),
            array('draw_id, prize_id', 'numerical', 'integerOnly' => true),
            array('winning_number', 'length', 'max' => 10),            
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
    public function search($id) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->condition = 't.draw_id=' . $id;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.draw_id', $this->draw_id);
        $criteria->compare('t.winning_number', $this->winning_number, true);
        $criteria->compare('t.prize_id', $this->prize_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.prize_id'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PrizebondDrawNumber the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getMatch($winning_number) {
        $array = Prizebond::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id . ' AND sl_number="' . $winning_number . '"'));
        $total = count($array);
        if ($total <= 0) {
            return null;
        } else {
            return '<i class="fa fa-bell text-danger"></i> Match Found';
        }
        return $total;
    }

}
