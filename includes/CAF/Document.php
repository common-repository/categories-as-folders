<?php

/* 
   Documents must be bound to categories
*/

namespace CAF;

class Document{

    private $document, $categories, $id, $media_files, $sorted_position, $link;

    public function __construct($document, $categories = null, $id = null, $media_files = [], $sorted_position = null){
        $this->document = get_post($document);
        $this->categories = $categories;
        $this->id = $this->document->ID;
        $options = get_option('caf_settings');

        if($options['display_media'] == 1)
        $this->media_files = count($media_files) == 0 ? $this->load_media() : $media_files;

        $this->sorted_position = $sorted_position;
    }

    public function getDocument(){
        return $this->document;
    }
    
    public function load_media(){

        if(empty($this->document))
        return null;

        $files = get_attached_media( '', $this->document->ID );

        if(count($files) > 1 || !empty($this->document->post_content))
        $this->link = get_page_link().'?document_id='.$this->document->ID;

        if(count($files) == 1)
        $this->link = current($files)->guid;

        $media_list = [];

        foreach($files as $k => $f){
            $media_list[$k] = new Media($f);
        }
        
        return $media_list;

    }

    public function setMedia($media_items){
       array_push($this->media_files, $media_items);
       return $this->media_files;
    }

    public function getMedia(){
    
        return $this->media_files;
     }

    public function thumbnail(){
        $image = plugin_dir_url( __FILE__ ) . '../../public/img/document-1.png';
        $thumbnail = get_the_post_thumbnail_url($this->document);
        return $thumbnail == false ? $image : $thumbnail;
     }

    public function card(){
        $markup = '<div class="col-md-3"><a href="'.$this->link.'"><figure class="row" data-id="'.$this->document->ID.'">
        <div class="col-4"><img src="'.$this->thumbnail().'" class="img-fluid"></div>
        <div class="col-8">'.$this->document->post_title.'</div>
        </figure>
        </a></div>';

        return $markup;
    }

    public function load_media_files(){

        $this->setMedia(\get_post_meta($this->id,'file_1'));
        $this->setMedia(\get_post_meta($this->id,'file_2'));
        $this->setMedia(\get_post_meta($this->id,'file_3'));
        $this->setMedia(\get_post_meta($this->id,'file_4'));
        $this->setMedia(\get_post_meta($this->id,'file_5'));
        
        return $this->getMedia();
    }

    /* 
    * data to be shown on single-document
    */
    public function get_content(){
        return $this->document->post_content;
    }

    private function viewDefault(){
        if(!empty(\get_post_meta($this->id,'file_1'))){
            $this->load_media_files();
        }

        $markup = '<div class="container-plugin-categories-as-folders container">';

        if(!empty($this->get_content())){
            $markup .= '<div class="row"><div class="col-12">';
             $markup .= $this->get_content();
             //end row
             $markup .= '</div></div>';
        } else {
           // $markup .= '<p>Documento sem conteúdo.</p>';
        }


        if(is_array($this->getMedia())){
         //start row
         $markup .= '<div class="row">';
        foreach($this->media_files as $k => $m){
            $markup .= $m->card();
        }
        //end row
        $markup .= '</div>';
        } else {
            if(empty($this->get_content())){
                $markup .= '<div class="alert alert-warning">Não há conteúdo nem anexos de '.$this->document->post_title.' para exibir</div>';
            }  
        }

       
        //end container
        return $markup.'</div>';
    }



    public function view(){

        return $this->viewDefault(); 


    }
}