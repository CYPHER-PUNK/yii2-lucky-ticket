<?php

namespace app\controllers;

use app\models;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

class TicketController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    public function actions()
    {
        return ['error' => ['class' => 'yii\web\ErrorAction']];
    }

    public function actionIndex()
    {
        $model = new models\Ticket();
        return $this->render('index', ['model' => $model]);
    }

    public function actionCount($length)
    {
        echo Json::encode(['message' => (int) models\Ticket::countLuckyTicket($length)]);
        Yii::$app->end();
    }

    public function actionList($length)
    {
        echo Json::encode(['message' => models\Ticket::generateSortedLuckyTicketList($length, 100)]);
        Yii::$app->end();
    }
}
