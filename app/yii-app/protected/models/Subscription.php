<?php

/**
 * This is the model class for table "subscriptions".
 *
 * The followings are the available columns in table 'subscriptions':
 * @property integer $id
 * @property integer $author_id
 * @property string $phone
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Author $author
 */
class Subscription extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'subscriptions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('author_id, phone', 'required'),
            array('author_id', 'numerical', 'integerOnly'=>true),
            array('phone', 'length', 'max'=>20),
            array('phone', 'match', 'pattern'=>'/^\+?[0-9]{10,15}$/', 'message'=>'Неверный формат телефона'),
            array('phone', 'unique', 'criteria'=>array(
                'condition'=>'author_id=:author_id',
                'params'=>array(':author_id'=>$this->author_id)
            ), 'message'=>'Вы уже подписаны на этого автора'),
            array('id, author_id, phone, created_at', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'author' => array(self::BELONGS_TO, 'Author', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'author_id' => 'Автор',
            'phone' => 'Телефон',
            'created_at' => 'Дата подписки',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('author_id',$this->author_id);
        $criteria->compare('phone',$this->phone,true);
        $criteria->compare('created_at',$this->created_at,true);

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
     * Send SMS notification about new book
     */
    public function notifyNewBook($book)
    {
        // TODO: Implement SMS notification using SMSpilot API
        // For now, just log the notification
        Yii::log("SMS notification sent to {$this->phone} about new book: {$book->title}", 'info');
        
        // SMS implementation will be added in later stage
        return true;
    }
}
