<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 15/02/2017
 * Time: 04:15 PM
 */

namespace core;

include "seguridad.php";


class model_equipos extends seguridad
{
    //Edición de Equipos Asignados
    public function editar_equipo($data_equipo = array()){

        if(array_key_exists('folio_equipo',$data_equipo) || array_key_exists('nombre_empleado',$data_equipo) || array_key_exists('departamento_empleado',$data_equipo)){
            $FechaActual = date("Ymd");
            $HoraActual = date("H:i:s");
            $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
            $PerfilUser = $_SESSION['data_login']['NoPerfil'];
            $FechaAsignacion = $this->getFormatFecha($data_equipo['fecha_asignacion'],1);

            $this->_query = "CALL sp_actualiza_equipo
            (
            '$FechaActual',
            '$UsuarioRecibe',
            '$PerfilUser',
            '$data_equipo[nombre_empleado]',
            '$data_equipo[departamento_empleado]',
            '$data_equipo[puesto_empleado]',
            '$data_equipo[id_equipo]',
            '$data_equipo[nombre_marca]',
            '$data_equipo[nombre_modelo]',
            '$data_equipo[nombre_procesador]',
            '$data_equipo[nombre_memoria]',
            '$data_equipo[nombre_disco]',
            '$data_equipo[caracteristicas]',
            '$data_equipo[codigo_cedis]',
            '$data_equipo[serie_cedis]',
            '$data_equipo[serie_equipo]',
            '$data_equipo[motivo_asignacion]',
            '$FechaAsignacion',
            '$data_equipo[estatus]',
            '$data_equipo[estatus_actual]',
            '$data_equipo[motivo_entrega]',
            '$data_equipo[condiciones_entrega]',
            '$FechaActual',
            '$HoraActual',
            '$data_equipo[folio_equipo]'
            )";

            $this->execute_query();
            $this->_query = "CALL sp_seguimiento_equipos('$data_equipo[folio_equipo]','Actualización de datos','C','$data_equipo[estatus_actual]','$UsuarioRecibe','$FechaActual','$HoraActual')";
            $this->execute_query();
            $this->_confirm = true;
            $this->_message = "Datos guardado correctamente !";

        }else{
            $this->_confirm = false;
            $this->_message = "No se encontraron las llaves, para realizar la edición del equipo asignado";
        }
    }

    //Eliminar documentos adjuntos
    public function EliminarDocumento($tpoDoc,$ID,$Folio){
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");
        $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
        if($query = $this->Consulta("UPDATE BSHAdjuntosEquipos SET UsuarioElimina = $UsuarioRecibe ,FechaBaja = '".$FechaActual."',HoraBaja='".$HoraActual."', Estatus = 5 WHERE Folio = '".$Folio."' AND IDArchivo = '".$ID."' AND TipoArchivo = '".$tpoDoc."' ")){

            $this->_query = "CALL sp_seguimiento_equipos('$Folio','Eliminación de Archivos','D','1','$UsuarioRecibe','$FechaActual','$HoraActual')";
            $this->execute_query();

            $this->MostrarDocumentos($Folio,$tpoDoc);
        }

    }

    public function MostrarDocumentos($Folio,$TipoDoc){
        //TipoDoc = 1 Documentos, TipoDoc = 2 Imagenes
        if($TipoDoc == 1){
            $Total_doc = $this->_query = "SELECT IDArchivo,TipoArchivo,NombreArchivo,RutaArchivo,FechaAlta,HoraAlta,UsuarioSube,Estatus FROM BSHAdjuntosEquipos WHERE Folio = $Folio AND TipoArchivo = 1 AND Estatus <> 5 ORDER BY IDArchivo DESC";
            $this->get_result_query();

        }else{
            $Total_doc = $this->_query = "SELECT Folio,IDArchivo,NombreArchivo,RutaArchivo,Estatus FROM BSHAdjuntosEquipos WHERE Folio = $Folio AND TipoArchivo = 2 and Estatus = 1";
            $this->get_result_query();
        }

        if(count($this->_rows) > 0){

            if($TipoDoc == 1){

                for($i = 0; $i < count($this->_rows) ; $i++){

                    echo "<tr><td>".$this->_rows[$i][0]."</td><td>".$this->_rows[$i][2]."</td><td>".$this->_rows[$i][4]."</td><td>".$this->_rows[$i][5]."</td><td>".$this->_rows[$i][6]."</td><td>".$this->_rows[$i][7]."</td><td><a href='modules/01HelpDesk/Adjuntos/equipos/documents/".$this->_rows[$i][2]."' title='Descargar'><span class='glyphicon glyphicon-eye-open'></span></a>&nbsp;&nbsp;<a style='text-decoration:underline;cursor:pointer;' data-opcion='baja'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
                }

            }else{

                $data = $this->_rows;

                for($i = 0 ; $i < count($data); $i++ ){
                    echo '<section>
                        <div class="col-md-3" >
                        <div class="pabel-body">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="panel panel-primary">
                        <div class="panel-heading">
                        <div class="row" style="margin-top:-9px;margin-bottom:-10px;">
                        <a href="modules/01HelpDesk/Adjuntos/equipos/pictures/'.$data[$i][2].'" target="_blank"><img src="modules/01HelpDesk/Adjuntos/equipos/pictures/'.$data[$i][2].'" width="100%" height="100%"></a>
                        </div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left small">'.substr($data[$i][2], 0,-4).'</span>
                            <span class="pull-right"><a style="text-decoration:underline;cursor:pointer;" data-opcion="baja"  data-toggle="tooltip" data-placement="bottom" title=" Eliminar" >
                            <i class="glyphicon glyphicon-trash small"></i>
                            </a></span><div class="clearfix">
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                        </section>';
                }
            }
        }
    }


    //Historial del Equipo Asignado
    public function get_mostrar_historial($Folio){

        $this->_query = "SELECT s.NoSeguimiento,s.FechaSeguimiento,s.HoraSeguimiento,s.Seguimiento,u.NombreDePila,c.Descripcion
        FROM BSHSeguimientoEquipos as s
        JOIN BSHCatalogoCatalogos as c
            ON s.Estatus = c.idDescripcion AND c.idCatalogo = 8
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as u
            ON s.NoUsuarioSeguimiento = u.NoUsuario
        WHERE Folio = $Folio ORDER BY s.NoSeguimiento DESC" ;

        $this->get_result_query();

        for($i=0; $i < count($this->_rows); $i++ ){
            echo "<tr><td>".$this->_rows[$i][0]."</td><td>".$this->_rows[$i][3]."</td><td>".$this->getFormatFecha($this->_rows[$i][1],2)."</td><td>".$this->_rows[$i][2]."</td><td>".$this->_rows[$i][4]."</td><td>".$this->_rows[$i][5]."</td></tr>";
        }
    }

    // Asignacion de Equipos Asignados
    public function set_equipos($data_equipo = array()){

        if(array_key_exists('nombre_empleado',$data_equipo) || array_key_exists('departamento_empleado',$data_equipo)){

            $HoraActual = date("H:i:s");
            $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];
            $FechaRegistro2 = $this->getFormatFecha($data_equipo['fecha_registro'],1);

            $this->_query = "CALL sp_asignacion_de_equipo
            (
            '$data_equipo[opcion]',
            '$data_equipo[folio]',
            '$FechaRegistro2',
            '$HoraActual',
            '$UsuarioRecibe',
            '$data_equipo[nombre_empleado]',
            '$data_equipo[departamento_empleado]',
            '$data_equipo[puesto_empleado]',
            '$data_equipo[id_equipo]',
            '$data_equipo[nombre_marca]',
            '$data_equipo[nombre_modelo]',
            '$data_equipo[nombre_procesador]',
            '$data_equipo[nombre_memoria]',
            '$data_equipo[nombre_disco]',
            '$data_equipo[caracteristicas]',
            '$data_equipo[codigo_cedis]',
            '$data_equipo[serie_cedis]',
            '$data_equipo[serie_equipo]',
            '$data_equipo[motivo_asignacion]',
            '$FechaRegistro2',
            '$data_equipo[estatus]'
            )";

            $this->execute_query();

            $this->_confirm = true;
            $this->_message = "Asignación de equipo, realizado correctamente !";


        }else{

            $this->_confirm = false;
            $this->_message = "Error no se encontraron las llaves para la asignación de Equipo";

        }
    }



}