<?php

namespace CacheResultSet\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
// use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Cache\Storage\StorageInterface;


class AlbumTable extends AbstractTableGateway
{

    protected $table = 'albums';
    protected $cache;

    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        // $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype = new HydratingResultSet();
        // $this->resultSetPrototype->setHydrator(new ObjectProperty());
        $this->resultSetPrototype->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods);
        // $this->resultSetPrototype->setArrayObjectPrototype(new Album());
        $this->resultSetPrototype->setObjectPrototype(new Album());

        $this->initialize();
    }

    public function setCache(StorageInterface $cache)
    {
    	$this->cache = $cache;
    }


    public function fetchAll()
    {
        if( ($resultSet = $this->cache->getItem('samplecache')) == FALSE) {

        	$resultSet = $this->select(function (Select $select){
        		$select->columns(array('id', 'title', 'artist'));
        		$select->order(array('id asc'));
        	});

        	$resultSet = $resultSet->toArray();
        	$this->cache->setItem('samplecache',  $resultSet);
        }

        return $resultSet;
    }

    public function getAlbum($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
        );

        $id = (int)$album->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }

        return $id; // Add Return
    }

    public function deleteAlbum($id)
    {
        $this->delete(array('id' => $id));
    }

    /**
     * Displaying the generated SQL from a ZendDbSql object
     * If you use ZendDbSql to generate your SQL, then it’s useful to find out what the generated SQL looks like.
     * At last To find out what the generated SQL will look like: $select->getSqlString();
     * This is less than helpful, so to avoid the warnings, you need to supply the correct platform information to the method:
     * $select->getSqlString($this->dbAdapter->getPlatform());
     *
     * @param unknown $title
     * @param unknown $since
     */
    public function fetchAllWithTitleSince($title, $since)
    {
        // $sql = new Sql($this->dbAdapter);

        // $select = $sql->select();
        $select = new Select();
        $select->from($this->table);
        $select->columns(array('id', 'title', 'url', 'date_updated'));
        $select->where->like('title', "%$title%");
        $select->where->greaterThanOrEqualTo('date_created', date('Y-m-d', strtotime($since)));
        // This is a short note to myself. ZendDbSql objects allow you to do this:
        // $id = 2;
        // $select->where(array('id' => $id)); // => WHERE `id` = '2'
        // $idList = array(1, 3, 4);
        // $select->where(array('id' => $idList)); // => WHERE `id` IN ('1', '3', '4')

        $statement = $this->adapter->createStatement();
        $select->prepareStatement($this->adapter, $statement);
        echo $select->getSqlString($this->adapter->getPlatform()); die;

        return $statement->execute();
    }

    public function settablegateway($db)
    {
        $this->tableGateway = $db;
    }
}
