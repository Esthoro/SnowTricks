<?php $title = "Snowtricks - Connexion"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondConnexion.webp">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;">Connexion</h2>
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
            <?php if(isset($_GET['Inscription'])):
                $_GET['Inscription'] = cleanData($_GET['Inscription']);
                switch ($_GET['Inscription']):
                    case 'AC':
                        echo '<p>Votre inscription a déjà été confirmée. Vous pouvez vous connecter.</p>';
                        break;
                    case '1':
                        echo '<p>Votre inscription a été confirmée ! Vous pouvez vous connecter.</p>';
                        break;
                    default:
                        echo '<p>Erreur de confirmation de l\'inscription, veuillez contacter l\'admin.</p>';
                        break;
                endswitch;
                endif; ?>
            <br>
          <form action="#" id="loginForm">
            <input id="emailLogin" name="emailLogin" type="email" placeholder="Email">
            <input id="passwordLogin" name="passwordLogin" type="password" placeholder="Mot de passe">
            <button type="submit" class="site-btn">Se connecter</button>
          </form>
            <br>
            <p><strong><i><span id="messageLogin"></span></i></strong></p>
            <br>
            <p>
                <a href="/Snowtricks/forgotPassword/">Mot de passe oublié ?</a>
            </p>
		  <p>
              Pas encore inscrit ? Rendez-vous sur <a href="/Snowtricks/registration/">cette page.</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

	
<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>