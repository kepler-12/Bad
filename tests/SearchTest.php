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
    $search = new Search(
      OddsOnly::class,
      [
        RemoveFives::class,
        AboveTen::class
      ]
    );

    $array = [5,5,6,5, 6, 11];
    $resultFalse = $search->run($array);
    var_dump($resultFalse);
    $this->assertTrue($resultFalse->success === true);
    $this->assertTrue($resultFalse->variations === 3);

    $resultTrue = $search->run([5, 7, 11, 13, 21]);

    var_dump($resultTrue);

    $this->assertTrue($resultTrue->success === true, "Success");
    $this->assertTrue($resultTrue->variations === 1, "Variations");
    $this->assertTrue($resultTrue->combined === [2 => 11,3 => 13,4 => 21], "Results");
  }

}
