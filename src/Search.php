<?php

namespace Kepler12\Bad;

/**
*  Search
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
*  @author yourname
*/
class Search {

   /**  @var Gaol $goal the class Goal to be met */
   public static $goal;

   /**  @var array $rules an array of class Rules */
   public static $rules;
    /**
    * Options here.
    *
    *
    */

    /**  @var boolean $depth_first run depth before breadth*/
   public static $depth_first = true;

   /**  @var integer $min_rules the minimum number of rules that must be run*/
   public static $min_rules = 1;


  /**
  * Constructor
  *
  *
  * @param Goal $goal the goal which is trying to be achived by this search
  * @param array $rules an array of rule classes
  *
  * @return string
  */

    /**
    *  Run
    *
    * Run the search with the provided data.
    *
    * @param class $data A refrence to data
    * @param class $options overwrite defaults
    * @return Results $results
    */
   public static function run($data, $options = [])
   {
        $min_rules = $options['min_rules'] ?? get_called_class()::$min_rules;
        $depth_first = $options['depth_first'] ?? get_called_class()::$depth_first;

        $results = self::collect_results(get_called_class()::$rules, $data);

        /* Set up the queue */
        $searchRules = [get_called_class()::$rules];
        $searchResults = [$results];

        $variations = 0;
        $goalResult = false;

        /* For Caching */
        $cachedOrders = [];

        /*
        * We act always act on the first item in the queue
        */
        while(count($searchResults) > 0)
        {
            if (! in_array(implode('', $searchRules[0]), $cachedOrders)) {
                $variations++;
                /* Validate the first item on the   queue */
                $goalResult = get_called_class()::$goal::validate(new Result($searchRules[0], $searchResults[0], $data));

                if ($goalResult->success === true) {
                    $goalResult->variations = $variations;
                    break;
                }

                /* Add this rule order to the cache */
                array_push($cachedOrders, implode('', $searchRules[0]));
            }
            /* Set the results to work on */
            $shortenResult = $searchResults[0];
            $shortenRules = $searchRules[0];

            /* Remove items from the queue */
            array_shift($searchResults);
            array_shift($searchRules);

            if (count($shortenResult) > $min_rules) {
                /* Add One less rule To the queue
                * ie if you've run [A,B,C]
                * breadth queues [A, B], [A, C], & [B, C] in that order
                * depth queues [B, C], [A, C], [A, B]
                * TODO: Do we want to have breadth and depth que in the same order.
                * TODO: ADD CACHING!!!!!!
                */
                for ($index = count($shortenResult) - 1; $index >= 0;  $index--) {
                    /* Copy the reults to a new array */
                    $newResults = $shortenResult;
                    $newRules = $shortenRules;

                    /* Remove one of the results and rules from this queue */
                    array_splice($newResults, $index, 1);
                    array_splice($newRules, $index, 1);

                    /* Push/unshift to the queue */
                    if ($depth_first) {
                        //Depth first always shifts new items to the start of the queue
                        array_unshift($searchResults, $newResults);
                        array_unshift($searchRules, $newRules);
                    } else {
                        //Breadth always pushes items to the end of the queue
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
    /**
    *  collect_results
    *
    * Returns an array of results for each rule
    *
    * @param array $rules a group of rules to pass on the data
    * @param mixed $data
    * @return array
    */
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
