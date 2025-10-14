<?php

declare(strict_types=1);

namespace Sucheela\ChineseCalendar\Tests;

use PHPUnit\Framework\TestCase;
use Sucheela\ChineseCalendar\ChineseCalendar;

final class ChineseCalendarTest extends TestCase
{
    public function testYearStemAndBranchForKnownYear(): void
    {
        $cc = new ChineseCalendar(2020, 1, 25); // 2020-01-25 is Chinese New Year (Rat year)
        $this->assertStringContainsString('2020', $cc->toString());

        $yearStem = $cc->getStem(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING);
        $yearBranch = $cc->getBranch(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING);

        // 2020 is a Yang Metal (Yang Metal is index 6) and Rat
        $this->assertIsString($yearStem);
        $this->assertIsString($yearBranch);
        $this->assertEquals('Yang Metal', $yearStem);
        $this->assertEquals('Rat', $yearBranch);
    }

    public function testGetBranchNameStatic(): void
    {
        $this->assertEquals('Rat', ChineseCalendar::getBranchName(1));
        $this->assertEquals('Boar', ChineseCalendar::getBranchName(12));
        $this->assertNull(ChineseCalendar::getBranchName(13));
    }

    public function testHourStemBranchWithHourProvided(): void
    {
        $cc = new ChineseCalendar(2021, 8, 8, 23);
        $hourBranch = $cc->getBranch(ChineseCalendar::TYPE_HOUR, ChineseCalendar::FORMAT_STRING);
        $this->assertIsString($hourBranch);
    }

    public function testReadmeSampleFor20001231(): void
    {
        // Sample from README.md:
        // $cal = new ChineseCalendar(2000, 12, 31, 23);
        // Expected stems (FORMAT_STRING):
        // Year stem: Yang Metal
        // Month stem: Yin Earth
        // Day stem: Yin Water
        // Hour stem: Yang Water
        // Expected branches (FORMAT_STRING):
        // Year branch: Dragon
        // Month branch: Ox
        // Day branch: Boar
        // Hour branch: Rat

        $cc = new ChineseCalendar(2000, 12, 31, 23);

        $this->assertEquals('Yang Metal', $cc->getStem(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Yin Earth', $cc->getStem(ChineseCalendar::TYPE_MONTH, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Yin Water', $cc->getStem(ChineseCalendar::TYPE_DAY, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Yang Water', $cc->getStem(ChineseCalendar::TYPE_HOUR, ChineseCalendar::FORMAT_STRING));

        $this->assertEquals('Dragon', $cc->getBranch(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Ox', $cc->getBranch(ChineseCalendar::TYPE_MONTH, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Boar', $cc->getBranch(ChineseCalendar::TYPE_DAY, ChineseCalendar::FORMAT_STRING));
        $this->assertEquals('Rat', $cc->getBranch(ChineseCalendar::TYPE_HOUR, ChineseCalendar::FORMAT_STRING));
    }

    public function testInvalidInputs(): void
    {
        // Invalid year (out of supported range) should yield empty stem/branch strings
        $ccOutOfRange = new ChineseCalendar(1800, 1, 1);
        $this->assertSame('', $ccOutOfRange->getStem(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING));
        $this->assertSame('', $ccOutOfRange->getBranch(ChineseCalendar::TYPE_YEAR, ChineseCalendar::FORMAT_STRING));

        // Invalid hour should prevent hour stem/branch from being set; accessing them raises an Error
        $ccInvalidHour = new ChineseCalendar(2020, 1, 1, -5);
        $this->expectException(\Error::class);
        // Accessing hour stem/branch should throw due to uninitialized typed properties
        $ccInvalidHour->getBranch(ChineseCalendar::TYPE_HOUR, ChineseCalendar::FORMAT_STRING);
    }
}
