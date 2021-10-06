<div class="text-white" style="background-color: black">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 pt-4 pr-4 pl-4">
                <p class="m-0">
                    <a href="https://t.me/Becas_Axie" target="_blank" class="text-light"><img src="../images/telegram-i2.png" alt="Facebook Icon" class="social-icon" /></a>
                    <a href="https://www.instagram.com/becas_axie/" target="_blank" class="text-light"><img src="../images/instagram-i2.png" alt="Instagram Icon" class="social-icon" /></a>
                    <a href="https://www.facebook.com/Becas.Axie" target="_blank" class="text-light"><img src="../images/facebook-i2.png" alt="Facebook Icon" class="social-icon" /></a>
                    <a href="https://twitter.com/Becas_Axie" target="_blank" class="text-light"><img src="<?= ABSPATH ?>../images/twitter-i2.png?v=<?= $cssv ?>" alt="Twitter Icon" class="social-icon" /></a>
                </p>
            </div>
            <div class="col-md-3 col-sm-6 pt-4 pr-4 pl-4">
                 <ul class="nav flex-column">
                <li class="nav-item m-0 p-0"><a href="/articulos" class="nav-link text-light pl-0 pr-0">Artículos</a></li>
                <li class="nav-item m-0 p-0"><a href="/equipo" class="nav-link text-light pl-0 pr-0">Conozca al Equipo</a></li>
                <li class="nav-item m-0 p-0"><a href="#" class="nav-link text-light pl-0 pr-0">Cont&aacute;ctanos</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-6">

            </div>
            <div class="col-md-3 col-sm-6">

            </div>
        </div>
        <div class="row">
            <p class="small p-4 m-0 w-100 text-center">
                &copy; Copyright. Todos los Derechos Reservados
            </p>
        </div>
    </div>
</div>
<?php
require_once('../app/newsletter/controllers/register.controller.php');

$Newsletter = new Newsletter\RegisterController();
$Newsletter->autoload();
?>