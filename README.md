# ChineseCalendar

Calculate Chinese dates from Gregorian dates. Works until the year 2100 based on Dr. Herong Yang's ChineseCalendar.java.

Installation
------------

Install using Composer (local development):

```bash
composer install
composer dump-autoload
```

Usage
-----

```php
require 'vendor/autoload.php';

use Sucheela\ChineseCalendar\ChineseCalendar;

$cal = new ChineseCalendar(2000, 12, 31, 23);

echo $cal->getStem(ChineseCalendar::TYPE_YEAR); // Yang Metal
echo $cal->getBranch(ChineseCalendar::TYPE_YEAR); // Dragon
```

There's an example script in `examples/usage.php`.
# ChineseCalendar
Calculate Chinese dates from Gregorian dates. Work until the year 2100

## Usage:
```
$cal = new ChineseCalendar(2000, 12, 31, 23);

print $cal->getStem($cal::TYPE_YEAR); // Yang Metal
print $cal->getStem($cal::TYPE_MONTH); // Yin Earth
print $cal->getStem($cal::TYPE_DAY); // Yin Water
print $cal->getStem($cal::TYPE_HOUR); // Yang Water

print $cal->getBranch($cal::TYPE_YEAR); // Dragon
print $cal->getBranch($cal::TYPE_MONTH); // Ox
print $cal->getBranch($cal::TYPE_DAY); // Boar
print $cal->getBranch($cal::TYPE_HOUR); // Rat
```
