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

namespace Grido\Components;

use Grido\Grid;
use Nette\Utils\Html;

/**
 * Toolbar button.
 *
 * @package     Grido
 * @subpackage  Components
 * @author      Petr Bugyík
 *
 * @property-read Html $element
 * @property-write Html $elementPrototype
 * @property array $options
 * @property-read string $destination
 * @property-read array $arguments
 */
class Button extends Component
{
    const ID = 'buttons';


    // first param for method $presenter->link()
    protected ?string $destination = null;

    // second param for method $presenter->link()
    protected array $arguments = [];

    // <a> html tag
    protected ?Html $elementPrototype = null;

    protected array $options = [];


    /**
     * @param string $destination - first param for method $presenter->link()
     * @param array $arguments - second param for method $presenter->link()
     */
    public function __construct(
        Grid $grid,
        string $name,
        string $label,
        ?string $destination = null,
        array $arguments = []
    ) {
        $this->label = $label;
        $this->destination = $destination;
        $this->arguments = $arguments;

        $this->addComponentToGrid($grid, $name);
    }


    public function setIcon(string $name): static
    {
        $this->setOption('icon', $name);
        return $this;
    }


    /**
     * Sets user-specific option.
     */
    public function setOption(string $key, mixed $value): static
    {
        if ($value === null) {
            unset($this->options[$key]);
        } else {
            $this->options[$key] = $value;
        }

        return $this;
    }


    public function setElementPrototype(Html $elementPrototype): static
    {
        $this->elementPrototype = $elementPrototype;
        return $this;
    }


    /*	 * ******************************************************************************************* */

    /**
     * @internal
     */
    public function getElement(): Html
    {
        $element = clone $this->getElementPrototype();

        $href = $this->presenter->link($this->getDestination(), $this->getArguments());
        $element->href($href);

        return $element;
    }


    /**
     * Returns element prototype (<a> html tag).
     * @throws Exception
     */
    public function getElementPrototype(): Html
    {
        if ($this->elementPrototype === null) {
            $this->elementPrototype = Html::el('a')
                ->setClass(['grid-button-' . $this->getName()])
                ->setText($this->label);
        }

        if (isset($this->elementPrototype->class)) {
            $this->elementPrototype->class = (array) $this->elementPrototype->class;
        }

        return $this->elementPrototype;
    }


    /**
     * Returns user-specific option.
     */
    public function getOption(string $key, mixed $default = null): mixed
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }


    /**
     * Returns user-specific options.
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * @internal
     */
    public function getDestination(): string
    {
        if ($this->destination === null) {
            $this->destination = $this->getName();
        }

        return $this->destination;
    }


    /**
     * @internal
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }


    /*	 * ******************************************************************************************* */

    /**
     * @throws Exception
     */
    public function render(): void
    {
        echo $this->getElement();
    }
}
