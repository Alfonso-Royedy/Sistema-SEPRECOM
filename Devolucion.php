<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Devolucion extends CI_Controller {	

	public function index(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			
			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Catalogos","Prestamos");						
			
			$this->load->view('Devolucion',$data);			
			
		}else{
			redirect($this->router->default_controller);
		}	
	}
    
    public function Obtener_Dataset_Prestamo(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			//Se armar? un json array con los registros de la consulta, este json alimentar? el datatable
			$this->load->model('devolucion_model');	
			$resPrestamo=$this->devolucion_model->Obtener_Prestamo();										
			
			if ($resPrestamo){
				while ($rowprestamo=$resPrestamo->unbuffered_row('array')){				
					foreach ($rowprestamo as $key=>$value){
						//las cadenas se deben encodear a utf8 para que el datatable muestre bien los caracteres como acentos y ?
						if (gettype($rowprestamo[$key])=="string"){													
							$rowInstitucion[$key]=utf8_encode($value);
						}else{
							$rowInstitucion[$key]=$value;
						}					
					}
					$output[]=$rowInstitucion;
				}								
				print(json_encode(array("data"=>$output)));
			}else{			
				print(json_encode(array("data"=>"")));
			}
		}else{
			redirect($this->router->default_controller);
		}
	}
	
	public function Eliminar_Prestamo(){		
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){		
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			$Operacion_Borrado_Exitosa=false;
			$this->db->trans_begin();								
			
			$this->load->model('devolucion_model');
			$Prestamo_Eliminada=$this->devolucion_model->Eliminar_Prestamo($this->input->post('id_prestamo'));															
			
			if ($Prestamo_Eliminada){
				$this->db->trans_commit();
				MostrarNotificacion("Se elimino el prestamo exitosamente","OK",true);
				$Operacion_Borrado_Exitosa=true;
			}else{
				$this->db->trans_rollback();
				MostrarNotificacion("Ocurrio un error al intentar eliminar el prestamo","Error",true);
			}
			
			echo "@".Obtener_Contador_Notificaciones();
			if ($Operacion_Borrado_Exitosa){
				echo "@T";
			}else{
				echo "@F";
			}
		}else{
			redirect($this->router->default_controller);
		}
			
	}
	
	public function Crear_Institucion(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			//los valores de tipo cadena deben decodificarse de utf8 para que lo almacena correctamente
			$this->form_validation->set_rules('institucion', 'Nombre de la institución', "required|xss_clean|strtoupper|utf8_decode",																							
												array(
													'required' => 'Debe proporcionar un %s.'
												)
											);												
				
			if ($this->form_validation->run() == FALSE){
				MostrarNotificacion("Hay errores en los datos capturados, corrija e intente de nuevo por favor","Error",true);
				echo "@".Obtener_Contador_Notificaciones();
				echo "@F";
				echo "@<div class='bg-danger' style='padding: 5px;'><b>Errores de validación:</b><br><font class='font_notif_error'>".validation_errors()."</font></div><br>";
			}else{
				$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));					
				
				$Operacion_Creacion_Exitosa=false;
				$this->db->trans_begin();								
				
				$Institucion_Creada=$this->Gestion_Instituciones_Model->Crear_Institucion($this->input->post('institucion'));
				
				if ($Institucion_Creada){
					$this->db->trans_commit();
					MostrarNotificacion("Se creó la institución exitosamente","OK",true);
					$Operacion_Creacion_Exitosa=true;
				}else{
					$this->db->trans_rollback();
					MostrarNotificacion("Ocurrio un error al intentar crear la institución","Error",true);
				}
				
				echo "@".Obtener_Contador_Notificaciones();
				if ($Operacion_Creacion_Exitosa){
					echo "@T";
				}else{
					echo "@F";
				}
			}
		}else{
			redirect($this->router->default_controller);
		}
	}
	
	public function Realizar_Devolucion(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
										
		//los valores de tipo cadena deben decodificarse de utf8 para que lo almacena correctamente

		$this->form_validation->set_rules('Encargado2', 'Nombre Encargado',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
												'required' => 'Debe proporcionar un %s.'
											)
										);
		$this->form_validation->set_rules('observacion1', 'Observaciones',"xss_clean|strtoupper|utf8_decode",	
																		
										array(
										'required' => 'Debe proporcionar un %s.'
										)
										);
		$this->form_validation->set_rules('Fecha_devolucion', 'Fecha de devolucines',"xss_clean|strtoupper|utf8_decode",	
																		
											array(
											'required' => 'Debe proporcionar un %s.'
											)
											);			
				
			if ($this->form_validation->run() == FALSE){
				MostrarNotificacion("Hay errores en los datos capturados, corrija e intente de nuevo por favor","Error",true);
				echo "@".Obtener_Contador_Notificaciones();
				echo "@F";
				echo "@<div class='bg-danger' style='padding: 5px;'><b>Errores de validación:</b><br><font class='font_notif_error'>".validation_errors()."</font></div><br>";
			}else{
				$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));					
				
				$Operacion_Edicion_Exitosa=false;
				$this->db->trans_begin();	
				
				$this->load->model('devolucion_model');
				$Devolucion_Editada = $this->devolucion_model->Editar_Devolucion($this->input->post('id_prestamo'),$this->input->post('id_producto'),$this->input->post('Encargado2'),$this->input->post('Fecha_devolucion'),$this->input->post('observacion1'),$this->input->post('id_solicitud1'),'devuelto');
				
				
				if ($Devolucion_Editada){
					$this->db->trans_commit();
					MostrarNotificacion("Se realizó la devolución exitosamente","OK",true);
					$Operacion_Edicion_Exitosa=true;
				}else{
					$this->db->trans_rollback();
					MostrarNotificacion("Ocurrio un error al intentar realizar la devolución","Error",true);
				}
				
				echo "@".Obtener_Contador_Notificaciones();
				if ($Operacion_Edicion_Exitosa){
					echo "@T";
				}else{
					echo "@F";
				}
			}
		}else{
			redirect($this->router->default_controller);
		}
	}


}
?>