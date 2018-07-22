<?php

use Yaecho\Mysql\Diff;
use Yaecho\Mysql\Database;

require 'vendor/autoload.php';

$M = new Diff([
       'dbName' => 'test_competition',
       'host' => 'localhost',
       'username' => 'test',
       'password' => 'test',], [
        'dbName' => 'test_competition2',
        'host' => 'localhost',
        'username' => 'test',
        'password' => 'test',]);

var_dump($M->compare());

// $t = new Database([
//     'dbName' => 'test_competition2',
//     'host' => 'localhost',
//     'username' => 'test',
//     'password' => 'test',]);

// var_dump($t->tables['njtech_admin']->columns['password']);

