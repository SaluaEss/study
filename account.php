<?php 
/** Template Name: Account - Page */
get_header();
?>

<div class="container">
  <h1><?php the_title(); ?></h1>
</div>

<main>
  <div class="container">
    <?php echo do_shortcode(the_content()); ?>
  </div>
</main>