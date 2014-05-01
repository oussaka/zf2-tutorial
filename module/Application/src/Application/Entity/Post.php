<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{
	/** 
	 * @ORM\Id 
	 * @ORM\Column(type="bigint", options={"unsigned"=true}) 
	 * @ORM\GeneratedValue(strategy="AUTO") 
	 */
	protected $id;
	/** 
	 * @ORM\Column(type="string", length=200) 
	 */
	protected $title;
	/**
	 * @ORM\Column(type="text") 
	 */
	protected $body;	
	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	protected $created;
	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	protected $updated;	

	/**
	 * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist"})
	 */
	protected $comments;

	public function __construct($title="", $body="")
	{
		$this->title = $title;
		$this->body = $body;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	public function addComment($text)
	{
		$this->comments[] = new Comment($this, $text);
	}	
}