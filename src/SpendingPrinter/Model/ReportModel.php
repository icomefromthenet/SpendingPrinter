<?php
namespace SpendingPrinter\Model;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\DBALException;


/**
  *  Model for Activity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
class ReportModel
{
     /*
     * @var Doctrine\DBAL\Connection
     */
    protected $db;
    
    /*
     * @var Doctrine\DBAL\Schema\Schema
     */
    protected $schemaMeta;
    
    
    //-------------------------------------------------------
    # Class Constructor
    
    /**
     *  Class Constructor
     *
     *  @access public
     *  @return void
     *  @param Connection $db the doctrine dbal connection
     *  @param Schema $meta the table meta data
     *
    */
    public function __construct(Connection $db, Schema $meta)
    {
        $this->db         = $db;
        $this->schemaMeta  = $meta;
        
          try {
                
            # setup the report piviot tables         
            $this->db->exec('CREATE TABLE T500 (ID INTEGER)');
            for($i = 1; $i <= 500; $i++) {
                $this->db->exec("INSERT INTO T500 VALUES ($i)");
            }
            
            $this->db->exec('CREATE TABLE T1 (ID INTEGER)');
            $this->db->exec('INSERT INTO T1 VALUES (1)');
        
        }
        catch(DBALException $e) {
            throw new \RuntimeException($e->getMessage(),0,$e);
        }
    }
    
    //-------------------------------------------------------
    # Query Methods
    
    /**
     *  Store an activity 
     *
     *  @access public
     *  @return boolean true if stored
     *  @param ActivityEntity $entity
     *
    */
    public function reportSummary(DateTime $start, DateTime $finish)
    {
        
        try {
        
            $activityTable = $this->getMeta()->getTable('finance_activity');
            $categoryTable = $this->getMeta()->getTable('finance_category');
        
            $startString = $activityTable->getColumn('activity_date')->getType()->convertToDatabaseValue($start,$this->db->getDatabasePlatform());
            $stopString  = $activityTable->getColumn('activity_date')->getType()->convertToDatabaseValue($finish,$this->db->getDatabasePlatform());
            
            # wrote this query only to discover that sqlite not support full outer joins
            # but still use the piviot tables to restrict result set to the given month
            
            $q = "SELECT 
                         SUM(act_cost) as total_cost,
                         act_category,
                         cat_id
                  FROM (
                        SELECT date(x.dy,'+' || t500.id || ' days','-1 days') dy, x.mth
                        FROM (
                            SELECT date('$startString') as dy, strftime('%m','$startString') as mth
                            FROM t1
                            ) x,t500
                        WHERE t500.id <= 31 AND strftime('%m',date(x.dy,'+' || t500.id || ' days','-1 days')) = x.mth
                   ) y,
                   (
                        SELECT act.activity_date as act_date,
                          act.activity_cost as act_cost,
                          cat.category_name as act_category,
                          cat.id as cat_id
                        FROM finance_activity AS act, finance_category AS cat
                        WHERE cat.id = act.category_id
                    ) z
                    WHERE act_date = dy
                    GROUP BY cat_id
                    ORDER BY cat_id;";
                    
              
                
            $stm = $this->db->executeQuery($q);
        
            $result = $stm->fetchAll();
            
            return $result;
      
        }
        catch(DBALException $e) {
            throw new \RuntimeException($e->getMessage(),0,$e);
        }
        
    }
    
    /**
     *  Store an activity 
     *
     *  @access public
     *  @return boolean true if stored
     *  @param ActivityEntity $entity
     *
    */
    public function reportDetail(DateTime $start, DateTime $finish)
    {
        
        try {
        
            $activityTable = $this->getMeta()->getTable('finance_activity');
            $categoryTable = $this->getMeta()->getTable('finance_category');
        
            $startString = $activityTable->getColumn('activity_date')->getType()->convertToDatabaseValue($start,$this->db->getDatabasePlatform());
            $stopString  = $activityTable->getColumn('activity_date')->getType()->convertToDatabaseValue($finish,$this->db->getDatabasePlatform());
            
            # wrote this query only to discover that sqlite not support full outer joins
            # but still use the piviot tables to restrict result set to the given month
            
            $q = "SELECT strftime('%W',dy) wk,
                         strftime('%d',dy) dm,
                         strftime('%w',dy,'+1 days') dw,
                         act_date,
                         act_cost,
                         act_category,
                         cat_id
                  FROM (
                        SELECT date(x.dy,'+' || t500.id || ' days','-1 days') dy, x.mth
                        FROM (
                            SELECT date('$startString') as dy, strftime('%m','$startString') as mth
                            FROM t1
                            ) x,t500
                        WHERE t500.id <= 31 AND strftime('%m',date(x.dy,'+' || t500.id || ' days','-1 days')) = x.mth
                   ) y,
                   (
                        SELECT act.activity_date as act_date,
                          act.activity_cost as act_cost,
                          cat.category_name as act_category,
                          cat.id as cat_id
                        FROM finance_activity AS act, finance_category AS cat
                        WHERE cat.id = act.category_id
                    ) z
                    WHERE act_date = dy
                    ORDER BY cat_id;";
                    
              
                
            $stm = $this->db->executeQuery($q);
        
            $result = $stm->fetchAll();
            
            return $result;
      
        }
        catch(DBALException $e) {
            throw new \RuntimeException($e->getMessage(),0,$e);
        }
        
    }
    
    
    //-------------------------------------------------------
    # Properties
    
    public function getMeta()
    {
        return $this->schemaMeta;
    }
    
    public function getConnection()
    {
        return $this->db;
    }    
    
}
/* End of File */