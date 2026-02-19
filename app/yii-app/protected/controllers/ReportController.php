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
        $model->year_from = $model->getDefaultYearFrom(); // Default to current year - 5
        $model->year_to = $model->getDefaultYearTo(); // Default to current year
        
        $dataProvider = null;
        
        if (isset($_POST['ReportForm'])) {
            $model->attributes = $_POST['ReportForm'];
            if ($model->validate()) {
                $dataProvider = $this->getTopAuthorsDataProvider($model->year_from, $model->year_to, $model->genre, $model);
                // Redirect to clean URL without GET parameters
                $this->redirect(array('report/topAuthors', 'ReportForm'=>$_POST['ReportForm']));
            }
        } elseif (isset($_GET['ReportForm'])) {
            $model->attributes = $_GET['ReportForm'];
            if ($model->validate()) {
                $dataProvider = $this->getTopAuthorsDataProvider($model->year_from, $model->year_to, $model->genre, $model);
            }
        } else {
            // Show default report for last 5 years
            $dataProvider = $this->getTopAuthorsDataProvider($model->year_from, $model->year_to, null, $model);
        }

        $this->render('topAuthors', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }
    
    /**
     * Get data provider for top authors report
     * @param integer $year_from
     * @param integer $year_to
     * @param string $genre (optional, for future expansion)
     * @return CActiveDataProvider
     */
    private function getTopAuthorsDataProvider($year_from, $year_to, $genre = null, $model = null)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'a';
        $criteria->select = 'a.id, a.full_name, COUNT(b.id) AS book_count';
        $criteria->join = 'LEFT JOIN book_authors ba ON ba.author_id = a.id 
                          LEFT JOIN books b ON b.id = ba.book_id AND b.year BETWEEN :year_from AND :year_to';
        $criteria->params = array(':year_from' => $year_from, ':year_to' => $year_to);
        $criteria->group = 'a.id, a.full_name';
        
        // Apply author_id filter if specified
        if ($model && isset($model->author_id) && $model->author_id !== '') {
            $criteria->addCondition('a.id = :author_id');
            $criteria->params[':author_id'] = intval($model->author_id);
        }
        
        // Build HAVING conditions
        $havingConditions = array('COUNT(b.id) > 0');
        
        // Apply min_books filter if specified
        if ($model && isset($model->min_books) && $model->min_books !== '') {
            $havingConditions[] = 'COUNT(b.id) >= ' . intval($model->min_books);
        }
        
        // Apply max_books filter if specified
        if ($model && isset($model->max_books) && $model->max_books !== '') {
            $havingConditions[] = 'COUNT(b.id) <= ' . intval($model->max_books);
        }
        
        $criteria->having = implode(' AND ', $havingConditions);
        
        // Log SQL for debugging
        Yii::log('SQL: ' . $criteria->condition . ' HAVING ' . $criteria->having . ' PARAMS: ' . json_encode($criteria->params), 'info');
        
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
        try {
            // Support both GET and POST parameters
            $formParams = isset($_POST['ReportForm']) ? $_POST['ReportForm'] : (isset($_GET['ReportForm']) ? $_GET['ReportForm'] : array());
            
            $year_from = isset($formParams['year_from']) ? $formParams['year_from'] : (new ReportForm())->getDefaultYearFrom();
            $year_to = isset($formParams['year_to']) ? $formParams['year_to'] : (new ReportForm())->getDefaultYearTo();
            $genre = isset($formParams['genre']) ? $formParams['genre'] : null;
            
            // Create model for filters
            $model = new ReportForm();
            $model->year_from = $year_from;
            $model->year_to = $year_to;
            $model->genre = $genre;
            if (isset($formParams['min_books'])) {
                $model->min_books = $formParams['min_books'];
            }
            if (isset($formParams['max_books'])) {
                $model->max_books = $formParams['max_books'];
            }
            
            $dataProvider = $this->getTopAuthorsDataProvider($year_from, $year_to, $genre, $model);
            $data = $dataProvider->getData();
            
            // Clean any output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="top_authors_' . $year_from . '_' . $year_to . '.csv"');
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            
            // CSV header
            echo '"Author","Book Count","Year Range"' . "\n";
            
            // CSV data
            foreach ($data as $author) {
                $bookCount = isset($author->book_count) ? $author->book_count : 0;
                echo '"' . str_replace('"', '""', $author->full_name) . '",' . $bookCount . ',"' . $year_from . '-' . $year_to . '"' . "\n";
            }
            
            Yii::app()->end();
        } catch (Exception $e) {
            // Clean any output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: text/plain');
            echo 'Error exporting data: ' . $e->getMessage();
            Yii::app()->end();
        }
    }
}
