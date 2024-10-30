<div class="container-plugin-categories-as-folders container no-print">
    <div class="row">
        <div class="col-md-12">


<ul class="breadcrumb mt-3">
    <li>
        <a href="<?php echo site_url(); ?>">Home</a>
    </li>

    <?php if(!isset($category_id)):  ?>
    <li>
        <a href="<?php the_permalink(); ?>" class="c-title"><?php the_title(); ?></a>
    </li>
<?php endif; ?>

    <?php if(isset($category_id)):  ?>
    <?php 
    $category = get_category($category_id); 
    $parent = isset($category->parent) ? get_category( $category->parent) : null;
    ?>
    <?php if(!empty($parent) && !is_wp_error($parent)): ?>
    <li>
        <a href="<?php get_page_link(); ?>?category_id=<?php echo $parent->cat_ID; ?>" class="c-title"><?php echo $parent->name; ?></a>
    </li>
    <?php endif; ?>   
    <?php if(isset($category->name)): ?>
    <li>
        <a href="<?php get_page_link(); ?>?category_id=<?php echo $category->cat_ID ?>" class="c-title"><?php $category->name ?></a>
    </li>             
    <?php endif; ?>    
    <?php endif; ?>    
    
    <?php if(isset($document)):  ?>
    <li>
        <a href="<?php echo get_permalink(); ?>?document_id=<?php echo $document->getDocument()->ID; ?>" class="c-title"><?php echo $document->getDocument()->post_title; ?></a>
    </li>
    <?php endif; ?>   
    
    
</ul>

</div>
</div>
</div>