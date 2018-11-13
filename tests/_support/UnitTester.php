<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

   /**
    * Define custom actions here
    */
    public function runPrivateMethod($object,$methodName,$params=array())
    {
		$reflector = new \ReflectionClass($object);
		$method = $reflector->getMethod($methodName);
		$method->setAccessible(true);
        return $method->invokeArgs($object, $params);
    }
    public function getPrivateProperty($object,$propName)
    {
		$reflector = new \ReflectionClass( $object );
		$property = $reflector->getProperty( $propName );
		$property->setAccessible(true);
        return $property->getValue($object);
    }
}
