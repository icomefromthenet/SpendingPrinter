<?php
namespace PhroznPlugin\Provider;

# load the autoloader for this plugin
include_once(__DIR__.'/../../../vendor/autoload.php');

use DateTime;
use Phrozn\Provider\Base;
use Phrozn\Provider;
use SpendingPrinter\Application;
use SpendingPrinter\Entity\ReportEntity;

class SpendingTracker extends Base implements Provider
{
   
    /**
     * @var \SpendingPrinter\Application
    */   
    static protected $app;

    public function get()
    {
        
        if(self::$app === null) {
            self::$app = new Application(__DIR__.'/../../../data/data.csv');
            $loder = self::$app->getActivityLoader();
            $loder->load();
        }
        
        
        # get reference to configuration object (it holds passed vars, if any)
        $config = $this->getConfig();

        # check if month been provided
        if(isset($config['month'])) {
            # can date string be parser, by strtotime
            $config['month'] = new DateTime($config['month']);
        }
        
        
        $lastDay = clone $config['month'];
        $lastDay->modify('+1 month');
        $lastDay->modify('-1 day');
        
        $detailReport = self::$app->getReportModel()->reportDetail($config['month'],$lastDay);        
        
        $summaryReport = self::$app->getReportModel()->reportSummary($config['month'],$lastDay);        

        return new ReportEntity($detailReport,$summaryReport,$config['month']);
    }
    
    
}