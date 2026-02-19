<?php

/**
 * This is the model class for table "users".
 *
 * Simple user model for authentication
 */
class User extends CActiveRecord
{
    public $password_repeat;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('username', 'length', 'max'=>255),
            array('username', 'unique'),
            array('password_repeat', 'compare', 'compareAttribute'=>'password'),
            array('username, password', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('username',$this->username,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Hash password before saving
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord || !empty($this->password)) {
                $this->password = CPasswordHelper::hashPassword($this->password);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password)
    {
        return CPasswordHelper::verifyPassword($password, $this->password);
    }
}
