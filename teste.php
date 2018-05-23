<?php

require"vendor\autoload.php";
use facades\app;

app::get('/home/{gean}','home@padrao');
app::get('/produto/{id}','home@padrao');
