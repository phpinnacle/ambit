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

use PHPinnacle\Ambit\BinaryReader;
use PHPinnacle\Ambit\BinaryWriter;

/**
 * @BeforeMethods({"init"})
 */
class ReadBench
{
    /**
     * @var string
     */
    private $binary;

    /**
     * @var BinaryReader
     */
    private $reader;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->binary = BinaryWriter::bigEndian()
            ->int8()
            ->int16()
            ->int32()
            ->int64()
            ->uint8()
            ->uint16()
            ->uint32()
            ->uint64()
            ->float()
            ->float()
            ->float()
            ->double()
            ->double()
            ->double()
            ->string()
            ->string()
            ->pack(1, 1, 1, 1, 1, 1, 1, 1, 1.1, -1.1, \M_PI, 1.1, -1.1, \M_PI, 'some string', "other string")
        ;

        $this->reader = BinaryReader::bigEndian()
            ->int8()
            ->int16()
            ->int32()
            ->int64()
            ->uint8()
            ->uint16()
            ->uint32()
            ->uint64()
            ->float()
            ->float()
            ->float()
            ->double()
            ->double()
            ->double()
            ->string(11)
            ->string(12)
        ;
    }

    /**
     * @Revs(1000)
     * @Iterations(100)
     *
     * @return void
     */
    public function benchRead(): void
    {
        $this->reader->unpack($this->binary);
    }
}
