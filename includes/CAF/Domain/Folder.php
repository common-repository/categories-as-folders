<?php

/* 
Categories must be shown as folders with posts inside
*/

namespace CAF\Domain; 

use CAF\Folder as F;

class Folder extends F{

    public function __construct($category_id, 
    $link = null,
    $folders = null,
    $documents = null,
    $parent = null, 
    $sorted_position = null){
        
        parent::__construct($category_id, 
        $link,
        $folders,
        $documents,
        $parent, 
        $sorted_position);
        
    }
   
    public function view($group_by = null){
        $this->setViewData();
        return parent::getViewDisplay($group_by);
    }

}