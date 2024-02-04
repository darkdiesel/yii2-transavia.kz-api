<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Test';
$this->params['breadcrumbs'][] = $this->title;

/** @var array $requestResults */
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <pre><?= print_r($requestResults, true); ?></pre>
</div>
