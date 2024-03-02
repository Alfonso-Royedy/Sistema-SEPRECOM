<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class devolucion_model extends CI_Model {			
	
	public function Obtener_Prestamo(){			
		$qry = "SELECT * FROM devolucion WHERE id_prestamo IS NOT NULL";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			return $resqry; 
		}else{			
			return 0;
		}
	}
	
	public function Obtener_Array_Cliente(){		
		$resqry = $this->Obtener_Prestamo();												
		if ($resqry->num_rows()>0){			
			$i=0;
			while ($row=$resqry->unbuffered_row('array')){
				$array[$i]=$row;
				$i++;
			}
			return $array; 
		}else{			
			return 0;
		}		
	}
	
	public function Eliminar_Prestamo($Id_prestamo){												
		$qry = "delete from devolucion where id_prestamo=?";
		$resqry = $this->db->query($qry,array((int)$Id_prestamo));			
		return ($this->db->affected_rows()>0);		
	}
	
	public function Obtener_Sig_Id_Prestamo(){
		$qry = "SELECT COALESCE(MAX(id_prestamo), 0) + 1 AS Sig_Id_prestamo FROM devolucion";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			$regqry=$resqry->unbuffered_row();
			return $regqry->Sig_Id_prestamo; 
		}else{
			return 1;
		}
	}


// En tu modelo Devolucion_model
public function crearPrestamoConProductos() {
    $this->db->trans_start(); // Iniciar transacción

    // Obtener la última solicitud creada
    $id_solicitud = $this->ObtenerUltimaSolicitud();

    // Obtener productos desde los inputs
    $equipo_solicitado1 = $this->input->post('Equipo_Solicitado1');
    $equipo_solicitado2 = $this->input->post('Equipo_Solicitado2');
    $equipo_solicitado3 = $this->input->post('Equipo_Solicitado3');

    $productos = array();

    // Verifica si cada input tiene un valor y agrégalo al array
    if (!empty($equipo_solicitado1)) {
        $productos[] = $equipo_solicitado1;
    }

    if (!empty($equipo_solicitado2)) {
        $productos[] = $equipo_solicitado2;
    }

    if (!empty($equipo_solicitado3)) {
        $productos[] = $equipo_solicitado3;
    }

    // Insertar los productos en la tabla devolucion
    foreach ($productos as $id_producto) {
        $prestamo_data = array(
            'id_producto' => $id_producto,
            'id_solicitud' => $id_solicitud,
            'estado' => 'Prestado'
        );

        $this->db->insert('devolucion', $prestamo_data);
    }

    $this->db->trans_complete(); // Finalizar transacción
}


// En tu modelo Devolucion_model
public function ObtenerUltimaSolicitudValida() {
    $qry = "SELECT COALESCE(MAX(id_solicitud), 0) AS UltimaSolicitud FROM prestamo";
    $resqry = $this->db->query($qry);

    if ($resqry->num_rows() > 0) {
        $regqry = $resqry->unbuffered_row();
        return $regqry->UltimaSolicitud;
    } else {
        return 0;
    }
}


    
    
    
    

    private function ObtenerUltimaSolicitud() {
        $qry = "SELECT COALESCE(MAX(id_solicitud), 0) AS UltimaSolicitud FROM prestamo";
        $resqry = $this->db->query($qry);

        if ($resqry->num_rows() > 0) {
            $regqry = $resqry->unbuffered_row();
            return $regqry->UltimaSolicitud;
        } else {
            return 0;
        }
    }
   
    



	
	
    public function Editar_Devolucion($Id_prestamo, $id_producto, $Encargado_Dev, $Fecha_Dev, $Obser, $Id_solicitud)
    {
        $qry = "UPDATE devolucion 
                SET id_prestamo=?, id_producto=?, encargado_devo=?, fecha_devo=?, observaciones=?, id_solicitud=?, estado='devuelto' 
                WHERE id_prestamo=?";
        
        $resqry = $this->db->query($qry, array((int)$Id_prestamo, (int)$id_producto, $Encargado_Dev, $Fecha_Dev, $Obser, (int)$Id_solicitud, (int)$Id_prestamo));
    
        return ($this->db->affected_rows() == 1);
    }
    
		
}
?>
