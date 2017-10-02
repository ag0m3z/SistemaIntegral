<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 17/02/2017
 * Time: 06:31 PM
 */

namespace core;
include "seguridad.php";

class model_encuestas extends seguridad
{

    public function BuscarEncuestaServicio($opc,$condicion){

        if(empty($condicion)){
            $where = " WHERE  a.NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."'";
        }else{
            $where = " WHERE ".$condicion." AND  a.NoDepartamento = '".$_SESSION['data_departamento']['NoDepartamento']."'";
        }

        $this->_query = "SELECT a.idEncuesta,a.idPregunta,a.Folio,d.Descripcion,a.Comentarios,c.NombreDePila,a.Fecha,a.Hora,idFolioEncuesta
        FROM BGEEncuestaServicios as a
        LEFT JOIN BSHReportes as b
        ON a.Anio = b.Anio AND a.Folio = b.Folio AND a.NoDepartamento = b.NoDepartamento
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c
        ON b.NoUsuarioCierre = c.NoUsuario
        LEFT JOIN BGECatalogoDepartamentos as  d
        ON b.NoSucursal = d.NoDepartamento " .$where. " AND a.Comentarios != '' GROUP BY a.Comentarios, a.idFolioEncuesta ORDER BY a.idEncuesta,a.idPregunta DESC";

        $this->get_result_query();
        return $this->_rows;

    }

    public  function buscar_encuesta_producto($opcion,$CondicionSql,$FormatJson = false){

        if($CondicionSql == ""){
            $where = "";
        }else{
            $where = " WHERE ".$CondicionSql;
        }

        $this->_query = "SELECT 
                                a.idEncuesta,
                                a.CodProducto,
                                ifnull(b.Descripcion,'Servicios'),
                                c.Descripcion,
                                d.Descripcion,
                                a.Descripcion,
                                a.Clasificacion,
                                a.NombreCorto,
                                a.FechaAlta,
                                e.Descripcion,
                                f.Descripcion,
                                a.ImporteVenta,
                                a.NoAtendidos,
                                g.Descripcion,
                                a.IncCondiciones,
                                h.Descripcion,
                                a.IncMontoSolicita,
                                a.IncMontoCompetidor,
                                a.IncNoCompetidor,
                                i.Descripcion,
                                a.Observacion,
                                a.FechaAlta,
                                a.HoraAlta
                            FROM BGEEncuestaProducto as a
                                LEFT JOIN BGECatalogoGeneral as b ON a.NoCategoria = b.OpcCatalogo AND CodCatalogo = 9
                                LEFT JOIN BGECatalogoGeneral as c ON a.TipoProducto = c.OpcCatalogo AND c.CodCatalogo = 5 AND c.Numero2 = a.NoCategoria
                                LEFT JOIN BGECatalogoGeneral as d ON a.NoMarca = d.OpcCatalogo AND d.CodCatalogo = 6 AND d.Numero2 = a.NoCategoria
                                LEFT JOIN BGECatalogoGeneral as e ON a.IncidenciaTipoServicio = e.OpcCatalogo AND e.CodCatalogo = 14
                                LEFT JOIN BGECatalogoDepartamentos as f ON a.NoSucursal = f.NoDepartamento 
                                LEFT JOIN BGECatalogoGeneral as g ON a.NoAtendidos = g.OpcCatalogo AND g.CodCatalogo = 17
                                LEFT JOIN BGECatalogoGeneral as h ON a.IncCondiciones = h.OpcCatalogo AND h.CodCatalogo = 15 
                                LEFT JOIN BGECatalogoGeneral as i ON a.IncNoCompetidor = i.OpcCatalogo AND i.CodCatalogo = 16
    
        ".$where." ORDER BY a.idEncuesta DESC";

        $this->get_result_query();
        $rows = $this->_rows ;

        if($FormatJson){

            for($i=0 ; $i < count($rows); $i++ ){

                $rowData[] = array(
                    "hFolio"=>"<a href='#' onclick='DatosEncuesta(".$rows[$i][0].")'><span class='text text-primary'>".$this->getFormatFolio($rows[$i][0],4)."</span></a>",
                    "hCategoria"=>$rows[$i][9],
                    "hDescripcion"=>$rows[$i][1],
                    "hECompra"=>$rows[$i][2],
                    "hBCompra"=>$rows[$i][3],
                    "hMCompra"=>$rows[$i][4],
                    "hCteNuevo"=>$rows[$i][5],
                    "hBuenCte"=>$rows[$i][6],
                    "hECliente"=>$rows[$i][10],
                    "hUsuarioUM"=>$rows[$i][7],
                    "hHoraUM"=>$this->getFormatFecha($rows[$i][8],2)
                );
            }

            return $rowData;

        }else{
            return $rows ;
        }

    }

}