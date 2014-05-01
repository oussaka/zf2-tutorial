<?php
namespace ZfrForum\Mapper;

use ZfrForum\Entity\Category;

interface CategoryMapperInterface
{
	/**
	 * @param  Category $category
	 * @return mixed
	 */
	public function create(Category $category);

	/**
	 * @param  Category $category
	 * @return mixed
	*/
	public function update(Category $category);
}