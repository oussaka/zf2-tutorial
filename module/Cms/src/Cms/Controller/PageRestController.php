<?php

namespace Cms\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Cms\Form\PageForm;
use Cms\Model\Page;
use Cms\Model\PageTable;
 
/**
 * Controller des Pages
 */
class PageRestController extends AbstractRestfulController {
 
    /**
     * @var PageTable
     */
    protected $pageTable;
 
    /**
     * Return list of resources
     *
     * @return mixed
     */
    public function getList() {
        $pageList = $this->getPageTable()->fetchAll();
        return new JsonModel($pageList);
    }
 
    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id) {
        $page = $this->getPageTable()->getPage($id);
        return new JsonModel($page->getArrayCopy());
    }
 
    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data) {
        $form = new PageForm();
        $page = new Page();
        //Initialisation du formulaire à partir des données reçues
        $form->setData($data);
        //Ajout des filtres de validation basés sur l'objet Page
        $form->setInputFilter($page->getInputFilter());
        //Contrôle les champs
        if ($form->isValid()) {
            $page->exchangeArray($data);
            $id = $this->getPageTable()->savePage($page);
            $page->id = $id;
            //Enregistrement réussi
            return new JsonModel($page->getArrayCopy());
        } else {
            //TODO retourner les erreurs de validation ici
            return new JsonModel(array('create'=>false));
        }
    }
 
    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data) {
        //Vérifie qu'il s'agit bien d'un objet ayant un id
        if ($id) {
            //Sinon on charge la page correspondant à l'Id
            $page = $this->getPageTable()->getPage($id);
            $form = new PageForm();
            //On charge ces données dans le formulaire initialise aussi les InputFilter
            $form->bind($page);
            $data['id'] = $id;
            $form->setData($data);
            //Contrôle les champs
            if ($form->isValid()) {
                $result = $this->getPageTable()->savePage($page);
                $page = $this->getPageTable()->getPage($id);
                return new JsonModel($page->getArrayCopy());
            } else {
                return new JsonModel(array("update" => $id, 'error' => $form->getMessages()));
            }
        }
    }
 
    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id) {
        $request = $this->getRequest();
        $this->getPageTable()->deletePage($id);
        return new JsonModel(array("deleted" => $id));
    }
    /**
     * Initialise et/ou retourne le TableGateway des pages
     *
     * @return PageTable
     */
    public function getPageTable() {
        if (!$this->pageTable) {
            $sm = $this->getServiceLocator();
            //On récupère le service 'page-table' configuré dans Module.php
            $this->pageTable = $sm->get('page-table');
        }
        return $this->pageTable;
    }
}