<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;

class DBALConnectionTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testConnectionMade()
    {
        $conn = $this->getApp()->getDBAL();
        
        $this->assertInstanceOf('Doctrine\DBAL\Connection',$conn);
        
        $tables = $conn->getSchemaManager()->listTables();
        
        $this->assertEquals($tables[0]->getName(),'finance_activity');
        $this->assertEquals($tables[1]->getName(),'finance_category');
        
    }
    
    
}
