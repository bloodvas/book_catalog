<?php

/**
 * SMS Controller for testing and managing SMS functionality
 */
class SmsController extends Controller
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
     * Test SMS functionality
     */
    public function actionTest()
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Access denied');
        }

        $model = new TestSmsForm();

        if (isset($_POST['TestSmsForm'])) {
            $model->attributes = $_POST['TestSmsForm'];
            
            if ($model->validate()) {
                $result = SMSNotificationService::testService($model->phone);
                
                if ($result) {
                    Yii::app()->user->setFlash('success', 'Тестовое SMS успешно отправлено');
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка отправки SMS. Проверьте логи.');
                }
                
                $this->refresh();
            }
        }

        $this->render('test', array(
            'model' => $model,
        ));
    }

    /**
     * Check SMS balance
     */
    public function actionBalance()
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Access denied');
        }

        $balance = Yii::app()->smspilot->getBalance();
        
        $this->render('balance', array(
            'balance' => $balance,
        ));
    }
}

/**
 * Test SMS Form model
 */
class TestSmsForm extends CFormModel
{
    public $phone;

    public function rules()
    {
        return array(
            array('phone', 'required'),
            array('phone', 'match', 'pattern'=>'/^\+?[0-9]{10,15}$/', 'message'=>'Неверный формат телефона'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'phone' => 'Телефон для теста',
        );
    }
}
