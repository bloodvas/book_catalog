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
        
        foreach ($authors as $author) {
            $subscriptions = Subscription::model()->findAllByAttributes(array(
                'author_id' => $author->id
            ));
            
            foreach ($subscriptions as $subscription) {
                $subscription->notifyNewBook($book);
            }
        }
    }
}
