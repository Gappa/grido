<?php

declare(strict_types=1);

/**
 * This file is part of the Grido (https://github.com/o5/grido)
 *
 * Copyright (c) 2011 Petr Bugyík (http://petr.bugyik.cz)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Grido\Components\Columns;

use Grido\Grid;

/**
 * Date column.
 *
 * @package     Grido
 * @subpackage  Components\Columns
 * @author      Petr Bugyík
 *
 * @property string $dateFormat
 */
class Date extends Editable
{
    const FORMAT_TEXT = 'd M Y';
    const FORMAT_DATE = 'd.m.Y';
    const FORMAT_DATETIME = 'd.m.Y H:i:s';

    protected string $dateFormat = self::FORMAT_DATE;


    public function __construct(Grid $grid, string $name, string $label, string $dateFormat = self::FORMAT_DATE)
    {
        parent::__construct($grid, $name, $label);
        $this->dateFormat = $dateFormat;
    }


    public function setDateFormat(string $format): static
    {
        $this->dateFormat = $format;
        return $this;
    }


    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }


    protected function formatValue(mixed $value): mixed
    {
        if ($value === null || is_bool($value)) {
            return $this->applyReplacement($value);
        } elseif (is_scalar($value)) {
            $value = \Latte\Runtime\Filters::escapeHtml($value);
            $replaced = $this->applyReplacement($value);
            if ($value !== $replaced && is_scalar($replaced)) {
                return $replaced;
            }
        }

        return $value instanceof \DateTimeInterface
            ? $value->format($this->dateFormat)
            : date($this->dateFormat, is_numeric($value) ? $value : strtotime((string) $value)); //@todo notice for "01.01.1970"
    }


    /**
     * @internal
     */
    public function renderExport(mixed $row): mixed
    {
        if (is_callable($this->customRenderExport)) {
            return call_user_func_array($this->customRenderExport, [$row]);
        }

        $value = $this->getValue($row);
        return $this->formatValue($value);
    }
}
