<?php
    require_once "permscheck.php";
    is_logged_in(true);

    $title = "Pieteikumi";
    $level = "..";

    require_once $level."/assets/parts/admin/head.php";
?>

<main class="container">
    <div>
        <div class="float-start" >
            <form role="search" class="d-flex" id="searchForm">
                <input class="form-control me-2" type="search" placeholder="Vārds, tālrunis, e-pasts..." aria-label="Meklēt"/>

                <button class="btn btn-outline-primary" type="button">Meklēt</button>
            </form>
        </div>
        
        <div class="float-end">
            <button type="button" class="btn btn-primary" id="addRegButton">Pievienot</button>
        </div>
    </div>

    <table class="table table-hover table-striped" id="regTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tālrunis</th>
                <th scope="col">Vārds</th>
                <th scope="col">Stāvoklis</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</main>

<div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="Pieteikums" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pieteikums</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                </div>

                <div class="modal-body">
                    <form class="row g-3" id="regForm">
                        <div class="col-md-6">
                            <label for="regNameField" class="form-label">Vārds*</label>
                            <input type="text" class="form-control" id="regNameField" name="name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="regFnameField" class="form-label">Uzvārds*</label>
                            <input type="text" class="form-control" id="regFnameField" name="fname" required>
                        </div>

                        <div class="col-md-6">
                            <label for="regPhoneField" class="form-label">Tālrunis*</label>
                            <input type="tel" class="form-control" id="regPhoneField" name="phone" required>
                        </div>

                        <div class="col-md-6">
                            <label for="regEmailField" class="form-label">E-pasts*</label>
                            <input type="email" class="form-control" id="regEmailField" name="email" required>
                        </div>

                        <div class="col-md-6">
                            <label for="regDateField" class="form-label">Pieteikuma datums*</label>
                            <input type="date" class="form-control" id="regDateField" name="date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="regStatusField" class="form-label">Stāvoklis*</label>
                            <select id="regStatusField" class="form-select" name="status" required>
                                <option value="pending">Gaida</option>
                                <option value="viewed">Skatīts</option>
                                <option value="completed">Pabeigts</option>
                                <option value="rejected">Atlikts</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="regNotesField" class="form-label">Piezīme*</label>
                            <textarea name="notes" id="regNotesField" class="form-control" required></textarea>
                        </div>

                        <input type="hidden" name="id" id="regIdField">
                    </form>

                    

                    <br>

                    <div class="row g-2 text-body-tertiary" id="moddedDates">
                        <small class="col-md-6" id="moddedDateStart"></small>
                        <small class="col-md-6 " id="moddedDateEdit"></small>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aizvērt</button>
                <button type="button" class="btn btn-primary" id="regSaveButton">Saglabāt</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= $level."/assets/scripts/reg.js"; ?>"></script>
<?php include_once $level."/assets/parts/admin/foot.php" ?>