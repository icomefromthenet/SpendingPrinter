<?php
namespace SpendingPrinter;


abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $app;

    /**
    * PHPUnit setUp for setting up the application.
    *
    * Note: Child classes that define a setUp method must call
    * parent::setUp().
    */
    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    /**
    * Creates the application.
    *
    * @return HttpKernel
    */
    abstract public function createApplication();

    /**
     *  Return the Pimple DI Container
     *
     *  @access public
     *  @return SpendingPrinter\Application
     *
    */
    public function getApp()
    {
        return $this->app;
    }
    
}
/* End of Class */