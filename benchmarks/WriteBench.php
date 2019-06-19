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

use PHPinnacle\Ambit\BinaryWriter;

/**
 * @BeforeMethods({"init"})
 */
class WriteBench
{
    /**
     * @var BinaryWriter
     */
    private $intWriter;

    /**
     * @var BinaryWriter
     */
    private $floatWriter;

    /**
     * @var BinaryWriter
     */
    private $stringWriter;

    /**
     * @var string
     */
    private $string;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->intWriter = BinaryWriter::bigEndian()
            ->int8()
            ->int16()
            ->int32()
            ->int64()
            ->uint8()
            ->uint16()
            ->uint32()
            ->uint64()
        ;
        $this->floatWriter = BinaryWriter::bigEndian()
            ->float()
            ->float()
            ->float()
            ->double()
            ->double()
            ->double()
        ;

        $this->stringWriter = BinaryWriter::bigEndian()
            ->string()
            ->string()
            ->string()
        ;

        $this->string = \str_repeat('str', 1000);
    }

    /**
     * @Revs(1000)
     * @Iterations(100)
     *
     * @return void
     */
    public function benchAppendIntegers(): void
    {
        $this->intWriter->pack(1, 1, 1, 1, 1, 1, 1, 1);
    }

    /**
     * @Revs(1000)
     * @Iterations(100)
     *
     * @return void
     */
    public function benchAppendFloats(): void
    {
        $this->floatWriter->pack(1.0, -1.0, \M_PI, 1.0, -1.0, \M_PI);
    }

    /**
     * @Revs(1000)
     * @Iterations(100)
     *
     * @return void
     */
    public function benchAppendString(): void
    {
        $this->stringWriter->pack('some string', "other string", $this->string);
    }
}
