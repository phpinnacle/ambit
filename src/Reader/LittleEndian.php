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

namespace PHPinnacle\Ambit\Reader;

use PHPinnacle\Ambit\BinaryReader;

final class LittleEndian extends BinaryReader
{
    /**
     * {@inheritdoc}
     */
    public function uint16(): BinaryReader
    {
        return $this->read('v', 2, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function uint32(): BinaryReader
    {
        return $this->read('V', 4, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function uint64(): BinaryReader
    {
        return $this->read('P', 8, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function int16(): BinaryReader
    {
        return $this->read('C2', 2, 2);
    }

    /**
     * {@inheritdoc}
     */
    public function int32(): BinaryReader
    {
        return $this->read('C4', 4, 4);
    }

    /**
     * @param int $value
     *
     * @return static
     */
    public function int64(): BinaryReader
    {
        return $this->read('C8', 8, 8);
    }

    /**
     * {@inheritdoc}
     */
    public function float(): BinaryReader
    {
        return $this->read('g', 4, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function double(): BinaryReader
    {
        return $this->read('e', 8, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function value(array $values, string $key, int $size)
    {
        switch ($size) {
            case 1:
                return $values[$key];
            case 2:
                $value =
                    ($values[$key.'2'] << 8) +
                    ($values[$key.'1'])
                ;

                return $value > 32768 ? $value - 65536 : $value;
            case 4:
                $value =
                    ($values[$key.'4'] << 24) +
                    ($values[$key.'3'] << 16) +
                    ($values[$key.'2'] << 8)  +
                    ($values[$key.'1'])
                ;

                return $value > 2147483648 ? $value - 4294967296 : $value;
            case 8:
                $value =
                    ($values[$key.'8'] << 56) +
                    ($values[$key.'7'] << 48) +
                    ($values[$key.'6'] << 40) +
                    ($values[$key.'5'] << 32) +
                    ($values[$key.'4'] << 24) +
                    ($values[$key.'3'] << 16) +
                    ($values[$key.'2'] << 8)  +
                    ($values[$key.'1'])
                ;

                return $value > (9223372036854775808 / 2) ? $value - 9223372036854775808 : $value;
            default:
                throw new \InvalidArgumentException;
        }
    }
}
