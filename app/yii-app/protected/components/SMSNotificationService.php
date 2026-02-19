<?php

/**
 * SMS Notification Service for Book Catalog
 */
class SMSNotificationService
{
    /**
     * Send notification about new book to subscriber
     * @param Subscription $subscription
     * @param Book $book
     * @return bool Success status
     */
    public static function notifyNewBook($subscription, $book)
    {
        $message = self::formatNewBookMessage($book);
        $formattedPhone = Yii::app()->smspilot->formatPhone($subscription->phone);
        
        $result = Yii::app()->smspilot->sendSMS($formattedPhone, $message);
        
        if (isset($result['error']) && $result['error']) {
            Yii::log('SMS notification failed: ' . $result['message'], 'error');
            return false;
        }
        
        Yii::log('SMS notification sent to ' . $subscription->phone . ' about book: ' . $book->title, 'info');
        return true;
    }
    
    /**
     * Format message for new book notification
     * @param Book $book
     * @return string Formatted message
     */
    private static function formatNewBookMessage($book)
    {
        $authors = array();
        foreach ($book->authors as $author) {
            $authors[] = $author->full_name;
        }
        
        $message = sprintf(
            "Новая книга! \"%s\" (%s) - %s. ISBN: %s",
            $book->title,
            implode(', ', $authors),
            $book->year,
            $book->isbn
        );
        
        // Ensure message is within SMS limits (160 characters for GSM, 70 for Unicode)
        if (mb_strlen($message, 'UTF-8') > 160) {
            $message = sprintf(
                "Новая книга! \"%s\" (%s) - %s",
                $book->title,
                implode(', ', $authors),
                $book->year
            );
        }
        
        return $message;
    }
    
    /**
     * Send bulk notifications about new book
     * @param Book $book
     * @param array $subscriptions Array of Subscription objects
     * @return array Results array with success/failure counts
     */
    public static function notifyBulkNewBook($book, $subscriptions)
    {
        $results = array(
            'success' => 0,
            'failed' => 0,
            'total' => count($subscriptions),
        );
        
        $messages = array();
        
        foreach ($subscriptions as $subscription) {
            $formattedPhone = Yii::app()->smspilot->formatPhone($subscription->phone);
            $message = self::formatNewBookMessage($book);
            $messages[$formattedPhone] = $message;
        }
        
        // Send bulk SMS
        $result = Yii::app()->smspilot->sendBulkSMS($messages);
        
        if (isset($result['error']) && $result['error']) {
            Yii::log('Bulk SMS notification failed: ' . $result['message'], 'error');
            $results['failed'] = $results['total'];
            return $results;
        }
        
        // Process results
        if (isset($result['send'])) {
            foreach ($result['send'] as $item) {
                if (isset($item['status']) && $item['status'] === 0) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }
            }
        }
        
        Yii::log('Bulk SMS notification sent. Success: ' . $results['success'] . ', Failed: ' . $results['failed'], 'info');
        
        return $results;
    }
    
    /**
     * Test SMS service functionality
     * @param string $phone Test phone number
     * @return bool Test result
     */
    public static function testService($phone)
    {
        $message = "Тестовое сообщение от Book Catalog";
        $formattedPhone = Yii::app()->smspilot->formatPhone($phone);
        
        $result = Yii::app()->smspilot->sendSMS($formattedPhone, $message);
        
        if (isset($result['error']) && $result['error']) {
            Yii::log('SMS test failed: ' . $result['message'], 'error');
            return false;
        }
        
        return true;
    }
}
