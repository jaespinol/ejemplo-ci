<h1>Acceso al sitio</h1>
<form method="post" action="<?php print site_url("inicio/validaUsuario"); ?>">
    <?php 
    if (!empty($error)) {
      print '<div class="alert alert-danger" role="alert">';
      print $error;
      print '</div>';
    }
    ?>
    <div class="card p-3 my-3" style="">
    <div class="form-group text-left">
      <label>* Usuario:</label> 
      <input type="text" name="usuario" id="usuario" value="<?php print set_value("usuario"); ?>" class="form-control"  placeholder="Correo electrónico">
   </div>
   <div class="form-group text-left">
      <label>* Clave de acceso:</label>
      <input type="password" name="clave" id="clave" value="" class="form-control">
    </div>
  </div>
    <div class="form-group text-left">
      <input type="submit" value="Enviar" class="btn btn-success">
      <a href="<?php print site_url('inicio/registro/'); ?>" class="btn btn-info">Registro usuario</a>
    </div>
</form>
