<?php $title = "Snowtricks - Réinitialisation du mot de passe"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondResetPwd.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;">Réinitialisation du mot de passe</h2>
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
                        <form action="#" id="resetPwdForm">
                            <input id="resetPwdInput" name="resetPwdInput" type="password" placeholder="Nouveau mot de passe">
                            <input id="resetPwdConf" name="resetPwdConf" type="password" placeholder="Confirmation du mot de passe">
                            <input type="hidden" id="token" value="<?= $_GET['token']; ?>">
                            <button type="submit" class="site-btn">Envoyer</button>
                        </form>
                        <br>
                        <p><strong><i><span id="messageResetPwd"></span></i></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>