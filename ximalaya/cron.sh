#!/bin/bash

APP_PATH=`dirname $0`
PHP_PATH=`which php`

cd $APP_PATH

$PHP_PATH run.php

exit 1