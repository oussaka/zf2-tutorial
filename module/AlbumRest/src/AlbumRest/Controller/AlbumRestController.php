<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AlbumRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Album\Model\Album;
use Album\Form\AlbumForm;
use Album\Model\AlbumTable;
use Zend\View\Model\JsonModel;
use Zend\View\Helper\ViewModel;

class AlbumRestController extends AbstractRestfulController
{
	protected $albumTable;

	public function getAlbumTable()
	{
		if (!$this->albumTable) {
			$sm = $this->getServiceLocator();
			$this->albumTable = $sm->get('Album\Model\AlbumTable');
		}
		return $this->albumTable;
	}

	/**
	 * For Test This: As we do not have any views for our Controller we need a method on how to test these. For this example i am using curl to test the functionality.
     * $ curl -i -H "Accept: application/json" http://zf2-tutorial.localhost/album-rest/get-all
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::getList()
	 */
	public function getList()
	{
	    $results = $this->getAlbumTable()->fetchAll();
	    $data = array();
	    foreach($results as $result) {
	        $data[] = $result;
	    }

	    return new JsonModel(array("data" => $data));
	    // return array('data' => $data);
	}

	/**
	 *
     * Return single resource
     *
     * And run curl to see the output.
     * $ curl -i -H "Accept: application/json" http://zf2-tutorial.localhost/album-rest/1

        HTTP/1.1 200 OK
        Date: Sat, 10 Nov 2012 19:45:07 GMT
        Server: Apache/2.2.22 (Ubuntu)
        X-Powered-By: PHP/5.4.8-1~precise+1
        Content-Length: 88
        Content-Type: application/json

     *  {"content":{"data":{"id":"1","artist":"The  Military  Wives","title":"In  My  Dreams"}}}
     *
	 * @param  mixed $id
	 * @return mixed
	 */
	public function get($id) {

		$album = $this->getAlbumTable()->getAlbum($id);
		return new JsonModel(array("data" => $album));
		// return array("data" => $album);
	}

	/**
	 * For Test: $ curl -i -H "Accept: application/json" -X POST -d "artist=AC DC&title=Dirty Deeds" http://zf2-tutorial.localhost/album-rest
	 */
	public function create($data)
	{
	    $form = new AlbumForm();
	    $album = new Album();
	    $form->setInputFilter($album->getInputFilter());
	    $form->setData($data);
	    if ($form->isValid()) {
	        $album->exchangeArray($form->getData());
	        $id = $this->getAlbumTable()->saveAlbum($album);
	    }

	    return new JsonModel(array(
	        'data' => $this->get($id),
	    ));
	}

	/* (non-PHPdoc)
	 * $ curl -i -H "Accept: application/json" -X PUT -d "artist=Ac-Dc&title=Dirty Deeds" http://zf2-tutorial.localhost/album-rest/1
	 */
	public function update($id, $data)
	{
	    $data['id'] = $id;
	    $album = $this->getAlbumTable()->getAlbum($id);
	    $form  = new AlbumForm();
	    $form->bind($album);
	    $form->setInputFilter($album->getInputFilter());
	    $form->setData($data);
	    if ($form->isValid()) {
	        $id = $this->getAlbumTable()->saveAlbum($form->getData());
	    }

	    return new JsonModel(array(
	        'data' => $this->get($id),
	    ));
	}

	/*
	 * $ curl -i -H "Accept: application/json" -X DELETE http://modules.zendframework.com.dev/album-rest/7
	 */
	public function delete($id)
	{
	    $this->getAlbumTable()->deleteAlbum($id);

	    return new JsonModel(array(
	        'data' => 'deleted',
	    ));
	}

}