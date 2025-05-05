<?php $title = "Snowtricks - Inscription"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondInscription.webp">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;">Inscription</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section class="contact">
  <div class="container">
    <div class="row">
      <div class="custom-margin-top">
        <div class="contact__form">
          <h3></h3>
          <form action="#" id="registrationForm" enctype="multipart/form-data">
            <input name="nameRegistration" type="text" placeholder="Nom*">
            <input name="emailRegistration" type="email" placeholder="Email*">
            <input name="passwordRegistration" type="password" placeholder="Mot de passe*">
            <input name="passwordConfirmation" type="password" placeholder="Confirmez le mot de passe*">
            <label style="color: #FFFFFF" for="profilePicture">Uploadez une photo de profil (JPEG, PNG, GIF - taille maximale, 1Mo)</label>
            <input id="profilePicture" name="profilePicture" type="file" accept="image/*">
            <button type="submit" class="site-btn">S'inscrire</button>
          </form>
            <br>
            <p><strong><i><span id="messageRegistration"></span></i></strong></p>
            <br>
		  <p>Après inscription, vous recevrez un email de confirmation contenant un lien à confirmer. <br>
              N'oubliez pas de vérifier dans vos spams. <br>
              A bientôt !</p>
            <p>
                Déjà inscrit ? Rendez-vous sur <a href="/Snowtricks/login/">cette page.</a>
            </p>
        </div>
      </div>
    </div>
  </div>
</section>

	
<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>