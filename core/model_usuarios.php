<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 02/03/2017
 * Time: 10:43 AM
 */

namespace core;

include "seguridad.php";


class model_usuarios extends seguridad
{




    public function ListarUsuarios($filtro,$condicion=null){

        $FechaActual = date("Ymd");

        switch($filtro){
            case 1:
                //Mostrar todos los empleados sin condicion
                $where = "";
                break;
            case 2:
                //Mostrar solo los empleados Activos
                $where = " WHERE a.NoEstado = 1 ORDER BY a.UsuarioLogin ASC";
                break;
            case 3:
                //Mostrar solo los empleados inactivos
                $where = " WHERE a.NoEstado = 0 ORDER BY a.UsuarioLogin ASC";
                break;
            case 4:
                //Mostrar solo los ultimos 50 registros
                $where = " ORDER BY a.UsuarioLogin ASC LIMIT 0,50";
                break;
            case 5:
                //Mostrar solo los ultimos 100 registros
                $where = " ORDER BY a.UsuarioLogin DESC LIMIT 0,100";
                break;
            case 6:
                //Mostrar solo los registros nuevos del dia de hoy
                $where = " WHERE a.FechaAlta >= $FechaActual ORDER BY a.HoraAlta DESC";
                break;
            case 7:
                //Mostrar solo los registros actualizados del dia de hoy
                $where = " WHERE a.FechaUM >= $FechaActual ORDER BY a.HoraUM DESC";;
                break;
            case 8:
                //Mostrar solo los registros actualizados del dia de hoy
                $where = " WHERE a.NoUsuario LIKE ".$condicion." OR a.UsuarioLogin LIKE ".$condicion." OR a.NombreDePila LIKE ".$condicion." OR b.Descripcion LIKE ".$condicion." AND a.NoEstado = 1 ORDER BY a.UsuarioLogin ASC";
                break;
            case 9:
                //Mostrar solo los registros actualizados del dia de hoy
                $where = " WHERE ".$condicion." ORDER BY a.UsuarioLogin ASC";
                break;
            case 10:
                $where = " WHERE f.Estatus = 'C' ORDER BY a.HoraUM DESC";;

                break;
            default:
                core::MyAlert("Opcion no encontrada en model_usuarios","alert");
                break;
        }

        // Listar Usuarios
        $SqlText = "
        SELECT  a.NoUsuario,a.NombreDePila,b.Descripcion,a.UsuarioLogin,a.Reportes,
                e.Descripcion,if(a.NoEstado = 1,'Activo','Desactivado'),c.NombreDePila,d.NombreDePila,a.FechaAlta,a.FechaUM,f.Estatus as EstadoConexion
        FROM SINTEGRALGNL.BGECatalogoUsuarios as a
          LEFT JOIN BGECatalogoDepartamentos as b
        ON a.NoDepartamento = b.NoDepartamento
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c
        ON a.NoUsuarioAlta = c.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d
        ON a.NoUsuarioUM = d.NoUsuario 
        LEFT JOIN BSHCatalogoCatalogos as e 
        ON a.NoPerfil = e.idDescripcion AND e.idCatalogo = 6 
        LEFT JOIN SINTEGRALGNL.BGEConexiones as f 
        ON a.NoUsuario = f.NoUsuario 
        ";


       $this->_query = $SqlText.$where;
       $this->get_result_query();

        if(count($this->_rows) > 0){

            $row = $this->_rows;

            for($i = 0 ; $i < count($row); $i++){

                if($row[$i][6] == 'Activo'){$estatus = "<span class='label label-success'>Activo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";}else{$estatus = "<span class='label label-danger'>Desactivado</span>";}

                $BtnDesconectar = "<a href='#' onclick='fngnDesconectar_sesion(".$row[$i][0].",2)'><span class='label label-warning'>Desbloquear</span></a>";

                $dataRow[] = array(
                    "hIdUsuario"=>"<a href='#' onclick='fnCatEditarUsuario(1,".$row[$i][0].")' ><span class='text text-primary'>".$this->getFormatFolio($row[$i][0],4)."</span></a>",
                    "hNombre"=>$row[$i][1],
                    "hDepto"=>$row[$i][2],
                    "hUsuario"=>$row[$i][3],
                    "hReportes"=>$row[$i][4],
                    "hPerfil"=>$row[$i][5],
                    "hEstado"=>$estatus,
                    "hUnloack"=>$BtnDesconectar,
                    "hFechaAlta"=>$this->getFormatFecha($row[$i][9],2),
                    "hUsuarioA"=>$row[$i][7],
                    "hFechaUM"=>$this->getFormatFecha($row[$i][10],2),
                    "hUsuarioU"=>$row[$i][8]
                );
            }

            return $dataRow;
        }
    }

    public function InfoUser($NoUsuario){

        $this->_query = "
        SELECT a.NoUsuario,a.idEmpleado,a.NoDepartamento,a.NombreDePila,
                a.UsuarioLogin,a.PassLogin,
                a.Reportes,a.NoEstado,
                a.NoUsuarioAlta,a.NoUsuarioUM,
                a.FechaAlta,a.HoraAlta,
                a.FechaUM,a.HoraUM,a.NoPerfil,b.Descripcion,a.BDDatos,CONCAT_WS(' ',c.Nombre,c.ApPaterno,c.ApMaterno),d.Descripcion,e.Descripcion
        FROM SINTEGRALGNL.BGECatalogoUsuarios as a
        LEFT JOIN BSHCatalogoCatalogos as b
        ON a.NoPerfil = idDescripcion AND idCatalogo = 6 
        LEFT JOIN SINTEGRALGNL.BGEEmpleados as c 
        ON a.idEmpleado = c.idEmpleado 
        LEFT JOIN BGECatalogoDepartamentos as d 
        ON a.NoDepartamento = d.NoDepartamento 
        LEFT JOIN SINTEGRALGNL.BGEDataBases as e 
        ON a.BDDatos = e.Nombre
        where a.NoUSuario = $NoUsuario LIMIT 0,1;
        ";

        $this->get_result_query();

        if(! count($this->_rows) > 0 ){
            echo "Error al realizar la consulta";
        }else{

            $data = $this->_rows[0];

            return $data;

        }
    }


    //Funcion para Mostrar los modulos de Conexion
    public function MostrarModulos(){

        $this->_query = "SELECT Nombre,Descripcion,Icon,Class,idModulo FROM 00GNMenuModulos where idSeccion  = 0 ORDER BY idOrden ASC";
        $this->get_result_query();

        $row = $this->_rows;
        for( $i=0; $i < count($row); $i++ ){
            echo '<a href="#" onclick="ShowHidenGroups(1,\'opc02-'.$row[$i][4].'\')" class="list-group-item padding-x5 waves-effect"><i class="fa '.$row[$i][2].'"></i> '.$row[$i][0].'</a>';
        }

    }

    //Funcion para mostrar las secciones
    public function MostrarSecciones(){

        $this->_query = "SELECT idModulo,Nombre FROM 00GNMenuModulos where idSeccion  = 0 ORDER BY idOrden ASC ";
        $this->get_result_query();

        $row = $this->_rows;

        for($i=0; $i < count($row) ;$i++){

            $idModulo = $row[$i][0];

            $this->_query = "SELECT idModulo,idSeccion,Nombre,Icon FROM 00GNMenuModulos where TipoOpcion = 2 AND idModulo = $idModulo  ORDER BY idOrden ASC ";
            $this->get_result_query();
            $rows = $this->_rows;

            echo '<div id="opc02-'.$idModulo.'" class="list-group opc02 ">
                                    <a href="#" class="list-group-item padding-x3 active">
                                        '.$row[$i][1].'
                                    </a>';

            for($i2=0; $i2< count($rows); $i2++ ){

                echo '<a href="#" onclick="ShowHidenGroups(3,\'gpo03-'.$idModulo.'-'.$rows[$i2][1].'\')"  class="list-group-item padding-x5 waves-effect"><i class="fa '.$rows[$i2][3].'"></i> '.$rows[$i2][2].'</a>';
            }
            echo '</div>';

        }
    }


    public function EditarAplicaciones($NoUsuario){

        $this->_query = "SELECT idModulo,Nombre FROM 00GNMenuModulos where idSeccion  = 0 ORDER BY idOrden ASC ";
        $this->get_result_query();

        $row = $this->_rows;

        $checkingA = "";
        $checkingB = "";
        $checkingC = "";
        $checkingV = "";

        for($i=0 ; $i < count($row); $i++){

            $idModulo = $row[$i][0] ;

            $this->_query = "SELECT idModulo,idSeccion,Nombre,Icon FROM 00GNMenuModulos where TipoOpcion = 2 AND idModulo = $idModulo ORDER BY idOrden ASC ";
            $this->get_result_query();

            $rows = $this->_rows;

            for($i2=0; $i2 < count($rows); $i2++ ){

                $idModulo2 = $rows[$i2][0];
                $idSeccion2 = $rows[$i2][1];


                $this->_query = "
                        SELECT a.idModulo,a.idSeccion,a.idOpcion,b.Nombre,b.Icon,a.NoUSuario,a.OpcionAlta,a.OpcionBaja,a.OpcionCambio,a.OpcionVista,a.OpcionReportes
                            FROM 00GNMenuAccesos as a
                            LEFT JOIN 00GNMenuModulos as b
                            ON a.idModulo = b.idModulo and a.idSeccion = b.idSeccion and a.idOpcion = b.idOpcion
                            where a.idModulo = $idModulo AND a.idSeccion = $idSeccion2 AND b.TipoOpcion = 3 and a.NoUsuario = $NoUsuario ORDER BY b.idOrden ASC
                        ";
                $this->get_result_query();
                $rows2 = $this->_rows;

                echo '<div id="gpo03-'.$idModulo2.'-'.$idSeccion2.'" class="list-group opc03 table-responsive" >
                                    <a href="#" class="list-group-item padding-x3 active">
                                       Aplicaciones
                                    </a><table style="background:#fff" class="table table-striper table-hover table-condensed"><thead><tr><th>Aplicacion</th><th width="25">Alta</th><th width="25">Cambio</th><th width="25">Baja</th><th width="25">Vista</th><th width="25">Reporte</th></tr></thead><tbody class="scroll">';

                for( $i3=0; $i3 < count($rows2); $i3++ ){

                    $checkingA = "";
                    $checkingB = "";
                    $checkingC = "";
                    $checkingV = "";
                    $checkingR = "";

                    $NombreApp2 = $rows2[$i3][2];

                    if($rows2[$i3][6] == 1 ){$checkingA = " checked " ;/* Opcion Alta */}
                    if($rows2[$i3][7] == 1 ){$checkingB = " checked " ;/* Opcion Alta */}
                    if($rows2[$i3][8] == 1 ){$checkingC = " checked " ;/* Opcion Alta */}
                    if($rows2[$i3][9] == 1 ){$checkingV = " checked " ;/* Opcion Alta */}
                    if($rows2[$i3][10] == 1 ){$checkingR = " checked " ;/* Opcion Alta */}

                    echo "<tr><td><i class='fa ".$rows2[$i3][4]."'></i> ".$rows2[$i3][3]."</td>
                                <td class='text-center'><input type='checkbox' ".$checkingA." name='app[]' value='".$idModulo2."-".$idSeccion2."-".$NombreApp2."-A'></td>
                                <td class='text-center'><input type='checkbox' ".$checkingC." name='app[]' value='".$idModulo2."-".$idSeccion2."-".$NombreApp2."-C'></td>
                                <td class='text-center'><input type='checkbox' ".$checkingB." name='app[]' value='".$idModulo2."-".$idSeccion2."-".$NombreApp2."-B'></td>
                                <td class='text-center'><input type='checkbox' ".$checkingV." name='app[]' value='".$idModulo2."-".$idSeccion2."-".$NombreApp2."-V'></td>
                                <td class='text-center'><input type='checkbox' ".$checkingR." name='app[]' value='".$idModulo2."-".$idSeccion2."-".$NombreApp2."-R'></td>
                                </tr>";
                }

                echo '</tbody></table></div>';


            }


        }


    }

    //REvisar si ya existe el nombre de usuario
    public function getExistsUsuario($userLogin){

        $this->_query = "SELECT UsuarioLogin FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE UsuarioLogin = '$userLogin' LIMIT 0,1";
        $this->get_result_query();
        if(count($this->_rows) > 0 ){
            return true;
        }else{
            return false;
        }

    }
    public function getExistsEmpleado($idEmpleado){

        $this->_query = "SELECT idEmpleado FROM SINTEGRALGNL.BGECatalogoUsuarios WHERE idEmpleado = $idEmpleado LIMIT 0,1";
        $this->get_result_query();

        if(count($this->_rows) > 0){

            return true;

        }else{

            return false;
        }

    }


    //Funcion para mostrar las aplicaciones
    public function MostrarAplicaciones(){

        $this->_query = "SELECT idModulo,Nombre FROM 00GNMenuModulos where idSeccion  = 0 ORDER BY idOrden ASC ";
        $this->get_result_query();

        $row = $this->_rows;

        for( $i=0; $i < count($row); $i++ ){

            $idModulo1 = $row[$i][0];

            $this->_query = "SELECT idModulo,idSeccion,Nombre,Icon FROM 00GNMenuModulos where TipoOpcion = 2 AND idModulo =$idModulo1  ORDER BY idOrden ASC ";
            $this->get_result_query();

            $rows = $this->_rows;

            for( $i2=0; $i2 < count($rows); $i2++ ){

                $idModulo2 = $rows[$i2][0];
                $idSeccion = $rows[$i2][1];

                $this->_query = "SELECT idModulo,idSeccion,idOpcion,Nombre,Icon FROM 00GNMenuModulos where TipoOpcion = 3 AND  idModulo = $idModulo1 AND idSeccion = $idSeccion  ORDER BY idOrden ASC ";
                $this->get_result_query();

                $rows2 = $this->_rows;


                echo '<div id="gpo03-'.$idModulo2.'-'.$idSeccion.'" class="list-group opc03 table-responsive" >
                                    <a href="#" class="list-group-item padding-x3 active">
                                      Aplicaciones
                                    </a><table style="background:#fff" class="table table-striper table-hover table-condensed"><thead><tr><th>Aplicacion</th><th width="25">Alta</th><th width="25">Cambio</th><th width="25">Baja</th><th width="25">Vista</th><th width="25">Reporte</th></tr></thead><tbody class="scroll">';
                for( $i3=0 ; $i3 < count($rows2); $i3++ ){

                    echo "<tr><td><i class='fa ".$rows2[$i3][4]."'></i> ".$rows2[$i3][3]."</td>
                                <td class='text-center'><input type='checkbox' name='app[]' value='".$rows[$i2][0]."-".$rows[$i2][1]."-".$rows2[$i3][2]."-A'></td>
                                <td class='text-center'><input type='checkbox' name='app[]' value='".$rows[$i2][0]."-".$rows[$i2][1]."-".$rows2[$i3][2]."-C'></td>
                                <td class='text-center'><input type='checkbox' name='app[]' value='".$rows[$i2][0]."-".$rows[$i2][1]."-".$rows2[$i3][2]."-B'></td>
                                <td class='text-center'><input type='checkbox' name='app[]' value='".$rows[$i2][0]."-".$rows[$i2][1]."-".$rows2[$i3][2]."-V'></td>
                                <td class='text-center'><input type='checkbox' name='app[]' value='".$rows[$i2][0]."-".$rows[$i2][1]."-".$rows2[$i3][2]."-R'></td>
                                </tr>";
                }

                echo '</tbody></table></div>';

            }


        }

    }




}