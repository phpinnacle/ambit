<?php
/**
 * This file is part of PHPinnacle/Ambit.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use PHPinnacle\Ambit\BinaryParser;
use PHPinnacle\Ambit\BinaryReader;
use PHPinnacle\Ambit\BinaryWriter;

$writer = BinaryWriter::bigEndian()
    ->uint8()
    ->uint16()
    ->uint32()
;
$reader = BinaryReader::bigEndian()
    ->uint8()
    ->uint16()
    ->uint32()
;

$message = 'Hello World';
$exchange = 'hello';
$queue = 'world';
$channel = 1;
$flags = 0;
$endByte = 206;

$binary = $writer
    ->uint8(1)
    ->uint16($channel)
    ->uint32(9 + \strlen($exchange) + \strlen($queue)) // uint16 (2) + uint16 (2) + int16 (2) + uint8 (1) + uint8 (1) + uint8 (1)
    ->uint16(60) // AMQP CLASS BASIC FRAME
    ->uint16(40) // AMQP METHOD BASIC PUBLISH
    ->int16(0)
    ->uint8(\strlen($exchange))
    ->string($exchange)
    ->uint8(\strlen($queue))
    ->string($queue)
    ->uint8(0) // mandatory and immediate flags
    ->uint8($endByte)
    ->uint8(2)
    ->uint16($channel)
    ->uint32(14) // uint16 (2) + uint16 (2) + uint64 (8) + uint16 (2)
    ->uint16(60)  // AMQP CLASS BASIC FRAME
    ->uint16(0)
    ->uint64(\strlen($message))
    ->uint16($flags)
    ->uint8($endByte)
    ->uint8(3)
    ->uint16($channel)
    ->uint32(\strlen($message))
    ->string($message)
    ->uint8($endByte)
    ->pack()
;

$length = \strlen($binary);

echo "Packed binary data length {$length}" . \PHP_EOL;

$frameParser = new BinaryParser(BinaryReader::bigEndian()->uint8()->uint16()->uint32(), 2);
$frameParser->append($binary);

while (!$frameParser->empty()) {
    [$type, $channel, $size, $body] = $frameParser->parse();

    // Not enough data
    if ($body === null) {
        echo "Not enough data for parse frame" . \PHP_EOL;

        break;
    }

    echo "Got frame: type={$type}, channel={$channel}, size={$size}, body={$body}" . \PHP_EOL;

    $frameParser->discard(1);

    echo "Remain data length {$frameParser->size()}" . \PHP_EOL;
}
