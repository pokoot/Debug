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
$d->warn("This is a warning");
$d->log("Breakpoint 2");
$d->error("Oops, this should be an error");
//->log("<pre>$query</pre>");


$d->show();




