<?php
/** 
 * Ruleloader.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
namespace PhpRules;

/**
 * Loads a Rule from a file. RuleLoader uses the Strategy pattern so you define custom algorithms for loading Rules and RuleContexts.
 * @package Phprules
 */
class RuleLoader {
    /**
     * The algorithm used to retrieve a Rule or RuleContext.
     * @access private
     * @var RuleContextLoaderStrategy
     */ 
    private $strategy = NULL;
    /**
     * @access public
     * @var Rule
     */ 	
    public $rule = NULL;
    /**
     * @access public
     * @var RuleContext
     */ 	
	public $ruleContext = NULL;
	
	public function __construct(){
		$this->rule = new Rule();
		$this->ruleContext = new RuleContext();
	}
	
	public function setStrategy( StrategyInterface $strategy ) {
		$this->strategy = $strategy;
		$strategy->rule = $this->rule;
		$strategy->ruleContext = $this->ruleContext;
	}
	
    /**
     * @return Rule
     */
	public function loadRule( $fileName ) {
		return $this->strategy->loadRule( $fileName );		
	}

    /**
     * @return RuleContext
     */
	public function loadRuleContext( $fileName, $id ) {
		return $this->strategy->loadRuleContext( $fileName, $id );
	}
}
