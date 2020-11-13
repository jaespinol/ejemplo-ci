<h1>Alumnos</h1>
<?php 
    print "<table class='table'>";
    print "<tr><th>id</th><th>Nombre</th><th>Apellidos</th><th>Nacimiento</th><th>Género</th><th>Promedio</th><th>Modificar</th><th>Borrar</th></tr>";
    foreach ($alumnos->result() as $alumno) {
      print "<tr>";
      print "<td><a href='";
      print site_url('inicio/alumnoDetalle/'.$alumno->id);
      print "'>".$alumno->id."</a></td>";
      print "<td>".$alumno->nombre."</td>";
      print "<td>".$alumno->apellidos."</td>";
      print "<td>".$alumno->nacimiento."</td>";
      print "<td>".$alumno->sexo."</td>";
      print "<td>".$alumno->promedio."</td>";
      print "<td><a href='";
      print site_url('inicio/alumnoModificar/'.$alumno->id);
      print "' class='btn btn-info'>Modificar</a></td>";
      print "<td><a href='";
      print site_url('inicio/alumnoBorrar/'.$alumno->id);
      print "' class='btn btn-danger'>Borrar</a></td>";
      print "</tr>";
    }
    print "</table>";
    print $this->pagination->create_links();
    ?>
    <a href="<?php print site_url('inicio/alumnoAlta'); ?>" class="btn btn-info">Alta de usuario</a>