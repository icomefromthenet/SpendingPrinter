<?php
namespace SpendingPrinter\Parser;

use SplFileObject;

/**
 *  Simple CSV Parser
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *  @since 1.0.0
 *
*/
class CSVParser
{
    /*
     * @var SplFileObject
     */
    protected $handle;
    protected $delimiter = ',';
    protected $enclosure = '"';
    protected $headersInFirstRow = true;
    protected $headers;
    protected $line;
    protected $init;
    protected $mapper;

    public function __construct(SplFileObject $file, $headersInFirstRow = true)
    {
        $this->handle            = $file;
        $this->headersInFirstRow = $headersInFirstRow;
        $this->line              = 0;
    }
    
    public function __destruct()
    {
        unset($this->handle);
    }

    //-------------------------------------------------------
    # Actions
    
    protected function init()
    {
        if (true === $this->init) {
            return;
        }
        $this->init = true;
        
        if($this->headersInFirstRow === true) {
            $this->headers = $this->handle->fgetcsv($this->delimiter, $this->enclosure);
            $this->line++;
        }
        
    }
  
    
    public function getRow()
    {
        $this->init();
        $entity = false;
        
        if (($row = $this->handle->fgetcsv($this->delimiter, $this->enclosure)) !== null) {
            $this->line++;
            $row = $this->headers ? array_combine($this->headers, $row) : $row;
            
            if($this->mapper instanceof \Closure) {
                $entity = call_user_func($this->mapper,$row);       
            } else {
                $entity = $row;
            }
        }
        
        return $entity;
    }

    public function getAll()
    {
        $data = array();
        while ($row = $this->getRow()) {
            $data[] = $row;
        }
        return $data;
    }
    
    
    //-------------------------------------------------------
    # Properties

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    
    public function getLineNumber()
    {
        return $this->line;
    }
    
    public function getHeaders()
    {
        $this->init();
        return $this->headers;
    }
    
    public function map(\Closure $map)
    {
        $this->mapper = $map;
        
        return $this;
    }
}