<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\ActivityEntity;


class ActivityModelTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testNewActivity()
    {
       $model = $this->getApp()->getActivityModel();

       $entity = new ActivityEntity();
       
       $entity->setCategoryId(1);
       $entity->setNote('a new note');
       $entity->setCost(100);
       $entity->setOccurDate(new \DateTime());
       
      
       $model->store($entity);
       
       $this->assertEquals(1,$entity->getActivityId());
    }
    

    
}
