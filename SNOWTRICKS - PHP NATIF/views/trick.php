<?php $title = "Snowtricks - Trick"; ?>

<?php ob_start(); ?>

<section class="blog-hero spad set-bg" data-setbg="/Snowtricks/assets/uploads/<?= $firstIllustration; ?>">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="blog__hero__text">
                    <h2><?= $trick->name; ?></h2>
                    <ul>
                        <li>by <span><?= $author; ?></span></li>
                        <li>Créé le : <?= date('d/m/Y', strtotime($trick->createdAt)); ?></li>
                        <li>Mis à jour le : <?= date('d/m/Y', strtotime($trick->updatedAt)); ?></li>
                    </ul>
                    <?php if (isConnected()): ?>
                    <div class="row" style="display: inline-flex">
                        <a href="/Snowtricks/updateTrick/?id=<?= $trick->id; ?>" class="p-3"><i class="fa-solid fa-pencil"></i></a>
                        <a href="#" class="p-3" data-bs-toggle="modal" data-bs-target="#deleteTrickModal" data-trickname="<?= $trick->name; ?>" data-trickid="<?= $trick->id; ?>"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>



<!--<section class="work">-->
<!--    <div class="work__gallery">-->
<!--        <div class="grid-sizer"></div>-->
<!--        --><?php //if ($allIllustrations):
//        foreach ($allIllustrations as $illustration): ?>
<!--        <div class="work__item wide__item set-bg" data-setbg="/Snowtricks/assets/uploads/--><?php //= $illustration->path; ?><!--"></div>-->
<!--        --><?php //endforeach;
//        endif; ?>
<!--        --><?php //if ($allVideos):
//            foreach ($allVideos as $video): ?>
<!--                <div class="work__item wide__item">-->
<!--                    --><?php //= html_entity_decode($video->embedCode); ?>
<!--                </div>-->
<!--            --><?php //endforeach;
//        endif; ?>
<!--    </div>-->
<!--</section>-->

<section class="work">
    <div class="team__btn" style="position: relative;display: flex;justify-content: center;">
        <a  id="toggleGallery" href="#" class="primary-btn">Afficher la galerie</a>
    </div>
</section>

<section id="workSection" class="work">
    <div class="work__gallery">
        <div class="grid-sizer"></div>
        <?php if ($allIllustrations):
            foreach ($allIllustrations as $illustration): ?>
                <div class="work__item wide__item set-bg" data-setbg="/Snowtricks/assets/uploads/<?= $illustration->path; ?>"></div>
            <?php endforeach;
        endif; ?>
        <?php if ($allVideos):
            foreach ($allVideos as $video): ?>
                <div class="work__item wide__item">
                    <?= html_entity_decode($video->embedCode); ?>
                </div>
            <?php endforeach;
        endif; ?>
    </div>
</section>

<div class="blog-details spad">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="blog__details__content">
                    <div class="blog__details__text">
                        <p><?= $trick->description; ?></p>
                    </div>
                    <div class="blog__details__tags">
                        <a href="#"><?= $trick->groupName; ?></a>
                        <a href="#">Créé le : <?= $trick->createdAt; ?></a>
                        <a href="#">Modifié le : <?= $trick->updatedAt; ?></a>
                    </div>

                    <?php if (isConnected()): ?>
                        <div class="blog__details__comment">
                            <h4>Laisser un message</h4>
                            <form action="#" id="commentaireForm">
                                <input type="hidden" value="comment" id="comment">
                                <input type="hidden" value="<?= $trick->id; ?>" id="trickId">
                                <textarea maxlength="1000" id="content" placeholder="Message (max. 1000 caractères)"></textarea>
                                <button type="submit" class="site-btn">ENvoyer le Message</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php if ($messages): ?>
                        <br>
                        <hr>
                    <div class="blog__details__comment">
                        <h4>Messages</h4>
                        <?php foreach ($messages as $message): ?>
                            <div class="row">
                                <div class="testimonial__item">
                                    <div class="testimonial__author">
                                        <div class="testimonial__author__pic">
                                            <?php if (!empty($message->authorPhoto)) : ?>
                                                <img src="/Snowtricks/assets/<?= htmlspecialchars($message->authorPhoto); ?>" alt="Photo de <?= htmlspecialchars($message->authorName); ?>">
                                            <?php else : ?>
                                                <i class="fa-solid fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="testimonial__author__text">
                                            <h5><?= $message->authorName; ?></h5>
                                            <span><?= $message->createdAt; ?></span>
                                        </div>
                                    </div>
                                    <div class="testimonial__text">
                                        <p><?= $message->content; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="col-lg-12 p-0">
                                <div class="team__btn" style="position: relative;display: flex;justify-content: center;">
                                    <a href="#" class="primary-btn">Load more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>
