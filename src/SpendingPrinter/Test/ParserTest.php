<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Parser\CSVParser;

class ParserTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testParserNoMapper()
    {
        $parser  = $this->getApp()->getParser();
        
        $row = $parser->getRow();
        
        $this->assertArrayHasKey('Date',$row);
        $this->assertArrayHasKey('Cost',$row);
        $this->assertArrayHasKey('Note',$row);
        $this->assertArrayHasKey('Category',$row);
        $this->assertArrayHasKey('Sub-Category',$row);
        $this->assertArrayHasKey('Need',$row);
        
        
    }
    
    
    public function testParserWithMapper()
    {
       $parser  = $this->getApp()->getParser();
       
       $parser->map(function(array $data){
            return new \stdClass();
       }); 
       
       $this->assertInstanceOf('stdClass',$parser->getRow());
       $this->assertInstanceOf('stdClass',$parser->getRow());
       
    }
    
}
