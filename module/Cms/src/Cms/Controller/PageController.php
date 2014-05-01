<?php
namespace Cms\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Cms\Form\PageForm,
    Cms\Model\Page;

/**
 * Controller des Pages
 */
class PageController extends AbstractActionController
{
    /**
     * Gateway PageTable
     */
    protected $pageTable;

    /**
     * Action index rÃ©cupÃ¨re tous les enregistrements et les stocke
     * dans un ViewModel pour les transmettre Ã  la vue
     */
    public function indexAction()
    {
        return new ViewModel(array(
            'pages' => $this->getPageTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new PageForm();
        $form->get('submit')->setAttribute('label', 'Add');
        $request = $this->getRequest();
        //VÃ©rifie le type de la requÃªte
        if ($request->isPost()) {
            $page = new Page();
            //Initialisation du formulaire Ã  partir des donnÃ©es reÃ§ues
            $form->setData($request->getPost());
            //Ajout des filtres de validation basÃ©s sur l'objet Page
            $form->setInputFilter($page->getInputFilter());
            //ContrÃ´le les champs
            if ($form->isValid()) {
                $page->exchangeArray($form->getData());
                $this->getPageTable()->savePage($page);
                //Redirection vers la liste des pages
                return $this->redirect()->toRoute('page');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        //Si l'Id est vie on redirige vers l'ajout
        if (!$id) {
            return $this->redirect()->toRoute('page', array('action'=>'add'));
        }
        //Sinon on charge la page correspondant Ã  l'Id
        $page = $this->getPageTable()->getPage($id);
        $form = new PageForm();
        //On charge ces donnÃ©es dans le formulaire initialise aussi les InputFilter
        $form->bind($page);
        $form->get('submit')->setAttribute('label', 'Edit');
        $request = $this->getRequest();
        //VÃ©rifie le type de la requÃªte
        if ($request->isPost()) {
            $form->setData($request->getPost());
            //ContrÃ´le les champs
            if ($form->isValid()) {
                $this->getPageTable()->savePage($page);
                //Redirection vers la liste des pages
                return $this->redirect()->toRoute('page');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('page');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');
            if ($del == 'Oui') {
                $id = (int)$request->getPost('id');
                $this->getPageTable()->deletePage($id);
            }

            //Redirection vers la liste des pages
            return $this->redirect()->toRoute('page');
        }
        return array(
            'id' => $id,
            'page' => $this->getPageTable()->getPage($id)
        );
    }

    public function viewAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        //Si l'Id est vie on redirige vers la liste
        if (!$id) {
            return $this->redirect()->toRoute('page');
        }
        try{
            //Sinon on charge la page correspondant Ã  l'Id
            $page = $this->getPageTable()->getPage($id);
        }
        catch(\Exception $e){
                //Si la page n'existe pas en base on gÃ©nÃ¨re une erreur 404
                $response   = $this->response;
                $event	  = $this->getEvent();
                $routeMatch = $event->getRouteMatch();
                $response->setStatusCode(404);
                $event->setParam('exception', new \Exception('Page Inconnue '.$id));
                $event->setController('page');
                return ;
        }
        return new ViewModel(array(
            'page' => $page
        ));
    }


    /**
     * Initialise et/ou retourne le TableGateway des pages
     */
    public function getPageTable()
    {
        if (!$this->pageTable) {
            $sm = $this->getServiceLocator();
            //On rÃ©cupÃ¨re le service 'page-table' configurÃ© dans Module.php
            $this->pageTable = $sm->get('Cms\Model\PageTable');
        }
        return $this->pageTable;
    }
    
    /**
     * Action pour charger la vue ajax sans remplacer index
     */
    public function ajaxAction()
    {
        return array();
    }
    
    /**
     * Action jsFindall récupère tous les enregistrements et les retourne au format JSON
     */
    public function jsFindAllAction()
    {
        $pageList = $this->getPageTable()->fetchAll()->toArray();
        return new JsonModel(array(
                'status' => 'ok',
                'message' => '',
                'data' => $pageList
        ));
    }


    /**
     * Action qui crée une nouvelle page
     * retourne l'id de la page nouvellement créée
     */
    public function jsCreateAction()
    {
    	$form = new PageForm();
    	$request = $this->getRequest();
    	//Vérifie le type de la requête
    	if ($request->isPost()) {
    		$page = new Page();
    		//Initialisation du formulaire à partir des données reçues
    		$form->setData($request->getPost());
    		//Ajout des filtres de validation basés sur l'objet Page
    		$form->setInputFilter($page->getInputFilter());
    		//Contrôle les champs
    		if ($form->isValid()) {
    			$data = $form->getData();
    			$page->exchangeArray($data);
    			$id = $this->getPageTable()->savePage($page);
    			$data['id']=$id;
    			$page->exchangeArray($data);
    			//Enregistrement réussi
    			return new JsonModel(array(
    					'id'=>$id
    			));
    		}
    		else{
    			//TODO retourner les erreurs de validation ici
    			return new JsonModel(array(
    					'status'=>'validation_error',
    					'message'=>''
    			));
    		}
    	}
    	return new JsonModel(array(
    			'status'=>'error',
    			'message'=>''
    	));
    }
    
    /**
     * Met à jour une page
     */
    public function jsUpdateAction()
    {
    	$request = $this->getRequest();
    	$id = (int)$request->getPost('id', false);
    	//Vérifie qu'il s'agit bien d'un objet ayant un id
    	if ($id) {
    		try{
    			//Sinon on charge la page correspondant à l'Id
    			$page = $this->getPageTable()->getPage($id);
    			$form = new PageForm();
    			//On charge ces données dans le formulaire initialise aussi les InputFilter
    			$form->bind($page);
    			//Vérifie le type de la requête
    			if ($request->isPost()) {
    				$form->setData($request->getPost());
    				//Contrôle les champs
    				if ($form->isValid()) {
    					$result = $this->getPageTable()->savePage($page);
    					return new JsonModel(array(
    							'status'=>'ok',
    							'data'=>$result
    					));
    				}
    				else{
    					return new JsonModel(array(
    							'status'=>'validation_error',
    							'message'=>''
    					));
    				}
    			}
    		}
    		catch(\Exception $e){
    			//Si la page n'existe pas en base on génère une erreur 404
    			return new JsonModel(array(
    					'status'=>'error',
    					'message'=>'Page Inconnue '.$id
    			));
    		}
    	}
    	return new JsonModel(array(
    			'status'=>'error',
    			'message'=>''
    	));
    }
    
    /**
     * Supprime une page
     */
    public function jsDestroyAction()
    {
    	$id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$this->getPageTable()->deletePage($id);
    		return new JsonModel(array(
    				'status'=>'ok',
    				'message'=>'',
    				'id'=>$id
    		));
    	}
    	return new JsonModel(array(
    			'status'=>'error',
    			'message'=>''
    	));
    }
}
