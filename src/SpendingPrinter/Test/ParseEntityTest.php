<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\ParseEntity;

class ParseEntityTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testEntityProperties()
    {
        $entity = new ParseEntity();
        
        $date = new \DateTime();
        $cost = 20.00 ;
        $note = 'a big note';
        $categoryId    = 2;
        $subcategoryId = 1;
        
        $entity->setOccurDate($date);
        $this->assertEquals($date,$entity->getOccurDate());
        
        $entity->setCost($cost);
        $this->assertEquals($cost,$entity->getCost());
        
        $entity->setNote($note);
        $this->assertEquals($note,$entity->getNote());
        
        $entity->setCategory($categoryId);
        $this->assertEquals($categoryId,$entity->getCategory());
        
        $entity->setSubCategory($subcategoryId);
        $this->assertEquals($subcategoryId,$entity->getSubCategory());
        
    }
    
    
}
