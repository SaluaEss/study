<?php get_header(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un lieu</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        label {
            display: block;
            font-weight: bold;
            color: #555555;
            margin: 15px 0 8px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fafafa;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #5c9f5c;
            outline: none;
        }

        textarea {
            resize: none;
            height: 120px;
        }

        .photo-upload {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }

        .photo-upload-label {
            font-weight: bold;
            color: #5c9f5c;
            cursor: pointer;
        }

        .rating {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 15px 0 20px;
        }

        .rating span {
            font-size: 30px;
            color: #cccccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating span.active {
            color: #ffcc00;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button.cancel {
            background-color: #f2f2f2;
            color: #555555;
            margin-right: 10px;
        }

        button.cancel:hover {
            background-color: #dddddd;
        }

        button.submit {
            background-color: #5c9f5c;
            color: #ffffff;
        }

        button.submit:hover {
            background-color: #4a804a;
        }

        .items-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 10px 0 20px;
        }

        .items-list label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #f9f9f9;
            padding: 5px 10px;
            border-radius: 20px;
            border: 1px solid #dddddd;
            cursor: pointer;
            font-size: 14px;
        }

        .items-list input[type="checkbox"] {
            display: none;
        }

        .items-list input[type="checkbox"]:checked + span {
            font-weight: bold;
            color: #5c9f5c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un lieu</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <!-- Nom -->
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" placeholder="Entrer le nom du lieu" required>

            <!-- Adresse -->
            <label for="adresse">Adresse *</label>
            <input type="text" id="adresse" name="adresse" placeholder="Entrer l'adresse du lieu" required>

            <!-- Description -->
            <label for="description">Description *</label>
            <textarea id="description" name="description" placeholder="Décrivez le lieu !" required></textarea>

            <!-- Items -->
            <label>Équipements disponibles :</label>
            <div class="items-list">
                <label>
                    <input type="checkbox" name="items[]" value="Wi-Fi">
                    <span>Wi-Fi</span>
                </label>
                <label>
                    <input type="checkbox" name="items[]" value="Prises">
                    <span>Prises</span>
                </label>
                <label>
                    <input type="checkbox" name="items[]" value="Ordinateurs">
                    <span>Ordinateurs</span>
                </label>
            </div>

            <!-- Photo -->
            <div class="photo-upload">
                <label for="photos" class="photo-upload-label">Ajouter des photos :</label>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">
            </div>

            <!-- Note -->
            <label>Note :</label>
            <div class="rating">
                <span data-value="1">&#9733;</span>
                <span data-value="2">&#9733;</span>
                <span data-value="3">&#9733;</span>
                <span data-value="4">&#9733;</span>
                <span data-value="5">&#9733;</span>
                <input type="hidden" name="rating" id="rating" value="3">
            </div>

            <!-- Boutons -->
            <div>
                <button type="button" class="cancel" onclick="window.location.href='<?php echo site_url('/'); ?>'">Annuler</button>
                <button type="submit" class="submit">Soumettre</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = sanitize_text_field($_POST['nom']);
            $adresse = sanitize_text_field($_POST['adresse']);
            $description = sanitize_textarea_field($_POST['description']);
            $rating = intval($_POST['rating']);
            $items = isset($_POST['items']) ? implode(', ', $_POST['items']) : 'Aucun';

            $lieu_id = wp_insert_post(array(
                'post_title'   => $nom,
                'post_content' => $description,
                'post_type'    => 'lieu',
                'post_status'  => 'publish',
                'meta_input'   => array(
                    'adresse' => $adresse,
                    'rating'  => $rating,
                    'items'   => $items,
                ),
            ));

            if ($lieu_id && !empty($_FILES['photos']['name'][0])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                foreach ($_FILES['photos']['name'] as $key => $value) {
                    $file = [
                        'name' => $_FILES['photos']['name'][$key],
                        'type' => $_FILES['photos']['type'][$key],
                        'tmp_name' => $_FILES['photos']['tmp_name'][$key],
                        'error' => $_FILES['photos']['error'][$key],
                        'size' => $_FILES['photos']['size'][$key],
                    ];

                    $upload = wp_handle_upload($file, ['test_form' => false]);
                    if (isset($upload['file'])) {
                        $attachment_id = wp_insert_attachment([
                            'post_mime_type' => $upload['type'],
                            'post_title'     => sanitize_file_name($file['name']),
                            'post_content'   => '',
                            'post_status'    => 'inherit',
                        ], $upload['file'], $lieu_id);

                        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                        wp_update_attachment_metadata($attachment_id, $attachment_data);
                        set_post_thumbnail($lieu_id, $attachment_id);
                    }
                }
            }

            if ($lieu_id) {
                echo '<p>Votre lieu a été publié avec succès !</p>';
            } else {
                echo '<p>Une erreur est survenue. Veuillez réessayer.</p>';
            }
        }
        ?>
    </div>

    <script>
        // Gestion de la notation par étoiles
        const stars = document.querySelectorAll('.rating span');
        const ratingInput = document.getElementById('rating');

        stars.forEach((star) => {
            star.addEventListener('click', () => {
                stars.forEach((s) => {
                    s.classList.remove('active');
                    if (parseInt(s.getAttribute('data-value')) <= parseInt(star.getAttribute('data-value'))) {
                        s.classList.add('active');
                    }
                });
                ratingInput.value = star.getAttribute('data-value');
            });
        });
    </script>
</body>
</html>

<?php get_footer(); ?>