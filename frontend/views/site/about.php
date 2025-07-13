<?php

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is a Yii2 powered application for managing and monitoring data. It includes the following features:</p>

    <ul>
        <li><b>User Authentication:</b> Secure login, registration, and password management.</li>
        <li><b>Data Management:</b> Create, read, update, and delete records in a comprehensive data table.</li>
        <li><b>Advanced Search and Filtering:</b> Easily search and filter data by various criteria including region, province, city/municipality, and status.</li>
        <li><b>Dynamic Address Dropdowns:</b> Dependent dropdowns for selecting region, province, and city/municipality for accurate data entry.</li>
        <li><b>Responsive Layout:</b> A modern, responsive user interface built with Bootstrap 5.</li>
    </ul>

</div>