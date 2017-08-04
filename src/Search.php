<?php

namespace Kepler12\Bad;

/**
*  A sample class
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
*  @author yourname
*/
class Search{

   /**  @var Gaol $goal the class Goal to be met */
   private $goal;

   /**  @var array $rules an array of class Rules */
   private $rules;

   /**  @var boolean $depth_first run depth before breadth*/
   public $depth_first;

    /**  @var Result $result the result of the search*/
   public $result;

   /**  @var mixed $data An array of data to be acted upon*/
   public $data;

  /**
  * Sample method
  *
  * Always create a corresponding docblock for each method, describing what it is for,
  * this helps the phpdocumentator to properly generator the documentation
  *
  * @param string $param1 A string containing the parameter, do this for each parameter to the function, make sure to make it descriptive
  *
  * @return string
  */
   public function __construct($goal, $rules){
        $this->goal = $goal;
        $this->rules = $rules;
   }
    /**
    * Sample method
    *
    * Always create a corresponding docblock for each method, describing what it is for,
    * this helps the phpdocumentator to properly generator the documentation
    *
    * @param class $goal A refrence to a goal
    * @param class $rules A refrence to a rules
    * @param class $data A refrence to data
    *
    * @return string
    */
   public function run($data, $depth_first = false)
   {
        $results = self::collect_results($this->rules, $data);

        $searchRules = [$this->rules];
        $searchResults = [$results];

        $variations = 0;
        $goalResult = false;
        while(count($searchResults) > 0)
        {
            $variations++;
            // var_dump('STARTING: ', $variations, $searchRules[0]);

            /* Validate the first item on the   queue */
            $goalResult = $this->goal::validate(new Result($searchRules[0], $searchResults[0], $data));
            // var_dump("GOAL RESULT", $goalResult);
            if ($goalResult->success === true) {
                $goalResult->variations = $variations;
                break;
            }

            /* Set the results to work on */
            $shortenResult = $searchResults[0];
            $shortenRules = $searchRules[0];
            // var_dump("SHORTEN RULES: ", $shortenRules);
            /* Remove items from the queue */
            array_shift($searchResults);
            array_shift($searchRules);
            // var_dump('Shorten Results', $shortenResult);

            if (count($shortenResult) > 1) {
                /* Add To the queue */
                for ($index = count($shortenResult) - 1; $index >= 0;  $index--) {
                    /* Copy the reults to a new array */
                    $newResults = $shortenResult;
                    $newRules = $shortenRules;

                    /* Remove one of the results and rules from this queue */
                    array_splice($newResults, $index, 1);
                    array_splice($newRules, $index, 1);
                    // var_dump("NEW RULES AFTER: ", $newRules);
                    // var_dump("SHORTEN RULES AFTER: ",$index, $shortenRules);

                    /* Push/unshift to the queue */
                    if ($depth_first) {
                        array_unshif($searchResults, $newResults);
                        array_unshif($searchRules, $newhRules);
                    } else {
                        array_push($searchResults, $newResults);
                        array_push($searchRules, $newRules);
                    }

                }
            }

        }
        if ($goalResult->success === true)
            return $goalResult;

        $failed = new Result(
            [], [], $data
        );
        $failed->variations = $variations;
        $failed->success = false;
        return $failed;
   }

   public static function collect_results($rules, $data)
   {
        $results = [];
        foreach($rules as $rule) {
            $result = $rule::validate_data($data) ? $rule::run($data) : $data;

            array_push($results, $result);
        }
        return $results;
   }
}
