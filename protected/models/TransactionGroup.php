<?php

/**
 * This is the model class for table "{{transaction_group}}".
 *
 * The followings are the available columns in table '{{transaction_group}}':
 * @property string $id
 * @property integer $user
 * @property string $title
 * @property string $members
 */
class TransactionGroup extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{transaction_group}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, title', 'required'),
            array('user', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 150),
            array('members', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, title, members', 'safe', 'on' => 'search'),
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
            'title' => 'Group name',
            'members' => 'Members',
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
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.members', $this->members, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 't.title'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TransactionGroup the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function checkUser($id) {
        if (($model = TransactionGroup::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

    public static function get_group($id) {
        $value = TransactionGroup::model()->findByAttributes(array('id' => $id));
        if (empty($value->title)) {
            return null;
        } else {
            return $value->title;
        }
    }

    public static function get_members($id) {
        $model = TransactionGroup::model()->findByPk($id);
        if (!empty($model->members)) {
            $exval = explode(',', $model->members);
            $tags = '';
            $total = count($exval);
            //$total_minus = ($total - 1);
            for ($i = 0; $i < $total; $i++) {
                $tags .= '<span class="btn btn-xs btn-info">' . TransactionContact::get_contact_name($exval[$i]) . '</span> ';
            }
        } else {
            $tags = null;
        }
        return $tags;
    }

}
