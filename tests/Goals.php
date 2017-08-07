<?php
use Kepler12\Bad\Goal;
use Kepler12\Bad\Result;

class OddsOnly extends Goal {

    public static function validate(Result $result)
    {
        $combinded = $result->results[0];
        if (count($result->results) > 0) {
            foreach($result->results as $a_result) {
                $combinded = array_intersect($combinded, $a_result);
            }
        }

        // var_dump("GOAL_RESULT: ", $result);
        // var_dump("COMBINDED: ", $combinded);

        $success = true;
        if (count($combinded) == 0 || count($result->rules) == 0){
            var_dump("FAILED at lengths");
            $result->success = false;
            return $result;
        }

        foreach($combinded  as $number) {
            if (gettype($number) !== 'integer' || $number % 2 == 0) {
                var_dump("FAILED AT", $number);
                $success = false;
                break;
            }
        }
        $result->combined = $combinded;
        $result->success = $success;

        return $result;
    }
}

class NeverTrue extends Goal {
    public static function validate(Result $result)
    {
        var_dump(implode("", $result->rules));
        return $result;
    }
}

class AllTrue extends Goal {
    public static function validate(\Kepler12\Bad\Result $results)
    {
        var_dump(implode("", $results->rules));
         $results->success = in_array(false, $results->results, true) === false;
      //  $results->success = true;
        return $results;
    }
}

