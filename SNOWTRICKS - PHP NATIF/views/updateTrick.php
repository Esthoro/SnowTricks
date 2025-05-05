<?php $title = "Snowtricks - Modifier un trick";?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/uploads/<?= $illustration; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;"><?= $trick->name; ?></h2>
                                <a style="padding: 10px;" class="updateTrick" href="#" data-bs-toggle="modal" data-bs-target="#updateIllustrationModal" data-path="/Snowtricks/assets/uploads/<?= $illustration; ?>" data-id="<?= $illustration->id; ?>"><i class="fa-solid fa-pencil"></i></a>
                                <a href="/Snowtricks/controllers/TrickController/?deleteTrick=OK&id=<?= $trick->id; ?>"><i class="fa-solid fa-trash-can"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php if ($trick): ?>

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
                    <div class="work__item wide__item set-bg" data-setbg="/Snowtricks/assets/uploads/<?= $illustration->path; ?>">
                        <div class="work__item__hover">
                            <ul>
                                <li><a class="updateTrick" href="#" data-bs-toggle="modal" data-bs-target="#updateIllustrationModal" data-path="/Snowtricks/assets/uploads/<?= $illustration->path; ?>" data-id="<?= $illustration->id; ?>"><i class="fa-solid fa-pencil"></i></a></li>
                                <li><a class="updateTrick" href="#" data-bs-toggle="modal" data-bs-target="#deleteIllustrationModal" data-path="/Snowtricks/assets/uploads/<?= $illustration->path; ?>" data-id="<?= $illustration->id; ?>"><i class="fa-solid fa-trash-can"></i></a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
            <?php if ($allVideos):
                foreach ($allVideos as $video): ?>
                    <div class="work__item wide__item">
                        <?= html_entity_decode($video->embedCode); ?>
                        <div class="work__item__hover">
                            <ul>
                                <li><a class="updateTrick" href="#" data-bs-toggle="modal" data-bs-target="#updateVideoModal" data-id="<?= $video->id; ?>" ><i class="fa-solid fa-pencil"></i></a></li>
                                <li><a class="updateTrick" href="#" data-bs-toggle="modal" data-bs-target="#deleteVideoModal" data-id="<?= $video->id; ?>"><i class="fa-solid fa-trash-can"></i></a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <div class="row g-0">
                <div class="custom-margin-top w-100">
                    <div class="contact__form">
                        <form action="#" id="updateTrickForm" enctype="multipart/form-data" class="w-100">
                            <input type="hidden" id="trickId" value="<?= $trick->id; ?>">
                            <input type="hidden" id="updateTrick" value="OK">
                            <input id="trickName" name="trickName" type="text" placeholder="Nom de la figure*" value="<?= $trick->name; ?>" required="">
                            <textarea id="trickDescription" name="trickDescription" placeholder="Description*" required=""><?= $trick->description; ?></textarea>
                            <?php $groups = ['butters' => 'Butters', 'grabs' => 'Grabs', 'spins' => 'Spins', 'flips' => 'Flips']; ?>
                            <select name="groupeTrickSelect" id="groupeTrickSelect">
                                <option disabled value="">Choisir un groupe*</option>
                                <?php foreach ($groups as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= ($trick->groupName === $value) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="site-btn col-6">Mettre à jour</button>
                        </form>
                        <br>
                        <a class="homeTrick" href="#" data-bs-toggle="modal" data-bs-target="#deleteTrickModal" data-trickname="<?= $trick->name; ?>" data-trickid="<?= $trick->id; ?>">Supprimer le trick</a>
                        <br>
                        <p><strong><i><span id="messageUpdateTrick"></span></i></strong></p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal update illustration -->
    <div class="modal fade" id="updateIllustrationModal" tabindex="-1" aria-labelledby="updateIllustrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateIllustrationModalLabel">Changer l'illustration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Fermer</button>
                </div>
                <div class="modal-body">
                    <form id="updateIllustrationForm" enctype="multipart/form-data">
                        <input type="hidden" name="UPDATE_ILLUSTRATION" value="OK">
                        <input type="hidden" name="idIllustrationToUpdate" id="idIllustrationToUpdate">
                        <input type="hidden" name="idTrickToUpdate" value="<?= $trick->id; ?>">
                        <div class="mb-3">
                            <img id="previewImageForUpdate" src="" alt="Illustration actuelle" class="img-fluid rounded previewImage" style="max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="mb-3">
                            <label for="illustration" class="form-label">Choisir une nouvelle illustration :</label>
                            <input type="file" class="form-control" id="illustration" name="illustration" accept="image/*" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Mettre à jour</button>
                        </div>
                    </form>
                    <br>
                    <p class="responseMessageUpdateTrick"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal delete illustration -->
    <div class="modal fade" id="deleteIllustrationModal" tabindex="-1" aria-labelledby="deleteIllustrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteIllustrationModalLabel">Supprimer l'illustration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Fermer</button>
                </div>
                <div class="modal-body">
                    <form id="deleteIllustrationForm">
                        <input type="hidden" id="DELETE_ILLUSTRATION" value="DELETE_ILLUSTRATION">
                        <input type="hidden" id="idIllustrationToDelete">
                        <div class="mb-3">
                            <img id="previewImageForDelete" src="" alt="Illustration actuelle" class="img-fluid rounded previewImage" style="object-fit: cover;">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Supprimer</button>
                        </div>
                    </form>
                    <br>
                    <p class="responseMessageUpdateTrick"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal update video -->
    <div class="modal fade" id="updateVideoModal" tabindex="-1" aria-labelledby="updateVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateVideoModalLabel">Changer la vidéo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Fermer</button>
                </div>
                <div class="modal-body">
                    <form id="updateVideoForm">
                        <input type="hidden" id="UPDATE_VIDEO" value="UPDATE_VIDEO">
                        <input type="hidden" id="idVideoToUpdate">
                        <input type="hidden" id="idTrickToUpdate" value="<?= $trick->id; ?>">
                        <div class="mb-3">
                            <label for="video">Collez la balise embed de la nouvelle vidéo :</label>
                            <textarea id="video" style="width: -webkit-fill-available;" name="video" placeholder="Collez ici le code embed de votre vidéo"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Mettre à jour</button>
                        </div>
                    </form>
                    <br>
                    <p class="responseMessageUpdateTrick"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal delete video -->
    <div class="modal fade" id="deleteVideoModal" tabindex="-1" aria-labelledby="deleteVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteVideoModalLabel">Supprimer la vidéo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Fermer</button>
                </div>
                <div class="modal-body">
                    <form id="deleteVideoForm">
                        <input type="hidden" id="DELETE_VIDEO" value="DELETE_VIDEO">
                        <input type="hidden" id="idVideoToDelete">
                        <div class="previewVideo"></div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Supprimer</button>
                        </div>
                    </form>
                    <br>
                    <p class="responseMessageUpdateTrick"></p>
                </div>
            </div>
        </div>
    </div>

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


<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>