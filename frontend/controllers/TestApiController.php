<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\components\TransAviaKZComponent;

/**
 * Site controller
 */
class TestApiController extends Controller
{

    /**
     * Test Action for TransAvia KZ
     *
     * @return mixed
     */
    public function actionTest()
    {
        $searchData = [
            "routes" => [
                [
                    "departure_code" => "MOW",
                    "arrival_code" => "MSQ",
                    "departure_date" => "2024-02-10"
                ]
            ]
        ];

        // You can use as component. Uncomment declaration in config main.php file to use.
        //$searchRes = Yii::$app->transAviaKZApi->searchFlight($searchData);

        $api = new TransAviaKZComponent();

        $requestRes = $api->searchFlight($searchData);

//        $requestRes = $api->familyFareFlight($hash, $sessionId);
//        $requestRes = $api->checkPriceFlight($hash, $sessionId, $orderId, $fareHash);
//        $requestRes = $api->bookFlight($bookData);
//        $requestRes = $api->bookFlight($bookData);
//        $requestRes = $api->cancelBookFlight($orderId);
//        $requestRes = $api->approveFlight($orderId);
//        $requestRes = $api->orderInfo($orderId);
//        $requestRes = $api->flightFareRulesInfo($orderId);

        return $this->render('test', [
            'requestResults' => $requestRes
        ]);



    }
}
