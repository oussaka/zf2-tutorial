<?php
namespace Cms\Model;

use Zend\Db\TableGateway\AbstractTableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet;

/**
 * Classe PageTable étends la Classe AbstractTableGateway
 * qui fournit les méthodes de base (select,update, insert, ...)
 */
class PageTable extends AbstractTableGateway
{
    protected $table ='cms_page';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        //On précise que les données récupérées seront de type Page
        $this->resultSetPrototype->setArrayObjectPrototype(new Page());
        $this->initialize();
    }

    /**
     * Méthode pour récupérer tous les enregistrements
     */
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    /**
     * Méthode pour récupérer une Page à partir de son Id
     */
    public function getPage($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Impossible de trouver l'enregistrement ayant pour Id : $id");
        }
        return $row;
    }

    /**
     * Méthode pour sauvegarder une Page
     */
    public function savePage(Page $page)
    {
        $data = array(
            'title'  => $page->title,
            'content' => $page->content,
        );
        $id = (int)$page->id;

        //Si l'objet n'a pas d'Id on réalise un insert sinon on met à jour
        if ($id == 0) {
            $this->insert($data);
        } else {
            //On vérifie que la page portant cet Id existe
            if ($this->getPage($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('l\'id n\'existe pas');
            }
        }
    }

    /**
     * Méthode pour créer une page à partir de ses champs
     */
    public function addPage($title, $content)
    {
        $data = array(
            'title' => $title,
            'content'  => $content,
        );
        $this->insert($data);
    }

    /**
     * Méthode pour modifier une page à partir de ses champs
     */
    public function updatePage($id, $title, $content)
    {
        $data = array(
            'title' => $title,
            'content'  => $content,
        );
        $this->update($data, array('id' => $id));
    }

    /**
     * Méthode pour supprimer une page à partir de son Id
     */
    public function deletePage($id)
    {
        $this->delete(array('id' => $id));
    }
}
