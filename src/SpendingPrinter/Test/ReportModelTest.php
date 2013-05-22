<?php
namespace SpendingPrinter\Test;

use DateTime;
use SpendingPrinter\TestCase;
use SpendingPrinter\Application;
use SpendingPrinter\Model\ReportModel;

class ReportModelTest extends TestCase
{
    
    public function createApplication()
    {
        return new Application(realpath(__DIR__.'/../../../data/data.csv'));
    }
    
    
   public function testReportDetail()
   {
        $loader = $this->getApp()->getActivityLoader();
        $loader->load();
         
        $report = $this->getApp()->getReportModel();
        
        $start = new DateTime('1st April 2013');
        $end = new DateTime('30th April 2013');
        
        $result = $report->reportDetail($start,$end);
        
        $this->assertTrue(true);
        
   }
   
    public function testReportSummary()
   {
        $loader = $this->getApp()->getActivityLoader();
        $loader->load();
         
        $report = $this->getApp()->getReportModel();
        
        $start = new DateTime('1st April 2013');
        $end = new DateTime('30th April 2013');
        
        $result = $report->reportSummary($start,$end);
        
        var_dump($result);
        
        $this->assertTrue(true);
        
   }
    
}
