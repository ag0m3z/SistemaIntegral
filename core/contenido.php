<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 23/01/2017
 * Time: 06:21 PM
 */

namespace core;

include 'bd.php';


class contenido extends bd
{

    public function getFormatoEstatus($Estatus = 1){
        if ($Estatus == 1){
            //Activado
            return "<span class='label label-success'>Activado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        }else if($Estatus == 0){
            //Desactivado
            return "<span class='label label-danger'>Desactivado</span>";
        }
    }

    //Metodo para Formatear el Folio Agregando ceros a la Izquierda
    public function getFormatFolio($NoFolio,$NoCeros){
        // Funcion para mostrar 4 ceros a la izquierda
        // Ejemplo: Numero de entrar 1, resultado: 0001
        $newNum = str_pad($NoFolio, $NoCeros,"0", STR_PAD_LEFT);
        return  $newNum;
    }

    //Metodo para Formatear las Fechas de 20150612 a 12/06/2015 y Viceversa
    public function getFormatFecha($Fecha,$Formato,$Hora = ""){
        switch ($Formato) {
            case 1 :
                // Cambia el Formato de la Fecha de (dd/mm/yyyy) a (yyyymmdd)
                list ( $Dia, $Mes, $Anio ) = explode ( "/", $Fecha );
                return $Anio . $Mes . $Dia;
                break;
            case 2 :
                // Cambia el Formato de la Fecha de yyyymmdd a dd/mm/yyyy
                if ($Fecha == "") {
                } else {
                    $NuevaFecha = substr ( $Fecha, 6, 2 ) . "/" . substr ( $Fecha, 4, 2 ) . "/" . substr ( $Fecha, 0, 4 );
                    return $NuevaFecha;

                }
                break;
            case 3:
                // formato DATETIME date("Y-m-d H:i:s")
                list ( $Dia, $Mes, $Anio ) = explode ( "/", $Fecha );

                if($Hora == ""){
                    $NuevaFecha2 = $Anio."-".$Mes."-".$Dia."-"." " . date("H:i:s") ;
                }else{
                    $NuevaFecha2 = $Anio."-".$Mes."-".$Dia."-"." " . $Hora ;
                }


                return $NuevaFecha2;
                break;
            case 'dd/mm/yyyy':
                return date("d/m/Y", strtotime($Fecha));
                break;
        }
    }

    //Stats para mostrar indicadores dentro de los badges y labels
    public function getAlertStats($opcion,$NoDepartamento,$NoUsuario,$Anio=null,$NoFolio=null,$Ceros=null,$Departamento_Tecnico = null){

        $sql = ""; $where = "";$tabla = "BSHReportes ";
        switch ($opcion){
            case 1:
                //Todos los Tickets Pendientes para mostrar en el Menu Tickets
                if($_SESSION['AsignarReporte'] == 'SI'){
                    //Si No es una Sucursal el Usuario
                    $where = " WHERE Estatus <= 3 AND NoDepartamento = $NoDepartamento ";
                }else{
                    //Si el Usuario es Sucursal
                    $where = " WHERE Estatus <= 3 AND NoSucursal = ".$_SESSION['data_departamento']['NoDepartamento']."";
                }
                break;
            case 2:
                //Total Tickets Pendientes con Prioridad Baja
                $where = " WHERE Estatus <= 3 AND NoDepartamento = $NoDepartamento AND PrioridadTicket = 1";
                break;
            case 3:
                //Total Tickets Pendientes con Prioridad Media
                $where = " WHERE Estatus <= 3 AND NoDepartamento = $NoDepartamento AND PrioridadTicket = 2";
                break;
            case 4:
                //Total Tickets Pendientes con Prioridad Alta
                $where = " WHERE Estatus <= 3 AND NoDepartamento = $NoDepartamento AND PrioridadTicket = 3";
                break;
            case 5:
                //Mis Tickets En Estatus Abiertos
                $where = " WHERE Estatus = 5 AND NoDepartamento = $NoDepartamento AND NoUsuarioAsignado = $NoUsuario ";
                break;
            case 6:
                //Mis Tickets En Estatus En Progreso
                $where = " WHERE Estatus = 2 AND NoDepartamento = $NoDepartamento AND NoUsuarioAsignado = $NoUsuario ";
                break;
            case 7:
                //Todos Mis Tickets Pendientes
                $where = " WHERE Estatus <= 3 AND NoDepartamento = '$NoDepartamento' AND NoUsuarioAsignado = $NoUsuario ";
                break;
            case 8:
                //Mis Tickets Cerrados Hoy
                $where = " WHERE Estatus = 4 AND NoDepartamento = '$NoDepartamento' AND NoUsuarioCierre = $NoUsuario AND FechaCierre = ".date("Ymd");
                break;
            case 9:
                //Tickets Sin Asignar Del Departamento
                $where = " WHERE Estatus <= 3 AND NoDepartamento = '$NoDepartamento' AND NoUsuarioAsignado = '0'";
                break;
            case 10:
                //Tickets En Progreso Del Departamento
                $where = $where = " WHERE Estatus = 2 AND NoDepartamento = '$NoDepartamento' ";
                break;
            case 11:
                //Tickets Pendietes Del Departamento
                $where = " WHERE Estatus <=3  AND NoDepartamento = '$NoDepartamento' ";
                break;
            case 12:
                //Tickets de hoy Cerrados del Departamento
                $where = " WHERE Estatus = 4 AND NoDepartamento = '$NoDepartamento' AND FechaCierre = ".date("Ymd");
                break;
            case 13:
                //Total de Equipos Asignados
                $tabla = "BSHInventarioEquipos ";
                $where = " WHERE Estatus = 1";
                break;
            case 14:
                //Total de Equipos En Procceso
                $tabla = "BSHInventarioEquipos ";
                $where = " WHERE Estatus = 4";
                break;
            case 15:
                //Total de Equipos Entregados
                $tabla = "BSHInventarioEquipos ";
                $where = " WHERE Estatus = 2";
                break;
            case 16:
                //Total de Equipos Enviados
                $tabla = "BSHInventarioEquipos ";
                $where = " WHERE Estatus = 3";
                break;
            case 17:
                //Stat para Traer la Informacion del # de Seguimientos del Ticket
                $tabla = " BSHSeguimientoReportes ";
                $where = " WHERE Folio= $NoFolio AND NoDepartamento= $NoDepartamento AND Anio= '$Anio' ";
                break;
            case 18:
                //Stat para Traer la Informacion del # de Tickets a Seguridad
                $tabla = " BSHReportes ";
                $where = " WHERE Estatus <= 3 AND NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoDepartamento = '1903' ";
                break;
            case 19:
                //Stat para Traer la Informacion del # de Tickets a Sistemas
                $tabla = " BSHReportes ";
                $where = " WHERE Estatus <= 3 AND NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoDepartamento = '0109'";
                break;
            case 20:
                //Stat para Traer la Informacion del # de Tickets a Matenimiento
                $tabla = " BSHReportes ";
                $where = " WHERE Estatus <= 3 AND NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoDepartamento = '0205' ";
                break;
            case 21:
                //Stat para Traer la Informacion del # de Seguimientos del Ticket
                $tabla = " BSHAdjuntos ";
                $where = " WHERE NoDepartamento = $NoDepartamento AND Anio = $Anio AND Folio = $NoFolio AND Estatus = 1 AND  TipoArchivo = 2 ";
                break;
            case 22:
                //Stat para Traer la Informacion del # de Seguimientos del Ticket
                $tabla = " BSHAdjuntos ";
                $where = " WHERE NoDepartamento = $NoDepartamento AND Anio = $Anio AND Folio = $NoFolio AND Estatus = 1 AND  TipoArchivo = 1 ";
                break;
            case 23:
                //Nueva Opcion para Mostrar los Tickets Pendientes del Usuario Tecnico Por Departamento
                $tabla = " BSHReportes ";
                $where = " WHERE Estatus <= 3 AND NoSucursal = '".$_SESSION['data_departamento']['NoDepartamento']."' AND NoDepartamento = '$Departamento_Tecnico' ";

                break;
        }

        if($Ceros == null){$Ceros = 3;}

        $this->_query = "SELECT COUNT(Folio) FROM ".$tabla.$where." " ;
        $this->get_result_query();

        $resultado = $this->_rows[0][0];

        return $this->getFormatFolio($resultado,$Ceros);
    }

    //Constructor de Condicion
    public function Constructor_Where($arrayData,$returCadena_array){


        foreach($arrayData as $id=>$valor){

            if($valor != "0"){

                $valor = "'$valor'";
                $Cond[] = array($id,$valor);
            }
        }

        $size = count($Cond);

        for($i=0;$i <= $size;$i++){
            if($size > $i){
                $and = " and ";
            }else{
                $and="";
            }
            $where[] = $Cond[$i][0]."=".$Cond[$i][1].$and;
        }
        $cadena = substr($where[0].$where[1].$where[2].$where[3].$where[4].$where[5].$where[6].$where[7].$where[8],0,-5);

        switch(strtolower($returCadena_array)){
            case 'cadena':
                return $cadena;
                break;
            case 'array':
                return $where;
                break;
            default:
                echo "<script>MyAlert('error opcion no encontrada','error')</script>";
                break;
        }

    }

    public function getNombreMes($mes){

        if ($mes==1) $mes="Enero";
        if ($mes==2) $mes="Febrero";
        if ($mes==3) $mes="Marzo";
        if ($mes==4) $mes="Abril";
        if ($mes==5) $mes="Mayo";
        if ($mes==6) $mes="Junio";
        if ($mes==7) $mes="Julio";
        if ($mes==8) $mes="Agosto";
        if ($mes==9) $mes="Septiembre";
        if ($mes==10) $mes="Octubre";
        if ($mes==11) $mes="Noviembre";
        if ($mes==12) $mes="Diciembre";

        return $mes;

    }


}