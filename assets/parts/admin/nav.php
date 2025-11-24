<header class="bg-body-tertiary sticky-top mb-3">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index.php">
                <img src="<?= $level."/assets/images/logo.png" ?>" width="30" height="30" alt="Taksometrs">
                Taksometrs
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                        $navitems = array(
                            array(
                                "title" => "Pārskats",
                                "link" => "index",
                                "icon" => "<i class='fa-solid fa-house'></i>"
                            ),
                            array(
                                "title" => "Pieteikumi",
                                "link" => "registrations",
                                "icon" => "<i class='fa-solid fa-file-contract'></i>"
                            ),
                            array(
                                "title" => "Mašīnas",
                                "link" => "cars",
                                "icon" => "<i class='fa-solid fa-taxi'></i>"
                            ),
                            array(
                                "title" => "Lietotāji",
                                "link" => "usr",
                                "icon" => "<i class='fa-solid fa-users'></i>",
                                "role" => array("admin", "superuser")
                            ),
                        );

                        foreach ($navitems as $item):
                            if (isset($item["role"])) {
                                if (!in_array($_SESSION[$SESSIONCREDS]["role"], $item["role"])) {
                                    continue;
                                }
                            }
                                
                    ?>
                        <?php if ($item["title"] == $title): ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= $item["link"] ?>.php"><?= $item["icon"]." ".$item["title"] ?></a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $item["link"] ?>.php"><?= $item["icon"]." ".$item["title"] ?></a>
                            </li>
                        <?php endif; ?>
                    <?php 
                        endforeach;
                    ?>
                
                </ul>
                
                <ul class="navbar-nav d-flex ms-auto">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            
                            <?php if ($_SESSION[$SESSIONCREDS]["imagehex"]) : ?>
                                <img src="<?= $_SESSION[$SESSIONCREDS]["imagehex"] ?>" width="20" height="20" class="object-fit-cover rounded-circle">  
                            <?php else: ?>
                                <i class="fa-solid fa-circle-user"></i>
                            <?php endif; ?>        
                             <?= $_SESSION[$SESSIONCREDS]["usr"] ?>
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item" id="themeswitch-nav">

                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fa-solid fa-user"></i> Profils
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="destroy.php">
                                    <i class="fa-solid fa-right-from-bracket"></i> Iziet
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>