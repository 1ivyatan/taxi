<?php 


require_once "./assets/strings.php";
require_once "./db/db.php";


?>

<!DOCTYPE html>
<html lang="lv">
<head>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Taksometri</title>
<link rel="shortcut icon" href="./assets/images/logo.png" type="image/png">



<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha512-TPh2Oxlg1zp+kz3nFA0C5vVC6leG/6mm1z9+mA81MI5eaUVqasPLO8Cuk4gMF4gUfP5etR73rgU/8PNMsSesoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.min.js" integrity="sha512-nKXmKvJyiGQy343jatQlzDprflyB5c+tKCzGP3Uq67v+lmzfnZUi/ZT+fc6ITZfSC5HhaBKUIvr/nTLCV+7F+Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" integrity="sha512-2bBQCjcnw658Lho4nlXJcc6WkV/UxpE/sAokbXPxQNGqmNdQrWqtw26Ns9kFF/yG792pKR1Sx8/Y1Lf1XN4GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<script src="./assets/scripts/global.js"></script>
<script src="./assets/scripts/client.js"></script>

</head>
<body>
    <?php require_once "./assets/parts/notifbox.php"; ?>

<header class="bg-body-tertiary sticky-top mb-3">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index.php">
                <img src="./assets/images/logo.png" width="30" height="30" alt="Taksometrs">
                Taksometrs
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav d-flex ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#pieteiksanas"><i class="fa-solid fa-file-contract"></i> Pieteikties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#masinas"><i class="fa-solid fa-taxi"></i> Mašīnas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontakti"><i class="fa-solid fa-phone"></i> Kontakti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="themeswitch-nav" href="#"></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<div class="container">


<div class="p-5 mb-4 bg-body-tertiary rounded-3" >
    <div class="container-fluid py-5" style="background: url('./assets/images/bg1.jpg'),rgba(0, 52, 200, 0.5)  no-repeat center center fixed;; background-blend-mode: overlay;background-size: cover; ">
        <h1 class="display-5 fw-bold text-light">Tagadnei vai pasākumam.</h1>
        <p class="col-md-8 fs-4 text-light">Mēs varam Jūs apkalpot tagad vai vēlāk pasākumam, pārvadājot viesus uz pasākuma vietu.</p>
        <a href="tel:+371 29999999" class="btn btn-primary btn-lg">
            +371 29999999
        </a>
        <a class="btn btn-secondary btn-lg" href="#pieteiksanas">
            Pieteikties 
        </a>
    </div>
</div>

</div>


<div class="container" id="pieteiksanas">
    <div class="row align-items-md-stretch  row-gap-4">
        <div class="col-md-6">
            <div class="h-100 rounded-3">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Pieteikšanās</strong>
                    </div>
                    <div class="card-body ">
                        <form id="regForm">
                        <div class="col-md-12">
                            <label for="regNameField" class="form-label">Vārds*</label>
                            <input type="text" class="form-control" id="regNameField" name="name" required>
                        </div>

                        <div class="col-md-12">
                            <label for="regFnameField" class="form-label">Uzvārds*</label>
                            <input type="text" class="form-control" id="regFnameField" name="fname" required>
                        </div>

                        <div class="col-md-12">
                            <label for="regPhoneField" class="form-label">Tālrunis*</label>
                            <input type="tel" class="form-control" id="regPhoneField" name="phone" required>
                        </div>

                        <div class="col-md-12">
                            <label for="regEmailField" class="form-label">E-pasts*</label>
                            <input type="email" class="form-control" id="regEmailField" name="email" required>
                        </div>

                        <div class="col-md-12">
                            <label for="regDateField" class="form-label">Pieteikuma datums*</label>
                            <input type="date" class="form-control" id="regDateField" name="date" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="regNotesField" class="form-label">Piezīme*</label>
                            <textarea name="notes" id="regNotesField" class="form-control" ></textarea>
                        </div>

                        <div class="col-md-12">
                        </div>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-primary" id="regSaveBtn" >Pieteikties</button>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-md-6 align-self-start">
            <div class="h-100 p-3 bg-body-tertiary border rounded-3">
                <h2>Ieguvumi piesakoties</h2>
                <p>Ir iespējams organizēt pārvadāšanu vairākiem klientiem uz noteikto vietu, piemēram, uz pasākuma vietu, taču iespējams arī pieprasīt nelielas kravas pārvadāšanu un ir iespējams ar mums sarunāt Jūsu pārvadāšanas nepieciešamības.</p>
            </div>
        </div>
    </div>
</div>




<div class="container">

<div class="pt-5 my-5 pb-5 text-center bg-primary text-light border rounded-3" id="masinas">
    <h1 class="display-4 fw-bold">Mūsu mašīnas</h1>
    <div class="col-lg-6 mx-auto">

<div id="carCarousel" class="carousel slide  rounded-3 px-5 ">

  <div class="carousel-inner" >
    <?php 
    
        $cars = $db->query("SELECT `model`, `imagehex` FROM `taxi_cars` WHERE `hidden` = 0 AND `removed` = 0", true);
    
        $i = 0;

        foreach ($cars as $car):
    ?>
        <div class="carousel-item <?= ($i == 0) ? "active" : "" ?>">
            <img src="<?= $car["imagehex"] ?>" style="height: 350px; aspect-ratio: auto; 
      object-fit: contain;" class="d-block w-100" alt="...">
            <div class="carousel-caption d-md-block">
                <h2 class="badge text-bg-primary fs-6"><?= $car["model"] ?></h2>
            </div>
        </div>


    <?php $i++; endforeach; ?>

  </div>
  <button class="carousel-control-prev text-light" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" class="text-light" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

        </div>



      </div>
    </div>
</div>







<div class="container" id="kontakti">
    <div class="row align-items-md-stretch  row-gap-4">
        <div >
            <div class="h-100 rounded-3">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Kontakti</strong>
                    </div>
                    <div class="card-body ">


<ul class="list-group">
  <li class="list-group-item"><i class="fa-solid fa-phone"></i> +371 29999999</li>
  <li class="list-group-item"><i class="fa-solid fa-envelope"></i> taksometrs@fantastisksepasts.lv</li>
  <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> Liepājas iela 5, Liepāja, Latvija</li>
</ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="container">
  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="./admin/login.php" class="nav-link px-2 text-muted">Administrācijai</a></li>
      
    </ul>
    <p class="text-center text-muted">© 2025 Leons Pīgoznis</p>
  </footer>
</div>
    


</body>
</html>