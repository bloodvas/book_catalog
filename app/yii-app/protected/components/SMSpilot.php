<?php

/**
 * SMSpilot API integration component
 * https://smspilot.ru/
 */
class SMSpilot extends CApplicationComponent
{
    public $apiKey;
    public $endpoint = 'https://smspilot.ru/api.php';
    public $sender = 'BookCatalog';
    public $testMode = false;
    
    /**
     * Send SMS message
     * @param string $phone Phone number in format +79991234567
     * @param string $message Message text
     * @return array API response
     */
    public function sendSMS($phone, $message)
    {
        $params = array(
            'apikey' => $this->apiKey,
            'send' => $message,
            'to' => $phone,
            'from' => $this->sender,
            'format' => 'json',
        );
        
        if ($this->testMode) {
            $params['test'] = 1;
        }
        
        return $this->makeRequest($params);
    }
    
    /**
     * Send multiple SMS messages
     * @param array $messages Array of phone => message pairs
     * @return array API response
     */
    public function sendBulkSMS($messages)
    {
        $allPhones = array();
        $allMessages = array();
        
        foreach ($messages as $phone => $message) {
            $allPhones[] = $phone;
            $allMessages[] = $message;
        }
        
        $params = array(
            'apikey' => $this->apiKey,
            'send' => $allMessages,
            'to' => $allPhones,
            'format' => 'json',
        );
        
        if ($this->testMode) {
            $params['test'] = 1;
        }
        
        return $this->makeRequest($params);
    }
    
    /**
     * Check account balance
     * @return array API response
     */
    public function getBalance()
    {
        $params = array(
            'apikey' => $this->apiKey,
            'balance' => '',
        );
        
        return $this->makeRequest($params);
    }
    
    /**
     * Make HTTP request to SMSpilot API
     * @param array $params Request parameters
     * @return array API response
     */
    private function makeRequest($params)
    {
        Yii::log('SMSpilot Request params: ' . json_encode($params), 'info');
        
        $ch = curl_init();
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'BookCatalog/1.0',
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($response === false || !empty($error)) {
            Yii::log('SMSpilot API Error: ' . $error, 'error');
            return array(
                'error' => true,
                'message' => $error,
                'http_code' => $httpCode,
            );
        }
        
        $result = json_decode($response, true);
        
        // If JSON parsing fails, try to parse as text response
        if ($result === null) {
            // Parse text response like "SUCCESS=APIKEY INFO\nid=44103\n..."
            $lines = explode("\n", trim($response));
            $result = array();
            
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $result[$key] = $value;
                }
            }
            
            // Check for error in text response
            if (isset($result['ERROR'])) {
                $result['error'] = true;
                $result['message'] = $result['ERROR'];
            } else {
                $result['error'] = false;
            }
        }
        
        // Log API response
        Yii::log('SMSpilot API Response: ' . $response, 'info');
        
        return $result;
    }
    
    /**
     * Format phone number for SMSpilot
     * @param string $phone Phone number
     * @return string Formatted phone number
     */
    public function formatPhone($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // Convert 8 to 7 for Russian numbers
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '8') {
            $phone = '7' . substr($phone, 1);
        }
        
        if (strlen($phone) === 10) {
            $phone = '7' . $phone;
        }
        
        // Return without + for SMSpilot
        return $phone;
    }
}
