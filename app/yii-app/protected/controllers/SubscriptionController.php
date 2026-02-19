<?php

class SubscriptionController extends Controller
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
     * Subscribe to an author
     * @param integer $author_id
     */
    public function actionSubscribe($author_id)
    {
        if (!Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Only guests can subscribe');
        }

        $author = Author::model()->findByPk($author_id);
        if ($author === null) {
            throw new CHttpException(404, 'Author not found');
        }

        $model = new Subscription();
        $model->author_id = $author_id;

        if (isset($_POST['Subscription'])) {
            $model->attributes = $_POST['Subscription'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Вы успешно подписались на автора ' . $author->full_name);
                $this->redirect(array('author/view', 'id' => $author_id));
            }
        }

        $this->render('subscribe', array(
            'model' => $model,
            'author' => $author,
        ));
    }

    /**
     * Create subscription (alternative action)
     */
    public function actionCreate()
    {
        if (!Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Only guests can subscribe');
        }

        $model = new Subscription();

        if (isset($_POST['Subscription'])) {
            $model->attributes = $_POST['Subscription'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Подписка успешно оформлена');
                $this->redirect(array('author/view', 'id' => $model->author_id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Manage subscriptions (for admin users)
     */
    public function actionAdmin()
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Access denied');
        }

        $model = new Subscription('search');
        $model->unsetAttributes();
        if (isset($_GET['Subscription'])) {
            $model->attributes = $_GET['Subscription'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Delete subscription
     * @param integer $id
     */
    public function actionDelete($id)
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'Access denied');
        }

        if (Yii::app()->request->isPostRequest) {
            $model = Subscription::model()->findByPk($id);
            if ($model !== null) {
                $model->delete();
            }

            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
}
