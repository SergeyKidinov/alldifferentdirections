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


$obg1 = new StdClass();
#$obg1 = new ArrayObject(); // SPL
$obg1->test1 = 'test1';
$obg1->test2 = 'test2';
$obg1->test3 = 'test3';

$obg2 = clone $obg1;

$obg1->test4 = 'test4';
echo '<pre>';
print_r($obg2);

#$x = include __DIR__ . '/App/Navigator.class.php';
#$foo = include '../data.php';
#echo '<pre>12'; print_r($foo);

$arr = [
    0 => ['test0','test0'],
    1 => ['test1',],
    /*2 => 'test2',
    3 => 'test3'*/
];
foreach ($arr as &$val) {
    $val[] = 'afafff';

}

echo '<pre>'; print_r($arr);

function arr1($a, $b , $c){
    /*$args = func_get_args();
    var_export($args);*/
    echo $a ." - ". $b ." - ". $c;
}

arr1(...[1,2,3,4]);

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