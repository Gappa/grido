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

namespace Grido\Components\Filters;

use Grido\Grid;
use Nette\Forms\Control;

/**
 * Filter with custom form control.
 *
 * @package     Grido
 * @subpackage  Components\Filters
 * @author      Petr Bugyík
 *
 * @property-read Control $formControl
 */
class Custom extends Filter
{
    protected Control $formControl;


    public function __construct(Grid $grid, string $name, string $label, Control $formControl)
    {
        $this->formControl = $formControl;
        parent::__construct($grid, $name, $label);
    }


    /**
     * @internal
     */
    public function getFormControl(): Control
    {
        return $this->formControl;
    }
}
