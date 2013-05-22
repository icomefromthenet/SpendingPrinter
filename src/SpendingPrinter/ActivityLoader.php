<?php
namespace SpendingPrinter;

use DateTime;
use SpendingPrinter\Parser\CSVParser;
use SpendingPrinter\Model\ActivityModel;
use SpendingPrinter\Model\CategoryModel;
use SpendingPrinter\Entity\ParseEntity;
use SpendingPrinter\Entity\CategoryEntity;
use SpendingPrinter\Entity\ActivityEntity;

/**
  *  Mediates for the parser and the Models
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
class ActivityLoader
{
    
    /*
     * @var SpendingPrinter\Parser\CSVParser
     */
    protected $parser;
    
    /*
     * @var SpendingPrinter\Model\CategoryModel
     */
    protected $categoryModel;
    
    /*
     * @var SpendingPrinter\Model\ActivityModel
     */
    protected $activityModel;
    
    /*
     * @var boolean true will stop reload
     */
    protected $loaded; 
    
    public function __construct(CSVParser $parser, CategoryModel $categoryModel, ActivityModel $activityModel)
    {
        $this->parser        = $parser;
        $this->categoryModel = $categoryModel;
        $this->activityModel = $activityModel;
        $this->loaded        = false;
        
        $this->parser->map(function(array $result) use ($activityModel) {
            $entity = new ParseEntity();
            
            $entity->setNote($result['Note']);
            $entity->setCost($result['Cost']);
            $entity->setCategory($result['Category']);
            $entity->setSubCategory($result['Sub-Category']);
            
            $date = DateTime::createFromFormat('Y-m-d',$result['Date']);
            
            $entity->setOccurDate($date);
            
            return $entity;
            
        });
    }
    
    
    public function load()
    {
       if($this->loaded === false) {
       
            $hashMaker =  new CategoryEntity();
            
            while(($row = $this->parser->getRow()) !== false) {
                
                $activity = new ActivityEntity();
                $topCategoryId = $hashMaker->createIdHash($row->getCategory(),null);
                
                # fetch the top category first
                $topCategory = $this->categoryModel->findById($topCategoryId);
                
                if($topCategory === false) {
                    $topCategory = $this->createCategory($topCategoryId,$row->getCategory(),null);
                    $this->categoryModel->store($topCategory);
                }
                
                
                
                # check the subcategory
                $subCategoryId = $hashMaker->createIdHash($row->getSubCategory(),null);
                $subCategory   = $this->categoryModel->findById($subCategoryId);
                
                if($subCategory === false) {
                    $subCategory = $this->createCategory($subCategoryId,$row->getSubCategory(),$topCategoryId);
                    $this->categoryModel->store($subCategory);
                }
                
                $time = new DateTime();
                
                $activity->setCategoryId($subCategoryId);
                $activity->setCost($row->getCost());
                $activity->setNote($row->getNote());
                $activity->setOccurDate($row->getOccurDate());
                
                $this->activityModel->store($activity);
            }
            
            $this->loaded = true;
        }
        return true;
    }
    
    
    protected function createCategory($id,$name,$parent = null)
    {
        $category = new CategoryEntity();
        $category->setCategoryId($id);
        $category->setCategoryName($name);
        $category->setParentCategoryId($parent);
        
        return $category;
    }
}


/* End of File */