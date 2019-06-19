<?php
/**
 * This file is part of PHPinnacle/Ambit.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPinnacle\Ambit\Tests;

use PHPinnacle\Ambit\BinaryWriter;
use PHPUnit\Framework\TestCase;

class BinaryWriterTest extends TestCase
{
    public function testString()
    {
        $binary = BinaryWriter::bigEndian();
        $binary->string();

        self::assertEquals('abcd', $binary->pack('abcd'));
    }

    // // 8-bit integer functions
    public function testUint8()
    {
        self::assertEquals("\xA9", (BinaryWriter::bigEndian())->uint8()->pack(0xA9));
        self::assertEquals("\xA9", (BinaryWriter::littleEndian())->uint8()->pack(0xA9));
    }

    public function testInt8()
    {
        self::assertEquals("\xA9", (BinaryWriter::bigEndian())->int8()->pack(0xA9 - 0x100));
        self::assertEquals("\xA9", (BinaryWriter::littleEndian())->int8()->pack(0xA9 - 0x100));
    }

    // 16-bit integer functions
    public function testUint16()
    {
        self::assertEquals("\xA9\x78", (BinaryWriter::bigEndian())->uint16()->pack(0xA978));
        self::assertEquals("\x78\xA9", (BinaryWriter::littleEndian())->uint16()->pack(0xA978));
    }

    public function testInt16()
    {
        self::assertEquals("\xA9\x78", (BinaryWriter::bigEndian())->int16()->pack(0xA978));
        self::assertEquals("\x78\xA9", (BinaryWriter::littleEndian())->int16()->pack(0xA978));
    }

    // 32-bit integer functions
    public function testUint32()
    {
        self::assertEquals("\xA9\x78\x23\x61", (BinaryWriter::bigEndian())->uint32()->pack(0xA9782361));
        self::assertEquals("\x61\x23\x78\xA9", (BinaryWriter::littleEndian())->uint32()->pack(0xA9782361));
    }

    public function testInt32()
    {
        self::assertEquals("\xA9\x78\x23\x61", (BinaryWriter::bigEndian())->int32()->pack(0xA9782361));
        self::assertEquals("\x61\x23\x78\xA9", (BinaryWriter::littleEndian())->int32()->pack(0xA9782361));
    }

    // 64-bit integer functions
    public function testUint64()
    {
        self::assertEquals("\x19\x78\x23\x61\x34\x73\x85\x25", (BinaryWriter::bigEndian())->uint64()->pack(0x1978236134738525));
        self::assertEquals("\x25\x85\x73\x34\x61\x23\x78\x19", (BinaryWriter::littleEndian())->uint64()->pack(0x1978236134738525));
    }

    public function testInt64()
    {
        self::assertEquals("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFE", (BinaryWriter::bigEndian())->int64()->pack(-2));
        self::assertEquals("\xFE\xFF\xFF\xFF\xFF\xFF\xFF\xFF", (BinaryWriter::littleEndian())->int64()->pack(-2));
    }

    // Float & Double
    public function testFloat()
    {
        self::assertEquals("\x3F\xC0\x00\x00", (BinaryWriter::bigEndian())->float()->pack(1.5));
        self::assertEquals("\x00\x00\xC0\x3F", (BinaryWriter::littleEndian())->float()->pack(1.5));
    }

    public function testDouble()
    {
        self::assertEquals("\x3F\xF8\x00\x00\x00\x00\x00\x00", (BinaryWriter::bigEndian())->double()->pack(1.5));
        self::assertEquals("\x00\x00\x00\x00\x00\x00\xF8\x3F", (BinaryWriter::littleEndian())->double()->pack(1.5));
    }
}
