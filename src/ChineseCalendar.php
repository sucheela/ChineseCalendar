<?php

declare(strict_types=1);

namespace Sucheela\ChineseCalendar;

/**
 * Calculate Chinese date based on Gregorian date.
 * Use getStem() or getBranch() to get heavenly stems and branches
 * associated with the date given at the constructor.
 */
class ChineseCalendar
{
    public const FORMAT_NUMBER = 1;
    public const FORMAT_STRING = 2;

    public const TYPE_YEAR = 1;
    public const TYPE_MONTH = 2;
    public const TYPE_DAY = 3;
    public const TYPE_HOUR = 4;

    public static array $DaysInGregorianMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    public static array $MonthNames = [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ];
    public static array $StemNames = [
        'Yang Wood', 'Yin Wood', 'Yang Fire', 'Yin Fire', 'Yang Earth', 'Yin Earth', 'Yang Metal', 'Yin Metal', 'Yang Water', 'Yin Water'
    ];
    public static array $BranchNames = [
        'Rat', 'Ox', 'Tiger', 'Rabbit', 'Dragon', 'Snake', 'Horse', 'Ram', 'Monkey', 'Rooster', 'Dog', 'Boar'
    ];

    // ... ChineseMonths and other large static arrays copied as-is for accuracy
    private static array $ChineseMonths = [
        0x00,0x04,0xad,0x08,0x5a,0x01,0xd5,0x54,0xb4,0x09,0x64,0x05,0x59,0x45,
        0x95,0x0a,0xa6,0x04,0x55,0x24,0xad,0x08,0x5a,0x62,0xda,0x04,0xb4,0x05,
        0xb4,0x55,0x52,0x0d,0x94,0x0a,0x4a,0x2a,0x56,0x02,0x6d,0x71,0x6d,0x01,
        0xda,0x02,0xd2,0x52,0xa9,0x05,0x49,0x0d,0x2a,0x45,0x2b,0x09,0x56,0x01,
        0xb5,0x20,0x6d,0x01,0x59,0x69,0xd4,0x0a,0xa8,0x05,0xa9,0x56,0xa5,0x04,
        0x2b,0x09,0x9e,0x38,0xb6,0x08,0xec,0x74,0x6c,0x05,0xd4,0x0a,0xe4,0x6a,
        0x52,0x05,0x95,0x0a,0x5a,0x42,0x5b,0x04,0xb6,0x04,0xb4,0x22,0x6a,0x05,
        0x52,0x75,0xc9,0x0a,0x52,0x05,0x35,0x55,0x4d,0x0a,0x5a,0x02,0x5d,0x31,
        0xb5,0x02,0x6a,0x8a,0x68,0x05,0xa9,0x0a,0x8a,0x6a,0x2a,0x05,0x2d,0x09,
        0xaa,0x48,0x5a,0x01,0xb5,0x09,0xb0,0x39,0x64,0x05,0x25,0x75,0x95,0x0a,
        0x96,0x04,0x4d,0x54,0xad,0x04,0xda,0x04,0xd4,0x44,0xb4,0x05,0x54,0x85,
        0x52,0x0d,0x92,0x0a,0x56,0x6a,0x56,0x02,0x6d,0x02,0x6a,0x41,0xda,0x02,
        0xb2,0xa1,0xa9,0x05,0x49,0x0d,0x0a,0x6d,0x2a,0x09,0x56,0x01,0xad,0x50,
        0x6d,0x01,0xd9,0x02,0xd1,0x3a,0xa8,0x05,0x29,0x85,0xa5,0x0c,0x2a,0x09,
        0x96,0x54,0xb6,0x08,0x6c,0x09,0x64,0x45,0xd4,0x0a,0xa4,0x05,0x51,0x25,
        0x95,0x0a,0x2a,0x72,0x5b,0x04,0xb6,0x04,0xac,0x52,0x6a,0x05,0xd2,0x0a,
        0xa2,0x4a,0x4a,0x05,0x55,0x94,0x2d,0x0a,0x5a,0x02,0x75,0x61,0xb5,0x02,
        0x6a,0x03,0x61,0x45,0xa9,0x0a,0x4a,0x05,0x25,0x25,0x2d,0x09,0x9a,0x68,
        0xda,0x08,0xb4,0x09,0xa8,0x59,0x54,0x03,0xa5,0x0a,0x91,0x3a,0x96,0x04,
        0xad,0xb0,0xad,0x04,0xda,0x04,0xf4,0x62,0xb4,0x05,0x54,0x0b,0x44,0x5d,
        0x52,0x0a,0x95,0x04,0x55,0x22,0x6d,0x02,0x5a,0x71,0xda,0x02,0xaa,0x05,
        0xb2,0x55,0x49,0x0b,0x4a,0x0a,0x2d,0x39,0x36,0x01,0x6d,0x80,0x6d,0x01,
        0xd9,0x02,0xe9,0x6a,0xa8,0x05,0x29,0x0b,0x9a,0x4c,0xaa,0x08,0xb6,0x08,
        0xb4,0x38,0x6c,0x09,0x54,0x75,0xd4,0x0a,0xa4,0x05,0x45,0x55,0x95,0x0a,
        0x9a,0x04,0x55,0x44,0xb5,0x04,0x6a,0x82,0x6a,0x05,0xd2,0x0a,0x92,0x6a,
        0x4a,0x05,0x55,0x0a,0x2a,0x4a,0x5a,0x02,0xb5,0x02,0xb2,0x31,0x69,0x03,
        0x31,0x73,0xa9,0x0a,0x4a,0x05,0x2d,0x55,0x2d,0x09,0x5a,0x01,0xd5,0x48,
        0xb4,0x09,0x68,0x89,0x54,0x0b,0xa4,0x0a,0xa5,0x6a,0x95,0x04,0xad,0x08,
        0x6a,0x44,0xda,0x04,0x74,0x05,0xb0,0x25,0x54,0x03
    ];

    private const BaseYear = 1901;
    private const BaseMonth = 1;
    private const BaseDate = 1;
    private const BaseIndex = 0;
    private const BaseChineseYear = 4597;
    private const BaseChineseMonth = 11;
    private const BaseChineseDate = 11;

    private static array $BigLeapMonthYears = [
        6, 14, 19, 25, 33, 36, 38, 41, 44, 52,
        55, 79, 117, 136, 147, 150, 155, 158, 185, 193
    ];

    private static array $SectionalTermMap = [
        [7,6,6,6,6,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,5,5,5,5,5,4,5,5],
        [5,4,5,5,5,4,4,5,5,4,4,4,4,4,4,4,4,3,4,4,4,3,3,4,4,3,3,3],
        [6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5],
        [5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,4,4,5,5,4,4,4,5,4,4,4,4,5],
        [6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5],
        [6,6,7,7,6,6,6,7,6,6,6,6,5,6,6,6,5,5,6,6,5,5,5,6,5,5,5,5,4,5,5,5,5],
        [7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,6,6,6,7,7],
        [8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,7],
        [8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,7],
        [9,9,9,9,8,9,9,9,8,8,9,9,8,8,8,9,8,8,8,8,7,8,8,8,7,7,8,8,8],
        [8,8,8,8,7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,7],
        [7,8,8,8,7,7,8,8,7,7,7,8,7,7,7,7,6,7,7,7,6,6,7,7,6,6,6,7,7]
    ];

    private static array $SectionalTermYear = [
        [13,49,85,117,149,185,201,250,250],
        [13,45,81,117,149,185,201,250,250],
        [13,48,84,112,148,184,200,201,250],
        [13,45,76,108,140,172,200,201,250],
        [13,44,72,104,132,168,200,201,250],
        [5,33,68,96,124,152,188,200,201],
        [29,57,85,120,148,176,200,201,250],
        [13,48,76,104,132,168,196,200,201],
        [25,60,88,120,148,184,200,201,250],
        [16,44,76,108,144,172,200,201,250],
        [28,60,92,124,160,192,200,201,250],
        [17,53,85,124,156,188,200,201,250]
    ];

    private static array $PrincipleTermMap = [
        [21,21,21,21,21,20,21,21,21,20,20,21,21,20,20,20,20,20,20,20,20,19,20,20,20,19,19,20],
        [20,19,19,20,20,19,19,19,19,19,19,19,19,18,19,19,19,18,18,19,19,18,18,18,18,18,18,18],
        [21,21,21,22,21,21,21,21,20,21,21,21,20,20,21,21,20,20,20,21,20,20,20,20,19,20,20,20,20],
        [20,21,21,21,20,20,21,21,20,20,20,21,20,20,20,20,19,20,20,20,19,19,20,20,19,19,19,20,20],
        [21,22,22,22,21,21,22,22,21,21,21,22,21,21,21,21,20,21,21,21,20,20,21,21,20,20,20,21,21],
        [22,22,22,22,21,22,22,22,21,21,22,22,21,21,21,22,21,21,21,21,20,21,21,21,20,20,21,21,21],
        [23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,23,23,22,22,22,23,22,22,22,22,23],
        [23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,23,23,22,22,22,23,23],
        [23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,23,23,22,22,22,23,23],
        [24,24,24,24,23,24,24,24,23,23,24,24,23,23,23,24,23,23,23,23,22,23,23,23,22,22,23,23,23],
        [23,23,23,23,22,23,23,23,22,22,23,23,22,22,22,23,22,22,22,22,21,22,22,22,21,21,22,22,22],
        [22,22,23,23,22,22,22,23,22,22,22,22,21,22,22,22,21,21,22,22,21,21,21,22,21,21,21,21,22]
    ];

    private static array $PrincipleTermYear = [
        [13,45,81,113,149,185,201],
        [21,57,93,125,161,193,201],
        [21,56,88,120,152,188,200,201],
        [21,49,81,116,144,176,200,201],
        [17,49,77,112,140,168,200,201],
        [28,60,88,116,148,180,200,201],
        [25,53,84,112,144,172,200,201],
        [29,57,89,120,148,180,200,201],
        [17,45,73,108,140,168,200,201],
        [28,60,92,124,160,192,200,201],
        [16,44,80,112,148,180,200,201],
        [17,53,88,120,156,188,200,201]
    ];

    private static array $HourBranchMap = [
        [23, 0], [1, 2], [3, 4], [5, 6], [7, 8], [9, 10], [11, 12], [13, 14], [15, 16], [17, 18], [19, 20], [21, 22]
    ];

    private int $gregorianYear;
    private int $gregorianMonth;
    private int $gregorianDate;
    private ?int $gregorianHour;
    private bool $isGregorianLeap;
    private int $dayOfYear;
    private int $dayOfWeek; // Sunday is the first day
    private int $chineseYear;
    private int $chineseMonth; // -n is a leap month
    private int $chineseDate;
    private int $sectionalTerm;
    private int $principleTerm;
    private int $monthStem;
    private int $monthBranch;
    private int $dateStem;
    private int $dateBranch;
    private int $hourStem;
    private int $hourBranch;

    public function __construct(int $year, int $month, int $day, ?int $hour = null)
    {
        $y = is_numeric($year) ? $year : 1901;
        $m = is_numeric($month) ? $month : 1;
        $d = is_numeric($day) ? $day : 1;
        $h = is_numeric($hour) ? $hour : null;

        $this->setGregorian($y, $m, $d, $h);
        $this->computeChineseFields();
        $this->computeSolarTerms();

        $this->computeMonthStemBranch();
        $this->computeDateStemBranch();
        if ($this->gregorianHour !== null) {
            $this->computeHourStemBranch();
        }
    }

    public function toString(): string
    {
        $str = 'Gregorian Year : ' . $this->gregorianYear . "\n"
            . 'Month : ' . $this->gregorianMonth . "\n"
            . 'Date : ' . $this->gregorianDate . "\n"
            . 'Chinese Year : ' . $this->chineseYear . "\n"
            . 'Month : ' . $this->chineseMonth . "\n"
            . 'Date : ' . $this->chineseDate . "\n";
        return $str;
    }

    public function getStem(int $type = self::TYPE_YEAR, int $format = self::FORMAT_STRING)
    {
        switch ($type) {
            case self::TYPE_YEAR:
                $stem = ($this->chineseYear - 1) % 10;
                break;
            case self::TYPE_MONTH:
                $stem = $this->monthStem;
                break;
            case self::TYPE_DAY:
                $stem = $this->dateStem;
                break;
            case self::TYPE_HOUR:
                $stem = $this->hourStem;
                break;
            default:
                $stem = 0;
        }

        if ($format === self::FORMAT_NUMBER) {
            return $stem + 1;
        }

        return self::$StemNames[$stem] ?? '';
    }

    public function getBranch(int $type = self::TYPE_YEAR, int $format = self::FORMAT_STRING)
    {
        switch ($type) {
            case self::TYPE_YEAR:
                $branch = ($this->chineseYear - 1) % 12;
                break;
            case self::TYPE_MONTH:
                $branch = $this->monthBranch;
                break;
            case self::TYPE_DAY:
                $branch = $this->dateBranch;
                break;
            case self::TYPE_HOUR:
                $branch = $this->hourBranch;
                break;
            default:
                $branch = 0;
        }

        if ($format === self::FORMAT_NUMBER) {
            return $branch + 1;
        }

        return self::$BranchNames[$branch] ?? '';
    }

    private function setGregorian(int $y, int $m, int $d, ?int $h): void
    {
        $this->gregorianYear = $y;
        $this->gregorianMonth = $m;
        $this->gregorianDate = $d;
        $this->gregorianHour = $h;
        $this->isGregorianLeap = $this->isGregorianLeapYear($y);
        $this->dayOfYear = $this->dayOfYear($y, $m, $d);
        $this->dayOfWeek = $this->dayOfWeek($y, $m, $d);
        $this->chineseYear = 0;
        $this->chineseMonth = 0;
        $this->chineseDate = 0;
        $this->sectionalTerm = 0;
        $this->principleTerm = 0;
    }

    private function isGregorianLeapYear(int $year): bool
    {
        $isLeap = false;
        if ($year % 4 == 0) $isLeap = true;
        if ($year % 100 == 0) $isLeap = false;
        if ($year % 400 == 0) $isLeap = true;
        return $isLeap;
    }

    private function daysInGregorianMonth(int $y, int $m): int
    {
        $d = self::$DaysInGregorianMonth[$m - 1];
        if ($m == 2 && $this->isGregorianLeapYear($y)) $d++;
        return $d;
    }

    private function dayOfYear(int $y, int $m, int $d): int
    {
        $c = 0;
        for ($i = 1; $i < $m; $i++) {
            $c += $this->daysInGregorianMonth($y, $i);
        }
        $c += $d;
        return $c;
    }

    private function dayOfWeek(int $y, int $m, int $d): int
    {
        $w = 1; // 01-Jan-0001 is Monday, so base is Sunday
        $y = ($y - 1) % 400 + 1; // Gregorian calendar cycle is 400 years
        $ly = (int)(($y - 1) / 4);
        $ly = $ly - (int)(($y - 1) / 100);
        $ly = $ly + (int)(($y - 1) / 400);
        $ry = $y - 1 - $ly;
        $w = $w + $ry;
        $w = $w + 2 * $ly;
        $w = $w + $this->dayOfYear($y, $m, $d);
        $w = ($w - 1) % 7 + 1;
        return $w;
    }

    private function computeChineseFields(): int
    {
        if ($this->gregorianYear < 1901 || $this->gregorianYear > 2100) return 1;

        $startYear = self::BaseYear;
        $startMonth = self::BaseMonth;
        $startDate = self::BaseDate;
        $this->chineseYear = self::BaseChineseYear;
        $this->chineseMonth = self::BaseChineseMonth;
        $this->chineseDate = self::BaseChineseDate;

        if ($this->gregorianYear >= 2000) {
            $startYear = self::BaseYear + 99;
            $startMonth = 1;
            $startDate = 1;
            $this->chineseYear = self::BaseChineseYear + 99;
            $this->chineseMonth = 11;
            $this->chineseDate = 25;
        }

        $daysDiff = 0;
        for ($i = $startYear; $i < $this->gregorianYear; $i++) {
            $daysDiff += 365;
            if ($this->isGregorianLeapYear($i)) $daysDiff += 1;
        }
        for ($i = $startMonth; $i < $this->gregorianMonth; $i++) {
            $daysDiff += $this->daysInGregorianMonth($this->gregorianYear, $i);
        }
        $daysDiff += $this->gregorianDate - $startDate;

        $this->chineseDate += $daysDiff;
        $lastDate = $this->daysInChineseMonth($this->chineseYear, $this->chineseMonth);
        $nextMonth = $this->nextChineseMonth($this->chineseYear, $this->chineseMonth);
        while ($this->chineseDate > $lastDate) {
            if (abs($nextMonth) < abs($this->chineseMonth)) $this->chineseYear++;
            $this->chineseMonth = $nextMonth;
            $this->chineseDate -= $lastDate;
            $lastDate = $this->daysInChineseMonth($this->chineseYear, $this->chineseMonth);
            $nextMonth = $this->nextChineseMonth($this->chineseYear, $this->chineseMonth);
        }
        return 0;
    }

    private function daysInChineseMonth(int $y, int $m): int
    {
        $index = $y - self::BaseChineseYear + self::BaseIndex;
        $v = 0;
        $l = 0;
        $d = 30;
        if (1 <= $m && $m <= 8) {
            $v = self::$ChineseMonths[2 * $index];
            $l = $m - 1;
            if ((($v >> $l) & 0x01) == 1) $d = 29;
        } elseif (9 <= $m && $m <= 12) {
            $v = self::$ChineseMonths[2 * $index + 1];
            $l = $m - 9;
            if ((($v >> $l) & 0x01) == 1) $d = 29;
        } else {
            $v = self::$ChineseMonths[2 * $index + 1];
            $v = ($v >> 4) & 0x0F;
            if ($v != abs($m)) {
                $d = 0;
            } else {
                $d = 29;
                foreach (self::$BigLeapMonthYears as $i) {
                    if ($i == $index) {
                        $d = 30;
                        break;
                    }
                }
            }
        }
        return $d;
    }

    private function nextChineseMonth(int $y, int $m): int
    {
        $n = abs($m) + 1;
        if ($m > 0) {
            $index = $y - self::BaseChineseYear + self::BaseIndex;
            $v = self::$ChineseMonths[2 * $index + 1];
            $v = ($v >> 4) & 0x0F;
            if ($v == $m) $n = -$m;
        }
        if ($n == 13) $n = 1;
        return $n;
    }

    private function computeSolarTerms(): int
    {
        if ($this->gregorianYear < 1901 || $this->gregorianYear > 2100) return 1;
        $this->sectionalTerm = $this->sectionalTerm($this->gregorianYear, $this->gregorianMonth);
        $this->principleTerm = $this->principleTerm($this->gregorianYear, $this->gregorianMonth);
        return 0;
    }

    private function sectionalTerm(int $y, int $m): int
    {
        if ($y < 1901 || $y > 2100) return 0;
        $index = 0;
        $ry = $y - self::BaseYear + 1;
        while ($ry >= self::$SectionalTermYear[$m - 1][$index]) $index++;
        $term = self::$SectionalTermMap[$m - 1][4 * $index + $ry % 4];
        if (($ry == 121) && ($m == 4)) $term = 5;
        if (($ry == 132) && ($m == 4)) $term = 5;
        if (($ry == 194) && ($m == 6)) $term = 6;
        return $term;
    }

    private function principleTerm(int $y, int $m): int
    {
        if ($y < 1901 || $y > 2100) return 0;
        $index = 0;
        $ry = $y - self::BaseYear + 1;
        while ($ry >= self::$PrincipleTermYear[$m - 1][$index]) $index++;
        $term = self::$PrincipleTermMap[$m - 1][4 * $index + $ry % 4];
        if (($ry == 171) && ($m == 3)) $term = 21;
        if (($ry == 181) && ($m == 5)) $term = 21;
        return $term;
    }

    private function computeMonthStemBranch(): void
    {
        $this->monthBranch = ($this->chineseMonth - 1 + 2) % 12;

        $yearStem = ($this->chineseYear - 1) % 10;
        $firstStem = (($yearStem * 2) + 2) % 10;
        $this->monthStem = ($firstStem - 1 + $this->chineseMonth) % 10;
    }

    private function computeDateStemBranch(): void
    {
        $d = $this->dayOfYear($this->gregorianYear, $this->gregorianMonth, $this->gregorianDate);
        if ($this->gregorianYear == 1944) {
            $c = ($d - 1) % 60;
        } elseif ($this->gregorianYear > 1944) {
            $nextLeap = 1948;
            $l = 1;
            while ($nextLeap < $this->gregorianYear) {
                if ($this->isGregorianLeapYear($nextLeap)) {
                    $l++;
                }
                $nextLeap += 4;
            }
            $c = (($this->gregorianYear - 1944) * 365 + $l + $d - 1) % 60;
        } else {
            $nextLeap = 1940;
            $l = 0;
            while ($nextLeap > $this->gregorianYear) {
                if ($this->isGregorianLeapYear($nextLeap)) {
                    $l++;
                }
                $nextLeap -= 4;
            }
            $c = 60 - ((($this->gregorianYear - 1944) * 365 - $l) % 60);
            $c = ($c + $d - 1) % 60;
        }
        $this->dateStem = $c % 10;
        $this->dateBranch = $c % 12;
    }

    private function computeHourStemBranch(): int
    {
        if ($this->gregorianHour === null || $this->gregorianHour < 0 || $this->gregorianHour > 23) {
            return 1;
        }
        foreach (self::$HourBranchMap as $i => $hours) {
            if (in_array($this->gregorianHour, $hours, true)) {
                $this->hourBranch = $i;
                break;
            }
        }
        $ratStem = ($this->dateStem * 2) % 10;
        $this->hourStem = ($ratStem + $this->hourBranch) % 10;
        return 0;
    }

    public static function getBranchName(int $branch_num): ?string
    {
        $index = $branch_num - 1;
        return self::$BranchNames[$index] ?? null;
    }
}
