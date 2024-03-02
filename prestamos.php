<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Solicitud</title>

<?php
	require "third_party/jquery.js";
	require "third_party/bootstrap.js";
	require "third_party/datatables.js";
	require "third_party/bsdatatimepicker.js";
	require "third_party/googlecharts.js";	
	require "owned/form_tweaks.js";
	require "owned/estilos_portal.php";
?>
	
</head>
<body>
	
<?php	
	$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));					
	require "owned/navigation_bar.php";
	require "owned/footer.php";			
?>		
		
	<!-- contenedor principal de la aplicaci?n -->		
	<div class='container main_div_container'>
					
		<div class='row'>
		
			<!-- Ubicación actual dentro del portal -->	
			<div class='col-md-7'>
				<ol class='breadcrumb main_breadcrumb'>
					<li><a class='color_amarillo' href='principal'><?php echo $this->config->item('mycfg_nombre_aplicacion'); ?></a></li>
					<li class='color_amarillo'>Catálogos</li>											
					<li class='active' style='color: white;'>Solicitudes</li>											
				</ol>
			</div>		
			
<?php
		require "owned/notification_center.php";

		if (isset($notificacion_exito)){
			MostrarNotificacion($notificacion_exito,"OK",true);																					
		}
		
		if (isset($notificacion_error)){
			MostrarNotificacion($notificacion_error,"Error",true);																					
		}

?>				
		</div>
		
			<div id="adminOptions" >
					<!-- Standard button -->
					<button style="margin-bottom: 15px;" type="button" id="CrearNuevo" class="btn btn-primary">Nuevo</button>
						<script>
							$(document).ready(function(){
								$("#CrearNuevo").click(function(){
									frmPrestamo.reset(); 
									//se muestra la ventana modal del formulario
									$('#modalPrestamo').modal();
									//se blanquea el div de errores del formulario
									$("#div_col_val_errors").html("");
								});
							});
						</script>

					<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
					<button style="margin-bottom: 15px;" type="button" id="EditarElemento" class="btn btn-primary">Editar</button>
							<script>
								$(document).ready(function () {
									$("#EditarElemento").click(function(){
							
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbPrestamo').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbPrestamo').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbPrestamo').DataTable().rows( rows ).data();												
											//se resetean los valores del formulario
											frmEditarPrestamo.reset();
											//se inicializan los valores del formulario
											$('#e_id_Solicitud').val(data[0].id_solicitud);
											$('#p_e_id_solicitud').html(data[0].id_solicitud);
											$('#Nombre_Solicitante1').val(data[0].profesor);
											$('#Edificio1').val(data[0].Edificio);
											$('#Tipo_Area1').val(data[0].Tipo_Area);
											$('#Id_Area1').val(data[0].Num_Area);
											$('#Encargado1').val(data[0].encargado_prest);
											$('#Fecha_solicitud1').val(data[0].fecha_prest);
											//se muestra la ventana modal del formulario
											$('#modalPrestamoEditar').modal();
											//se blanquea el div de errores del formulario
											$("#div_col_e_val_errors").html("");
										}else{
											alert('Debe elegir un registro');
										}
									});
								});
							</script>

					<!-- Indicates a successful or positive action -->
					<button style="margin-bottom: 15px;" type="button" id="EliminarElemento" class="btn btn-primary">Eliminar</button>
							<script>
								$(document).ready(function () {
									$("#EliminarElemento").click(function(){
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbPrestamo').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbPrestamo').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbPrestamo').DataTable().rows( rows ).data();												
											var respuesta = confirm('¡Está seguro que desea eliminar la Solicitud: '+data[0].id_solicitud+'?');
											if (respuesta){										
												$.ajax({
													type: "POST",
													url: "<?php echo base_url();?>index.php/prestamo/Eliminar_Solicitud",
													data: {"id_solicitud" : data[0].id_solicitud},
													success: function(msg){															
														var msg_substr = msg.split("@", 3);
														var msg_html = msg_substr[0];
														var msg_cont_notif = msg_substr[1];
														var msg_result = msg_substr[2];
														$('#div_notifications_content').html(msg_html);	
														$("#span_notif_count").html(msg_cont_notif);         
														$('#modal_notificaciones').modal();
														if (msg_result=="T"){																																	
															$('#tbPrestamo').DataTable().ajax.reload(null, false);
														}																								
													},
													error: function(){
														alert("Ocurrió error al procesar la petición al servidor.");
													}
												});
											}	
										}else{
											alert('Debe elegir un registro');
										}
									});
								});
							</script>

					<!-- Contextual button for informational alert messages -->
					<button style="margin-bottom: 15px;" type="button" id="VerElemento" class="btn btn-primary">Ver Información</button>
							<script>
								$(document).ready(function () {
									$("#VerElemento").click(function(){
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbPrestamo').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbPrestamo').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbPrestamo').DataTable().rows( rows ).data();												
											//se inicializan los valores del formulario												
											$('#p_v_id_Solicitud').html(data[0].id_solicitud);
											$('#p_v_encargado').html(data[0].encargado_prest);
											$('#p_v_Solicitante').html(data[0].nombre);
											$('#p_v_Edificio').html(data[0].Edificio);
											$('#p_v_Tipoarea').html(data[0].Tipo_Area);
											$('#p_v_N_area').html(data[0].Num_Area);
											$('#p_v_fecha').html(data[0].fecha_prest);
											//se muestra la ventana modal del formulario
											$('#modalVisualizarPrestamo').modal();
										}else{
											alert('Debe elegir un registro');
										}
									});
								});

							</script>

				</div>

				<!-- En tu vista principal.php -->
					<div 
						id="userRole" data-role="<?php echo $session_data['default_pfc_name']; ?>">
					</div>

					<script>
									// En tu script jQuery
						$(document).ready(function() {
							// Obtener el rol del usuario desde el atributo de datos
							var userRole = $("#userRole").data("role");

							// Realizar acciones basadas en el rol del usuario
							if (userRole === "Administrador") {
								// Mostrar opciones específicas para administradores
								$("#adminOptions").show();
							} else {
								// Ocultar opciones para otros roles
								$("#adminOptions").hide();
							}
						});

					</script>
	
		<!-- Espacio disponible para mostrar informaci?n del portal -->	
		<div class='row'>
			<div class='col-md-12'>
<script>															
				$(document).ready(function () {
					$("#btnGuardarNuevaInstitucion").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/instituciones/crear_institucion",
							data: $('#frmNuevaInstitucion').serialize(),
							success: function(msg){																					
								var msg_substr = msg.split("@", 4);
								var msg_html = msg_substr[0];
								var msg_cont_notif = msg_substr[1];
								var msg_result = msg_substr[2];
								var msg_val_errors = msg_substr[3];
								$('#div_notifications_content').html(msg_html);	
								$("#span_notif_count").html(msg_cont_notif);         																																																																					
								$('#modal_notificaciones').modal();								
								if (msg_result=="T"){																				
									$("#modalNuevaInstitucion").modal('hide');																				
									$('#tbInstituciones').DataTable().ajax.reload(null, false);
									$('#tbInstituciones').DataTable().page('last');
									$("#div_col_val_errors").html("");
								}else{
									$("#div_col_val_errors").html(msg_val_errors);
								}									
							},
							error: function(){
								alert("Ocurri? un error al procesar la petici?n servidor.");
							}
						});
					});
										
				});
</script>

	<!-- Ventana modal del formulario para realizar el registro de prestamo -->	
	<div class='modal fade' id='modalPrestamo'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Datos del préstamo</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("prestamo/Crear_Solicitud","id='frmPrestamo' name='frmPrestamo' role='form'"); 
								//Agregamos los campos de la llave primaria como campos de tipo hidden
						
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de quién entrega:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									

										<input style="border: none; outline: none; !important; color:orange; font-size: 17px; font-weight: bold; margin-bottom: -25px;" type="text" name='Encargado' value="<?php echo set_value('Encargado', $session_data['full_name']); ?>" readonly />

										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre del solicitante:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php
											ComboBox("Nombre_Solicitante","Nombre_Solicitante","form-control","",1,false,1,$array_cliente,"","","","Nombre del solicitante");												
?>
										</div>
									</div>
								</div>

								<div style="width: auto; display: flex; flex-wrap: wrap; align-items: flex-start;">
									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>Edificio:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
											<?php ComboBox("Edificio","Edificio","form-control","",1,false,1,array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","F"=>"F","G"=>"G"),"","","","Elige un edificio"); ?>
										</div>
									</div>
									
									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>Tipo Area:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
											<?php ComboBox("Tipo_Area","Tipo_Area","form-control","",1,false,1,array("Aula"=>"Aula","Sala CIC"=>"Sala CIC","Externa"=>"Externa","I+D+I"=>"I+D+I","Laboratorio"=>"Laboratorio","Cubículo"=>"Cubículo","Coordinacion"=>"Coordinacion","Site"=>"Site"),"","","","Elige el area"); ?>
										</div>
									</div>

									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>ID Area:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
											<?php ComboBox("Id_Area","Id_Area","form-control","",1,false,1,array("1"=>"1","2"=>"2"),"","","","id del area"); ?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Equipo o accesorio solicitado:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											ComboBox("Equipo_Solicitado1","Equipo_Solicitado1","form-control","",1,false,1,$array_producto,"","","","Elige un equipo o accesorio");												
?>
										</div>
									</div>

									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											ComboBox("Equipo_Solicitado2","Equipo_Solicitado2","form-control","",1,false,1,$array_producto,"","","","Elige un equipo o accesorio");												
?>
										</div>
									</div>

									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											ComboBox("Equipo_Solicitado3","Equipo_Solicitado3","form-control","",1,false,1,$array_producto,"","","","Elige un equipo o accesorio");												
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Fecha solicitado:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											DateEditBox("Fecha_solicitud", "Fecha_solicitud", "form-control", "", 1, 255, 255, false, "Fecha de solicitud", "");												
?>
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span> Cancelar</button>
								<button type='button'  class='btn btn-color' id='btnGuardarSolicitud' name='btnGuardarSolicitud' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>
				<!--este script realiza la accion de mostrar la ventana modal para registrar los datos del prestamo-->
					<script>
					$(document).ready(function () {
						$("#Rprestamo").click(function(){
							frmPrestamo.reset(); 
								//se muestra la ventana modal del formulario
								$('#modalPrestamo').modal();
								//se blanquea el div de errores del formulario
								$("#div_col_val_errors").html("");
						});
					});
					</script>

				
	<!-- Ventana modal del formulario para realizar el registro de prestamo -->	
	<div class='modal fade' id='modalPrestamoEditar'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Editar datos del préstamo</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("prestamo/Editar_Solicitud","id='frmEditarPrestamo' name='frmEditarPrestamo' role='form'"); 
								
									//Agregamos los campos de la llave primaria como campos de tipo hidden
									echo form_input(array("type"=>"hidden","name"=>"e_id_Solicitud","id"=>"e_id_Solicitud","value"=>""));
						
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de quién entrega:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									

										<input style="border: none; outline: none; !important; color:orange; font-size: 17px; font-weight: bold; margin-bottom: -25px;" type="text" name='Encargado1' value="<?php echo set_value('Encargado1', $session_data['full_name']); ?>" readonly />

										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Id. de la solicitud:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
											<!-- Mostramos los valores de la llave primaria como textos no editables <p></p> -->
											<p id='p_e_id_solicitud' name='p_e_id_solicitud'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre del solicitante:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php
											ComboBox("Nombre_Solicitante1","Nombre_Solicitante1","form-control","",1,false,1,$array_cliente,"","","","Nombre del solicitante");												
?>
										</div>
									</div>
								</div>

								<div style="width: auto; display: flex; flex-wrap: wrap; align-items: flex-start;">
									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>Edificio:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
											<?php ComboBox("Edificio1","Edificio1","form-control","",1,false,1,array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","F"=>"F","G"=>"G"),"","","","Elige un edificio"); ?>
										</div>
									</div>
									
									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>Tipo Area:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
										<?php ComboBox("Tipo_Area1","Tipo_Area1","form-control","",1,false,1,array("Aula"=>"Aula","Sala CIC"=>"Sala CIC","Externa"=>"Externa","I+D+I"=>"I+D+I","Laboratorio"=>"Laboratorio","Cubículo"=>"Cubículo","Coordinacion"=>"Coordinacion","Site"=>"Site"),"","","","Elige el area"); ?>
										</div>
									</div>

									<div style="flex: 0 0 auto; margin-right: 10px;">
										<div>ID Area:</div>
										<div style="width: 100px; margin-top: 5px;" class='form-group'>
											<?php ComboBox("Id_Area1","Id_Area1","form-control","",1,false,1,array("1"=>"1","2"=>"2"),"","","","id del area"); ?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Fecha solicitado:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											DateEditBox("Fecha_solicitud1", "Fecha_solicitud1", "form-control", "", 1, 255, 255, false, "Fecha de solicitud", "");												
?>
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span>Cancelar</button>
								<button type='button'  class='btn btn-color' id='btnGuardar_Edicion_Solicitud' name='btnGuardar_Edicion_Solicitud' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>
				
				<script>
					$(document).ready(function () {
					$("#btnGuardar_Edicion_Solicitud").click(function(){

						$(this).prop("disabled", true).html("<span class='glyphicon glyphicon-floppy-disk'></span> Guardando....");

						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/prestamo/Editar_Solicitud",
							data: $('#frmEditarPrestamo').serialize(),
							success: function(msg){																					
								var msg_substr = msg.split("@", 4);
								var msg_html = msg_substr[0];
								var msg_cont_notif = msg_substr[1];
								var msg_result = msg_substr[2];
								var msg_val_errors = msg_substr[3];
								$('#div_notifications_content').html(msg_html);	
								$("#span_notif_count").html(msg_cont_notif);         																																																																					
								$('#modal_notificaciones').modal();								
								if (msg_result=="T"){																				
									$("#modalPrestamoEditar").modal('hide');																				
									$('#tbPrestamo').DataTable().ajax.reload(null, false);
									$("#div_col_e_val_errors").html("");
								}else{
									$("#div_col_e_val_errors").html(msg_val_errors);
								}			
								$("#btnGuardar_Edicion_Solicitud").prop("disabled", false).html("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar");	
							},
							error: function(){
								alert("Ocurrió un error al procesar la petición servidor.");
								$("#btnGuardar_Edicion_Solicitud").prop("disabled", false).html("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar");
							}
						});
					});
					
					
				});

				</script>
				
				<!-- Ventana modal  para visualizar la informacion de la información -->	

				<div class='modal fade' id='modalVisualizarPrestamo'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Visualizar información del préstamo</h4>
							</div>
							<div class='modal-body'>
											
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Id. de la Solicitud:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_id_Solicitud' name='p_v_id_Solicitud'></p>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-5'>
										Nombre de quién entrego:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_encargado' name='p_v_encargado'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Num. del solicitante:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_Solicitante' name='p_v_Solicitante'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Edificio:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_Edificio' name='p_v_Edificio'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Tipo de area:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_Tipoarea' name='p_v_Tipoarea'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Numero de area:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_N_area' name='p_v_N_area'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Fecha de la solicitud:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_fecha' name='p_v_fecha'></p>										
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'>Cancelar</button>
							</div>
							</form>
						</div>
					</div>
				</div>

				
				
				<!-- Tabla din?mica para mostrar los registros del cat?logo -->	
				<table id='tbPrestamo' name='tbPrestamo' class='display cell-border order-column dt-responsive'>
					<thead>
						<tr>							
							<th style='width: 50px;'>Id. Solicitud					
							<th>Profesor
							<th>Edificio
							<th>Tipo de area
							<th>Numero de area
							<th>Encargado del Prestamo
							<th>Fecha de prestamo				
					</thead>
					<tfoot>
						<tr>																										
							<th>						
							<th>					
					</tfoot>					
					<tbody>															
					</tbody>
				</table>


<script>
				var tbPrestamo;
				$(document).ready( function () {
					$.fn.dataTable.ext.errMode = 'throw';
					tbPrestamo = $('#tbPrestamo').DataTable(
						{																									
							dom : 'Blfiprtip',																																																	
							language: {
								processing:     "Procesando...",
								search:         "Buscar:",
								lengthMenu:     "Mostrar _MENU_ registro(s) a la vez</div>",
								info:           "Mostrando _START_ a _END_ de _TOTAL_ registro(s)",
								infoEmpty:      "Mostrando 0 a 0 de 0 registros",
								infoFiltered:   "(Filtrados de _MAX_ registros en total)",
								infoPostFix:    "",
								loadingRecords: "Cargando...",
								zeroRecords:    "No hay registros para mostrar",
								emptyTable:     "No hay datos disponibles en la tabla",
								paginate: {
									first:      "Primero",
									previous:   "Anterior",
									next:       "Siguiente",
									last:       "Ultimo"
								},
								aria: {
									sortAscending:  ": Ordenar ascendentemente",
									sortDescending: ": Ordenar descendentemente"
								},
								select: {
									rows: {
										_: " - %d registros seleccionados",
										0: "",
										1: " - 1 registro seleccionado"
									}
								}
							},											
							"pageLength": 10,
							"lengthMenu": [ 5,10, 25, 50, 100, 250, 500, 1000, 5000, 10000],
							responsive: true,
							select: {
								style: 'os'
							},
							buttons: [
							
								{
									extend: 'copyHtml5',
									text: '<span class="glyphicon glyphicon-indent-left"></span> Copiar registros',
								},								
								{
									extend: 'excelHtml5',
									text: '<span class="glyphicon glyphicon-export"></span> Exportar a Excel'
								},
								

							],																		
							columnDefs: [
								{ responsivePriority: 1, targets: 0 },
								{ responsivePriority: 1, targets: 1 }								
							],											
							ajax: '<?php echo base_url();?>index.php/prestamo/Obtener_Dataset_Prestamo',
							autoWidth: false,							
							columns: [								
								{ data: "id_solicitud" },
								{ data: "profesor" },
								{ data: "Edificio" },
								{ data: "Tipo_Area" },
								{ data: "Num_Area" },
								{ data: "encargado_prest" },
								{ data: "fecha_prest" }
							],
							"footerCallback": function ( row, data, start, end, display ) {
								var api = this.api(), data;
					 
								// Remove the formatting to get only the number data
								var numericVal = function ( i ) {
									return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
											i : 0;
								};
								/*
								// Total over all pages
								total = api
									.column( 1 )
									.data()
									.reduce( function (a, b) {
										return numericVal(a) + numericVal(b);
									}, 0 );
					 
								// Total over this page
								pageTotal = api
									.column( 1, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return numericVal(a) + numericVal(b);
									}, 0 );
					 
								// Update footer data
								$( api.column( 1 ).footer() ).html(
									pageTotal +' (de '+ total +')'
								);
								*/
							}

							
						} 
					);																							
					
					// Apply the search
					$('#tbPrestamo').DataTable().columns().every( function () {
						var that = this;
				 
						$( 'input', this.header() ).on( 'keyup change', function () {				
							if ( that.search() !== this.value ) {
								that
									.search( this.value )
									.draw();
							}
						} );
					} );
					
				} );	
								
</script>
			
			</div>	
		</div>	
	
	</div>
	
	
<?php
	require "owned/set_security_controller.php";
	require "owned/notification_messages_controller.php";
?>
</body>
</html>