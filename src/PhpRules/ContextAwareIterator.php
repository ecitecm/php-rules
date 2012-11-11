<?php

namespace PhpRules;

class ContextAwareIterator implements \Iterator
{
    /**
     *
     */
    private $var = array();


    /**
     * @param array $array array of RuleElement object's
     */
    public function __construct(array $array, RuleContext $ruleContext)
    {
        $this->var = $array;
        $this->ruleContext = $ruleContext;
    }

    /**
     *
     */
    protected function applyContext(RuleElement $element)
    {
        if ($element->getType() === "Proposition" or $element->getType() === "Variable") {
            if ($this->ruleContext->hasElement( $element->getName() ) ) {
                $context = $this->ruleContext->findElement( $element->getName() );
                $element->value = $context ? $context->value : null;
            }
        }
        
        return $element;
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->var);
    }

    /**
     *
     */
    public function current()
    {
        return $this->applyContext(current($this->var));
    }

    /**
     *
     */
    public function key()
    {
        return key($this->var);
    }

    /**
     *
     */
    public function next()
    {
        $next = next($this->var);
        return $next ? $this->applyContext($next) : false;
    }

    /**
     *
     */
    public function valid()
    {
        $key = key($this->var);
        return ($key !== null && $key !== false);
    }
}
