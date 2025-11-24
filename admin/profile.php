<?php
    require_once "permscheck.php";
    is_logged_in(true);

    $title = "Profils";
    $level = "..";

    require_once $level."/assets/parts/admin/head.php";
?>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">


    <h3>Profils</h3>
    <form class="row" id="proTextForm" enctype="multipart/form-data" autocomplete="off">

        <div class="col-md-12">
            <label for="proUsrField" class="form-label">Lietotājvārds*</label>
            <input type="text" class="form-control" id="proUsrField" name="usr" required>
        </div>

        <div class="col-md-6">
            <label for="proNameField" class="form-label">Vārds*</label>
            <input type="text" class="form-control" id="proNameField" name="name" required>
        </div>

        <div class="col-md-6">
            <label for="proFnameField" class="form-label">Uzvārds*</label>
            <input type="text" class="form-control" id="proFnameField" name="fname" required="required">
        </div>


        <div class="col-md-6">
            <label for="proEmailField" class="form-label">E-pasts*</label>
            <input type="email" class="form-control" id="proEmailField" name="email" required>
        </div>

        <div class="col-md-6">
            <label for="proPhoneField" class="form-label">Tālrunis*</label>
            <input type="tel" class="form-control" id="proPhoneField" name="phone" required>
        </div>

        <div class="col-md-12" >
            <label for="proPasswdfield" class="form-label">Jauna parole</label>
            <input type="password" class="form-control" name="passwd" id="proPasswdfield" value="">
        </div>

        <div class="col-md-12">
            <label for="proDescField" class="form-label">Anotācija</label>
            <textarea class="form-control" id="proDescField" rows="3" name="desc" ></textarea>
        </div>

        <div class="col-md-12" id="wholeImageField">
            <label for="carImageField" class="form-label">Attēls</label>

            <img src="#" class="mx-auto d-block img-thumbnail d-none" id="proImagePreview">

            <br>

            <input type="file" class="form-control" id="proImageField" accept="image/png, image/jpeg" name="imagehex">

            <div class="form-check" id="proImageRemoveField">
                <input class="form-check-input" type="checkbox" value=""  name="removeimage" id="proImageRemove">
                <label class="form-check-label" for="proImageRemove">
                    Noņemt attēlu
                </label>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="proSaveBtn">Saglabāt</button>
    </form>


        </div>
    </div>




</main>

<script src="<?= $level."/assets/scripts/profile.js"; ?>"></script>

<?php include_once $level."/assets/parts/admin/foot.php" ?>