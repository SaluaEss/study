<?php 
/** Template Name: Connexion - Page */
get_header();
?>

<main>
  <div class="page-wrapper">
    <div class="form-wrapper">
      <form method="post" action="">
          <h2>Connexion</h2>
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input type="password" name="password" placeholder="Mot de passe" required>
          <button type="submit" name="login">Se connecter</button>
      </form>

      <?php
      if (isset($_POST['login'])) {
          $creds = array(
              'user_login'    => sanitize_text_field($_POST['username']),
              'user_password' => $_POST['password'],
              'remember'      => true,
          );

          $user = wp_signon($creds, false);

          if (is_wp_error($user)) {
              echo "<p class='error-message'>Nom d'utilisateur ou mot de passe incorrect.</p>";
          } else {
              wp_redirect(home_url('/mon-compte')); // Redirection après connexion
              exit;
          }
      }
      ?>
    </div>
  </div>
</main>

<style>
/* Reprend le style général que nous avons déjà créé */
.page-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.form-wrapper {
    width: 400px;
    background-color: #ffffff;
    border: 1px solid #b2d8b2;
    border-radius: 4px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form input[type="text"],
form input[type="password"] {
    width: 90%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background-color: #f9fff9;
    font-size: 16px;
}

form button {
    width: 150px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #b2d8b2;
    background-color: transparent;
    color: #333;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

form button:hover {
    background-color: #b2d8b2;
    color: #fff;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}
</style>
