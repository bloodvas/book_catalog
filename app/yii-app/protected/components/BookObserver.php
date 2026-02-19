<?php

/**
 * Observer for notifying subscribers when new book is added
 */
class BookObserver
{
    /**
     * Notify subscribers about new book
     * @param Book $book
     */
    public static function notifySubscribers($book)
    {
        $authors = $book->authors;
        $allSubscriptions = array();
        
        // Collect all subscriptions for all authors of the book
        foreach ($authors as $author) {
            $subscriptions = Subscription::model()->findAllByAttributes(array(
                'author_id' => $author->id
            ));
            
            foreach ($subscriptions as $subscription) {
                $allSubscriptions[] = $subscription;
            }
        }
        
        if (!empty($allSubscriptions)) {
            // Send bulk notifications for better performance
            $results = SMSNotificationService::notifyBulkNewBook($book, $allSubscriptions);
            
            Yii::log('Book notification results: ' . json_encode($results), 'info');
        }
    }
}
