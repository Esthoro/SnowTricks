<?php $title = "Snowtricks - Créer un trick"; ?>

<?php ob_start(); ?>

    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="/Snowtricks/assets/img/fondUpdateTrick.png">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2 style="color:#100028;">Créer un trick</h2>
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
          <h3>Créer un trick</h3>
          <form action="#" id="createTrickForm" enctype="multipart/form-data">
            <input id="createTrick" name="createTrick" type="hidden" value="OK">
            <input id="trickName" name="trickName" type="text" placeholder="Nom de la figure*" required>
            <textarea id="trickDescription" name="trickDescription" placeholder="Description*" required></textarea>
            <select name="groupeTrickSelect" id="groupeTrickSelect">
				<option disabled value="">Choisir un groupe*</option>
				<option value="butters">Butters</option>
				<option value="grabs">Grabs</option>
				<option value="spins">Spins</option>
				<option value="flips">Flips</option>
			</select>
              <label style="color: #FFFFFF" for="trickPictures">Uploadez des photos d'illustration (JPEG, PNG, GIF - taille maximale, 1Mo chacune)</label>
              <input id="trickPictures" name="trickPictures[]" type="file" accept="image/*" multiple>
              <div id="video-container">
                  <label for="video_embed_1">Collez la balise Embed de la vidéo 1 :</label>
                  <textarea id="video_embed_1" name="video_embed[]" placeholder="Collez ici le code embed de votre vidéo"></textarea>
              </div>
              <a style="margin-bottom: 20px" class="primary-btn" href="#video-container" onclick="addVideoField()">Ajouter une vidéo</a>

              <button type="submit" class="site-btn">Créer</button>
          </form>
		    <br>
            <p><strong><i><span id="messageCreateTrick"></span></i></strong></p>
            <br>
        </div>
      </div>
    </div>
  </div>
</section>

    <script>
        let videoCount = 1;

        function addVideoField() {
            videoCount++;
            const newField = document.createElement('div');
            newField.innerHTML = `
      <label for="video_embed_${videoCount}">Collez la balise Embed de la vidéo ${videoCount} :</label>
      <textarea id="video_embed_${videoCount}" name="video_embed[]" rows="4" cols="50" placeholder="Collez ici le code embed de votre vidéo"></textarea>
    `;
            document.getElementById('video-container').appendChild(newField);
        }
    </script>

	
<?php $content = ob_get_clean(); ?>

<?php
require('layout.php'); ?>