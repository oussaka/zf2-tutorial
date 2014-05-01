<?php

namespace Application\view\Helper;
use Zend\View\Helper\AbstractHelper;

class DisplayAvatar extends AbstractHelper
{
    public function __invoke($img = null, $params = array() )
    {
        $avatar = $img ?: 'anonymous.jpg' ;
        $before = $params['before'] ?: '<span class="avatar">';
        $after = $params['after'] ?: '</span>';

        return $before . '<img src="images/'.$avatar.'"></img>' . $after;
    }
}