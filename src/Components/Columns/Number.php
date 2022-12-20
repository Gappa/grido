<?php

declare(strict_types=1);

/**
 * This file is part of the Grido (http://grido.bugyik.cz)
 *
 * Copyright (c) 2011 Petr Bugyík (http://petr.bugyik.cz)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Grido\Components\Columns;

use Grido\Grid;

/**
 * Number column.
 *
 * @package     Grido
 * @subpackage  Components\Columns
 * @author      Petr Bugyík
 *
 * @property array $numberFormat
 */
class Number extends Editable
{
    protected array $numberFormat = [
        self::NUMBER_FORMAT_DECIMALS => 0,
        self::NUMBER_FORMAT_DECIMAL_POINT => '.',
        self::NUMBER_FORMAT_THOUSANDS_SEPARATOR => ','
    ];

    /** @const keys of array $numberFormat */
    const NUMBER_FORMAT_DECIMALS = 0;
    const NUMBER_FORMAT_DECIMAL_POINT = 1;
    const NUMBER_FORMAT_THOUSANDS_SEPARATOR = 2;

    /**
     * @param ?int $decimals number of decimal points
     * @param ?string $decPoint separator for the decimal point
     * @param ?string $thousandsSep thousands separator
     */
    public function __construct(
        Grid $grid,
        string $name,
        string $label,
        int $decimals = 0,
        ?string $decPoint = null,
        ?string $thousandsSep = null
    ) {
        parent::__construct($grid, $name, $label);

        $this->setNumberFormat($decimals, $decPoint, $thousandsSep);
    }


    /**
     * Sets number format. Params are same as internal function number_format().
     * @param int $decimals number of decimal points
     * @param ?string $decPoint separator for the decimal point
     * @param ?string $thousandsSep thousands separator
     */
    public function setNumberFormat(int $decimals = 0, ?string $decPoint = null, ?string $thousandsSep = null): static
    {
        $this->numberFormat[self::NUMBER_FORMAT_DECIMALS] = $decimals;

        if ($decPoint !== null) {
            $this->numberFormat[self::NUMBER_FORMAT_DECIMAL_POINT] = $decPoint;
        }

        if ($thousandsSep !== null) {
            $this->numberFormat[self::NUMBER_FORMAT_THOUSANDS_SEPARATOR] = $thousandsSep;
        }

        return $this;
    }


    public function getNumberFormat(): array
    {
        return $this->numberFormat;
    }


    protected function formatValue(mixed $value): mixed
    {
        $value = parent::formatValue($value);

        $decimals = $this->numberFormat[self::NUMBER_FORMAT_DECIMALS];
        $decPoint = $this->numberFormat[self::NUMBER_FORMAT_DECIMAL_POINT];
        $thousandsSep = $this->numberFormat[self::NUMBER_FORMAT_THOUSANDS_SEPARATOR];

        return is_numeric($value)
            ? number_format((float) $value, $decimals, $decPoint, $thousandsSep)
            : $value;
    }
}
