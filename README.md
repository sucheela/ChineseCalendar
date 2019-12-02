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
