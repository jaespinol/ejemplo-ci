<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
    $this->load->library("form_validation");
	}
	public function index($error="")
  {
    $this->load->view("encabezado",array("titulo"=>"Login", "error"=>$error));
    $this->load->view("login");
    $this->load->view("piepagina");
  }
  public function registro()
  {
    $this->load->view("encabezado",array("titulo"=>"Registro"));
    $this->load->view("registro");
    $this->load->view("piepagina");
  }
  public function alumnos($pag=0)
  {
    $this->load->model("InicioModelo");
    //
    //Configuración del la paginación
    //
    $num = 5;
    $tot = $this->InicioModelo->numAlumnos();
    //
    //Leemos los datos
    //
    $alumnos = $this->InicioModelo->getAlumnos($pag,$num);
    //
    $this->load->library('pagination');
    //
    $config['base_url'] = site_url('Inicio/alumnos');
    $config['total_rows'] = $tot;
    $config['per_page'] = $num;

    //
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];

    $config['first_link'] = false;
    $config['last_link'] = false;

    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';

    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';

    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);
    //
    $data["alumnos"] = $alumnos;
    //
    $this->load->view("encabezado",array("titulo"=>"Alumnos"));
    $this->load->view("alumnos",$data);
    $this->load->view("piepagina");
  }
  public function verificarRegistro()
  {
    $this->form_validation->set_rules('usuario', 'Usuario', 'required|valid_email');
    $this->form_validation->set_rules('clave', 'Clave de acceso', 'required|matches[clave2]');
    $this->form_validation->set_rules('clave2', 'Clave de acceso', 'required');
    $this->form_validation->set_rules('nombre', 'Nombre', 'required');
    //
    $this->form_validation->set_message("required","Este campo es obligatorio");
    $this->form_validation->set_message("matches","Las claves de acceso no coinciden");
    $this->form_validation->set_error_delimiters("<span class='rojo'>","</span>");
    //
    if ($this->form_validation->run()) {
      //
      $data['usuario'] = $this->input->post("usuario");
      $data['clave'] = password_hash($this->input->post("clave"),1);
      $data['nombre'] = $this->input->post("nombre");
      //
      $this->load->model("inicioModelo");
      $this->inicioModelo->usuarioInsertar($data);
      redirect("inicio/index");
    } else {
      $this->registro();
    }
  }

  public function validaUsuario()
  {
    //
    //Reglas de validación
    //
    $this->form_validation->set_rules('usuario', 'Usuario', 'required');
    $this->form_validation->set_rules('clave', 'Clave de acceso', 'required');
    $this->form_validation->set_message("required","* Este campo es obligatorio");
    //
    //Validamos con run
    //
    if ($this->form_validation->run()) {
      $this->load->model("iniciomodelo");
      $usuario = $this->input->post("usuario");
      $clave = $this->input->post("clave");
      $data = $this->iniciomodelo->loginUsuario($usuario);
      $usuario = $data->result();
      //
      //Validar el password
      //
      if (count($usuario)==0) {
        $this->index("Usuario o clave de acceso erróneos");
      } else {
        if (password_verify($clave,$usuario[0]->clave)) {
          //Entra a alumnos
          $this->session->usuario = $usuario[0]->nombre;
          $this->alumnos();
        } else {
          $this->index("Usuario o clave de acceso erróneos");
        }
      }
    } else {
      $this->index("Todos los datos son requeridos");
    }
  }
  /*************************************/
  public function alumnoDetalle($id=0)
  {
    //
    //Llamamos al modelo
    //
    $this->load->model("InicioModelo");
    //
    //Leemos los datos
    //
    $alumno = $this->InicioModelo->getAlumnoId($id);
    //
    //Pasamos parametros
    //
    $data["alumno"] = $alumno->result();
    //
    //Lanzar la vista
    //
    $this->load->view("encabezado",array("titulo"=>"Detalle alumno"));
    $this->load->view("alumnoDetalle",$data);
    $this->load->view("piepagina");
  }
  public function alumnoAlta(){
    //
    //Lanzar la vista
    //
    $this->load->view("encabezado",array("titulo"=>"Alta alumno"));
    $this->load->view("alumnoAlta");
    $this->load->view("piepagina"); 
  }
  public function alumnoModificar($id)
  {
    //
    //Llamamos al modelo
    //
    $this->load->model("InicioModelo");
    //
    //Leemos los datos
    //
    $alumno = $this->InicioModelo->getAlumnoId($id);
    //
    //Pasamos parametros
    //
    $data["alumno"] = $alumno->result();
    //
    //Lanzar la vista
    //
    $this->load->view("encabezado",array("titulo"=>"Modificar alumno"));
    $this->load->view("alumnoModificar",$data);
    $this->load->view("piepagina");
    
  }
  public function alumnoBorrar($id)
  {
    //
    //Llamamos al modelo
    //
    $this->load->model("InicioModelo");
    //
    //Leemos los datos
    //
    $alumno = $this->InicioModelo->getAlumnoId($id);
    //
    //Pasamos parametros
    //
    $data["alumno"] = $alumno->result();
    //
    //Lanzar la vista
    //
    $this->load->view("encabezado",array("titulo"=>"Borrar alumno"));
    $this->load->view("alumnoBorrar",$data);
    $this->load->view("piepagina");
  }
  /*************************************/
    public function alumnoAltaVerificar()
  {
    //Configuramos las reglas
      $config = array(
      array(
        "field" => "nombre",
        "label" => "Nombre",
        "rules" => "required"
      ),
      array(
        "field" => "apellidos",
        "label" => "Apellidos",
        "rules" => "required"
      ),
      array(
        "field" => "nacimiento",
        "label" => "Fecha de nacimiento",
        "rules" => "required"
      ),
      array(
        "field" => "sexo",
        "label" => "Género",
        "rules" => "required"
      ),
      array(
        "field" => "promedio",
        "label" => "Promedio",
        "rules" => "required"
      )
    );
   //
  //Ejecutamos reglas de verificación
  //
    $this->form_validation->set_rules($config);
    $this->form_validation->set_error_delimiters("<span class='rojo'>","</span>");
    $this->form_validation->set_message("required","Este campo es obligatorio");
    //
    //Validamos
    //
    if ($this->form_validation->run()) {
      //
      //Recibimos los datos del formulario
      //
      $data['nombre'] = $this->input->post("nombre");
      $data['apellidos'] = $this->input->post("apellidos");
      $data['nacimiento'] = $this->input->post("nacimiento");
      $data['sexo'] = $this->input->post("sexo");
      $data['promedio'] = $this->input->post("promedio");
      //
      $this->load->model("InicioModelo");
      $this->InicioModelo->alumnoInsertar($data);
      redirect("inicio/alumnos");
    } else {
      //
      //Datos incorrectos o incompletos
      //
      $this->load->view("encabezado",array("titulo"=>"Alta alumno"));
      $this->load->view("alumnoAlta");
      $this->load->view("piepagina");
    }
  }
  public function alumnoActualizar()
    {
      //
      //Recibir los datos del formulario
      //
      $data['id'] = $this->input->post('id');
      $data['nombre'] = $this->input->post("nombre");
      $data['apellidos'] = $this->input->post("apellidos");
      $data['nacimiento'] = $this->input->post("nacimiento");
      $data['sexo'] = $this->input->post("sexo");
      $data['promedio'] = $this->input->post("promedio");
      //
      $this->load->model("InicioModelo");
      $this->InicioModelo->alumnoActualizar($data);
      redirect("inicio/alumnos");
    }
    public function alumnoBorrarRegistro()
    {
      $data['id'] = $this->input->post('id');
      //
      $this->load->model("InicioModelo");
      $this->InicioModelo->alumnoBorrar($data);
      redirect("inicio/alumnos");
    }
}
