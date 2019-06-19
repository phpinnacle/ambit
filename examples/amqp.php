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

use PHPinnacle\Ambit\BinaryReader;
use PHPinnacle\Ambit\BinaryWriter;

$writer = BinaryWriter::bigEndian();
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

while (!empty($binary)) {
    [$type, $channel, $size] = $reader->unpack($binary);

    // Not enough data for header
    if ($size === null) {
        continue;
    }

    echo "Got frame header: type={$type}, channel={$channel}, size={$size}" . \PHP_EOL;

    // header size + end byte
    if ($length < $size + 8) {
        continue;
    }

    $body = \substr($binary, 7, $size);

    switch ($type) {
        case 1:
            $classReader = BinaryReader::bigEndian()
                ->uint16() // AMQP CLASS BASIC FRAME
                ->uint16() // AMQP METHOD BASIC PUBLISH
                ->int16()
                ->uint8();

            [$class, $method, ,$exchangeSize] = $classReader->unpack($body);

            $classReader
                ->string($exchangeSize)
                ->uint8();

            [$class, $method, ,$exchangeSize, $exchange, $queueSize] = $classReader->unpack($body);

            $classReader
                ->string($queueSize)
                ->uint8();

            [$class, $method, ,$exchangeSize, $exchange, $queueSize, $queue, $flags] = $classReader->unpack($body);

            echo "Got type frame: class={$class}, method={$method}, exchange=$exchange, queue=$queue" . \PHP_EOL;

            break;
        case 2:
            $headerReader = BinaryReader::bigEndian()
                ->uint16()  // AMQP CLASS BASIC FRAME
                ->uint16()
                ->uint64()
                ->uint16();

            [$class, $method, $messageSize, $flags] = $headerReader->unpack($body);

            echo "Got headers frame: class={$class}, method={$method}, messageSize={$messageSize}, flags={$flags}" . \PHP_EOL;

            break;
        case 3:
            echo "Got body frame: {$body}" . \PHP_EOL;

            break;
    }

    // drop header & body
    $binary = \substr($binary, 8 + $size);
    $length = \strlen($binary);

    echo "Remain data length {$length}" . \PHP_EOL;
}
