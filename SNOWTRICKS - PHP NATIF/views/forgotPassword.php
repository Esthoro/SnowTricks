<?php $title = "Snowtricks - Mot de passe oublié"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondMdpOublie.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;">Mot de passe oublié</h2>
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
                        <br>
                        <form action="#" id="forgotPswdForm">
                            <input id="emailForgotPswd" name="emailForgotPswd" type="email" placeholder="Email">
                            <button type="submit" class="site-btn">Envoyer</button>
                        </form>
                        <br>
                        <p><strong><i><span id="messageForgotPwd"></span></i></strong></p>
                        <br>
                        <p>
                            Vous allez recevoir un email afin de réinitialiser votre mot de passe. N'oubliez pas de vérifier vos spams !
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>