<?php

/**
 * This is the model class for table "{{transaction}}".
 *
 * The followings are the available columns in table '{{transaction}}':
 * @property string $id
 * @property integer $user
 * @property string $description
 * @property string $amount
 * @property string $created
 * @property string $modified
 * @property integer $transaction_type
 * @property integer $account
 * @property string $tag
 * @property integer $group
 * @property integer $status
 * @property string $notes
 */
class Transaction extends CActiveRecord
{

    public $AccountTo;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{transaction}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user,description,amount,account', 'required'),
            array('user, transaction_type, group, status', 'numerical', 'integerOnly' => true),
            array('account, description, notes', 'length', 'max' => 250),
            array('amount', 'length', 'max' => 13),
            array('tag, created, modified', 'safe'),
            array('AccountTo', 'checkAccountTo'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, description, amount, created, modified, transaction_type, account, tag, group, status, notes', 'safe', 'on' => 'search'),
        );
    }

    public function checkAccountTo($attribute, $params)
    {
        if ($this->transaction_type == 3 && empty($this->AccountTo)) {
            $this->addError($attribute, 'Please select transfer Account.');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'transaction_tag_relation' => array(self::HAS_MANY, '{{transaction_tag}}', 'transaction'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user' => 'User',
            'description' => 'Description',
            'amount' => 'Amount',
            'created' => 'Date',
            'modified' => 'Modified On',
            'transaction_type' => 'Type',
            'account' => 'Account',
            'tag' => 'Tags',
            'group' => 'Group',
            'status' => 'Status',
            'notes' => 'Notes',
            'AccountTo' => 'Account To',
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
        $criteria->alias = 't';
        $criteria->condition = 't.user=' . Yii::app()->user->id;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);
        $criteria->compare('t.transaction_type', $this->transaction_type);
        $criteria->compare('t.account', $this->account);
        $criteria->compare('t.tag', $this->tag, true);
        $criteria->compare('t.group', $this->group);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.notes', $this->notes, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.created DESC, t.id DESC'),
        ));
    }

    public function search_tag($tag)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $tags = implode(',', Tag::get_parent_child($tag));

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN {{transaction_tag}} tt ON tt.transaction=t.id';
        //        $criteria->condition = 'FIND_IN_SET("' . $tags . '", t.tag)>0 AND t.user=' . Yii::app()->user->id;
        $criteria->condition = 'tt.tag IN(' . $tags . ') AND t.user=' . Yii::app()->user->id;
        $criteria->group = 't.id';

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);
        $criteria->compare('t.transaction_type', $this->transaction_type);
        $criteria->compare('t.account', $this->account);
        $criteria->compare('t.tag', $this->tag, true);
        $criteria->compare('t.group', $this->group);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.notes', $this->notes, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.created DESC, t.id DESC'),
        ));
    }

    public function search_account($account)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->condition = 'FIND_IN_SET(' . $account . ', t.account)>0 AND t.user=' . Yii::app()->user->id;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);
        $criteria->compare('t.transaction_type', $this->transaction_type);
        $criteria->compare('t.account', $this->account);
        $criteria->compare('t.tag', $this->tag, true);
        $criteria->compare('t.group', $this->group);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.notes', $this->notes, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.created DESC, t.id DESC'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Transaction the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function checkUser($id)
    {
        if (($model = Transaction::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

    public static function get_amount($type, $amount)
    {
        if ($type == 1) {
            $amount = number_format($amount, 2, '.', ',');
            return '<span class="text-danger"><i class="fa-fw fa fa-minus"></i> ' . $amount . '</span>';
        } elseif ($type == 2 || $type == 4) {
            $amount = number_format($amount, 2, '.', ',');
            return '<span class="text-success"><i class="fa-fw fa fa-plus"></i> ' . $amount . '</span>';
        } elseif ($type == 3) {
            $amount = number_format($amount, 2, '.', ',');
            return '<span class="text-orangeDark"><i class="fa-fw fa fa-exchange"></i> ' . $amount . '</span>';
        } else {
            $amount = number_format($amount, 2, '.', ',');
            return '<span class="text-info">' . $amount . '</span>';
        }
    }

    public static function get_income_current_month()
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND MONTH(created) = MONTH(NOW()) AND YEAR(created) = YEAR(NOW())')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', ',');
        return '<span class="txt-color-blue"><i class="fa-fw fa fa-plus"></i> ' . $amount . '</span>';
    }

    public static function get_expense_current_month()
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH(NOW()) AND YEAR(created) = YEAR(NOW())')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', ',');
        return '<span class="txt-color-red"><i class="fa-fw fa fa-minus"></i> ' . $amount . '</span>';
    }

    public static function get_saved_current_month()
    {
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND MONTH(created) = MONTH(NOW()) AND YEAR(created) = YEAR(NOW())')
            ->queryScalar();

        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH(NOW()) AND YEAR(created) = YEAR(NOW())')
            ->queryScalar();
        $balance = $income - $expance;

        if ($balance < 0) {
            $balance = abs($balance);
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="txt-color-red"><i class="fa-fw fa fa-minus"></i> ' . $amount . '</span>';
        } else {
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="txt-color-blue"><i class="fa-fw fa fa-plus"></i> ' . $amount . '</span>';
        }
    }

    public static function get_net_worth()
    {
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4)')
            ->queryScalar();

        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1)')
            ->queryScalar();
        $balance = $income - $expance;

        if ($balance < 0) {
            $balance = abs($balance);
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="txt-color-red"><i class="fa-fw fa fa-minus"></i> ' . $amount . '</span>';
        } else {
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="txt-color-blue"><i class="fa-fw fa fa-plus"></i> ' . $amount . '</span>';
        }
    }

    public static function dashboardExpenseChart()
    {
        $array = Yii::app()->db->createCommand()
            ->select('(SELECT IFNULL(SUM(b.amount),0) FROM {{transaction}} b WHERE FIND_IN_SET(t.id, b.tag) AND b.transaction_type IN(1) AND MONTH(b.created) = MONTH(NOW()) AND YEAR(b.created) = YEAR(NOW())) AS total, t.tag_name')
            ->from('{{tag}} t')
            ->where('t.user IN(0,' . Yii::app()->user->id . ')')
            ->queryAll();
        $return = null;
        foreach ($array as $key => $value) {
            if ($value['total'] > 0)
                $return .= "['" . $value['tag_name'] . "', " . $value['total'] . "],";
        }
        return $return;
    }

    public static function dashboardIncomeChart()
    {
        $array = Yii::app()->db->createCommand()
            ->select('(SELECT IFNULL(SUM(b.amount),0) FROM {{transaction}} b WHERE FIND_IN_SET(t.id, b.tag) AND b.transaction_type IN(2,4) AND MONTH(b.created) = MONTH(NOW()) AND YEAR(b.created) = YEAR(NOW())) AS total, t.tag_name')
            ->from('{{tag}} t')
            ->where('t.user=' . Yii::app()->user->id)
            ->queryAll();
        $return = null;
        foreach ($array as $key => $value) {
            if ($value['total'] > 0)
                $return .= "['" . $value['tag_name'] . "', " . $value['total'] . "],";
        }
        return $return;
    }

    public static function dashboardBalanceChart()
    {
        $array = Account::model()->findAll(array('condition' => 'user=' . Yii::app()->user->id));
        $return = null;
        foreach ($array as $key => $value) {
            $return .= "['" . $value['account_name'] . "', " . Account::get_balance_chart($value['id']) . "],";
        }
        return $return;
    }

    public static function accountBalanceChart()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Account::get_balance_month_year($date) . ",";
        }
        return $return;
    }

    public static function accountBalanceComparisonChart()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Account::get_balance_specific_month($date) . ",";
        }
        return $return;
    }

    public static function get_income_specific_month($date)
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '")')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function accountIncomeComparisonChart()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::get_income_specific_month($date) . ",";
        }
        return $return;
    }

    public static function get_expense_specific_month($date)
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '")')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function accountExpanceComparisonChart()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::get_expense_specific_month($date) . ",";
        }
        return $return;
    }

    public static function get_worth_specific_month($date)
    {
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND created <= "' . $date . '"')
            ->queryScalar();

        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND created <= "' . $date . '"')
            ->queryScalar();
        $balance = $income - $expance;

        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function accountWorthComparisonChart()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::get_worth_specific_month($date) . ",";
        }
        return $return;
    }

    //create chart for specific tag page
    public static function income_specific_month_tag($date, $tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(2,4) AND MONTH(t.created) = MONTH("' . $date . '") AND YEAR(t.created) = YEAR("' . $date . '") AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function tagIncomeChart($tag)
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::income_specific_month_tag($date, $tag) . ",";
        }
        return $return;
    }

    public static function expense_specific_month_tag($date, $tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(1) AND MONTH(t.created) = MONTH("' . $date . '") AND YEAR(t.created) = YEAR("' . $date . '") AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function tagExpenseChart($tag)
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::expense_specific_month_tag($date, $tag) . ",";
        }
        return $return;
    }

    //create chart for specific account page
    public static function income_specific_month_account($date, $account)
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '") AND FIND_IN_SET(' . $account . ', account)>0')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function accountIncomeChart($account)
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::income_specific_month_account($date, $account) . ",";
        }
        return $return;
    }

    public static function expense_specific_month_account($date, $account)
    {
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '") AND FIND_IN_SET(' . $account . ', account)>0')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function accountExpenseChart($account)
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $date = date("Y-m-t", strtotime(date('Y-m-01') . " -$t months"));
            $return .= Transaction::expense_specific_month_account($date, $account) . ",";
        }
        return $return;
    }

    public static function getYearlyIncomeExpanse()
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT 
                    YEAR(created) AS year,
                    SUM(CASE WHEN transaction_type = 1 THEN amount ELSE 0 END) AS total_income,
                    SUM(CASE WHEN transaction_type = 2 THEN amount ELSE 0 END) AS total_expense,
                    SUM(CASE WHEN transaction_type = 1 THEN amount ELSE 0 END) -
                    SUM(CASE WHEN transaction_type = 2 THEN amount ELSE 0 END) AS net_result
                FROM os_transaction
                WHERE status = 1 AND `user` = ' . Yii::app()->user->id . '
                GROUP BY YEAR(created)
                ORDER BY year DESC');
        return $command->queryAll();
    }


}
