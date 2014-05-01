<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Couleurs;
use Album\Form\CouleurForm;


class CouleursController extends AbstractActionController
{
    protected $couleursTable;

    public function ajaxexecutionAction()
    {
        //ajout du traitement de sauvegarde de la couleur
	    $request = $this->getRequest();
	    if ($request->isPost()) {

	        $id = $request->getPost()->id;
    	    $nomcouleur = $request->getPost()->nom;
    	    $this->getCouleursTable()->saveCouleur($id, $nomcouleur);
   	    }
   	    //AJOUT DU TRAITEMENT DE LA SUPPRESSION
        else if($request->getQuery()) {
            $id = $request->getQuery()->id;
            $this->getCouleursTable()->deleteCouleur($id);
        }
   	        
   	        
        $viewModel =  new ViewModel(array(
                'couleurs' => $this->getCouleursTable()->fetchAll(),
        ));
        //*********IMPORTANT SINON LE LAYOUT EST ENTIEREMENT RECHARGE DANS LA DIV contenu **********
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function getCouleursTable()
    {
        if (!$this->couleursTable) {
            $sm = $this->getServiceLocator();
            $this->couleursTable = $sm->get('Tutoriels\Model\CouleursTable');
        }
        return $this->couleursTable;
    }
    
    public function addcouleurAction()
    {
        $form = new CouleurForm();
        $form->get('submit')->setValue('Add');
        $viewModel = new ViewModel(array(
                'form' => $form,
        ));
        
        $viewModel->setTerminal(true);
        return $viewModel;
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
    
    public function editcouleurAction()
    {
        $request = $this->getRequest();
        $id = $request->getQuery()->id;
        $couleur = $this->getCouleursTable()->getCouleur($id);
    
        $form  = new CouleurForm();
        $form->bind($couleur);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $viewModel = new ViewModel(array(
                'id'     => $id,
                'form'   => $form,
        ));
    
        $viewModel->setTerminal(false);
        return $viewModel;
    }
    
    
}
