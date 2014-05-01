<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class CouleursTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    

    public function saveCouleur($id=0, $nomcouleur)
    {
        $data = array(
                'nom' => $nomcouleur,
        );
    
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCouleur($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Le formulaire n\'existe pas');
            }
        }
    }
    
    public function getCouleur($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function deleteCouleur($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    
}

