<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property string $id
 * @property integer $user
 * @property integer $account_type
 * @property integer $currency
 * @property string $institution
 * @property string $created
 * @property string $modified
 */
class Account extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{account}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, account_type, currency, account_name, institution', 'required'),
            array('user, account_type, currency, default_account', 'numerical', 'integerOnly' => true),
            array('account_name, institution, status', 'length', 'max' => 150),
            array('credit_limit', 'length', 'max' => 12),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, account_type, currency, account_name, institution, credit_limit, default_account, created, modified, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user' => 'User',
            'account_type' => 'Type',
            'currency' => 'Currency',
            'institution' => 'Financial Institution',
            'credit_limit' => 'Credit Limit',
            'account_name' => 'Name',
            'default_account' => 'Default Account',
            'created' => 'Created On',
            'modified' => 'Modified On',
            'status' => 'Status',
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
        $criteria->compare('t.account_type', $this->account_type);
        $criteria->compare('t.currency', $this->currency);
        $criteria->compare('t.account_name', $this->account_name, true);
        $criteria->compare('t.institution', $this->institution, true);
        $criteria->compare('t.credit_limit', $this->credit_limit);
        $criteria->compare('t.default_account', $this->default_account);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.account_name'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Account the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function get_account($id)
    {
        $value = Account::model()->findByAttributes(array('id' => $id));
        if (empty($value->account_name)) {
            return null;
        } else {
            return $value->account_name;
        }
    }

    public static function get_default_account()
    {
        $value = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{account}}')
            ->where('default_account=1 AND user=' . Yii::app()->user->id)
            ->queryScalar();

        if (empty($value)) {
            return 0;
        } else {
            return $value;
        }
    }

    public static function get_accounts($id)
    {
        $model = Transaction::model()->findByPk($id);
        $exval = explode(',', $model->account);
        $tags = '';
        $total = count($exval);
        if ($total > 1) {
            for ($i = 0; $i < $total; $i++) {
                if ($i == 0) {
                    //$tags .= '<span class="account_text">' . Account::get_account($exval[$i]) . '</span> <i class="fa fa-long-arrow-right "></i> ';
                    $tags .= CHtml::link(Account::get_account($exval[$i]), array('transaction/account', 'id' => $exval[$i]), array('data-placement' => 'bottom', 'title' => Account::get_account($exval[$i]), 'rel' => 'tooltip', 'data-original-title' => Account::get_account($exval[$i]), 'class' => 'account_text')) . ' <i class="fa fa-long-arrow-right "></i> ';
                } else {
                    //$tags .= '<span class="account_text">' . Account::get_account($exval[$i]) . '</span>';
                    $tags .= CHtml::link(Account::get_account($exval[$i]), array('transaction/account', 'id' => $exval[$i]), array('data-placement' => 'bottom', 'title' => Account::get_account($exval[$i]), 'rel' => 'tooltip', 'data-original-title' => Account::get_account($exval[$i]), 'class' => 'account_text'));
                }
            }
        } else {
            for ($i = 0; $i < $total; $i++) {
                //$tags .= '<span class="account_text">' . Account::get_account($exval[$i]) . '</span>';
                $tags .= CHtml::link(Account::get_account($exval[$i]), array('transaction/account', 'id' => $exval[$i]), array('data-placement' => 'bottom', 'title' => Account::get_account($exval[$i]), 'rel' => 'tooltip', 'data-original-title' => Account::get_account($exval[$i]), 'class' => 'account_text')) . ' ';
            }
        }

        return $tags;
    }

    public static function get_balance($id)
    {
        $modelAccount = Account::model()->findByPk($id);
        $userId = (int)$modelAccount->user;
        //SELECT * FROM `os_transaction` WHERE account LIKE '1,%' AND transaction_type=3
        $transfer_in = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account LIKE "' . $id . ',%" AND transaction_type=3 AND user=' . $userId)
            ->queryScalar();
        //SELECT * FROM `os_transaction` WHERE account LIKE '%,1' AND transaction_type=3
        $transfer_out = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account LIKE "%,' . $id . '" AND transaction_type=3 AND user=' . $userId)
            ->queryScalar();
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account=' . $id . ' AND transaction_type IN(2,4) AND user=' . $userId)
            ->queryScalar();
        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account=' . $id . ' AND transaction_type IN(1) AND user=' . $userId)
            ->queryScalar();
        $balance = (($income - $expance) - $transfer_in + $transfer_out);
        if ($modelAccount->account_type == 4) {
            $balance = ($modelAccount->credit_limit - abs($balance));
        }
        if ($balance < 0) {
            $balance = abs($balance);
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="text-danger"><i class="fa-fw fa fa-minus"></i> ' . $amount . '</span>';
        } else {
            $amount = number_format($balance, 2, '.', ',');
            return '<span class="text-success"><i class="fa-fw fa fa-plus"></i> ' . $amount . '</span>';
        }
    }

    public static function get_balance_chart($id)
    {
        $modelAccount = Account::model()->findByPk($id);
        $userId = (int)$modelAccount->user;
        $transfer_in = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account LIKE "' . $id . ',%" AND transaction_type=3 AND user=' . $userId)
            ->queryScalar();
        $transfer_out = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account LIKE "%,' . $id . '" AND transaction_type=3 AND user=' . $userId)
            ->queryScalar();
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account=' . $id . ' AND transaction_type IN(2,4) AND user=' . $userId)
            ->queryScalar();
        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('account=' . $id . ' AND transaction_type IN(1) AND user=' . $userId)
            ->queryScalar();
        $balance = (($income - $expance) - $transfer_in + $transfer_out);
        return number_format($balance, 2, '.', '');
    }

    public static function last_twelve_months()
    {
        $return = null;
        for ($t = 0; $t < 12; $t++) {
            $return .= "'" . date("M y", strtotime(date('Y-m-01') . " -$t months")) . "',";
        }
        return $return;
    }

    public static function last_twelve_years()
    {
        $return = null;
        $year = date('Y');
        $from = $year - 12;
        for ($t = $from; $t <= $year; $t++) {
            $return .= "'" . $t . "',";
        }
        return $return;
    }

    //get balance fron last day of month to previous all
    public static function get_balance_month_year($date)
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
        return number_format($balance, 2, '.', '');
    }

    //get balance specific month and year
    public static function get_balance_specific_month($date)
    {
        $income = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(2,4) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '")')
            ->queryScalar();

        $expance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(amount),0)')
            ->from('{{transaction}}')
            ->where('user=' . Yii::app()->user->id . ' AND transaction_type IN(1) AND MONTH(created) = MONTH("' . $date . '") AND YEAR(created) = YEAR("' . $date . '")')
            ->queryScalar();
        $balance = $income - $expance;
        return number_format($balance, 2, '.', '');
    }

    public static function checkUser($id)
    {
        if (($model = Account::model()->find(array('condition' => 'user=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

}
