<?php
define ("HOST", "127.0.0.1");
define ("USER","root");
define ("PASSWORD", "");
define ("DB", "sls");

define("MAXPERPAGE",9);
$conn = mysqli_connect(HOST,USER,PASSWORD,DB) or die("Couldn't connect to database");

