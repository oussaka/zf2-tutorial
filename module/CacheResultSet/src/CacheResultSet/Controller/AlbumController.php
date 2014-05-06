<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CacheResultSet\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Hydrator;
use Zend\Stdlib\ArrayObject;
use CacheResultSet\Model\Album;
use Zend\Db\ResultSet\HydratingResultSet;

class AlbumController extends AbstractActionController
{
    protected $albumTable;

    public function indexAction()
    {
        $albums = $this->getAlbumTable()->fetchAll();
        // FIXME: solution ne marche pas au bureau, Zend 2.2.7 version

        // @todo: soltion à améliorer, utiliser les hydrators. http://framework.zend.com/manual/2.2/en/modules/zend.stdlib.hydrator.html
        $hydrator = new Hydrator\ArraySerializable();
        $oAlbums = null;
        foreach ($albums as $alb) {
            $oAlbums[] = $hydrator->hydrate($alb, new Album());
        }

        return new ViewModel(array(
    			'albums' => $oAlbums
    	));
    }

    public function getAlbumTable()
    {
    	if (!$this->albumTable){
    		$this->albumTable = $this->getServiceLocator()->get('CacheResultSet\Model\AlbumTable');
    	}
    	return $this->albumTable;
    }


}
