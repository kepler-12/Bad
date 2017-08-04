<?php namespace Kepler12\Bad;

/**
*  Rule
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
*  @author yourname
*/
abstract class Rule {

   /** @var array $data_type the data type which the data passed to this function must be
   *   for any use '*'
   *   for one type use string 'Array'
   *   for multiple use array ['Array', 'String']
   *   Types to be use http://php.net/manual/en/function.gettype.php
   */
  public static $data_type = ["*"];


  /**
  * Run
  *
  * Always create a corresponding docblock for each method, describing what it is for,
  * this helps the phpdocumentator to properly generator the documentation
  *
  * @param array $data An array of the data to be acted upon
  * @param array $data The result of the search
  *
  * @return
  */
   public static function validate_data($data)
   {
      return in_array('*', self::$data_type) ? true : in_array(gettype($data), self::$data_type);

   }

  public static function execute($data)
  {
    return self::validate_data($data) ? self::run($data) : $data;
  }

}
