<?php

/* 
Categories must be shown as folders with posts inside
*/

namespace CAF;

class Folder{

    protected $id, $category, $link,
    $folders,
    $documents,
    $parent, 
    $sorted_position;

    public function __construct($category_id, 
    $link = null,
    $folders = null,
    $documents = null,
    $parent = null, 
    $sorted_position = null){        
        $this->id = $category_id;
        $this->category = $this->load_category();
        $this->link = $link;
        $this->folders = $folders;
        $this->documents = $documents;
        $this->parent = $parent;
        $this->sorted_position = $sorted_position;
    }

    public function load_category(){
        return get_category($this->id);
    }

    public function thumbnail(){
        $image = plugin_dir_url( __FILE__ ) . '../../public/img/folder-1.png';
        return $image;
     }

    public function load_posts(){
        $terms = isset($this->id) ? $this->id : [];

        $args = [
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => get_post_types(),
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $terms,
                    'include_children' => false // Remove if you need posts from term 7 child terms
                ],
            ],
        ];

        return get_posts( $args );
    }

    public function load_documents(){
        $posts = $this->load_posts();
       // var_dump($posts);
        $documents = [];
        foreach($posts as $k => $p){
            $documents[$k] = new Document($p);
           // $folder[$k]->folders = $this->load_folders();
           // $folder[$k]->documents = $this->load_documents();
        }
        return $documents;
    }

    public function load_subcategories(){
        global $wpdb, $post;
        $term_id = isset($this->id) ? $this->id : 0;
        $children = $wpdb->get_results( sprintf("SELECT wt.term_id FROM ". $wpdb->prefix ."term_taxonomy AS wtt INNER JOIN ". $wpdb->prefix ."terms AS wt ON wtt.term_id = wt.term_id WHERE parent = %d ORDER BY wt.name ASC", $term_id ));
        
        return $children;
    }

    public function load_folders(){

     global $categories_as_folders_plugin;

        $categories = $this->load_subcategories();
        $folders = [];
        foreach($categories as $k => $c){

            if(!empty($categories_as_folders_plugin->getNamespace())){
                $domain_class = "CAF\\".$categories_as_folders_plugin->getNamespace()."\\Folder";
            }
    
            $folders[$c->term_id] = isset($domain_class) && class_exists($domain_class) ? new $domain_class($c->term_id) :  new Folder($c->term_id);
        
            $folders[$c->term_id]->parent = $this;
           // $folder[$k]->folders = $this->load_folders();
           // $folder[$k]->documents = $this->load_documents();
        }

        return $folders;
    }

    public function card(){

        $card_name = !empty($this->category->name) ? $this->category->name : __('Term', 'categories_as_folders').' '.$this->id;
        $markup = '<div class="col-md-3"><a href="'.get_page_link().'?category_id='.$this->id.'"><figure class="row" data-id="'.$this->id.'">
        <div class="col-4"><img src="'.$this->thumbnail().'" class="img-fluid"></div>
        <div class="col-8"><figcaption>'.$card_name .'</figcaption></div>
        </figure>
        </a></div>';

        return $markup;
    }


    protected function getViewByYear(){

        $items = [];

        foreach($this->folders as $k => $f){
            $pd = new \DateTime($f->post_date);
            $items[$pd->format('Y')][] = $f;
        }

        foreach($this->documents as $k => $f){
            $pd = new \DateTime($f->getDocument()->post_date);
            $items[$pd->format('Y')][] = $f;
        }

        $markup = '<div class="alert alert-warning">Não há itens em '.$this->category->name.'</div>';

        if(!empty($items)){
 
         $markup = '<div class="container-plugin-categories-as-folders container" id="accordion">';
       
            foreach($items as $year => $item_list){
                $markup .= '<div class="card"><div class="card-header p-0" id="heading-'.$year.'">
                <button class="collapsed btn-link btn d-block w-100" data-toggle="collapse" data-target="#collapse-'.$year.'" aria-expanded="false" aria-controls="collapse-'.$year.'"><h2 class="mt-2"><span class="dashicons dashicons-portfolio"></span> '.$year.'</h2></button></div>';
                $markup .= '<div class="collapse" id="collapse-'.$year.'" aria-labelledby="heading-'.$year.'" data-parent="#accordion">';

                $markup .= '<div class="row card-body">';
                foreach($item_list as $k => $i){
                    $markup .= $i->card();
                } 
               //end row
               $markup .= '</div>';
               //end card
               $markup .= '</div></div>';

        } 
          
        //end container
        return $markup.'</div>';
        }
       
    

    }

    protected function getViewDefault(){

        $markup = '<div class="container-plugin-categories-as-folders container">';

        if(is_array($this->folders)){
            $markup .= '<div class="row">';
            foreach($this->folders as $k => $f){
                $markup .= $f->card();
            } 
             //end row
             $markup .= '</div>';
        } else {
            $markup .= '<div class="alert alert-warning">Não há subpastas em '.$this->category_name.'</div>';
        }

                     
        if(is_array($this->documents)){
            //start row
            $markup .= '<div class="row">';
           foreach($this->documents as $k => $d){
               $markup .= $d->card();
           }
           //end row
           $markup .= '</div>';
           } else {
                $options = get_option('caf_settings');
               if($options['display_documents'] == 0)
               $markup .= '<div class="alert alert-warning">Não há documentos em '.$this->category_name.'</div>';
           }

       
        //end container
        return $markup.'</div>';
    }

    protected function setViewData(){
        $options = get_option('caf_settings');

        
        if($options['display_folders'] == 1)
        $this->folders = $this->load_folders();
        
        if($options['display_documents'] == 1)
        $this->documents = $this->load_documents();
    }

    protected function getViewDisplay($group_by){

        if($group_by == 'year'){
            return $this->getViewByYear();
        }

        return $this->getViewDefault(); 
    }

    public function view($group_by = null){


        $this->setViewData();
        return $this->getViewDisplay($group_by);
    }
}