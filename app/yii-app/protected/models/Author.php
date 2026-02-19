<?php

/**
 * This is the model class for table "authors".
 *
 * The followings are the available columns in table 'authors':
 * @property integer $id
 * @property string $full_name
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property BookAuthor[] $bookAuthors
 * @property Book[] $books
 * @property Subscription[] $subscriptions
 */
class Author extends CActiveRecord
{
    // Virtual property for reports
    public $book_count;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'authors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('full_name', 'required'),
            array('full_name', 'length', 'max'=>255),
            array('id, full_name, created_at, updated_at', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'bookAuthors' => array(self::HAS_MANY, 'BookAuthor', 'author_id'),
            'books' => array(self::MANY_MANY, 'Book', 'book_authors(author_id, book_id)'),
            'subscriptions' => array(self::HAS_MANY, 'Subscription', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'full_name' => 'ФИО',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('full_name',$this->full_name,true);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('updated_at',$this->updated_at,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Author the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Get book count for specific year
     */
    public function getBookCountByYear($year)
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('books');
        $criteria->addCondition('books.year = :year');
        $criteria->params = array(':year' => $year);
        $criteria->together = true;
        
        return $this->count($criteria);
    }
}
