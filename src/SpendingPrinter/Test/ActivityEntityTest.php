<?php
namespace SpendingPrinter\Test;

use DateTime;
use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\ActivityEntity;

class ActivityEntityTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testEntityProperties()
    {
        $entity = new ActivityEntity();
        
        $id = 1;
        $date = new DateTime();
        $cost = 20.00 ;
        $note = 'a big note';
        $categoryId    = 2;
        
        $entity->setActivityId($id);
        $this->assertEquals($id,$entity->getActivityId());
        
        $entity->setOccurDate($date);
        $this->assertEquals($date,$entity->getOccurDate());
        
        $entity->setCost($cost);
        $this->assertEquals($cost,$entity->getCost());
        
        $entity->setNote($note);
        $this->assertEquals($note,$entity->getNote());
        
        $entity->setCategoryId($categoryId);
        $this->assertEquals($categoryId,$entity->getCategoryId());
        
    }
    
    
}
