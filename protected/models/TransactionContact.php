<?php

/**
 * This is the model class for table "{{transaction_contact}}".
 *
 * The followings are the available columns in table '{{transaction_contact}}':
 * @property string $id
 * @property integer $user
 * @property string $contact_name
 * @property string $email
 */
class TransactionContact extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{transaction_contact}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, contact_name', 'required'),
            array('user', 'numerical', 'integerOnly' => true),
            array('contact_name, email', 'length', 'max' => 150),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, contact_name, email', 'safe', 'on' => 'search'),
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
            'contact_name' => 'Name',
            'email' => 'Email',
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
        $criteria->condition = 't.user IN(0,' . Yii::app()->user->id . ')';

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.contact_name', $this->contact_name, true);
        $criteria->compare('t.email', $this->email, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.contact_name'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TransactionContact the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function get_contact_name($id) {
        $value = TransactionContact::model()->findByAttributes(array('id' => $id));
        if (empty($value->contact_name)) {
            return null;
        } else {
            return $value->contact_name;
        }
    }

    public static function get_contact_email($id) {
        $value = TransactionContact::model()->findByAttributes(array('id' => $id));
        if (empty($value->email)) {
            return null;
        } else {
            return $value->email;
        }
    }

    public static function checkUser($id) {
        if (($model = TransactionContact::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

}
