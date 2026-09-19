<?php

/**
 * This is the model class for table "{{email_preference}}".
 *
 * The followings are the available columns in table '{{email_preference}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $enabled
 * @property string $frequency
 * @property string $last_sent_at
 * @property string $created_at
 * @property string $updated_at
 */
class EmailPreference extends CActiveRecord
{
    public function tableName()
    {
        return '{{email_preference}}';
    }

    public function rules()
    {
        return array(
            array('user_id, enabled, frequency', 'required'),
            array('user_id, enabled', 'numerical', 'integerOnly' => true),
            array('frequency', 'length', 'max' => 20),
            array('last_sent_at, created_at, updated_at', 'safe'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return EmailPreference the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getPreference($userId)
    {
        $pref = self::model()->findByAttributes(array('user_id' => $userId));
        if ($pref === null) {
            $pref = new self();
            $pref->user_id = $userId;
            $pref->enabled = 1;
            $pref->frequency = 'daily';
            $pref->save();
        }
        return $pref;
    }
}
