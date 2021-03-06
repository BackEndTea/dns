<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class RecordSetTest extends TestCase
{
    public function test_array_access() : void
    {
        $records = $this->get_records();
        $this->assertTrue(isset($records[0]));
        $this->assertFalse(isset($records[2]));
        $records[2] = $records[0];
        $this->assertTrue(isset($records[2]));
        $records[] = $records[1];
        $this->assertTrue(isset($records[3]));
        unset($records[3]);
    }

    public function test_iterable() : void
    {
        $records = $this->get_records();

        $this->assertSame('A', $records->current()->getType());
        $records->next();
        $this->assertSame('AAAA', $records->current()->getType());
        $this->assertTrue($records->valid());
        $records->next();
        $this->assertFalse($records->valid());
        $this->assertSame(2, $records->key());
        $records->rewind();
        $this->assertSame(0, $records->key());
    }

    public function test_count() : void
    {
        $records = $this->get_records();

        $this->assertSame(2, $records->count());
    }

    public function test_construct() : void
    {
        $records = new RecordSet([
            new Record('example.com', 3600, 'IN', 'A', null, '93.184.216.34'),
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
        $this->assertSame(2, $records->count());
    }

    public function test_construct_fail() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $records = new RecordSet([
            /** @phpstan-ignore PHPStan.Rules.Deprecations */
            'invalid record',
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
    }

    private function get_records() : RecordSet
    {
        return new RecordSet([
            new Record('example.com', 3600, 'IN', 'A', null, '93.184.216.34'),
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
    }
}
