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

use PHPinnacle\Ambit\BinaryReader;
use PHPUnit\Framework\TestCase;

class BinaryReaderTest extends TestCase
{
    // string functions
    public function testString()
    {
        $reader = BinaryReader::bigEndian()->string(4);

        self::assertEmpty($reader->unpack(""));
        self::assertEquals(['abcd'], $reader->unpack('abcd'));
    }

    // 8-bit integer functions
    public function testUint8()
    {
        $reader = BinaryReader::bigEndian()->uint8();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0x00], $reader->unpack("\x00"));
        self::assertEquals([127], $reader->unpack("\x7F"));
        self::assertEquals([255], $reader->unpack("\xFF"));
    }

    public function testInt8()
    {
        $reader = BinaryReader::bigEndian()->int8();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0xA9 - 0x100], $reader->unpack("\xA9"));
        self::assertEquals([127], $reader->unpack("\x7F"));
        self::assertEquals([-128], $reader->unpack("\x80"));
    }

    // 16-bit integer functions
    public function testUint16()
    {
        $reader = BinaryReader::bigEndian()->uint16();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0x0000], $reader->unpack("\x00\x00"));
        self::assertEquals([0xA978], $reader->unpack("\xA9\x78"));
        self::assertEquals([0xFFFF], $reader->unpack("\xFF\xFF"));
    }

    public function testInt16()
    {
        $reader = BinaryReader::bigEndian()->int16();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0xA978 - 0x10000], $reader->unpack("\xA9\x78"));
    }

    // 32-bit integer functions
    public function testUint32()
    {
        $reader = BinaryReader::bigEndian()->uint32();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0xA9782361], $reader->unpack("\xA9\x78\x23\x61"));
    }

    public function testInt32()
    {
        $reader = BinaryReader::bigEndian()->int32();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0xA9782361 - 0x100000000], $reader->unpack("\xA9\x78\x23\x61"));
    }

    // 64-bit integer functions
    public function testUint64()
    {
        $reader = BinaryReader::bigEndian()->uint64();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([0x1978236134738525], $reader->unpack("\x19\x78\x23\x61\x34\x73\x85\x25"));
    }

    public function testInt64()
    {
        $reader = BinaryReader::bigEndian()->int64();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([-2], $reader->unpack("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFE"));
    }

    // Float
    public function testFloat()
    {
        $reader = BinaryReader::bigEndian()->float();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([1.5], $reader->unpack("\x3F\xC0\x00\x00"));
    }

    // Double
    public function testDouble()
    {
        $reader = BinaryReader::bigEndian()->double();

        self::assertEmpty($reader->unpack(""));
        self::assertEquals([1.5], $reader->unpack("\x3F\xF8\x00\x00\x00\x00\x00\x00"));
    }
}
