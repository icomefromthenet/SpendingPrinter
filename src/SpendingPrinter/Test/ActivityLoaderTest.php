<?php
namespace SpendingPrinter\Test;

use DateTime;
use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\ActivityLoader;

class ActivityLoaderTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
   public function testLoader()
   {
        $loader = $this->getApp()->getActivityLoader();
        $loader->load();
        
        $this->assertTrue(true);
        
        $result = $this->getApp()->getDBAL()->executeQuery('SELECT count(*) as total FROM finance_activity');
        $this->assertEquals($result->fetchColumn(0),84);
        
   }
    
}
