<?php

/**
 * This is the model class for table "{{email_log}}".
 *
 * The followings are the available columns in table '{{email_log}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property string $subject
 * @property string $status
 * @property string $error_message
 * @property string $sent_at
 */
class EmailLog extends CActiveRecord
{
    public function tableName()
    {
        return '{{email_log}}';
    }

    public function rules()
    {
        return array(
            array('user_id, email, subject, status', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('email, subject, status', 'length', 'max' => 255),
            array('error_message, sent_at', 'safe'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return EmailLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
