<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Snowtricks">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>

    <link href="/Snowtricks/favicon.png" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="/Snowtricks/assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="/Snowtricks/assets/css/custom.css" type="text/css">
	
	<script src="https://kit.fontawesome.com/282a5313bd.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="/Snowtricks/"><img src="/Snowtricks/assets/img/logo.png" alt="Logo"></a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="header__nav__option">
                    <nav class="header__nav__menu mobile-menu">
                        <ul>
                            <li class="active"><a href="/Snowtricks/">Home</a></li>
                            <li class="active"><a href="/Snowtricks/#portfolio_tricks_filters">Tricks</a></li>
                            <?= isConnected() ?
                                '<li class="active"><a href="/Snowtricks/createTrick/">Ajouter un trick</a></li>' .
                                '<li class="active"><a class="logout" href="#">Logout</a></li>' :
                                '<li class="active"><a href="/Snowtricks/login/">Se connecter/S\'inscrire</a></li>'; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>

<main>

    <?= $content ?>

</main>

<footer>
    <div class="container">
        <div class="footer__copyright">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="footer__copyright__text">Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        All rights reserved | This template is made with
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-footer d-flex justify-content-around p-3">
        <button class="btn text-secondary text-center">
            <a href="/Snowtricks/"><i class="fas fa-home fa-lg"></i></a>
        </button>
        <button class="btn text-secondary text-center">
            <a href="/Snowtricks/#portfolio_tricks_filters"><i class="fa-solid fa-person-snowboarding"></i></a>
        </button>
        <button class="btn text-secondary text-center">
            <?= isConnected() ?
                '<a class="logout" href="#"><i class="fa-solid fa-right-from-bracket"></i></a>' :
                '<a href="/Snowtricks/login/"><i class="fas fa-user fa-lg"></i></a>'; ?>
        </button>
    </div>
</footer>


<!-- Js Plugins -->
<script src="/Snowtricks/assets/js/jquery-3.3.1.min.js"></script>
<script src="/Snowtricks/assets/js/bootstrap.min.js"></script>
<script src="/Snowtricks/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/Snowtricks/assets/js/mixitup.min.js"></script>
<script src="/Snowtricks/assets/js/masonry.pkgd.min.js"></script>
<script src="/Snowtricks/assets/js/jquery.slicknav.js"></script>
<script src="/Snowtricks/assets/js/owl.carousel.min.js"></script>
<script src="/Snowtricks/assets/js/main.js"></script>
<script src="/Snowtricks/assets/js/custom.js"></script>
</body>

</html>
