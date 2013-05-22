<?php
namespace SpendingPrinter\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\DBALException;
use SpendingPrinter\Entity\CategoryEntity;


/**
  *  Model for Categorys
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class CategoryModel
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
    
    /*
     * @var map of stored categories
     */
    protected $identityMap;
    
    
    protected function fillColumnTypes()
    {
        $columns = $this->tableMeta->getColumns();
        
        foreach($columns as $column) {
            $this->columnTypes[$column->getName()] = $column->getType();
        }
    }
    
    /**
     *  Map a row result into an entity
     *
     *  @access public
     *  @return CategoryEntity
     *
    */
    protected function map(\stdClass $result)
    {
        $entity = new CategoryEntity();
        
        $entity->setCategoryId($result->id);
        $entity->setParentCategoryId($result->parent_id);
        $entity->setCategoryName($result->category_name);
        
        return $entity;
    }
    
    /**
     *  Check the Identity map for an entity
     *
     *  @access public
     *  @return boolean entity or false if not found
     *  @param string $hash the unique hash 
     *
    */
    protected function exist($hash)
    {
        $found = false;
        
        if(isset($this->identityMap[$hash])) {
            $found = true;
        } 
        
        return $found;
    }
    
    /**
     *  Add an entity to the IdentityMap
     *
     *  @access public
     *  @return void
     *  @param CategoryEntity $entity
     * 
    */
    protected function set($hash,CategoryEntity $entity)
    {
        if($this->exist($hash) === false) {
            $this->identityMap[$hash] = $entity;
        }
    }
    
    /**
     *  Fetch an entity from the Identity Map
     *
     *  @access public
     *  @return CategoryEntity or false if not in map
     *
    */
    protected function get($hash)
    {
        $found = false;
        
        if($this->exist($hash) === true) {
            $found = $this->identityMap[$hash];
        }
        
        return $found;
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
        $this->identityMap = array();
        
        $this->fillColumnTypes();
        
    }
    
    //-------------------------------------------------------
    # Query Methods
    
    /**
     *  Store a category 
     *
     *  If category is in identityMap not overwritten
     *
     *  @access public
     *  @return boolean true if stored
     *  @param CategoryEntity $entity
     *
    */
    public function store(CategoryEntity $entity)
    {
        
        if($this->exist($entity->getCategoryId())) {
            return true;
        }
        
        try {
        
            # store the entity in db
            $data = array(
                'id'            => $entity->getCategoryId(),
                'parent_id'     => $entity->getParentCategoryId(),
                'category_name' => $entity->getCategoryName()
            );
            
            if($this->db->insert($this->tableMeta->getName(),$data,$this->columnTypes) != 1) {
                throw new \RuntimeException('Unable to insert new category because '.$this->db->errorInfo());
            }
            
            # store in the internal map
            $this->set($entity->getCategoryId(),$entity);            

        }
        catch(DBALException $e) {
            throw new \RuntimeException($e->getMessage(),0,$e);
        }
        
    }
    
    /**
     *  Fetch the CategoryEntity if its been stored
     *
     *  @access public
     *  @return CategoryEntity | false if not stored
     *  @param string the 32bit id of the category
     *
    */    
    public function findById($hash)
    {
        return $this->get($hash);
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
