<?php

namespace app\components;

use Yii;
/**
 * Site controller
 */
class AuthController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function beforeAction($action)
    {
        if (Yii::$app->controller->id . '/' . Yii::$app->controller->action->id != 'site/login') {
            $cookies = Yii::$app->request->cookies;
            if (!isset($cookies['access-token'])) {
                return $this->redirect(['/site/login']);
            }
        }
        return parent::beforeAction($action);
    }
}