<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class producto_model extends CI_Model {			
	
	public function Obtener_Producto(){			
		$qry = "SELECT * FROM producto WHERE id_producto IS NOT NULL";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			return $resqry; 
		}else{			
			return 0;
		}		
	}
	
    public function Obtener_Array_Nombre_Producto(){
        $resqry = $this->Obtener_Producto();												
        if ($resqry->num_rows() > 0){			
            $array = array();
            while ($row = $resqry->unbuffered_row('array')){
                $array[$row["id_producto"]] = $row["nombre_producto"];
            }
            return $array; 
        } else {			
            return 0;
        }		
    }

	
	public function Eliminar_Institucion($Id_Institucion){												
		$qry = "delete from GC_Instituciones where Id_Institucion=?";
		$resqry = $this->db->query($qry,array((int)$Id_Institucion));			
		return ($this->db->affected_rows()>0);		
	}
	
	public function Obtener_Sig_Id_Institucion(){
		$qry = "select isnull(max(Id_Institucion),0)+1 Sig_Id_Institucion from GC_Instituciones";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			$regqry=$resqry->unbuffered_row();
			return $regqry->Sig_Id_Institucion; 
		}else{
			return 1;
		}
	}
	
	public function Crear_Institucion($Descrip_Institucion){						
		$Sig_Id_Institucion=$this->Obtener_Sig_Id_Institucion();		
		$qry = "insert into GC_Instituciones (Id_Institucion,Institucion) values (?,?)";
		$resqry = $this->db->query($qry,array((int)$Sig_Id_Institucion,$Descrip_Institucion));			
		return ($this->db->affected_rows()>0);
	}
	
	public function Editar_Institucion($Id_Institucion,$Descrip_Institucion){								
		$qry = "update GC_Instituciones set Institucion=? where Id_Institucion=?";
		$resqry = $this->db->query($qry,array($Descrip_Institucion,(int)$Id_Institucion));			
		return ($this->db->affected_rows()==1);
	}
		
}
?>
