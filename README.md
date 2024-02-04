<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 User Profiles</h1>
    <br>
</p>

## Table of content
- [About](#About)
- [Project Structure](#project-structure)
- [Install](#install)
- [Run](#run-application)
- [Migration](#migration)
- [Useful commands](#useful-commands)

## About
This project as technical task to implement API for [transavia.kz](http://docs.transavia.kz/en) to provide my skills and code style.

Created TransAviaKZComponent component. 

Using as component where you want

```php
        use common\components\TransAviaKZComponent;
        
        //... 

        $api = new TransAviaKZComponent();

        $requestRes = $api->searchFlight($searchData);

        $requestRes = $api->familyFareFlight($hash, $sessionId);
        $requestRes = $api->checkPriceFlight($hash, $sessionId, $orderId, $fareHash);
        $requestRes = $api->bookFlight($bookData);
        $requestRes = $api->bookFlight($bookData);
        $requestRes = $api->cancelBookFlight($orderId);
        $requestRes = $api->approveFlight($orderId);
        $requestRes = $api->orderInfo($orderId);
        $requestRes = $api->flightFareRulesInfo($orderId);
```

Or you can use as global component. Uncomment declaration in config main.php file to use.

config/main.php
```php
 [
    //...
    'components' => [
        //...
        'transAviaKZApi' => [
            'class' => 'common\components\TransAviaKZComponent',
        ],
        //...
    ],
    //...
];

```

and you like this:

```php
        $searchData = [
            "routes" => [
                [
                    "departure_code" => "MOW",
                    "arrival_code" => "MSQ",
                    "departure_date" => "2024-02-10"
                ]
            ]
        ]; 
        
        $searchRes = Yii::$app->transAviaKZApi->searchFlight($searchData);
```

## Project structure

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

## Install

1. Install via command: ```composer create-project --prefer-dist yiisoft/yii2-app-advanced yii2-transaviakz-api```
2. Init : ```php init```
3. Create DB and update **common/config/main-local.php**
4. Run migration ```php yii migrate```

## Run application:

1. Use docker, vagrant or setup 2 hosts `admin` to `/backend/web` and `front` to `frontend/web`
2. Update `common\config\params-local.php` and add api variables
```php
return [
    'transaviakz.host' => '',
    'transaviakz.username' => '',
    'transaviakz.password' => '',
];

```
3. Check url as example output for search results `http://transaviakz.loc/index.php?r=test-api%2Ftest`. Code located here `frontend\controllers\TestApiController.php`
3. Component location `common/components/TransAviaKZComponent.php`



## Useful commands:

## Migration:
* Run migration ```php yii migrate```
* Revert last migration ```yii migrate/redo```
* Refresh migration ```yii migrate/fresh ```