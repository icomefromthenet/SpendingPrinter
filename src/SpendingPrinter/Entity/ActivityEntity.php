<?php
namespace SpendingPrinter\Entity;


/**
  *  Entity For each line of activity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
class ActivityEntity
{
    
    protected $id;
    protected $date;
    protected $cost;
    protected $note;
    protected $category;
    
    
    public function getActivityId()
    {
        return $this->id;
    }
    
    public function setActivityId($id)
    {
        $this->id = $id;
    }
    
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
    
    public function setCategoryId($id)
    {
        $this->category = $id;
    }
    
    
    public function getCategoryId()
    {
        return $this->category;
    }
    
}
/* End of File */
