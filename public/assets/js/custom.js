$(document).ready(function () {

    $('[class^="flash-"]').each(function() {
        const message = $(this).data('message');
        const type = $(this).attr('class').replace('flash-', '');
        showFlashMessage(type, message)})

    $(document).on('click', '#toggleGallery', function (e) {
        e.preventDefault();
        let gallery = $('#workSection');
        if (gallery.is(':visible')) {
            gallery.hide();
        } else {
            gallery.show();
            $('.work__gallery').masonry('layout');
        }
    });

    // Pour ajouter des illustrations/vidéos lors de la création d'un trick

    let imageWrapper = $('#trick_images_wrapper');
    let imagePrototype = $('#trick_images').data('prototype');
    let imageIndex = imageWrapper.find('input').length;

    $('#add_image_btn').on('click', function(e) {
        e.preventDefault();
        let newForm = imagePrototype.replace(/__name__/g, imageIndex);
        let newElement = $('<div></div>').html(newForm);
        imageWrapper.append(newElement);
        imageIndex++;
    });

    let videoWrapper = $('#trick_videos_wrapper');
    let videoPrototype = $('#trick_videos').data('prototype');
    let videoIndex = videoWrapper.find('input').length;

    $('#add_video_btn').on('click', function(e) {
        e.preventDefault();
        let newForm = videoPrototype.replace(/__name__/g, videoIndex);
        let newElement = $('<div></div>').html(newForm);
        videoWrapper.append(newElement);
        newElement.find('input').attr('placeholder', 'Code embed de la vidéo');
        videoIndex++;
    });

    //Suppression d'un trick page d'accueil
    let trickIdToDelete = null;

    $('.homeTrick').on('click', function () {
        trickIdToDelete = $(this).data('trickid');
        const trickName = $(this).data('trickname');

        $('#deleteTrickName').text(`Voulez-vous vraiment supprimer le trick "${trickName}" ?`);
        $('#idTrickToDelete').val(trickIdToDelete);
    });

    $('#deleteTrickForm').on('submit', function (e) {
        e.preventDefault();

        const trickId = $('#idTrickToDelete').val();

        $.ajax({
            url: `/trick/${trickId}/delete`,
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            contentType: 'application/json',
            success: function () {
                $(`[data-trickid="${trickId}"]`).closest('.mix').remove();
                $('#deleteTrickModal').modal('hide');
                showFlashMessage('success', 'Trick supprimé avec succès.');
                //Si on supprime un trick depuis la page du trick lui-même, retourner sur la page d'accueil
                if (window.location !== '/') {
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 3000);
                }
            },
            error: function () {
                showFlashMessage('error', 'Une erreur est survenue lors de la suppression.');
            }
        });
    });

    // Affichage d'une illustration et insertion de l'id dans le modal de mise à jour/suppression des images/vidéos
    $('.updateIllustration, .deleteIllustration, .updateVideo, .deleteVideo').on('click', function(e) {
        e.preventDefault();

        const targetModal = $(this).data('bs-target');
        const imagePath = $(this).data('path');
        const id = $(this).data('id');

        if (targetModal === '#updateIllustrationModal' || targetModal === '#deleteIllustrationModal') {
            const previewImageSelector = targetModal === '#updateIllustrationModal' ? '#previewImageForUpdate' : '#previewImageForDelete';
            const idInputSelector = targetModal === '#updateIllustrationModal' ? '#idIllustrationToUpdate' : '#idIllustrationToDelete';

            $(previewImageSelector).attr('src', imagePath);
            $(idInputSelector).val(id);
        }

        if (targetModal === '#updateVideoModal' || targetModal === '#deleteVideoModal') {
            const idInputSelector = targetModal === '#updateVideoModal' ? '#idVideoToUpdate' : '#idVideoToDelete';
            $(idInputSelector).val(id);
        }
    });

    $('#updateIllustrationForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/illustration/update',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                showFlashMessage('success', 'Illustration modifiée avec succès.');
                setTimeout(() => {
                    window.location.reload();
                    }, 3000);
                },
            error: function() {
                showFlashMessage('error', 'Une erreur est survenue lors de la mise à jour.');
            }
        });
    });

    $('#deleteIllustrationForm').on('submit', function(e) {
        e.preventDefault();

        const illustrationId = $('#idIllustrationToDelete').val();

        $.ajax({
            url: '/illustration/delete',
            method: 'POST',
            data: {
                id: illustrationId
            },
            success: function() {
                showFlashMessage('success', 'Illustration supprimée avec succès.');
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            },
            error: function() {
                showFlashMessage('error', 'Une erreur est survenue lors de la suppression.');
            }
        });
    });

    $('#deleteVideoForm').on('submit', function(e) {
        e.preventDefault();

        const videoId = $('#idVideoToDelete').val();

        $.ajax({
            url: '/video/delete',
            method: 'POST',
            data: {
                id: videoId
            },
            success: function() {
                showFlashMessage('success', 'Vidéo supprimée avec succès.');
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            },
            error: function() {
                showFlashMessage('error', 'Une erreur est survenue lors de la suppression.');
            }
        });
    });

    $('#updateVideoForm').on('submit', function(e) {
        e.preventDefault();

        const videoId = $('#idVideoToUpdate').val();
        const videoEmbedCode = $('#video').val();

        $.ajax({
            url: '/video/update',
            method: 'POST',
            data: {
                id: videoId,
                video: videoEmbedCode
            },
            success: function(response) {
                showFlashMessage('success', response);
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Erreur inconnue.';
                showFlashMessage('error', errorMsg);
            }
        });
    });

    $('#updateTrickForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/trick/update',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showFlashMessage('success', 'Figure mise à jour avec succès.');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 3000);
                }
            },
            error: function() {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Erreur inconnue.';
                showFlashMessage('error', errorMsg);            }
        });
    });

    $('#commentaireForm').on('submit', function (e) {
        e.preventDefault();

        const trickId = $('#trickId').val();
        const message = $('#content').val();

        $.ajax({
            url: '/message/create',
            method: 'POST',
            data: {
                id: trickId,
                message: message
            },
            success: function(response) {
                showFlashMessage('success', response.success);
                $("#messages").load(window.location.href + " #messages > *");
                $('#content').val('');
            },
            error: function() {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Erreur inconnue.';
                showFlashMessage('error', errorMsg);
            }
        });
    });


});

function showFlashMessage(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const flash = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
        </div>
    `);

    $('#flash-message-container').append(flash);

    setTimeout(() => {
        flash.alert('close');
    }, 3000);
}