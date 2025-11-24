<?php
    require_once "permscheck.php";
    is_logged_out();

    $title = "Pieteikšanās";
    $level = "..";
    require_once $level."/assets/parts/admin/head-min.php";
?>


<main class="container mt-5">
<div class="row justify-content-center"> 
<div class="col-md-10">


<div class="card">
<div class="card-header">
    <strong>Pieteikšanās</strong>
</div>


<div class="card-body ">


<form method="post" action="<?= $level."/admin/auth.php" ?>">
    <?php require_once $level."/assets/parts/sessionmessageboxes/err.php"; ?>

    <div class="form-row">
        <div class="col-auto mb-2">
            <label for="usr">Lietotājvārds</label>

            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        &nbsp;
                        <i class="fa-solid fa-user"></i>
                        &nbsp;
                    </div>
                </div>

                <input type="text" name="usr" id="usr" class="form-control" required>
            </div>
        </div>

    </div>

    <div class="form-row">
        <div class="col-auto mb-2">
            <label for="passwd">Parole</label>

            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        &nbsp;
                        <i class="fa-solid fa-lock"></i>
                        &nbsp;
                    </div>
                </div>

                <input type="password" name="passwd" id="passwd" class="form-control" required>
            </div>
        </div>
    </div>

    
    <div class="row g-2 text-body-tertiary">
        <small class="col-md-6">
            <button class="btn btn-primary" type="submit">Pieteikties</button>
        </small>
        <small class="col-md-6">
            <a href="../index.php" class="btn btn-secondary float-end">Atgriezties</a>
        </small>
    </div>
</form>


</div>
</div>

</div>
</div>
</main>

<?php include_once $level."/assets/parts/admin/foot.php" ?>