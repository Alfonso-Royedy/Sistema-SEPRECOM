<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function index(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			
			/*
			$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc']);						
			$this->db->close();							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($rowPerfil->Dbname,$this->Seguridad_SIIA_Model->Desencriptar($rowPerfil->Password)));
			*/

			$this->load->model('producto_model');
			$data['array_producto']=$this->producto_model->Obtener_Array_Nombre_Producto();

			$this->load->model('cliente_model');
			$data['array_cliente']=$this->cliente_model->Obtener_Array_Nombre_Cliente();
			
			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Principal","");
			$this->load->view('principal',$data);
		}else{			
			redirect($this->router->default_controller);
		}														
	}
	
	public function metodo_prueba(){					
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			
			/*
			$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc']);						
			$this->db->close();							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($rowPerfil->Dbname,$this->Seguridad_SIIA_Model->Desencriptar($rowPerfil->Password)));
			*/
			
			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Prueba","");
			$this->load->view('prueba',$data);
		}else{			
			redirect($this->router->default_controller);
		}														
	}
		
}
?>