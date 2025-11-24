<?php
    require_once "permscheck.php";
    is_logged_in(true);

    $title = "Lietotāji";
    $level = "..";

    if ($_SESSION[$SESSIONCREDS]["role"] == "driver") {
        header("Location: ".$level."/admin/index.php");
        die;
    }

    require_once $level."/assets/parts/admin/head.php";
?>

<main class="container">
    <div class="float-end">
        <button type="button" class="btn btn-primary" id="addUsrButton">Pievienot</button>
    </div>
    <table class="table table-hover table-striped" id="usrtable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Vārds</th>
                <th scope="col">Lietotājvārds</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</main>

<div class="modal fade" id="usrModal" tabindex="-1" aria-labelledby="Lietotājs" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Lietotājs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
            </div>

            <div class="modal-body">
                <form class="row g-3" id="usrTextForm" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label for="usrUsrField" class="form-label">Lietotājvārds*</label>
                        <input type="text" class="form-control" id="usrUsrField" name="usr" required>
                    </div>

                    <div class="col-md-6">
                        <label for="usrNameField" class="form-label">Vārds*</label>
                        <input type="text" class="form-control" id="usrNameField" name="name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="usrFnameField" class="form-label">Uzvārds*</label>
                        <input type="text" class="form-control" id="usrFnameField" name="fname" required="required">
                    </div>

                     <div class="col-md-6">
                        <label for="usrEmailField" class="form-label">E-pasts*</label>
                        <input type="email" class="form-control" id="usrEmailField" name="email" required>
                    </div>

                    <div class="col-md-6">
                        <label for="usrPhoneField" class="form-label">Tālrunis*</label>
                        <input type="tel" class="form-control" id="usrPhoneField" name="phone" required>
                    </div>

                    <div class="col-md-12" id="usrPasswdContainer">
                        <label for="usrPasswdfield" class="form-label">Jauna parole</label>
                        <input type="password" class="form-control" name="passwd" id="usrPasswdfield">
                    </div>

                    <div class="col-md-12" id="usrRoleContainer">
                        <label for="usrRoleField" class="form-label">Loma*s </label>
                        <select id="usrRoleField" class="form-select" name="role" required>
                            <option value="admin">Administrators</option>
                            <option selected value="driver">Darbinieks</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="usrDescField" class="form-label">Anotācija</label>
                        <textarea class="form-control" id="usrDescField" rows="3" name="desc" ></textarea>
                    </div>

                    <input type="hidden" id="usrIdField" name="id" >

                    <div class="col-md-12" id="wholeImageField">
                        <label for="usrImageField" class="form-label">Attēls</label>

                        <img src="#" class="mx-auto d-block img-thumbnail" id="usrImagePreview" >

                        <br>

                        <input type="file" class="form-control" id="usrImageField" accept="image/png, image/jpeg" name="imagehex">

                        <div class="form-check" id="usrImageRemoveField">
                            <input class="form-check-input" type="checkbox" value=""  name="removeimage" id="usrImageRemove">
                            <label class="form-check-label" for="usrImageRemove">
                                Noņemt attēlu
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aizvērt</button>
                <button type="button" class="btn btn-primary" id="usrSaveBtn">Saglabāt</button>
            </div>

        </div>
    </div>
</div>

<script src="<?= $level."/assets/scripts/user.js"; ?>"></script>

<?php include_once $level."/assets/parts/admin/foot.php" ?>