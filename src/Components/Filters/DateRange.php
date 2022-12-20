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

namespace Grido\Components\Filters;

use Nette\Forms\Controls\TextInput;
use Nette\Utils\Strings;

/**
 * Date-range input filter.
 *
 * @package     Grido
 * @subpackage  Components\Filters
 * @author      Petr Bugyík
 *
 * @property string $mask
 */
class DateRange extends Date
{
    protected mixed $condition = 'BETWEEN ? AND ?';

    protected string $mask = '/(.*)\s?-\s?(.*)/';

    protected array $dateFormatOutput = ['Y-m-d', 'Y-m-d G:i:s'];


    public function setDateFormatOutput(string $formatFrom, ?string $formatTo = null): self
    {
        $formatTo = $formatTo === null
            ? $formatFrom
            : $formatTo;

        $this->dateFormatOutput = [$formatFrom, $formatTo];
        return $this;
    }


    /**
     * Sets mask by regular expression.
     */
    public function setMask(string $mask): self
    {
        $this->mask = $mask;
        return $this;
    }


    public function getMask(): string
    {
        return $this->mask;
    }


    protected function getFormControl(): TextInput
    {
        $control = parent::getFormControl();

        $prototype = $control->getControlPrototype();
        array_pop($prototype->class); //remove "date" class
        $prototype->class[] = 'daterange';

        return $control;
    }


    /**
     * @throws \Exception
     * @internal
     */
    public function __getCondition(mixed $value): ?Condition
    {
        if ($value === '' || $value === null) {
            return null; //skip
        }

        if ($this->where === null && is_string($this->condition)) {

            list(, $from, $to) = \Nette\Utils\Strings::match($value, $this->mask);
            $from = \DateTime::createFromFormat($this->dateFormatInput, trim((string) $from));
            $to = \DateTime::createFromFormat($this->dateFormatInput, trim((string) $to));

            if ($to && !Strings::match($this->dateFormatInput, '/G|H/i')) { //input format haven't got hour option
                Strings::contains($this->dateFormatOutput[1], 'G') || Strings::contains($this->dateFormatOutput[1], 'H')
                    ? $to->setTime(23, 59, 59)
                    : $to->setTime(11, 59, 59);
            }

            $values = $from && $to
                ? [$from->format($this->dateFormatOutput[0]), $to->format($this->dateFormatOutput[1])]
                : null;

            return $values
                ? Condition::setup($this->getColumn(), $this->condition, $values)
                : Condition::setupEmpty();
        }

        return parent::__getCondition($value);
    }
}
