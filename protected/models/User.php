<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $activation
 * @property integer $group_id
 * @property integer $status
 */
class User extends CActiveRecord {

    public $status;
    public $title;
    public $passwordConfirm;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, username, email, group_id, status', 'required'),
            array('password', 'required', 'on' => 'insert'),
            array('group_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('username', 'length', 'max' => 150),
            array('username', 'unique'),
            array('email', 'unique'),
            array('email, password, passwordConfirm, activation', 'length', 'max' => 100),
            array('registerDate, lastvisitDate, user_type', 'safe'),
            array('name', 'ext.alpha', 'allowNumbers' => true, 'allowSpaces' => true, 'minChars' => 3, 'extra' => array('.', '-', "'", '"', ':'), 'message' => 'Name not valid. Non alpha-numeric characters (like @,#,$,_ etc.) are not allowed'),
            array('username', 'ext.alpha', 'allowNumbers' => true, 'extra' => array('.', '-', '_', "'", '"', ':'), 'minChars' => 3, 'message' => 'User name not valid'),
            array('email', 'email', 'checkMX' => true),
            array('passwordConfirm', 'compare', 'compareAttribute' => 'password', 'message' => "Confirm password is incorrect.", 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, username, email, password, passwordConfirm, registerDate, lastvisitDate, activation,group_id,status,title', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'UserStatus' => array(self::BELONGS_TO, 'UserStatus', 'status'),
            'UserGroup' => array(self::BELONGS_TO, 'UserGroup', 'group_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'passwordConfirm' => 'Confirm password',
            'registerDate' => 'Joined',
            'lastvisitDate' => 'Last Online',
            'activation' => 'Activation',
            'group_id' => 'Group',
            'status' => 'Status',
            'user_type' => 'User Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('registerDate', $this->registerDate, true);
        $criteria->compare('lastvisitDate', $this->lastvisitDate, true);
        $criteria->compare('activation', $this->activation, true);
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
            'sort' => array('defaultOrder' => 'name ASC')
        ));
    }

    public static function checkUser($id) {
        if (($model = User::model()->find(array('condition' => 'id=' . Yii::app()->user->id . ' AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/user/view', 'id' => Yii::app()->user->id));
        } else {
            return true;
        }
    }

    public function getGroupName($id) {
        $returnValue = Yii::app()->db->createCommand()
                ->select('title')
                ->from('{{user_group}}')
                ->where('id=:id', array(':id' => $id))
                ->queryScalar();

        return $returnValue;
    }

    public static function get_user_name($user_id) {
        $value = User::model()->findByAttributes(array('id' => $user_id));
        if (empty($value->name)) {
            return null;
        } else {
            return $value->name;
        }
    }

    //get profile picture for grid
    public static function get_picture_grid($id) {
        $value = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $filePath = Yii::app()->basePath . '/../uploads/profile_picture/' . $value->profile_picture;
        if ((is_file($filePath)) && (file_exists($filePath))) {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/profile_picture/' . $value->profile_picture, 'Profile Picture', array('alt' => User::get_user_name($value->user_id), 'class' => 'nav-user-photo', 'title' => User::get_user_name($value->user_id), 'style' => 'width:50px;'));
        } else {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/profile_picture/profile.jpg', 'Profile Picture', array('alt' => User::get_user_name($value->user_id), 'class' => 'nav-user-photo', 'title' => User::get_user_name($value->user_id), 'style' => 'width:50px;'));
        }
    }

    //get profile picture for left top panel
    public static function get_profile_picture($id) {
        $value = UserProfile::model()->findByAttributes(array('user_id' => $id));
        $filePath = Yii::app()->basePath . '/../uploads/profile_picture/' . $value->profile_picture;
        if ((is_file($filePath)) && (file_exists($filePath))) {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/profile_picture/' . $value->profile_picture, 'Profile Picture', array('alt' => User::get_user_name($value->user_id), 'class' => 'online', 'title' => User::get_user_name($value->user_id), 'style' => ''));
        } else {
            return CHtml::image(Yii::app()->baseUrl . '/uploads/profile_picture/profile.jpg', 'Profile Picture', array('alt' => User::get_user_name($value->user_id), 'class' => 'online', 'title' => User::get_user_name($value->user_id), 'style' => ''));
        }
    }

    public static function get_alert_message() {
        if (Yii::app()->user->hasFlash('success')) {
            echo '<div class="alert alert-success fade in">';
            echo '<button class="close" data-dismiss="alert">×</button>';
            echo '<i class="fa-fw fa fa-check"></i>';
            echo Yii::app()->user->getFlash('success');
            echo '</div>';
        } elseif (Yii::app()->user->hasFlash('error')) {
            echo '<div class="alert alert-danger fade in">';
            echo '<button class="close" data-dismiss="alert">×</button>';
            echo '<i class="fa-fw fa fa-times"></i>';
            echo Yii::app()->user->getFlash('error');
            echo '</div>';
        }
    }

    /**
     * Send mail method
     */
    public static function sendMail($to, $subject, $message, $fromName, $fromMail) {
        $headers = "From: " . $fromName . "<" . $fromMail . "> \r\nX-Mailer: php\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

    /**
     * Send mail BCC method
     */
    public static function sendMailBCC($to, $subject, $message, $fromName, $fromMail, $bccList) {
        $headers = "From: " . $fromName . "<" . $fromMail . "> \r\nX-Mailer: php\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $headers .= "Bcc: $bccList\r\n";
        $to = $fromMail;
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

}
