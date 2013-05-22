<?php
namespace SpendingPrinter\Entity;


/**
  *  Entity For each line of activity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
class ParseEntity
{
    
    protected $date;
    protected $cost;
    protected $note;
    protected $category;
    protected $subCategory;
    
    
    public function setOccurDate(\DateTime $occured)
    {
        $this->date = $occured;
    }
    
    public function getOccurDate()
    {
        return $this->date;
    }
    
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
    
    
    public function getCost()
    {
        return $this->cost;
    }
    
    public function getNote()
    {
        return $this->note;
    }
    
    
    public function setNote($note)
    {
        $this->note = $note;
    }
    
    public function setCategory($id)
    {
        $this->category = $id;
    }
    
    
    public function getCategory()
    {
        return $this->category;
    }
    
    public function setSubCategory($id)
    {
        $this->subCategory = $id;
    }
    
    public function getSubCategory()
    {
        return $this->subCategory;
    }
    
}
/* End of File */
