<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Application\Forms\LinkForm;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $config = $this->getServiceLocator()->get("configuration");
        // var_dump($config['db']);

    	// simple use : generate only one paragraph (element p)
    	// echo \RtExtends\Useful\Data\Fake::getParagraphLoremIpsum();

    	// generate 3 paragraphs (element <p>) with class 'lead'
    	// echo \RtExtends\Useful\Data\Fake::getParagraphLoremIpsum(3,"p",array("class"=>"lead"));

    	// generate 2 divs (element <div>) with class 'alert alert-info'
    	// echo \RtExtends\Useful\Data\Fake::getParagraphLoremIpsum(2,"div",array("class"=>"alert alert-info"));

    	// var_dump(\RtExtends\Useful\I18n\Languages::getSimpleCodeLanguages());
    	// var_dump(\RtExtends\Useful\Location\Country\Fr::states());



    	// $this->getConfig();
    	//         var_dump(\RtExtends\Useful\I18n\Languages::getSimpleCodeLanguages());
    	//         // array("fr"=>"FranÃ§ais","en"=>"English",'pt'=>'PortuguÃªs',....)
    	//         var_dump(\RtExtends\Useful\Location\Countries::getCountries());
    	//         echo \RtExtends\Useful\Data\Fake::getParagraphLoremIpsum(2,"div",array("class"=>"alert alert-info"),false);

    	// $translator = $this->getServiceLocator()->get('translator');
    	// $translator->translate('mon texte');

    	$sessionConfig = new SessionConfig();
    	$sessionConfig->setOptions(array(
    			//  ’remember_me_seconds’ => 1800,
    			// ’name’ => ’aromatix’,
    	));
    	// $sessionManager = new SessionManager($sessionConfig);

    	// voici comment récupérer le gestionnaire de session à partir du container:
   	$sessionContainer = new \Zend\Session\Container('monNamespace');
   	$sessionManager   = $sessionContainer->getManager();
    	// ou en récupérant le gestionnaire de session par défaut
   	$sessionManager = \Zend\Session\Container::getDefaultManager();

   	$userContainer = new Container('user');
    	// $userContainer->getManager()->getStorage()->clear();


        /* $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $post = new \Application\Entity\Post();
        $post->setTitle('This is a dummy title');

        $em->persist($post);
        $em->flush();
        */

        return new ViewModel();
    }

    public function testformAction()
    {

        // @todo: je veux faire une validation de formulaire dans le form pas dans l'entité
        $form = new LinkForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($form->getInputFilter());
            if ($form->isValid()) {

            } else {
                echo "non valid";
                foreach($form->getMessages() as $err) {
                	// $flashMessenger->addMessage(reset(array_values($err)));
                	echo (array_values($err)[0]);
                }
            }
        }

        return new ViewModel(array(
        	"form" => $form
        ));
    }

}
