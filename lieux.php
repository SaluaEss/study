<?php 
/** Template Name: Lieux - Page */
get_header();
?>
<main>
  <style>
      body {
          margin: 0;
          font-family: Arial, sans-serif;
          background-color: #f8f8f8;
      }
      .container {
          padding: 20px;
      }
      .rectangle {
          width: 100%;
          height: 200px;
          background-color: #D7E2D1;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #333;
          font-size: 24px;
          text-align: center;
      }
      .card {
          border: 1px solid #ddd;
          border-radius: 8px;
          transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .card:hover {
          transform: scale(1.05);
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      }
      .card-title {
          font-size: 1.25rem;
          font-weight: bold;
          color: #333;
      }
      .card-text {
          font-size: 0.9rem;
          color: #555;
      }
      .btn {
          background-color: #5c9f5c;
          color: #fff;
          border: none;
          border-radius: 4px;
          padding: 8px 16px;
          text-decoration: none;
          transition: background-color 0.3s ease;
      }
      .btn:hover {
          background-color: #4a874a;
      }
  </style>

  <div class="rectangle">
      <p>Welcome to the Workspace section!<br>
         Explore and discover the best study spaces curated by students, for students.</p>
  </div>

  <div class="container my-4">
     
      <div class="row">
          <?php
          $query = new WP_Query(array(
              'post_type' => 'lieu',
              'posts_per_page' => -1,
          ));

          if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
                  $adresse = get_post_meta(get_the_ID(), 'adresse', true);
                  $rating = get_post_meta(get_the_ID(), 'rating', true);
                  $items = get_post_meta(get_the_ID(), 'items', true);
                  ?>
                  <div class="col-md-4 mb-4">
                      <div class="card shadow-sm">
                          <?php if (has_post_thumbnail()) : ?>
                              <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top img-fluid" alt="<?php the_title(); ?>" style="height: 200px; object-fit: cover;">
                          <?php endif; ?>
                          <div class="card-body">
                              <h5 class="card-title"><?php the_title(); ?></h5>
                              <p class="card-text"><strong>Adresse :</strong> <?php echo esc_html($adresse); ?></p>
                              <p class="card-text"><strong>Note :</strong> <?php echo esc_html($rating); ?> / 5</p>
                              <p class="card-text"><strong>Items :</strong> <?php echo esc_html($items); ?></p>
                              <a href="<?php the_permalink(); ?>" class="btn">Voir plus</a>
                          </div>
                      </div>
                  </div>
              <?php endwhile;
              wp_reset_postdata();
          else :
              echo '<p>Aucun lieu trouvé.</p>';
          endif;
          ?>
      </div>
  </div>
</main>
<?php get_footer(); ?>