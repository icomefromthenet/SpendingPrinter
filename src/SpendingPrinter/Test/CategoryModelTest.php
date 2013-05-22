<?php
namespace SpendingPrinter\Test;

use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\CategoryEntity;
use SpendingPrinter\Model\CategoryModel;

class CategoryModelTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
    public function testNewCategory()
    {
       $model = $this->getApp()->getCategoryModel();

       $entity = new CategoryEntity();
       $entity->setCategoryName('new category');
       $entity->setCategoryId($entity->createIdHash('new category'));
      
       $model->store($entity);
       
       $this->assertEquals('e776898a86a01782e475a3a6cb741f91',$entity->getCategoryId());
    }
    
    public function testDuplicateInMap()
    {
       $model = $this->getApp()->getCategoryModel();
       $entity = new CategoryEntity();
       $category_id = $entity->createIdHash('new category');
       
       $entity->setCategoryName('new category');
       $entity->setCategoryId($category_id);
      
       $model->store($entity);
       $model->store($entity);
       
       $this->assertEquals($category_id,$entity->getCategoryId());
    }  
    
    public function testFindById()
    {
       $model = $this->getApp()->getCategoryModel();
       $entity = new CategoryEntity();
       $category_id = $entity->createIdHash('new category');
       
       $entity->setCategoryName('new category');
       $entity->setCategoryId($category_id);
      
       $model->store($entity); 
       
       $foundEntity = $model->findById($entity->getCategoryId());
       
       $this->assertEquals($foundEntity,$entity);
    }
    
}
