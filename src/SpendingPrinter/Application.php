<?php
namespace SpendingPrinter;

use Pimple;

/**
  *  Application Container
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class Application extends Pimple
{
    
    
    public function __construct($filePath)
    {
        $this['data_path'] = $filePath;    
        
        $this['data_file'] = $this->share(function($app){

            $fpath = $app['data_path'];
      
            if(is_file($fpath) === false) {
               throw new \RuntimeException('Datafile not found under '. $fpath);
            }   
                
           return new \SplFileObject($fpath);
        });
        
        $this['parser'] = $this->share(function($app) {
            return new \SpendingPrinter\Parser\CSVParser($app->getDataFile(),true);
        });
        
        $this['dbal'] = $this->share(function($app){
            
            $config = new \Doctrine\DBAL\Configuration();
            
            $connectionParams = array(
                'user'     => null,
                'password' => null,
                'memory'   => true,
                'driver'   => 'pdo_sqlite',
            );
            
            $conn =  \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            
            #fetch the schema
            $schema = $this['schema'];
            
            # get queries to create this schema.
            $queries = $schema->toSql($conn->getDatabasePlatform()); 
            
            # build the schema
            foreach($queries as $query) {
                $conn->exec($query);    
            }
            
            return $conn;
        });
        
        $this['schema'] = $this->share(function($app) {
            $schema = new \Doctrine\DBAL\Schema\Schema();
            
            
            $financeCategory = $schema->createTable("finance_category");
            $financeCategory->addColumn("id", "string",array("length" => 32));
            $financeCategory->addColumn("category_name", "string", array("length" => 100));
            $financeCategory->addColumn("parent_id", "string", array("length" => 32,'notNull' => false));
            $financeCategory->setPrimaryKey(array("id"));
            $financeCategory->addForeignKeyConstraint($financeCategory,array('parent_id')
                                                                      ,array('id')
                                                                      ,array("onUpdate" =>"CASCADE"));    
            
            
            $activityTable = $schema->createTable("finance_activity");
            $activityTable->addColumn("id", "integer", array("unsigned" => true));
            $activityTable->addColumn("category_id", "string", array("length" => 32));
            $activityTable->addColumn("activity_note", "string",array("length" => 100));
            $activityTable->addColumn("activity_cost", "decimal");
            $activityTable->addColumn("activity_date", "date");
            
            $activityTable->setPrimaryKey(array("id"));
            
                
            
            return $schema;
            
        });
        
        $this['category_model'] = $this->share(function($app) {
            $db    = $app->getDBAL();
            $table = $app->getDBMeta()->getTable('finance_category'); 
            
            return new \SpendingPrinter\Model\CategoryModel($db,$table);
        });
        
        $this['activity_model'] = $this->share(function($app) {
            $db    = $app->getDBAL();
            $table = $app->getDBMeta()->getTable('finance_activity'); 
            
            return new \SpendingPrinter\Model\ActivityModel($db,$table);
        });
        
        $this['report_model'] = $this->share(function($app) {
            $db     = $app->getDBAL();
            $schema = $app->getDBMeta();
            
            return new \SpendingPrinter\Model\ReportModel($db,$schema);
        });
        
        $this['activity_loader'] = $this->share(function($app) {
           $activityModel = $app->getActivityModel();
           $categoryModel = $app->getCategoryModel();
           $parser        = $app->getParser();
           
           return new \SpendingPrinter\ActivityLoader($parser,$categoryModel,$activityModel); 
        });
    }
    
    
    public function getDBMeta()
    {
        return $this['schema'];  
    }
    
    
    public function getDBAL()
    {
        return $this['dbal'];
    }
    
    /**
     *  Return the category Model
     *
     *  @access public
     *  @return SpendingPrinter\Model\CategoryModel
     *
    */
    public function getCategoryModel()
    {
        return $this['category_model'];
    }
    
     /**
     *  Return the category Model
     *
     *  @access public
     *  @return SpendingPrinter\Model\ActivityModel
     *
    */
    public function getActivityModel()
    {
        return $this['activity_model'];
    }
    
    /**
     *  Return the report Model 
     *
     *  @access public
     *  @return SpendingPrinter\Model\ReportModel
     *
    */
    public function getReportModel()
    {
        return $this['report_model'];  
    }
    
    /**
     *  Return the csv parser
     *
     *  @access public
     *  @return SpendingPrinter\Parser\CSVParser
    */
    public function getParser()
    {
        return $this['parser'];
    }
   
    
    /**
     *  Fetch the Datafile as SPLFileObject
     *
     *  @access public
     *  @return SplFileObject
     *  @throws \RuntimeException if file not under the path
    */
    public function getDataFile()
    {
        return $this['data_file'];
    }
    
    /**
     *  Return an instance of the activity loader
     *
     *  @access public
     *  @return SpendingPrinter\ActivityLoader
     *
    */
    public function getActivityLoader()
    {
        return $this['activity_loader'];
    }
}
/* End of Class */
