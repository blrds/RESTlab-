<?php
    include("api/helps.php");
    $method = $_SERVER['REQUEST_METHOD'];
    $formData = getFormData($method);
    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = rtrim($url, '/');
    $urls = explode('/', $url);
    $router = $urls[0];
    $urlData = array_slice($urls, 1);
    include_once 'api/routers/' . $router . '.php';
    route($method, $urlData, $formData);
?>