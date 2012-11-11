<?php

namespace PhpRules\Test;

use PhpRules\Rule;
use PhpRules\RuleContext;
use PhpRules\RuleElement\Operator;

class RuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function evaluateProvider()
    {
        return array(
            array(1, '=', 0, null),
            array(0, '=', 1, null),
            array(1, '=', 1, true),

            array(1, '!=', 1, null),
            array(0, '!=', 1, true),
            array(1, '!=', 0, true),

            array(1, '<', 1, null),
            array(0, '<', 1, true),
            array(1, '<', 0, null),
            
            array(1, '>', 2, null),
            array(2, '>', 2, null),
            array(2, '>', 1, true),
            array(4, '>',-1, true),

            array(1, '>=', 1, true),
            array(0, '>=', 1, null),
            array(1, '>=', 0, true),

            array(1, '<=', 1, true),
            array(1, '<=', 0, null),
            array(0, '<=', 1, true),

            // test lazy
        );
    }

    /**
     * @dataProvider evaluateProvider
     */
    public function testEvaluate($x, $op, $y, $expectedValue)
    {
        $rule = new Rule;
        $rule->addVariable($y, $y);
        $rule->addVariable($x, $x);
        $rule->addOperator($op);

        $result = $rule->evaluate(new RuleContext);
        $this->assertEquals($expectedValue, $result->value, $result->name);
    }

    /**
     *
     */
    public function testContextEvaluate()
    {
        $rule = new Rule;
        $rule->addVariable('isTest', null);
        $rule->addVariable(1, 1);
        $rule->addOperator('=');

        $context = new RuleContext;
        $result = $rule->evaluate($context);
        $this->assertEquals(null, $result->value, $result->name);

        $context->addVariable('isTest', 1);
        $result = $rule->evaluate($context);
        $this->assertEquals(true, $result->value, $result->name);
    }

    /**
     *
     */
    public function testLazyContextEvaluate()
    {
        $rule = new Rule;
        $rule->addVariable('isTest', null);
        $rule->addVariable(1, 1);
        $rule->addOperator('=');

        $context = new RuleContext;
        $context->addVariable('isTest', function() { return 1; });
        $result = $rule->evaluate($context);
        $this->assertEquals(true, $result->value, $result->name);
    }

    /**
     *
     */
    public function testLazyContextNotEvaluated()
    {
        $rule = new Rule;
        $rule->addVariable('isTest', null);
        $rule->addVariable(1, 1);
        $rule->addOperator('=');

        $context = new RuleContext;
        $count = 0;
        $context->addVariable('isTest', function() { return 1; });
        $context->addVariable('unused', function() use (&$count) { ++$count; });

        $result = $rule->evaluate($context);
        $this->assertEquals(0, $count, 'Unused context variable not evaluated.');
    }

    /**
     *
     */
    public function testLazyContextOnlyEvaluatesOnce()
    {
        $rule = new Rule;
        $rule->addVariable('isTest', null);
        $rule->addVariable(1, 1);
        $rule->addOperator('=');

        $context = new RuleContext;
        $count = 0;
        $context->addVariable('isTest', function() use (&$count) { ++$count; return 1; });

        $rule->evaluate($context);
        $this->assertEquals(1, $count);

        $rule->evaluate($context);
        $this->assertEquals(1, $count);

        $rule->evaluate($context);
        $this->assertEquals(1, $count);
    }
}
