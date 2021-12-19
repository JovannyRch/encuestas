<?php include('header.php'); ?>
<div style="min-height: 100vh; width: 100vw;">
    <h3>Inicio de sesión</h3>
    <div class="row" >
        <form class="col s6">
            <div class="row">
                <div class="input-field col s12">
                    <input placeholder="Ingresa tu boleta" id="boleta" name="boleta" type="text" class="validate">
                    <label for="boleta">Boleta</label>
                </div>
                <div class="input-field col s12">
                    <input id="password" placeholder="Ingresa tu contraseña" name="password" type="text" class="validate">
                    <label for="password">Contraseña</label>
                </div>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
        </form>
    </div>

</div>
<?php include('footer.php'); ?>