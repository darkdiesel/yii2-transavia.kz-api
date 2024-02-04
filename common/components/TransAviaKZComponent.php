<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

class TransAviaKZComponent extends Component
{
    private $token;
    private $username;
    private $password;
    private $host;
    private $client;

    private const AUTH_URI = '/auth/login';
    private const FLIGHT_SEARCH_URI = '/flight/search';
    private const FLIGHT_FARE_FAMILIES_URI = '/flight/fare_families';
    private const FLIGHT_CHECK_PRICE_URI = '/flight/check_price';
    private const FLIGHT_BOOK_URI = '/flight/book_flight';
    private const FLIGHT_CANCEL_BOOK_URI = '/flight/cancel_book';
    private const FLIGHT_APPROVE_URI = '/order/approve_book';
    private const ORDER_INFO_URI = '/order/get_order_info';
    private const FLIGHT_FARE_RULES_URI = '/flight/fare_rules';

    function __construct()
    {
        $this->username = Yii::$app->params['transaviakz.username'];;
        $this->password = Yii::$app->params['transaviakz.password'];;
        $this->host = Yii::$app->params['transaviakz.host'];

        // create common http client for requests
        $this->client = new Client(['baseUrl' => $this->host]);
    }

    /**
     * Default headers for request
     *
     * @return string[]
     */
    private function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Headers with auth token
     *
     * @return array
     */
    private function getHeadersAuth(): array
    {
        return array_merge($this->getHeaders(), ['Authorization' => 'Bearer ' . $this->token]);
    }

    /**
     * Get Auth Token
     *
     * @return void
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function auth() : void
    {
        $requestData = [
            'username' => $this->username,
            'password' => $this->password
        ];

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setUrl(self::AUTH_URI)
            ->addHeaders($this->getHeaders())
            ->setData($requestData)
            ->send();

        if ($response->isOk) {
            $responseData = $response->getData();

            $this->token = $responseData['access_token'] ?? null;
        }
    }

    /**
     * Search flights by data
     *
     * @param array $requestData
     * @return false|mixed
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function searchFlight(array $requestData)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            return FALSE;
        }

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl(self::FLIGHT_SEARCH_URI)
            ->addHeaders($this->getHeadersAuth())
            ->setData($requestData)
            ->send();

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Check Family fate flights from search result
     *
     * @param string $searchHash
     * @param string $sessionId
     * @return false|mixed
     */
    public function familyFareFlight(string $searchHash, string $sessionId)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        $requestData = [
            'search_hash' => $searchHash,
            'session_id' => $sessionId
        ];

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(self::FLIGHT_FARE_FAMILIES_URI)
                ->addHeaders($this->getHeadersAuth())
                ->setData($requestData)
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Check flights price from search result
     *
     * @param string $searchHash
     * @param string $sessionId
     * @param int $orderId Order ID
     * @param string $fareHash param not required
     * @return false|mixed
     */
    public function checkPriceFlight(string $searchHash, string $sessionId, int $orderId, string $fareHash = '')
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        $requestData = [
            'search_hash' => $searchHash,
            'session_id' => $sessionId,
            'order_id' => $orderId,
            'fare_hash' => $fareHash,
        ];

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(self::FLIGHT_CHECK_PRICE_URI)
                ->addHeaders($this->getHeadersAuth())
                ->setData($requestData)
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Book flight
     *
     * @param array $requestData

     * @return false|mixed
     */
    public function bookFlight(array $requestData)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(self::FLIGHT_BOOK_URI)
                ->addHeaders($this->getHeadersAuth())
                ->setData($requestData)
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Cancel book flight
     *
     * @param int $orderId Order ID

     * @return false|mixed
     */
    public function cancelBookFlight(int $orderId)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(sprintf("%s/%s", self::FLIGHT_CANCEL_BOOK_URI, $orderId))
                ->addHeaders($this->getHeadersAuth())
                ->setData([])
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Approve flight
     *
     * @param int $orderId Order ID
     * @param string $type

     * @return false|mixed
     */
    public function approveFlight(int $orderId, string $type = 'flight')
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        $requestData = [
            'order_id' => $orderId,
            'type' => $type,
        ];

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(self::FLIGHT_APPROVE_URI)
                ->addHeaders($this->getHeadersAuth())
                ->setData($requestData)
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Get order info
     *
     * @param int $orderId Order ID

     * @return false|mixed
     */
    public function orderInfo(int $orderId)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        try {
            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(sprintf("%s/%s", self::ORDER_INFO_URI, $orderId))
                ->addHeaders($this->getHeadersAuth())
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }

    /**
     * Get flight fare rules
     *
     * @param int $orderId Order ID

     * @return false|mixed
     */
    public function flightFareRulesInfo(int $orderId)
    {
        // get access token before make request
        try {
            $this->auth();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        $requestData = [
            'order_id' => $orderId,
        ];

        try {
            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl(self::FLIGHT_FARE_RULES_URI)
                ->addHeaders($this->getHeadersAuth())
                ->setData($requestData)
                ->send();
        } catch (\Exception $e) {
            // Log Exception
            return FALSE;
        }

        if ($response->isOk) {
            return $response->getData();
        }

        return FALSE;
    }
}