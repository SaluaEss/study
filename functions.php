<?php
// Support de thème
add_theme_support('post-thumbnails');
add_theme_support('title-tag');
add_theme_support('menus');
register_nav_menu('header', 'Menu En-tête');

// Chargement des styles et scripts
function styles_scripts() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/assets/css/app.css');
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('app-js', get_template_directory_uri() . '/assets/js/app.js', ['bootstrap-bundle'], null, true);
}
add_action('wp_enqueue_scripts', 'styles_scripts');

// Gestion de l'inscription
function custom_registration_form_handler() {
    if (isset($_POST['register'])) { // Vérifie si le formulaire est soumis
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $errors = [];

        // Vérifications
        if (username_exists($username)) {
            $errors[] = "Ce nom d'utilisateur existe déjà.";
        }
        if (email_exists($email)) {
            $errors[] = "Cet email est déjà utilisé.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit comporter au moins 6 caractères.";
        }

        // Création de l'utilisateur
        if (empty($errors)) {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                wp_signon([
                    'user_login' => $username,
                    'user_password' => $password,
                    'remember' => true,
                ]);
                wp_safe_redirect(home_url()); // Redirection après inscription
                exit;
            } else {
                $errors[] = $user_id->get_error_message();
            }
        }

        // Sauvegarde des erreurs pour affichage
        set_transient('registration_errors', $errors, 30);
    }
}
add_action('init', 'custom_registration_form_handler');

// Shortcode pour afficher le formulaire d'inscription
function custom_registration_form_shortcode() {
    $errors = get_transient('registration_errors');
    delete_transient('registration_errors'); // Supprime les erreurs après affichage

    ob_start(); ?>
    <form method="post" action="">
        <h2>Inscription</h2>
        <?php if (!empty($errors)): ?>
            <ul class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo esc_html($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="register">S'inscrire</button>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('custom_registration_form', 'custom_registration_form_shortcode');

// Redirection des utilisateurs non connectés
add_action('template_redirect', function () {
    if (!is_user_logged_in() && !is_page(['connexion', 'inscription'])) {
        wp_safe_redirect(home_url('/connexion'));
        exit;
    }
});

// Redirection après connexion
function redirect_after_login($redirect_to, $request, $user) {
    // Redirige tous les utilisateurs vers la page d'accueil après connexion
    return home_url();
}
add_filter('login_redirect', 'redirect_after_login', 10, 3);

// Redirection après soumission du formulaire de connexion
function custom_login_handler() {
    if (isset($_POST['login'])) {
        $creds = [
            'user_login'    => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember'      => isset($_POST['remember']) ? true : false,
        ];

        $user = wp_signon($creds, false);

        if (!is_wp_error($user)) {
            wp_safe_redirect(home_url()); // Redirection après connexion
            exit;
        } else {
            echo '<p class="error-message">Nom d\'utilisateur ou mot de passe incorrect.</p>';
        }
    }
}
add_action('init', 'custom_login_handler');


// formulaire
// Enregistrer un Custom Post Type pour les lieux
function register_lieu_post_type() {
  register_post_type('lieu', array(
      'labels' => array(
          'name' => 'Lieux',
          'singular_name' => 'Lieu',
          'add_new' => 'Ajouter un lieu',
          'add_new_item' => 'Ajouter un nouveau lieu',
          'edit_item' => 'Modifier le lieu',
          'new_item' => 'Nouveau lieu',
          'view_item' => 'Voir le lieu',
          'search_items' => 'Rechercher un lieu',
          'not_found' => 'Aucun lieu trouvé',
          'not_found_in_trash' => 'Aucun lieu dans la corbeille',
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'thumbnail'),
      'rewrite' => array('slug' => 'lieux'),
  ));
}
add_action('init', 'register_lieu_post_type');



