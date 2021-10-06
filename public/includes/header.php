
<div class="navbar navbar-expand-lg navbar-dark navbar-fixed-top" style="background-color: black">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://<?= $_SERVER['HTTP_HOST'] ?>">
                <img src="../images/logos/lunacia_logo.png?v=<?= $cssv ?>" width="250px" class="logo" alt="logo">
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#slide-navbar-collapse" aria-controls="slide-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation" style="border-color:none!important;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div  class="collapse navbar-collapse" id="slide-navbar-collapse">
            <ul class="nav navbar-nav">
                <?php
                $CategoriesList = $News->CategoriesLoad(11, 'o');
                for ($i = 0; $i <= 7; $i++) {
                    $id = $CategoriesList[$i]["cat_urlseo"] ?: $CategoriesList[$i]["cat_id"];
                    if($CategoriesList[$i]["cat_id"]):
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $SiteServer ?>/seccion/<?= $Security->escString($id) ?>"><?= $Security->escString($CategoriesList[$i]["cat_name"]) ?></a>
                    </li>
                    <?php
                    endif;
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="/equipo">Conozca al Equipo</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="/becados">Nuestros Becados</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php
require_once('../app/finance/controllers/coinmarketcap.controller.php');
$Coin = new Finance\CoinmarketcapController();
$Coin->finance_load();
?>