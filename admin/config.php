<?php

//database
define ("HOST", "127.0.0.1");
define ("USER","root");
define ("PASSWORD", "");
define ("DB", "sls");
//page configuration
define ("MAXPERPAGE",5);

$conn = mysqli_connect(HOST,USER,PASSWORD,DB) or die("Couldn't connect to database");

