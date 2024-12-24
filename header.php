<?php wp_head(); ?>
<!DOCTYPE html>
<html lang="en">
<body>
    <header>
        <div class="logo">
         <img src="<?php echo get_template_directory_uri();?>/Logo StudyClick/Capture_d_écran_2024-12-06_à_14.54.26-removebg-preview.png" alt="" class="img-fluid custom-img"> <!-- Ajout des classes manquantes -->
        </div>
        <?php
            wp_nav_menu([
            'theme_location' => 'header',
            'container' => false,
            'menu_class' => 'navbar-nav me-auto mb-2 mb-lg-0'
            ]);
        ?>
        
       
    </header>
</body>
</html>