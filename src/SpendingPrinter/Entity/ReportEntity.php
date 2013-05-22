<?php
namespace SpendingPrinter\Entity;


use DateTime;

/**
  *  Report Entity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class ReportEntity
{
    protected $activity;
    
    protected $summary;
    
    protected $reportDate;
    
    public function __construct($activity,$summary, DateTime $reportDate)
    {
        $this->activity = $activity;
        $this->summary  = $summary;
        $this->reportDate = $reportDate;
    }
    
    
    public function getActivity()
    {
        return $this->activity;
    }
    
    public function getSummary()
    {
        return $this->summary;
    }
    
    public function getSpendingTotal()
    {
        $total = 0.00;
        $summary = $this->getSummary();
        
        foreach($summary as $value) {
            $total = $total + $value['total_cost'];
        }
        
        return $total;
    }
    
    public function getReportDate()
    {
        return $this->reportDate;
    }
    
}
/* End of File */