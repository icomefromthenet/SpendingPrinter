<?php
namespace SpendingPrinter\Entity;

/**
  *  Entity to represent a category
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class CategoryEntity
{
    
    protected $categoryId;
    protected $categoryName;
    protected $parentCategoryId;
    
    public function setCategoryId($id)
    {
        $this->categoryId = $id;
    }
    
    public function getCategoryId()
    {
        return $this->categoryId;
    }
    
    public function setCategoryName($name)
    {
        $this->categoryName = $name;
    }
    
    public function getCategoryName()
    {
        return $this->categoryName;
    }
    
    public function getParentCategoryId()
    {
        return $this->parentCategoryId;
    }
    
    public function setParentCategoryId($id)
    {
        $this->parentCategoryId = $id;
    }
    
    
    /**
     *  Convert the name into a hash
     *
     *  @access public
     *  @return string a md5 hash
     *  @param string $name the category name
     *  @param integer $parent_id 
     *
    */
    public function createIdHash($name,$parentId = null)
    {
        $hash = null;
        
        if($parentId === null) {
            $hash = \md5($name .'_'. (string) $parentId);
        } else {
            $hash = \md5($name); // could be parent category
        }
        
        return $hash;
    }
}
