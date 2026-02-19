<?php

/**
 * This is the model class for table "book_authors".
 *
 * The followings are the available columns in table 'book_authors':
 * @property integer $book_id
 * @property integer $author_id
 *
 * The followings are the available model relations:
 * @property Book $book
 * @property Author $author
 */
class BookAuthor extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'book_authors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('book_id, author_id', 'required'),
            array('book_id, author_id', 'numerical', 'integerOnly'=>true),
            array('book_id, author_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
            'author' => array(self::BELONGS_TO, 'Author', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'book_id' => 'Книга',
            'author_id' => 'Автор',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('book_id',$this->book_id);
        $criteria->compare('author_id',$this->author_id);

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
}
