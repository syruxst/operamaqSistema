<?php require('template/header.php');  require('admin/conex.php');?>
<style>
        :root {
        --main-color: #00B9FE;
        --verde: #024959;
        }
        .containe-div {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 650px;
        transform: translate(-50%, -50%);
        height: 350px;
        background-color: var(--main-color);
        display: flex;
        border-radius: 20px 20px 20px 20px;
        box-shadow: 0 0 30px #919191ae;
        }
        .left {
        width: 250px;
        height: 100%;
        background-color: var(--main-color);
        border-radius: 20px 0px 0px 20px;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: rgb(255, 255, 255) !important;
        padding: 20px;
        font-weight: 600;
        }
        .left p {
        position: absolute;
        bottom: 0;
        font-size: .6em;
        }
        .right {
        width: 400px;
        height: 100%;
        background-color: #fff;
        border-radius: 20px 20px 20px 20px;
        animation: slideRight 1s forwards;
        padding: 20px;
        }
        .inputbox {
        position: relative;
        margin: 30px 0;
        width: 100%;
        border-bottom: 1px solid var(--main-color);
        }
        .inputbox label {
        position: absolute;
        top: 50%;
        left: 5px;
        transform: translateY(-50%);
        color: var(--main-color);
        font-size: 1em;
        pointer-events: none;
        transition: 0.5s;
        }
        .inputbox input:focus ~ label,
        input:valid ~ label {
        top: -5px;
        font-size: .8em;
        }
        .inputbox input {
        width: 100%;
        height: 50px;
        background-color: transparent;
        border: none;
        outline: none;
        font-size: 1em;
        padding: 0 35px 0 5px;
        color: var(--main-color);
        }
        .inputbox i {
        position: absolute;
        right: 8px;
        font-size: 1.2em;
        top: 20px;
        color: var(--main-color)
        }
        .right input[type="submit"] {
        width: 100%;
        height: 50px;
        border: none;
        outline: none;
        background-color: var(--main-color);
        color: #fff;
        font-size: 1em;
        border-radius: 25px;
        cursor: pointer;
        transition: 0.5s;
        }
        .right input[type="submit"]:hover {
        background-color: #fff;
        color: var(--main-color);
        border: 1px solid var(--main-color);
        }
        @keyframes slideRight {
        from {
            left: -100%; /* El div comienza fuera de la pantalla a la izquierda */
        }
        to {
            left: 0; /* Al final de la animaciÃ³n, el div estarÃ¡ en su posiciÃ³n final */
        }
        }
        .globo {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 1px solid #fff;
        left: 50%;
        transform: translate(-50%);
        }
        .globo img {
            width: 100%; /* La imagen ocupará todo el espacio dentro del círculo */
            height: auto;
            border-radius: 50%; /* Hacer la imagen redonda */
        }
    @media (max-width: 700px) {
        body{
            padding: 10px;
        }
        .containe-div {
            flex-direction: column; 
            align-items: center;
            width: 400px;
            height: 600px;
        }

        .left,
        .right {
            max-width: 100%; /* Utiliza todo el ancho disponible */
        }
    }
    </style>
<body>
<section class="tres">
    <div class="containe-div">
        <div class="left">
            <div class="globo">
                <img src="https://acreditasys.tech/img/logoInicioRedondo.png" alt="Logo" width="100%" height="100%">
            </div>    
            <br> 
            <h2 class="animate__animated animate__backInLeft">OPERAMAQ</h2>  
            <label for="">Soluciones Operacionales</label> 
            <p>©2023. Todos los derechos reservados.</p>
        </div>
        <div class="animate__animated animate__backInLeft right">
            <form action="./api/log.php" method="post">
                <div class="inputbox">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="usuario" id="usuario" class="rut-input" autocomplete="off" required maxlength="12">
                    <label for="">Usuario</label>
                </div>
                <div class="inputbox">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" name="contraseña" id="contraseña" autocomplete="off" required maxlength="12">
                    <label for="">Contraseña</label>
                </div>
                <input type="submit" value="Ingresar" id="btn-login">
            </form>
        </div>
    </div>

    <div class="wave" style="height: 200px; overflow: hidden;" >
        <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;"><path d="M-3.16,91.03 C197.74,213.08 349.20,-49.85 507.00,90.05 L500.00,149.60 L-0.00,149.60 Z" style="stroke: none; fill: #fff;"></path></svg>
    </div>
</section>
<section class="button-icons">
    <a href="https://www.facebook.com/OperamaqEmpresa" target="_blank" class="fa fa-facebook-official" id="facebook"></a>
    <a href="https://api.whatsapp.com/send?phone=+56927527140&amp;text=Hola+Operamaq,+soluciones+operacionales" target="_blank" class="fa fa-whatsapp" id="whatsapp"></a>
    <a href="https://www.instagram.com/operamaq2023/" target="_blank" class="fa fa-instagram" id="instagram"></a>
</section>
<?php  require('template/foot.php'); ?>

