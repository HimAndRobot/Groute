<?php
require 'vendor/autoload.php';
use facades\app;
app::get('/produto/{id[int-?]}/{gean[?]}','produto');
app::run();


?>