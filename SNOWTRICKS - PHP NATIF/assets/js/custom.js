$(document).ready(function () {

    $('#registrationForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/AuthController.php',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    $('#messageRegistration').html(response.message);
                    setTimeout(() => {
                        window.location.href = '/Snowtricks/';
                    }, 1500);
                } else {
                    $('#messageRegistration').html(response.message);
                    alert('Erreur : ' + response.message);
                }
            },
            error: function () {
                $('#messageRegistration').html('Une erreur est survenue.');
            }
        });
    });

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let password = $('#passwordLogin').val();
        let email = $('#emailLogin').val();

        let data = {
            passwordLogin: password,
            emailLogin: email
        };

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/AuthController.php',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#messageLogin').html(response.message);
                    setTimeout(() => {
                        window.location.href = '/Snowtricks/';
                    }, 1500);
                } else {
                    $('#messageLogin').html(response.message);
                }
            },
            error: function () {
                $('#messageLogin').html('Une erreur est survenue.');
            }
        });
    });

    $(document).on('click', '.logout', function (e) {
        console.log('heu')
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/AuthController.php',
            data: { Logout: 'OK' },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location.href = '/Snowtricks/';
                } else {
                    alert('Erreur : ' + response.message);
                }
            },
            error: function () {
                alert("Une erreur est survenue.");
            }
        });
    });

    $('#forgotPswdForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let email = $('#emailForgotPswd').val();
        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/AuthController.php',
            data: { forgotPwd: 'OK', emailforgotPwd: email },
            dataType: 'json',
            success: function (response) {
                $('#messageForgotPwd').html(response.message);
            },
            error: function () {
                $('#messageForgotPwd').html('Une erreur est survenue.');
            }
        });
    });

    $('#resetPwdForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let pwd = $('#resetPwdInput').val();
        let pwdConfirm = $('#resetPwdConf').val();
        let token = $('#token').val();

        if (pwd === pwdConfirm) {
            $.ajax({
                type: 'POST',
                url: '/Snowtricks/controllers/AuthController.php',
                data: { resetPwd: 'OK', newPwd: pwd, token: token },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#messageResetPwd').html(response.message);
                        setTimeout(() => {
                            window.location.href = '/Snowtricks/';
                        }, 1500);
                    } else {
                        $('#messageResetPwd').html(response.message);
                    }
                },
                error: function () {
                    $('#messageResetPwd').html('Une erreur est survenue.');
                }
            });
        } else {
            $('#messageResetPwd').html('Les deux mots de passe doivent être identiques.');
        }

    });

    $('#createTrickForm').on('submit', function(e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        const formData = new FormData(this);

        $.ajax({
            url: '/Snowtricks/controllers/TrickController.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success) {
                    $('#messageCreateTrick').html(response.message);
                    setTimeout(() => {
                        window.location.href = '/Snowtricks/';
                    }, 2000);
                } else {
                    $('#messageCreateTrick').html(response.message);
                }
            },
            error: function () {
                $('#messageCreateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#updateTrickForm').on('submit', function(e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let trickName = $('#trickName').val();
        let trickDescription = $('#trickDescription').val();
        let trickGroupeName = $('#groupeTrickSelect').val();
        let trickId = $('#trickId').val();
        let updateTrick = $('#updateTrick').val();

        $.ajax({
            url: '/Snowtricks/controllers/TrickController.php',
            type: 'POST',
            data: {trickName: trickName, trickDescription: trickDescription, trickGroupeName: trickGroupeName, trickId: trickId, updateTrick: updateTrick},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#messageUpdateTrick').html(response.message);
                    setTimeout(() => {
                        window.location.href = '/Snowtricks/';
                    }, 2000);
                } else {
                    $('#messageUpdateTrick').html(response.message);
                }
            },
            error: function () {
                $('#messageUpdateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#deleteIllustrationForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let action = $('#DELETE_ILLUSTRATION').val();
        let idIllustration = $('#idIllustrationToDelete').val();

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/IllustrationController.php',
            data: { action: action, idIllustration: idIllustration},
            dataType: 'json',
            success: function (response) {
                $('.responseMessageUpdateTrick').html(response.message);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function () {
                $('.responseMessageUpdateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#updateIllustrationForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        const formData = new FormData(this);

        $.ajax({
            url: '/Snowtricks/controllers/IllustrationController.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('.responseMessageUpdateTrick').html(response.message);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function () {
                $('.responseMessageUpdateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#deleteVideoForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let action = $('#DELETE_VIDEO').val();
        let idVideo = $('#idVideoToDelete').val();

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/VideoController.php',
            data: { action: action, idVideo: idVideo},
            dataType: 'json',
            success: function (response) {
                $('.responseMessageUpdateTrick').html(response.message);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function () {
                $('.responseMessageUpdateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#updateVideoForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let action = $('#UPDATE_VIDEO').val();
        let idVideo = $('#idVideoToUpdate').val();
        let embedCodeVideo = $('#video').val();
        let idTrick = $('#idTrickToUpdate').val();

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/VideoController.php',
            data: { action: action, idVideo: idVideo, embedCodeVideo: embedCodeVideo, idTrick: idTrick},
            dataType: 'json',
            success: function (response) {
                $('.responseMessageUpdateTrick').html(response.message);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function () {
                $('.responseMessageUpdateTrick').html('Une erreur est survenue.');
            }
        });
    });

    $('#deleteTrickForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let idTrick = $('#idTrickToDelete').val();
        let action = $('#DELETE_TRICK').val();

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/TrickController.php',
            data: { action: action, idTrick: idTrick},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location.href = '/Snowtricks/';
                } else {
                    alert('Erreur : ' + response.message);
                }
            },
            error: function () {
                alert("Une erreur est survenue.");
            }
        });
    });

    $('#commentaireForm').on('submit', function (e) {
        e.preventDefault();

        loader($(this).find('button[type="submit"]'));

        let action = $('#comment').val();
        let content = $('#content').val();
        let trickId = $('#trickId').val();

        $.ajax({
            type: 'POST',
            url: '/Snowtricks/controllers/MessageController.php',
            data: { action: action, content: content, trickId: trickId},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location.href = '/Snowtricks/';
                } else {
                    alert('Erreur : ' + response.message);
                }
            },
            error: function () {
                alert("Une erreur est survenue.");
            }
        });

    })

    function loader (button) {
        button.html('<img src="/Snowtricks/assets/img/loader.svg" alt="Chargement..." style="height: 15px;">');
        button.prop("disabled", true);
    }

    //Page updateTrick
    $(document).on('click', 'a.updateTrick', function () {
        let path = $(this).data('path');
        let id = $(this).data('id');
        $('.previewImage').attr('src', path);
        $('#idIllustrationToDelete, #idIllustrationToUpdate, #idVideoToDelete, #idVideoToUpdate').val(id);
    });

    $(document).on('click', 'a.homeTrick', function () {
        let trickName = $(this).data('trickname');
        let trickId = $(this).data('trickid');
        $('#deleteTrickName').html('<b>' + trickName + '</b>');
        $('#idTrickToDelete').val(trickId);
    });

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

});

