<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/03/2017
 * Time: 12:27 PM
 */

namespace core;
include 'seguridad.php';

class model_departamentos extends seguridad
{
    public function myGrid_departamentos($filtro,$json = true,$condicion = NULL){

        $FechaActual = date("Ymd");

        switch($filtro){

            case 1:
                // mostrar los departamentos Activos
                $where = " WHERE a.NoEstado = 1 ORDER BY a.Descripcion ASC";
                break;
            case 2:
                // Mostrar todos los Departamentos
                $where = "";
                break;
            case 3:
                // Mostrar los Departamentos Desactivados
                $where = " WHERE a.NoEstado = 0";
                break;
            case 4:
                //Mostrar solo los ultimos 50 registros
                $where = " ORDER BY a.NoDepartamento DESC LIMIT 0,50";
                break;
            case 5:
                //Mostrar solo los ultimos 100 registros
                $where = " ORDER BY a.NoDepartamento DESC LIMIT 0,100";
                break;
            case 6:
                //Mostrar solo los registros nuevos del dia de hoy
                $where = " WHERE a.FechaAlta >= $FechaActual ORDER BY a.HoraAlta ASC";
                break;
            case 7:
                //Mostrar solo los registros actualizados del dia de hoy
                $where = " WHERE a.FechaUM >= $FechaActual ORDER BY a.HoraUM DESC";;
                break;
            case 8:
                // Mostrar la Lista con la condicion de busqueda
                $where = " WHERE ". $condicion ."ORDER BY a.Descripcion ASC";

        }

        $sql = "
        SELECT a.NoDepartamento,a.idEmpresa,b.Descripcion as Empresa,a.NoTipo,a.AsignarReportes,a.NoSucursal,a.Descripcion,a.Domicilio,
        a.idEstado,a.idMunicipio,a.Telefono01,a.Telefono02,a.Telefono03,a.Telefono04,a.Correo,a.NoZona,c.Descripcion as NombreZona,
        c.Texto1 as DescripcionZona,a.NoSupervisor,d.Texto1,a.Encargado,concat_ws('',g.Nombre,g.ApPaterno),a.Diagrama,a.NoEstado,if(a.NoEstado = 1,'Activo','Desactivado'),
        a.NoUsuarioAlta,e.NombreDepila as UsuarioAlta,a.NoUsuarioUM,f.NombreDePila as UsuarioUM,a.FechaAlta,a.FechaUM,a.HoraAlta,a.HoraUM,b.Abreviacion,
        CASE a.NoTipo WHEN 'S' THEN 'Sucursal'  WHEN 'D' THEN 'Departamento'  WHEN 'F' THEN 'Franquicia'  WHEN 'R' THEN 'Restaurante' END
        FROM BGECatalogoDepartamentos as a
        LEFT JOIN BGEEmpresas as b
        ON a.idEmpresa = b.idEmpresa
        LEFT JOIN BGECatalogoGeneral as c
        ON a.NoZona = c.OpcCatalogo AND c.CodCatalogo = 18
        LEFT JOIN BGECatalogoGeneral as d
        ON a.NoSupervisor = d.OpcCatalogo AND d.CodCatalogo = 19
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e
        ON a.NoUsuarioAlta = e.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as f
        ON a.NoUsuarioUM = f.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGEEmpleados as g
        ON a.Encargado = g.idEmpleado
         ";

        $this->_query = $sql .$where;
        $this->get_result_query();

        $consSql  = $this->_rows;

        if(count($consSql) > 0){
            for($i=0; $i < count($consSql);$i++){

                $idEstado = $consSql[$i][24];

                if($consSql[$i][24] == 'Activo'){$estatus = "<span class='label label-success'>$idEstado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";}else{$estatus = "<span class='label label-danger'>$idEstado</span>";}
                $dataRow[] = array(
                    "jNoDepartamento"=>"<a href='javascript:void(0)' onclick='fnCatFrmNuevoDepartamento(4,\"".$consSql[$i][0]."\",1)'><span class='text text-primary'>".$this->getFormatFolio($consSql[$i][0],4)."</span></a>",
                    "jNombreDepartamento"=>$consSql[$i][6],
                    "jEmpresa"=>$consSql[$i][2],
                    "jTipo"=>$consSql[$i][3],
                    "jReportes"=>$consSql[$i][4],
                    "jCorreo"=>$consSql[$i][14],
                    "jEstado"=>$estatus,
                    "jDomicilio"=>$consSql[$i][7],
                    "jTelefono1"=>$consSql[$i][10],
                    "jTelefono2"=>$consSql[$i][11],
                    "jTelefono3"=>$consSql[$i][12],
                    "jTelefono4"=>$consSql[$i][13],
                    "jNoZona"=>$consSql[$i][17],
                    "jSupervisor"=>$consSql[$i][19],
                    "jEncargado"=>$consSql[$i][21],
                    "jFechaA"=>$this->getFormatFecha($consSql[$i][29],2),
                    "jHoraA"=>$consSql[$i][31],
                    "jNoUsuarioAlta"=>$consSql[$i][26],
                    "jFechaUM"=>$this->getFormatFecha($consSql[$i][30],2),
                    "jHoraUM"=>$consSql[$i][32],
                    "jNoUsuarioUM"=>$consSql[$i][28],
                );
            }
            return $dataRow;
        }

    }

    public function get_info_departamento($NoDepartamento){
        $this->_query = "
        SELECT a.NoDepartamento,a.idEmpresa,b.Descripcion as Empresa,a.NoTipo,a.AsignarReportes,a.NoSucursal,a.Descripcion,a.Domicilio,
        a.idEstado,a.idMunicipio,a.Telefono01,a.Telefono02,a.Telefono03,a.Telefono04,a.Correo,a.NoZona,c.Descripcion as NombreZona,
        c.Texto1 as DescripcionZona,a.NoSupervisor,CONCAT_WS(' ',d.Nombre,d.ApPaterno,d.ApMaterno),a.Encargado,concat_ws('',g.Nombre,' ',g.ApPaterno,' ',g.ApMaterno),a.Diagrama,a.NoEstado,if(a.NoEstado = 1,'Activo','Desactivado'),
        a.NoUsuarioAlta,e.NombreDepila as UsuarioAlta,a.NoUsuarioUM,f.NombreDePila as UsuarioUM,a.FechaAlta,a.FechaUM,a.HoraAlta,a.HoraUM,b.Abreviacion,
        CASE a.NoTipo WHEN 'S' THEN 'Sucursal'  WHEN 'D' THEN 'Departamento'  WHEN 'F' THEN 'Franquicia'  WHEN 'R' THEN 'Restaurante' END,h.estado,i.nombre_municipio
        FROM BGECatalogoDepartamentos as a
        LEFT JOIN BGEEmpresas as b
        ON a.idEmpresa = b.idEmpresa
        LEFT JOIN BGECatalogoGeneral as c
        ON a.NoZona = c.OpcCatalogo AND c.CodCatalogo = 18
        LEFT JOIN SINTEGRALGNL.BGEEmpleados as d
        ON a.NoSupervisor = d.idEmpleado 
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as e
        ON a.NoUsuarioAlta = e.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as f
        ON a.NoUsuarioUM = f.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGEEmpleados as g
        ON a.Encargado = g.idEmpleado
        LEFT JOIN BGEEstados as h
        ON a.idEstado = h.id_estado
        LEFT JOIN BGEMunicipios as i
        ON a.idMunicipio = i.id_municipio
         WHERE a.NoDepartamento = '$NoDepartamento'
         ";
        $this->get_result_query();
        $data = $this->_rows;
        return $data;
    }




}