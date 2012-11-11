<?php

namespace PhpRules;

/**
 * TODO: actually enforce something
 */
interface RuleInterface
{
    function evaluate(RuleContext $context);
    function addProposition( $name, $truthValue );
    function addVariable( $name, $value );
    function getName();
}
