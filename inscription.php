<?php 
/** Template Name: Inscription - Page */
get_header();
?>

<main>
  <div class="page-wrapper">
    <div class="form-wrapper">
      <?php echo do_shortcode(the_content()); ?>
      
      <form method="post" action="">
          <h2>Inscription</h2>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <button type="submit" name="register">S'inscrire</button>
      </form>

      <!-- Message d'erreur ou de validation -->
      <?php
     if (isset($_POST['register'])) {
        global $wpdb;
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
    
        if (!username_exists($username) && !email_exists($email)) {
            $user_id = wp_create_user($username, $password, $email);
    
            if (!is_wp_error($user_id)) {
                wp_signon(array( // Connecte automatiquement l'utilisateur après inscription
                    'user_login'    => $username,
                    'user_password' => $password,
                    'remember'      => true,
                ));
    
                // Redirection vers la page d'accueil après inscription
                wp_redirect(home_url());
                exit;
            } else {
                echo "<p class='error-message'>Erreur lors de l'inscription : " . $user_id->get_error_message() . "</p>";
            }
        } else {
            echo "<p class='error-message'>Le nom d'utilisateur ou l'email existe déjà.</p>";
        }
    }
    if (!headers_sent()) {
        ob_start();
    }    
      ?>
    </div>
  </div>
</main>


<style>
/* Styles généraux pour le body */
body {
    font-family: 'Arial', sans-serif; /* Police simple et élégante */
    background-color: #f8f8f8; /* Fond pâle */
    margin: 0;
    padding: 0;
}

/* Conteneur principal (page-wrapper) */
.page-wrapper {
    display: flex;
    justify-content: center; /* Centre horizontalement */
    align-items: center; /* Centre verticalement */
    height: 100vh; /* Prend toute la hauteur de la fenêtre */
}

/* Formulaire */
form {
    width: 400px; /* Largeur fixe du formulaire */
    background-color: #ffffff; /* Fond blanc */
    border: 1px solid #b2d8b2; /* Bordure vert clair */
    border-radius: 4px; /* Coins légèrement arrondis */
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce */
    text-align: center;
}

/* Titre du formulaire */
form h2 {
    font-size: 24px;
    color: #333333; /* Couleur sombre pour le texte */
    margin-bottom: 20px;
    font-weight: 400;
}

/* Champs d'entrée */
form input[type="text"],
form input[type="email"],
form input[type="password"] {
    width: 90%; /* Champs occupent 90% de la largeur du formulaire */
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #e0e0e0; /* Bordure fine */
    border-radius: 4px; /* Coins arrondis */
    background-color: #f9fff9; /* Fond légèrement teinté de vert */
    font-size: 16px;
    color: #333;
}

/* Boutons */
form button {
    width: 150px; /* Taille fixe pour les boutons */
    padding: 15px;
    font-size: 16px;
    margin-top: 15px;
    border: 1px solid #b2d8b2; /* Bordure verte */
    background-color: transparent; /* Fond transparent */
    color: #333; /* Couleur du texte */
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease; /* Transition pour les effets */
}

/* Effet au survol des boutons */
form button:hover {
    background-color: #b2d8b2; /* Fond vert clair au survol */
    color: #fff; /* Texte blanc */
}

/* Conteneur des messages d'erreur */
.error-messages {
    color: red;
    list-style: none;
    padding: 0;
}
.error-messages li {
    margin-bottom: 5px;
}
</style>