<?php

class ReportController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     */
    public function filterAccessControl($filterChain)
    {
        $access = new AccessControl();
        $access->filter($filterChain);
    }

    /**
     * TOP 10 authors by book count for specific year
     */
    public function actionTopAuthors()
    {
        $model = new ReportForm();
        $model->year = date('Y'); // Default to current year
        
        $dataProvider = null;
        
        if (isset($_GET['ReportForm'])) {
            $model->attributes = $_GET['ReportForm'];
            if ($model->validate()) {
                $dataProvider = $this->getTopAuthorsDataProvider($model->year, $model->genre);
            }
        } else {
            // Show default report for current year
            $dataProvider = $this->getTopAuthorsDataProvider($model->year);
        }

        $this->render('topAuthors', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }
    
    /**
     * Get data provider for top authors report
     * @param integer $year
     * @param string $genre (optional, for future expansion)
     * @return CActiveDataProvider
     */
    private function getTopAuthorsDataProvider($year, $genre = null)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'a';
        $criteria->select = 'a.id, a.full_name, COUNT(b.id) as book_count';
        $criteria->join = 'LEFT JOIN book_authors ba ON ba.author_id = a.id 
                          LEFT JOIN books b ON b.id = ba.book_id AND b.year = :year';
        $criteria->params = array(':year' => $year);
        $criteria->group = 'a.id, a.full_name';
        $criteria->having = 'COUNT(b.id) > 0';
        $criteria->order = 'book_count DESC, a.full_name ASC';
        $criteria->limit = 10;
        
        // Future expansion: genre filter
        if ($genre && $genre !== '') {
            // This would require adding genre field to books table
            // $criteria->addCondition('b.genre = :genre');
            // $criteria->params[':genre'] = $genre;
        }
        
        return new CActiveDataProvider('Author', array(
            'criteria' => $criteria,
            'pagination' => false,
            'sort' => false,
        ));
    }
    
    /**
     * Export report to CSV (future expansion)
     */
    public function actionExportTopAuthors()
    {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        
        $dataProvider = $this->getTopAuthorsDataProvider($year, $genre);
        $data = $dataProvider->getData();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="top_authors_' . $year . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV header
        fputcsv($output, array('Author', 'Book Count', 'Year'));
        
        // CSV data
        foreach ($data as $author) {
            fputcsv($output, array(
                $author->full_name,
                $author->book_count,
                $year
            ));
        }
        
        fclose($output);
        Yii::app()->end();
    }
}
