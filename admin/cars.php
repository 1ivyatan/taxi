<?php
    require_once "permscheck.php";
    is_logged_in(true);

    $title = "Mašīnas";
    $level = "..";

    require_once $level."/assets/parts/admin/head.php";
?>

<main class="container">
    <div class="float-end">
        <button type="button" class="btn btn-primary" id="addCarButton">Pievienot</button>
    </div>

    <table class="table table-hover table-striped" id="carTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Numurzīme</th>
                <th scope="col">Modelis</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="carModal" tabindex="-1" aria-labelledby="Mašīna" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Mašīna</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
            </div>

            <div class="modal-body">
                <form class="row g-3" id="carForm" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label for="carModelField" class="form-label">Modelis*</label>
                        <input type="text" class="form-control" id="carModelField" name="model" required>
                    </div>

                    <div class="col-md-6">
                        <label for="carLicenseField" class="form-label">Numurs*</label>
                        <input type="text" class="form-control" id="carLicenseField" name="license" required>
                    </div>

                    <div class="col-md-6">
                        <label for="carSeatField" class="form-label">Sēdvietas*</label>

                        <input type="number" name="seats" id="carSeatField" min="1"  max="16" value="1" class="form-control"  required>
                    </div>


                    <div class="col-md-12"  class="form-check">
                        <input class="form-check-input" type="checkbox" id="carHide" name="hidden">
                        <label for="carHide" class="form-check-label">Slēpts</label>
                    </div>

                    <input type="hidden" id="carIdField" name="id" >

                    <div class="col-md-12" id="wholeImageField">
                        <label for="carImageField" class="form-label">Attēls*</label>

                        <img src="#" class="mx-auto d-block img-thumbnail" id="carImagePreview" >

                        <br>

                        <input type="file" class="form-control" id="carImageField" accept="image/png, image/jpeg" name="imagehex">
                        
                    </div>

                </form>

                <br>

                <div class="row g-2 text-body-tertiary" id="moddedDates">
                    <small class="col-md-6" id="moddedDateStart"></small>
                    <small class="col-md-6 " id="moddedDateEdit"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aizvērt</button>
                <button type="button" class="btn btn-primary" id="carSaveBtn">Saglabāt</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= $level."/assets/scripts/car.js"; ?>"></script>

<?php include_once $level."/assets/parts/admin/foot.php" ?>