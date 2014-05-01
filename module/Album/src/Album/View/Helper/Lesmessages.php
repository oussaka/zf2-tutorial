<?php 
namespace Album\View\Helper;

use    Zend\View\Helper\AbstractHelper,
    Zend\View\Exception,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceMAnager\ServiceLocatorInterface;



class Lesmessages extends AbstractHelper implements ServiceLocatorAwareInterface {  


	protected $messagesTable;
	protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        // $sm is the view helper manager, so we need to fetch the main service manager
        $this->serviceLocator = $serviceLocator->getServiceLocator();
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

	/* public function __construct($sm) {
	 
	    // $sm is the view helper manager, so we need to fetch the main service manager
	    $this->serviceLocator = $sm->getServiceLocator();
	} */
    
	public function getMessagesTable()
	{
		if (!$this->messagesTable) {
		
    		$this->messagesTable = $this->getServiceLocator()
    		                            // ->getServiceLocator()
                                        ->get('Album\Model\MessagesTable');
		}

		return $this->messagesTable;
	}


	public function allPosts()
	{
		$all = $this->getMessagesTable()->fetchAll();
		return $all;
	}
  

  
	public function __invoke()
	{
		// $all = $this->getPostsTable()->fetchAll();
		$all = $this->allPosts();
		
		foreach($all  as $k=>$v){
			 echo $v->comment;
		}
	  
	} 
}

