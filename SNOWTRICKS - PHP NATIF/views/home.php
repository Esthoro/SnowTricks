<?php $title = "Snowtricks - Home"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondAccueil.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <span style="color:#100028;">Pour les passionnés de snowboard</span>
                                <h2 style="color:#100028;">Snowtricks</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="team__btn">
                            <a href="#portfolio_tricks_filters" id="scrollDown" style="padding: 10px;">
                                <i class="fa-solid fa-arrow-down" style="color: #FFF;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php if ($allTricks):?>
    <section class="portfolio spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
<!--                    <p id="flashMessage"></p>-->
                    <ul class="portfolio__filter" id="portfolio_tricks_filters">
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".butters">Butters</li>
                        <li data-filter=".grabs">Grabs</li>
                        <li data-filter=".spins">Spins</li>
                        <li data-filter=".flips">Flips</li>
                    </ul>
                </div>
            </div>
            <div class="row portfolio__gallery">
                <?php foreach ($allTricks as $trick): ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 mix <?= $trick->groupName; ?>">
                        <a href="/Snowtricks/trick/<?= $trick->slug; ?>/">
                            <div class="portfolio__item">
                                <div class="portfolio__item__video set-bg" data-setbg="/Snowtricks/assets/uploads/<?= $allIllustrations[$trick->id]; ?>">
                                </div>
                                <div class="portfolio__item__text">
                                    <h4><?= $trick->name; ?></h4>
                                    <?php if (isConnected()): ?>
                                        <a style="padding: 10px;" href="/Snowtricks/updateTrick/?id=<?= $trick->id; ?>"><i class="fa-solid fa-pencil"></i></a>
                                        <a class="homeTrick" href="#" data-bs-toggle="modal" data-bs-target="#deleteTrickModal" data-trickname="<?= $trick->name; ?>" data-trickid="<?= $trick->id; ?>"><i class="fa-solid fa-trash-can"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($allTricks) >= 9) :?>
                <div class="row">
                    <div class="col-lg-12 p-0">
                        <div class="team__btn" style="position: relative;display: flex;justify-content: center;">
                            <a href="#" class="primary-btn">Load more</a>
                        </div>
                    </div>
                </div>
			    <div class="row">
                    <div class="col-lg-12 p-0">
                        <div class="team__btn">
                            <a href="#" id="scrollToTop">
						    <i class="fa-solid fa-arrow-up" style="color: #FFF;"></i>
						    </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Modal delete trick -->
    <div class="modal fade" id="deleteTrickModal" tabindex="-1" aria-labelledby="deleteTrickModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer le trick</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Fermer</button>
                </div>
                <div class="modal-body">
                    <p id="deleteTrickName"></p>
                    <form id="deleteTrickForm">
                        <input type="hidden" id="DELETE_TRICK" value="DELETE_TRICK">
                        <input type="hidden" id="idTrickToDelete">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
	
<script>
    function scrollToElement(buttonId, targetId) {
        const button = document.getElementById(buttonId);
        const target = document.getElementById(targetId);

        if (button && target) {
            button.addEventListener("click", (event) => {
                event.preventDefault();
                target.scrollIntoView({ behavior: "smooth" });
            });
        }
    }

    scrollToElement("scrollToTop", "portfolio_tricks_filters");
    scrollToElement("scrollDown", "portfolio_tricks_filters");
</script>

	
	<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>