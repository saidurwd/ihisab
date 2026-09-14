<?php

/**
 * This is the model class for table "{{transaction_budget}}".
 *
 * The followings are the available columns in table '{{transaction_budget}}':
 * @property string $id
 * @property integer $user
 * @property string $tag_name
 * @property string $amount
 * @property string $created
 * @property string $modified
 */
class TransactionBudget extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{transaction_budget}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, tag_name', 'required'),
            array('user, tag_name', 'numerical', 'integerOnly' => true),
            array('amount', 'length', 'max' => 13),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, tag_name, amount, created, modified', 'safe', 'on' => 'search'),
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
            'tag_name' => 'Tag',
            'amount' => 'Amount',
            'created' => 'Created On',
            'modified' => 'Modofied On',
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
        $criteria->compare('t.tag_name', $this->tag_name);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 't.tag_name'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TransactionBudget the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function get_tag($id) {
        $value = TransactionBudget::model()->findByAttributes(array('id' => $id));
        if (empty($value->tag_name)) {
            return null;
        } else {
            return $value->tag_name;
        }
    }

    public static function get_budget($budget_id) {
        $value = TransactionBudget::model()->findByAttributes(array('id' => $budget_id));
        if (empty($value->amount)) {
            return 0.00;
        } else {
            return $value->amount;
        }
    }

    public static function checkUser($id) {
        if (($model = TransactionBudget::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

    public static function total_budget_current_month() {
        $balance = Yii::app()->db->createCommand()
                ->select('IFNULL(SUM(amount),0)')
                ->from('{{transaction_budget}}')
                ->where('user=' . Yii::app()->user->id)
                ->queryScalar();

        return abs($balance);
    }

    public static function total_expense_current_month() {
        $balance = Yii::app()->db->createCommand()
                ->select('IFNULL(SUM(amount),0)')
                ->from('{{transaction}}')
                ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH(NOW()) AND YEAR(created) = YEAR(NOW()) AND tag IN(SELECT c.tag_name FROM {{transaction_budget}} c WHERE c.user=' . Yii::app()->user->id . ')')
                ->queryScalar();

        return abs($balance);
    }

    public static function budget_balance_current_month() {
        $budget = TransactionBudget::total_budget_current_month();
        $expense = TransactionBudget::total_expense_current_month();
        $balance = $budget - $expense;
        if ($balance >= 0) {
            $return = '<span class="txt-color-blue"><i class="fa-fw fa fa-plus"></i> ' . number_format($balance, 2, '.', ',') . '</span>';
        } else {
            $return = '<span class="txt-color-red"><i class="fa-fw fa fa-minus"></i> ' . number_format(abs($balance), 2, '.', ',') . '</span>';
        }
        return $return;
    }

    public static function expense_current_month_tag($tag) {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
                ->select('IFNULL(SUM(t.amount),0)')
                ->from('{{transaction}} t')
                ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
                ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(1) AND MONTH(t.created) = MONTH(NOW()) AND YEAR(t.created) = YEAR(NOW()) AND tt.tag IN(' . $tags . ')')
                ->queryScalar();

        return abs($balance);
    }

    public static function getBudgetChart() {
        $model = TransactionBudget::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id));
        foreach ($model as $key => $value) {
            $budget = TransactionBudget::get_budget($value['id']);
            $expense = TransactionBudget::expense_current_month_tag($value['tag_name']);
            $balance = $budget - $expense;
            //Example: (spent / budgeted) * 100
            $percent = ($expense / $budget) * 100;
            if ($balance >= 0) {
                $check = '<span class="pull-right txt-color-green">' . number_format($balance, 2, '.', ',') . ' LEFT</span>';
            } else {
                $check = '<span class="pull-right txt-color-red">' . number_format($balance, 2, '.', ',') . ' OVER</span>';
            }
            echo '<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> ' . Tag::get_tag($value['tag_name']) . ' <span class="text-muted">SPENT ' . TransactionBudget::expense_current_month_tag($value['tag_name']) . '</span> ' . $check . ' </span>';
            echo '<div class="progress">';
            if ($percent <= 100) {
                echo '<div class="progress-bar bg-color-green" style="width: ' . round($percent) . '%;"></div>';
            } else {
                echo '<div class="progress-bar bg-color-red" style="width: ' . round($percent) . '%;"></div>';
            }
            echo '</div>';
            echo '</div>';
        }
    }

}
