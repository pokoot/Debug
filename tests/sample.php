<?php

$loader = require __DIR__ . "/../vendor/autoload.php";

use Goldfinger\Debug;

$d = new Debug();


$query = "
        Select

        * f
        *
        forom

    ";

$d->query($query);
$d->header("FILE TRANSACTION");
$d->log("Breakpoint 1 <Br/> here <Br/> here<Br/> here<Br/> here");
$d->warn("This is a warning");
$d->log("Breakpoint 2");
$d->error("Oops, this should be an error");
$d->log("Sample");
$d->log("Only");
$d->log("Holy Cow");


$arr = array("test 1", "test 2" => array( "test 3" => "test 4" ));


$d->dump($arr);
$d->show();




