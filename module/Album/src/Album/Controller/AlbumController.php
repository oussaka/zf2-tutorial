<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Zend\EventManager\SharedEventManager;
use Album\Event\Foo;
use Zend\EventManager\StaticEventManager;

use Zend\Version;
use Zend\EventManager\EventManager;
use Album\Event\Class1;
use Zend\Console\Charset\Ascii;
use Application\ConfigAwareInterface;


use Zend\Db\TableGateway\TableGateway;

class AlbumController extends AbstractActionController implements ConfigAwareInterface
{
    protected $albumTable;

    protected $config;

    public function setConfig($config)
    {
    	$this->config = $config;
    }

    public function indexAction()
    {
        $this->headTitle("My website")->setSeparator(" - ")->append("easy ?!");

        // $this->getServiceLocator()->get('Configuration');
        // $this->getServiceLocator()->get('Album\Model\AlbumTable');
        $loc =  $this->getServiceLocator();
        $config =  $loc->get('ControllerLoader')->get('testfactory')->getTestParam();
        // Print it for Testing ... <span class="wp-smiley emoji emoji-wink" title=";)">;)</span>
        // print_r($config['Test']);

        $loc->get('ViewHelperManager')
            ->get('headtitle')->set($config['Test']['moduletitle']);
        $loc->get('ViewHelperManager')
            ->get('headmeta')->appendName('description', $config['Test']['moduledesc']);

        echo "<p>EventManager Tutotorial</p>";

        $callback = function($e){
        	$event  = $e->getName();
        	$params = $e->getParams();

        	printf(
        	'Handled CallbackEvent event "%s" with parameter "%s"',
        	$event,
        	json_encode($params)
        	);
        };

        // $events = new EventManager; and add use Zend\EventManager\EventManager;
        $events = $this->getEventManager();
        $events->attach('do', function($e) {
        	$event  = $e->getName();
        	$params = $e->getParams();

        	printf(
        	'Handled event "%s" with parameter "%s"',
        	$event,
        	json_encode($params)
        	);
        });

        $params = array('foo' => 'bar','baz' => 'bat');
        $events->trigger('do', null, $params); //event, target, parameter
        //print : Handled event "do" with parameter "{"foo":"bar","baz":"bat"}"

        // $events = $this->getEventManager();
        // $events->trigger("dispatch.error"); // !? pourquoi ca ne marche pas

        //1.Wildcard Attachment
        // We can attach to many events at once.
        // for example :

        $events->attach(array('these', 'are', 'event', 'names'), $callback);
        // or using ‘*’ to make $callback available in all events.
        // $events->attach('*', $callback);

        // 2.Shared Manager
        // $sharedEvent = new SharedEventManager;
        $sharedEvent = $events->getSharedManager();

        $foo = new Foo();
        $foo->getEventManager()->setSharedManager($sharedEvent);
        // $foo->getEventManager()->setSharedManager($this->getEventManager()->getSharedManager());
        // $foo->bar('bazvalue', 'batvalue');
        //print : bar called on Foo, using params {"baz":"bazvalue","bat":"batvalue"}
        // ---- an other example
        $eventTest = new EventManager();
        $eventTest->setIdentifiers("tweetId");
        // $eventTest->trigger("sendTweet", null, array("id" => 99));

        // We can call other class too, let’s assume we have two class that had virtually no knowledge of each other.
        StaticEventManager::getInstance()->attach('Album\Event\Class1', 'cls', array(new \Album\Event\Class2, 'listen'));
        $cls = new \Album\Event\Class1();
        // $cls->run();
        //print : Class2 has been called by Class1

        // 3.Short-Circuiting - cours-circuiter un evenement
        // il est possible de permettre à un listener de cours-circuiter un évènement. ce qui aura pour conséquence d’arrêter la propagation de l’évènement aux autres listeners (qui écoutent le même évènement).
        // This feature utilize if a particular result is obtained, or if a listener determines that something is wrong, or that it can return something quicker than the target.

        // if we pass new Foo() to execute() function, it should execute ‘standard execution…’ only, and stopped. Let’s check it :
        $fooShortCircuit = new \Album\Event\FooShortCircuit;
        $fooShortCircuit->getEventManager()->setSharedManager($sharedEvent);
        $fooShortCircuit->execute(new Foo()) ;
        //print : standard execution... !!! ne marche pas ?!!!
        // example 2
        // deux manières de faire :
        /*
         * $listener = function($e) {
            // ...

            if() {
            // ...
            // les autres listeners ne recevront pas l'évènement
            $e->stopPropagation(true);
            }

            return $result;
        };

        $params = array("param_1"=>"value");

        // on attache coomme d'habitude
        //
        $events->attach('do', $listener );

        // shared EM : on spécifie un context
        // $events->attach('context_name','do', $listener );

        // EvenetManager : $events->trigger('do',$params);
        // EM partagé    : $events->trigger('do', 'contexte',$params);
        };*/
        /*
         on peut aussi utiliser un callable/calback en dernier paramètre de la conftion trigger.
        si cette fonction renvoie true, la propagation de l’évènement est stoppée (les listener en priorité inférieur ne s’exécuteront pas)
        $listener = function($e) {
            // ...

            return new ViewModel($resultArray);
        };

        $params = array("param_1"=>"value");

        // on attache comme d'habitude
        //
        $events->attach('do', $listener );

        // shared EM : on spécifie un context
        //$events->attach('context_name','do', $listener );

        // si un listener retourne un ViewModel, on arrête la propagation
        // notre listener retournera true dans ce cas là
        $coursCircuit = function($result) {
            return ($result instanceof ViewModel);
        };

        // on déclenche l'évènement dans notre application
        // dès qu'un listener retourne un ViewModel on arrête la propagation

        // EvenetManager :
        $events->trigger('do',$params, $coursCircuit);

        // EM partagé    :
        //$events->trigger('do', 'contexte', $params, $coursCircuit);
         */

        // 4.Listener response Aggregation : Single class can listen to multiple events.
        $baz = new \Album\Event\Baz;
        $barListeners = new \Album\Event\Bar;
        $baz->getEventManager()->attachAggregate($barListeners);

        // $baz->get(1);

        //________________________________________________________
        echo "<hr />";
    	var_dump($this->getServiceLocator()->has("simple"));
    	$simpleClass = $this->getServiceLocator()->get("simple");
    	echo get_class($simpleClass) . "\n";

    	var_dump($this->getServiceLocator()->has("MyService"));
    	$myservice = $this->getServiceLocator()->get("MyService");

    	var_dump($this->getServiceLocator()->has("alias_simple"));
        $classalias = $this->getServiceLocator()->get("alias_simple");
        echo $classalias->getName();

    	// Lancement de l'évènement
    	$this->getEventManager()->trigger('sendTweet', null, array('content' => "content"));

    	//echo $simpleClass->getName();
    	// var_dump(class_exists("Version"));


    	/*
    	 * Zend Framework 2.2 is coming, more feature, more improvement.One of features that i like is DbTableGateway adapter for Paginator that can be used at your Table Class to make our life easier. The current ZF2 doc is using DbSelect Adapter, so now i will post an example how to use DbTableGateway Adapter.
    	 */
    	$paginated = true;
    	/* return new ViewModel(array(
    			'paginator' => $this->getAlbumTable()->fetchAll($paginated),
    	)); */
    	// grab the paginator from the AlbumTable
    	$paginator = $this->getAlbumTable()->fetchAll($paginated);
    	if($paginated) {
        	// set the current page to what has been passed in query string, or to 1 if none set
        	$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        	// set the number of items per page to 10
        	$paginator->setItemCountPerPage(10);
    	}

    	return new ViewModel(array(
    			'paginator' => $paginator
    	));


    }

    public function paginatorAction()
    {
        $select = new Select();

        $order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'id';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

        $albums = $this->getAlbumTable()->fetchAll(false, $select->order($order_by . ' ' . $order));
        $itemsPerPage = 2;

        $albums->buffer();
        $albums->current();
        // $albums->next();
        // $albums->rewind();

        $paginator = new Paginator(new paginatorIterator($albums));
        $paginator->setCurrentPageNumber($page)
                  ->setItemCountPerPage($itemsPerPage)
                  ->setPageRange(7);

        return new ViewModel(array(
                    'order_by' 	=> $order_by,
                    'order' 	=> $order,
                    'page' 		=> $page,
                    'paginator' => $paginator,
                ));

    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album', array('action'=>'add'));
        }
        $album = $this->getAlbumTable()->getAlbum($id);

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id' => $id,
            'album' => $this->getAlbumTable()->getAlbum($id)
        );
    }

    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }

    public function setTestParam($param)
    {
        $this->param  = $param;
    }

    public function getTestParam()
    {
        return $this->param;
    }

}
