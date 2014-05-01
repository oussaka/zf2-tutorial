<?php

namespace Album\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Paginator\Paginator;


class AlbumTable extends AbstractTableGateway
{

    protected $table = 'albums';

    public function __construct(Adapter $dbAdapter)
    {
        $this->adapter = $dbAdapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Album());

        $this->initialize();
        /*
         * trouver dans un blog, je ne comprends pas a quoi sert hydrator !!!
         * $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
        $this->resultSetPrototype->setObjectPrototype(new Sample());

        $this->initialize();
         */
    }

    public function fetchAll($paginated = false, $select = null)
    {
        /* if ($paginated) { // Zend\Paginator\Adapter\DbTableGateway; is availbale in ZF 2.2
            // source : http://samsonasik.wordpress.com/2013/05/06/zend-framework-2-paginator-using-tablegateway-object/
        	$dbTableGatewayAdapter = new DbTableGateway($this->tableGateway);
        	$paginator = new Paginator($dbTableGatewayAdapter);
            //Note : currently, you can pass $where and $order to DbTableGateway adapter after tableGateway parameter.

        	return $paginator;
        } */
        if ($paginated) {
        	// create a new Select object for the table album
        	$select = new Select('albums');
        	// create a new result set based on the Album entity
        	$resultSetPrototype = new ResultSet();
        	$resultSetPrototype->setArrayObjectPrototype(new Album());
        	// create a new pagination adapter object
        	$paginatorAdapter = new DbSelect(
        			// our configured select object
        			$select,
        			// the adapter to run it against
        			$this->getAdapter(),
        			// the result set to hydrate
        			$resultSetPrototype
        	);
        	$paginator = new Paginator($paginatorAdapter);
        	return $paginator;
        }

        $resultSet = $this->select($select);
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
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select();
        $select->from($this->tableName);
        $select->columns(array('id', 'title', 'url', 'date_updated'));
        $select->where->like('title', "%$title%");
        $select->where->greaterThanOrEqualTo('date_created', date('Y-m-d', strtotime($since)));

        $statement = $this->dbAdapter->createStatement();
        $select->prepareStatement($this->dbAdapter, $statement);
        return $statement->execute();
    }
    public function settablegateway($db)
    {
        $this->tableGateway = $db;
    }
}
