<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Sucheela\ChineseCalendar\ChineseCalendar;

$cal = new ChineseCalendar(2025, 10, 14, 10);
echo $cal->toString();
echo 'Year Stem: ' . $cal->getStem(ChineseCalendar::TYPE_YEAR) . "\n";
echo 'Year Branch: ' . $cal->getBranch(ChineseCalendar::TYPE_YEAR) . "\n";
