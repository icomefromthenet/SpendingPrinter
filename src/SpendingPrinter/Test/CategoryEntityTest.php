<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\CategoryEntity;

class CategoryEntityTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testEntityProperties()
    {
        $entity = new CategoryEntity();
        
        $id = \md5('a example string');
        $categoryName = 'groceries';
        $parentCategoryId = 1;
        
        $entity->setCategoryId($id);
        $this->assertEquals($id,$entity->getCategoryId());
        
        $entity->setCategoryName($categoryName);
        $this->assertEquals($categoryName,$entity->getCategoryName());
        
        $entity->setParentCategoryId($parentCategoryId);
        $this->assertEquals($parentCategoryId,$entity->getParentCategoryId());
        
    }    
    
    public function testHashCreator()
    {
        $entity = new CategoryEntity();
        
        $categoryName = 'groceries';
        $parentCategoryId = \md5('aaa');
        
        $hashA = $entity->createIdHash($categoryName,$parentCategoryId);
        $hashB = $entity->createIdHash($categoryName,$parentCategoryId);
        
        $this->assertEquals($hashA,$hashB);
        
        $hashC = $entity->createIdHash($categoryName,$parentCategoryId);
        $hashD = $entity->createIdHash($categoryName);
        
        $this->assertNotEquals($hashC,$hashD);
        
        $this->assertEquals(\strlen($hashA),32);
        $this->assertEquals(\strlen($hashD),32);
    }
    
}
