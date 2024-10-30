<?php

/* 
   Media must be sent as attachment to post
*/

namespace CAF;

class Media{

    private $attachment, $id, $link, $sorted_position;

    public function __construct($attachment, $id = null, $sorted_position = null){
        $this->attachment = $attachment;
        $this->id = $this->attachment->id;
        $this->sorted_position = $sorted_position;
        
    }

    public function getName(){
        return $this->attachment->post_title;
    }

    public function getLink(){
        return $this->attachment->guid;
    }

    public function card(){
        $markup = '<div class="col-md-3"><a href="'.$this->getLink().'"><figure class="row" data-id="'.$this->id.'">
        <div class="col-4"><img src="'.$this->thumbnail().'" class="img-fluid"></div>
        <div class="col-8">'.$this->getName().'</div>
        </figure>
        </a></div>';

        return $markup;
    }

    public function getType(){
        $t = wp_check_filetype($this->getLink());
        return $t['ext'];
    }
    
    public function thumbnail(){

        $type = $this->getType();
        if($type == 'jpg' || $type == 'png' || $type == 'gif')
        $image = plugin_dir_url( __FILE__ ) . '../../public/img/picture-1.png';

        if($type == 'pdf')
        $image = plugin_dir_url( __FILE__ ) . '../../public/img/pdf-1.png';

        return $image;

     }

}