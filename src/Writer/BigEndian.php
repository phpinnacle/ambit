<?php
/**
 * This file is part of PHPinnacle/Ambit.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPinnacle\Ambit\Writer;

use PHPinnacle\Ambit\BinaryWriter;

final class BigEndian extends BinaryWriter
{
    /**
     * {@inheritdoc}
     */
    public function uint16(): BinaryWriter
    {
        return $this->write('n', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function uint32(): BinaryWriter
    {
        return $this->write('N', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function uint64(): BinaryWriter
    {
        return $this->write('J', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function int16(): BinaryWriter
    {
        return $this->write('C2', 2);
    }

    /**
     * {@inheritdoc}
     */
    public function int32(): BinaryWriter
    {
        return $this->write('C4', 4);
    }

    /**
     * {@inheritdoc}
     */
    public function int64(): BinaryWriter
    {
        return $this->write('C8', 8);
    }

    /**
     * {@inheritdoc}
     */
    public function float(): BinaryWriter
    {
        return $this->write('G', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function double(): BinaryWriter
    {
        return $this->write('E', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function value(array $values, int $key, int $size): array
    {
        $value = $values[$key];

        switch ($size) {
            case 2:
                return [
                    ($value >> 8) & 0xFF,
                    ($value >> 0) & 0xFF,
                ];
            case 4:
                return [
                    ($value >> 24) & 0xFF,
                    ($value >> 16) & 0xFF,
                    ($value >> 8) & 0xFF,
                    ($value >> 0) & 0xFF,
                ];
            case 8:
                return [
                    ($value >> 56) & 0xFF,
                    ($value >> 48) & 0xFF,
                    ($value >> 40) & 0xFF,
                    ($value >> 32) & 0xFF,
                    ($value >> 24) & 0xFF,
                    ($value >> 16) & 0xFF,
                    ($value >> 8) & 0xFF,
                    ($value >> 0) & 0xFF,
                ];
            default:
                return [];
        }
    }
}
