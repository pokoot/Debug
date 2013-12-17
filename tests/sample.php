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


$d->log("Breakpoint 1");
$d->log("Breakpoint 2");
//->log("<pre>$query</pre>");


$d->show();




