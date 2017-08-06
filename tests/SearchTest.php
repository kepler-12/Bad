<?php
// include_once('./Rules.php');
// include_once('./Goals.php');

use PHPUnit\Framework\TestCase;
use Kepler12\Bad\Search;
/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author Tyler Smith
*/





class SearchTest extends TestCase
{

  /**
  * Just check if the YourClass has no syntax error
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function test_odds(){


    $array = [5,5,6,5, 6, 11];
    $resultFalse = OddsOnlySearch::run($array);

    $this->assertTrue($resultFalse->success === true);
    $this->assertTrue($resultFalse->variations === 1, "Variations");

    $resultTrue = OddsOnlySearch::run([5, 7, 11, 13, 21]);

    $this->assertTrue($resultTrue->success === true, "Success");
    $this->assertTrue($resultTrue->variations === 1, "Variations");
    $this->assertTrue($resultTrue->combined === [2 => 11,3 => 13,4 => 21], "Results");
  }

  public function test_cache()
  {
    $result = AlwaysFail::run([false]);
    var_dump('VARIATIONS: ', $result->variations);
    $this->assertTrue($result->variations === 15, "Correct Variations");
  }

}
