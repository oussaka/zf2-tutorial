<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
	/** 
	 * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") 
	 */
	protected $id;
	/**
	 * @ORM\Column(type="text") 
	 */
	protected $comment;
	/**
	 * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
	 */
	protected $post;

	public function __construct(Post $post, $text)
	{
		$this->post = $post;
		$this->comment = $text;
	}	
}