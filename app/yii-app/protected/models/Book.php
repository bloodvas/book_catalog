<?php

/**
 * This is the model class for table "books".
 *
 * The followings are the available columns in table 'books':
 * @property integer $id
 * @property string $title
 * @property integer $year
 * @property string $description
 * @property string $isbn
 * @property string $cover_image
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property BookAuthor[] $bookAuthors
 * @property Author[] $authors
 */
class Book extends CActiveRecord
{
    public $author_ids = array();
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'books';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, year, isbn', 'required'),
            array('year', 'numerical', 'integerOnly'=>true),
            array('title, isbn, cover_image', 'length', 'max'=>255),
            array('isbn', 'unique'),
            array('description', 'safe'),
            array('cover_image', 'file', 'types'=>'jpg, jpeg, png, gif', 'allowEmpty'=>true),
            array('author_ids', 'safe'),
            array('id, title, year, description, isbn, cover_image, created_at, updated_at, author_ids', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'bookAuthors' => array(self::HAS_MANY, 'BookAuthor', 'book_id'),
            'authors' => array(self::MANY_MANY, 'Author', 'book_authors(book_id, author_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
            'author_ids' => 'Авторы',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('year',$this->year);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('isbn',$this->isbn,true);
        $criteria->compare('cover_image',$this->cover_image,true);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('updated_at',$this->updated_at,true);

        // Handle author_ids filter
        if (!empty($this->author_ids)) {
            $criteria->with = array('authors');
            $criteria->together = true;
            $criteria->addInCondition('authors.id', $this->author_ids);
        }

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
     * After save - handle many-to-many relationship and notify subscribers
     */
    protected function afterSave()
    {
        parent::afterSave();
        
        if ($this->isNewRecord || !empty($this->author_ids)) {
            // Delete existing relations
            BookAuthor::model()->deleteAllByAttributes(array('book_id' => $this->id));
            
            // Add new relations
            if (!empty($this->author_ids)) {
                foreach ($this->author_ids as $author_id) {
                    $bookAuthor = new BookAuthor();
                    $bookAuthor->book_id = $this->id;
                    $bookAuthor->author_id = $author_id;
                    $bookAuthor->save();
                }
            }
        }
        
        // Notify subscribers about new book
        if ($this->isNewRecord) {
            BookObserver::notifySubscribers($this);
        }
    }
    
    /**
     * After find - populate author_ids
     */
    protected function afterFind()
    {
        parent::afterFind();
        
        $this->author_ids = array();
        foreach ($this->authors as $author) {
            $this->author_ids[] = $author->id;
        }
    }
}
