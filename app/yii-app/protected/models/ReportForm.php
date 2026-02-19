<?php

/**
 * ReportForm - form model for report filters
 * Designed for future expansion with additional filter options
 */
class ReportForm extends CFormModel
{
    public $year_from;
    public $year_to;
    public $genre;
    public $author_id;
    public $publisher;
    public $min_books;
    public $max_books;
    
    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('year_from, year_to', 'numerical', 'integerOnly'=>true, 'min'=>1900, 'max'=>date('Y')+1),
            array('year_from', 'compare', 'compareAttribute'=>'year_to', 'operator'=>'<=', 'message'=>'Начальный год должен быть меньше или равен конечному году'),
            array('genre', 'length', 'max'=>100),
            array('publisher', 'length', 'max'=>255),
            array('author_id', 'numerical', 'integerOnly'=>true),
            array('min_books, max_books', 'numerical', 'integerOnly'=>true, 'min'=>0),
            // Future validation rules can be added here
        );
    }
    
    /**
     * Declares customized attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'year_from' => 'Год с',
            'year_to' => 'Год по',
            'genre' => 'Жанр',
            'author_id' => 'Автор',
            'publisher' => 'Издательство',
            'min_books' => 'Минимум книг',
            'max_books' => 'Максимум книг',
        );
    }
    
    /**
     * Get available years for filter dropdown
     */
    public function getYearOptions()
    {
        $years = array();
        $currentYear = date('Y');
        
        // Get years from database using raw SQL
        $sql = "SELECT MIN(year) as min_year, MAX(year) as max_year FROM books";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryRow();
        
        if ($result && $result['min_year']) {
            for ($year = $result['max_year']; $year >= $result['min_year']; $year--) {
                $years[$year] = $year;
            }
        } else {
            // Fallback to last 10 years
            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                $years[$year] = $year;
            }
        }
        
        return $years;
    }
    
    /**
     * Get default year from (current year - 5)
     */
    public function getDefaultYearFrom()
    {
        $currentYear = date('Y');
        return $currentYear - 5;
    }
    
    /**
     * Get default year to (current year)
     */
    public function getDefaultYearTo()
    {
        return date('Y');
    }
    
    /**
     * Get genre options (future expansion)
     */
    public function getGenreOptions()
    {
        // This would be populated from database when genre field is added
        return array(
            'fiction' => 'Художественная литература',
            'non-fiction' => 'Научная литература',
            'technical' => 'Техническая литература',
            'biography' => 'Биография',
        );
    }
    
    /**
     * Get author options
     */
    public function getAuthorOptions()
    {
        return CHtml::listData(Author::model()->findAll(array('order'=>'full_name')), 'id', 'full_name');
    }
}
