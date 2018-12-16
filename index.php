<?php

define('DEBUG', 1);

error_reporting(DEBUG ? E_ALL & ~E_STRICT & ~E_NOTICE : 0);
ini_set('display_errors', "On");

$mapping = [
    'TestApp\Navigator' => __DIR__ . '/App/Navigator.class.php',
    'TestApp\Router'    => __DIR__ . '/App/Router.class.php',
];

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);

?>
<html>
<head>
    <title>All different directions / svk test</title>
    <style>
        td {
            vertical-align: top;
            padding-right: 10px;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <td>
            <form method="post">
                <label for="input">Sample Input</label>
                <br>
                <textarea name="input" id="input" cols="80" rows="10"><?=!empty($_POST['input'])?$_POST['input']:'';?></textarea>
                <br /><br />
                <button type="submit">Submit</button>
            </form>
        </td>
        <td>
            <?php if (!empty($_POST['input'])) { ?>
                <label for="output">Sample Output</label>
                <br>
                <textarea id="output" cols="50" rows="10"><?=(new \TestApp\Navigator($_POST['input']))->route();?></textarea>
            <?php } ?>
        </td>
    </tr>
</table>
