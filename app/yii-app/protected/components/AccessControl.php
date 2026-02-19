<?php

/**
 * AccessControl filter for managing permissions
 */
class AccessControl extends CFilter
{
    protected function preFilter($filterChain)
    {
        $controller = $filterChain->controller;
        $action = $filterChain->action;
        
        // Define access rules
        $rules = array(
            // Guest access (not authenticated)
            'guest' => array(
                'book/index',
                'book/view',
                'author/index', 
                'author/view',
                'site/index',
                'site/contact',
                'site/login',
                'report/topAuthors',
                'report/exportTopAuthors',
                'report/index',
                'subscription/create',
                'subscription/subscribe',
            ),
            // User access (authenticated)
            'user' => array(
                'book/*',
                'author/*',
                'site/logout',
                'site/index',
                'site/contact',
                'report/*',
                'sms/*',
            ),
        );
        
        $userRole = Yii::app()->user->isGuest ? 'guest' : 'user';
        $route = $controller->id . '/' . $action->id;
        
        // Check if current route is allowed for user role
        if (!in_array($route, $rules[$userRole]) && !in_array($controller->id . '/*', $rules[$userRole])) {
            if (Yii::app()->user->isGuest) {
                Yii::app()->user->loginRequired();
            } else {
                throw new CHttpException(403, 'Доступ запрещен');
            }
        }
        
        return true;
    }
}
