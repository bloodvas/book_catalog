<?php

class TestController extends Controller
{
    public function actionIndex()
    {
        echo "<h1>Database Test</h1>";
        
        try {
            $users = User::model()->findAll();
            echo "<h2>Users in database:</h2>";
            echo "<ul>";
            foreach ($users as $user) {
                echo "<li>Username: " . CHtml::encode($user->username) . "</li>";
            }
            echo "</ul>";
            
            $authors = Author::model()->findAll();
            echo "<h2>Authors in database:</h2>";
            echo "<ul>";
            foreach ($authors as $author) {
                echo "<li>" . CHtml::encode($author->full_name) . "</li>";
            }
            echo "</ul>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . CHtml::encode($e->getMessage()) . "</p>";
        }
    }
}
