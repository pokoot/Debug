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


$d->header("FILE TRANSACTION");
$d->log("Breakpoint 1 <Br/> here <Br/> here<Br/> here<Br/> here");
$d->warn("This is a warning");
$d->log("Breakpoint 2");
$d->error("Oops, this should be an error");
$d->log("Sample");
$d->log("Only");
$d->log("Holy Cow");



$d->show();




