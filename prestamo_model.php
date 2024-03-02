<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class prestamo_model extends CI_Model {			
	
    public function Obtener_Prestamo(){			
        $qry = "SELECT P.*, C.nombre AS nombre
        FROM Prestamo P
        JOIN cliente C ON P.profesor = C.matricula
        WHERE P.id_solicitud IS NOT NULL";
    
        $resqry = $this->db->query($qry);										
        if ($resqry->num_rows() > 0){			
            return $resqry; 
        } else {			
            return 0;
        }		
    }
    
	
    public function Obtener_Array_Nombre_prestador(){
        $resqry = $this->Obtener_Prestamo();												
        if ($resqry->num_rows() > 0){			
            $array = array();
            while ($row = $resqry->unbuffered_row('array')){
                $array[$row["profesor"]] = $row["nombre"];
            }
            return $array; 
        } else {			
            return 0;
        }		
    }
	
	public function Eliminar_Solicitud($Id_solicitud){												
		$qry = "delete from prestamo where id_solicitud=?";
		$resqry = $this->db->query($qry,array((int)$Id_solicitud));			
		return ($this->db->affected_rows()>0);		
	}
	
    public function Obtener_Sig_Id_Solicitud() {
        // Verificar que la conexión a la base de datos esté establecida
        if ($this->db !== null) {
            $qry = "SELECT COALESCE(MAX(id_solicitud), 0) + 1 AS Sig_Id_Solicitud FROM prestamo";
            
            // Realizar la consulta
            $resqry = $this->db->query($qry);
    
            // Verificar si la consulta se ejecutó correctamente
            if ($resqry !== false && $resqry->num_rows() > 0) {
                $regqry = $resqry->unbuffered_row();
                return $regqry->Sig_Id_Solicitud;
            } else {
                // Manejar el caso de una consulta sin resultados
                return 1;
            }
        } else {
            // Manejar el caso de una conexión nula
            return -1; // Puedes ajustar el valor de retorno según tus necesidades
        }
    }
    


    public function Crear_Data_Solicitud($solicitud_data) {
        
            // Obtener el siguiente ID de solicitud
            $Sig_Id_Solicitud = $this->Obtener_Sig_Id_Solicitud();
    
            // Verificar que la conexión a la base de datos esté establecida
            if ($this->db !== null) {
                // Preparar la consulta SQL
                $qry = "INSERT INTO prestamo (id_solicitud, profesor, Edificio, Tipo_Area, Num_Area, encargado_prest, fecha_prest) VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                // Ejecutar la consulta
                $resqry = $this->db->query($qry, array(
                    (int)$Sig_Id_Solicitud,
                    $solicitud_data['profesor'],
                    $solicitud_data['Edificio'],
                    $solicitud_data['Tipo_Area'],
                    (int)$solicitud_data['Num_Area'],
                    $solicitud_data['encargado_prest'],
                    $solicitud_data['fecha_prest']
                ));
    
                return ($this->db->affected_rows() > 0);
            }

    }
    
    public function Editar_Solicitud($Id_solicitud,$Nombre_Solicitante,$Edificio,$Tipo_Area,$Numero_Area,$Encargado,$Fecha_Solicitud){							
		$qry = "UPDATE prestamo set id_solicitud=?, profesor=?, Edificio=?, Tipo_Area=?, Num_Area=?, encargado_prest=?, fecha_prest=? where id_solicitud=?";
		$resqry = $this->db->query($qry,array((int)$Id_solicitud,$Nombre_Solicitante,$Edificio,$Tipo_Area,$Numero_Area, $Encargado,$Fecha_Solicitud,(int)$Id_solicitud));			
		return ($this->db->affected_rows()==1);
		//return array(($this->db->affected_rows()==1),$Fecha_Nacimiento_Formateada);
	}
		
}
?>
