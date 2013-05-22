<?php
namespace SpendingPrinter\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\DBALException;
use SpendingPrinter\Entity\ActivityEntity;


/**
  *  Model for Activity
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
class ActivityModel
{
     /*
     * @var Doctrine\DBAL\Connection
     */
    protected $db;
    
    /*
     * @var Doctrine\DBAL\Schema\Table
     */
    protected $tableMeta;
    
    /*
     * @var array of doctrine column types
     */
    protected $columnTypes;
    
    protected function fillColumnTypes()
    {
        $columns = $this->tableMeta->getColumns();
        
        foreach($columns as $column) {
            $this->columnTypes[$column->getName()] = $column->getType();
        }
        
    }
    
    //-------------------------------------------------------
    # Class Constructor
    
    /**
     *  Class Constructor
     *
     *  @access public
     *  @return void
     *  @param Connection $db the doctrine dbal connection
     *  @param Table $meta the table meta data
     *
    */
    public function __construct(Connection $db, Table $meta)
    {
        $this->db         = $db;
        $this->tableMeta  = $meta;
        
        $this->fillColumnTypes();
        
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
    public function store(ActivityEntity $entity)
    {
        
        $this->fillColumnTypes();
        unset($this->columnTypes['id']);
        $platform = $this->db->getDatabasePlatform();
        
        try {
        
            # store the entity in db
            $data = array(
                'category_id'   => $this->columnTypes['category_id']->convertToDatabaseValue($entity->getCategoryId(),$platform),
                'activity_note' => $this->columnTypes['activity_note']->convertToDatabaseValue($entity->getNote(),$platform),
                'activity_cost' => $this->columnTypes['activity_cost']->convertToDatabaseValue($entity->getCost(),$platform),
                'activity_date' => $this->columnTypes['activity_date']->convertToDatabaseValue($entity->getOccurDate(),$platform)
            );
            
            if($this->db->insert($this->tableMeta->getName(),$data,$this->columnTypes) != 1) {
                throw new \RuntimeException('Unable to insert new category because '.$this->db->errorInfo());
            }
            
            # set the id to database value
            $entity->setActivityId($this->db->lastInsertId());            

        }
        catch(DBALException $e) {
            throw new \RuntimeException($e->getMessage(),0,$e);
        }
        
    }
    
    
    //-------------------------------------------------------
    # Properties
    
    public function getMeta()
    {
        return $this->tableMeta;
    }
    
    public function getConnection()
    {
        return $this->db;
    }    
    
}
/* End of File */