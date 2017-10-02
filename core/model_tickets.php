<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 01/02/2017
 * Time: 04:25 PM
 */

namespace core;

include "seguridad.php";


class model_tickets extends seguridad
{


    public function setAgregarFirma($data_array = array()){

        if(
            array_key_exists('nombre_firma',$data_array) &&
            array_key_exists('imagen',$data_array)
        ){
            // Validar que no cuenta ya con una Firma

            $this->_query = "SELECT * FROM BSHFirmaReportes WHERE Anio = '$data_array[anio]' AND NoDepartamento = '$data_array[NoDepartamento]'  AND Folio = '$data_array[folio]' ";
            $this->get_result_query();

            if(count($this->_rows)){
                $this->_confirm = false;
                $this->_message = "Ya se encontro una firma para este reporte";

            }else{

                $this->_query = " CALL sp01_agregar_fimra_reportes
                (
                '$data_array[anio]',
                '$data_array[folio]',
                '$data_array[NoDepartamento]',
                '$data_array[nombre_firma]',
                '$data_array[imagen]',
                '$data_array[FechaFirma]',
                '$data_array[NoUsuarioFirma]'
                )";
                $this->execute_query();

                $this->_confirm = true;
                $this->_message = "Se guardo la firma correctamente";

            }


        }else{
                $this->_confirm = false;
                $this->_message = "Error no se encontraron los paramentros para el registro";
        }

    }

    public function getValidarFirma($data_array = array()){

        if(
            array_key_exists('folio',$data_array) &&
            array_key_exists('anio',$data_array) &&
            array_key_exists('NoDepartamento',$data_array)
        ){
            $this->_query = "SELECT * FROM BSHFirmaReportes WHERE Anio = '$data_array[anio]' AND NoDepartamento = '$data_array[NoDepartamento]'  AND Folio = '$data_array[folio]' ";
            $this->get_result_query();

            if(count($this->_rows)){
                return true;
            }else{
                return false;
            }

        }else{
                return false;
        }

    }

    public function set_registra_ticket($data_ticket = array()){

        if(array_key_exists('NoSucursal',$data_ticket) || array_key_exists('solicitante',$data_ticket) ){
            //si existe la llave

            //datos del servidor
            $FechaActual = $this->getFormatFecha($data_ticket['FechaAlta'],1);
            $AnioActual = substr($FechaActual,0,4);
            $HoraActual = date("H:i:s");

            //Datos del Usuario
            $NoDepartamento = $_SESSION['data_departamento']['NoDepartamento'];
            $UsuarioRecibe = $_SESSION['data_login']['NoUsuario'];

            // Calculo de Fecha Promesa
            $FechaPromesa = $this->getFechaPromesa($data_ticket['prioridad'],$FechaActual);

            //si el Ticket lo levanta una Sucursal, se pasa el departamento tecnico a la variable @$NoDepartamento
            if(!$data_ticket['param_registra'] == 0){
                $NoDepartamento = $data_ticket['param_registra'];
            }

            //Valida Si el Departamento es Mantenimiento, por que este departamento no cuenta con estos catalogos.
            if($NoDepartamento == '0205'){
                $data_ticket['NoArea'] = 0;
                $data_ticket['NoCategoria'] = 0;
                $data_ticket['NoEstado'] = 1;
            }

            // Valida Si el Estado esta en Progreso o en Espera y Se Asigna al UsuarioRecibe
            if($data_ticket['NoEstado'] == 2 or $data_ticket['NoEstado'] == 3){

                if($data_ticket['NoUsuarioAsignado'] <= 0 ){

                    $data_ticket['NoUsuarioAsignado'] = $UsuarioRecibe;

                }
            }

            //Valida Si tiene Usuario Asignado y si el estatus es igual a Abierto se Cambia a Progreso.
            if($data_ticket['NoUsuarioAsignado'] > 0 ){
                if($data_ticket['NoEstado'] == 1){
                    $data_ticket['NoEstado'] = 2;
                }
            }

            if ($data_ticket['NoEstado'] >= 4){
                $data_ticket['Noestado'] = 2;
                $estado_Anterior = 4;
                $data_ticket['NoUsuarioAsignado'] = $UsuarioRecibe;
            }

            //Se lanza el Query para Registrar el Ticket.
            $this->_query = "CALL sp_RegistrarTicket(
            '$AnioActual',
            '$NoDepartamento',
            '$FechaActual',
            '$HoraActual',
            '$data_ticket[reporte]',
            '$data_ticket[descripcion_reporte]',
            '$data_ticket[medio_contacto]',
            '$data_ticket[prioridad]',
            '$UsuarioRecibe',
            '$data_ticket[NoUsuarioAsignado]',
            '$data_ticket[NoEstado]',
            '$data_ticket[NoArea]',
            '$data_ticket[NoSucursal]',
            '$data_ticket[solicitante]',
            '$data_ticket[NoCategoria]',
            '$FechaPromesa',
            '$data_ticket[tipo_servicio]',
            @N_NumFolio
            )";

            $this->execute_query();

            $this->_query = "SELECT @N_NumFolio as _folio";

            $this->get_result_query();

            // Recuperar el Numero de Ticket Registrado
            $NoTicket = $this->_rows[0][0];

            if($_SESSION['data_departamento']['NoDepartamento'] != '0205'){
                echo '<script language="JavaScript">fnsdMenu(3,"fl='.$NoTicket.'&dpto='.$NoDepartamento.'&anio='.$AnioActual.'&state='.$estado_Anterior.'");</script>';
            }else{
                echo '<script language="JavaScript">fnsdMenu(10,"fl='.$NoTicket.'&dpto='.$NoDepartamento.'&anio='.$AnioActual.'&state='.$estado_Anterior.'");</script>';
            }


            $this->_confirm = true;

        }else{
            // las llaves principales para el registro no existen
            $this->_confirm = false;
            $this->_message = "Error no se encontraron las llaves principales, para el registro del ticket";
        }

    }

    public function Adjuntar_Archivo($NoSucursal,$AnioTicket,$NoTicket,$NoDepartamento,$FechaActual,$HoraActual,$RutaArchivo,$NombreArchivo,$NoUsuario,$ip1,$ip2,$tipoDocumento){

        $this->_query = "CALL sp_agregar_adjuntos_tickets('$NoSucursal','$AnioTicket','$NoTicket','$NoDepartamento','$FechaActual','$HoraActual','$RutaArchivo','$NombreArchivo','$NoUsuario','$ip1','$ip2','$tipoDocumento')";
        $this->get_result_query();

    }

    public function cerrar_ticket($NoTicket,$TipoCierre,$Solucion,$NoUsuario,$AnioTicket,$NoDepartamento,$TipoAtencion){
        $this->_confirm = false;

        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");

        $this->_query = "CALL sp_cerrar_ticket(
                            '$NoTicket',
                            '$TipoCierre',
                            '$TipoAtencion',
                            '$Solucion',
                            '$NoUsuario',
                            '$FechaActual',
                            '$HoraActual',
                            '$AnioTicket',
                            '$NoDepartamento'
                           )";

        if(!$this->execute_query()){
            $this->_confirm = true;
        }

    }

    public function seguimiento_ticket($AnioTicket,$NoTicket,$NoDepartamento,$TpoAtencion,$Seguimiento,$NoUsuario){

        $this->_confirm = false;

        $this->_query = "SELECT Estatus FROM BSHReportes where  Anio = '$AnioTicket' AND NoDepartamento = '$NoDepartamento' AND Folio = '$NoTicket'  ";
        $this->get_result_query();

        // Informacion de Fecha y Hora
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");

        $estatus = $this->_rows[0][0];


        // si el estatus esta en abierto se pasa a en progreso
        if($estatus = 1){
            $estatus = 2 ;
        }

        // REgistrar el seguimiento del ticket
        $this->_query = "CALL sp_SeguimientoReporte('$AnioTicket','$NoTicket','$NoDepartamento','$TpoAtencion','$Seguimiento','$NoUsuario','$estatus','$FechaActual','$HoraActual')";

        if(!$this->execute_query()){
            $this->_confirm = true;
        }
    }

    //Mostrar Imagenes Adjuntas
    public function mostrar_imagenes_adjuntas($NoTicket,$NoDepartamento,$AnioTicket,$TipoArchivo){
        $cont = 0;
       $this->_query = "SELECT A.ID,A.NombreAdjunto,A.FechaAlta,A.HoraAlta,A.UsuarioSube
                                FROM BSHAdjuntos AS A
                                WHERE A.Folio=$NoTicket AND A.Estatus = 1 AND A.TipoArchivo = $TipoArchivo AND A.Anio = '$AnioTicket' AND A.NoDepartamento = '$NoDepartamento' ORDER BY A.ID ASC";

       $this->get_result_query();

       echo '<div class="row">';
        for($i = 0 ; $i < count($this->_rows); $i++ ){
            $cont++;
            echo '<section>
                            <div class="col-md-2" >
                                <div class="pabel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <div class="row" style="margin-top:-9px;margin-bottom:-10px;">
                                                        <a href="#" onclick="fnsdAbrirModalTicket(11,\''.$this->_rows[$i][1].'\',1,1)">
                                                            <img src="modules/01HelpDesk/Adjuntos/pictures/'.$this->_rows[$i][1].'" width="100%" height="99vh">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="panel-footer" style="padding:3px;">
                                                    <span class="pull-left" style="font-size:1.55vh">'.$this->_rows[$i][1].'</span>
                                                    <span class="pull-right">
                                                        <a style="text-decoration:underline;cursor:pointer;"
                                                            onclick="fnsdEliminaAdjunto(' . $this->_rows[$i][0] . ',' . $NoTicket . ',' . $AnioTicket . ',\''.$NoDepartamento.'\','.$TipoArchivo.')"
                                                            data-toggle="tooltip" data-placement="bottom" title=" Eliminar" >
                                                            <i class="glyphicon glyphicon-trash small"></i>
                                                        </a>
                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </section>';
        }
        echo '</div>';
    }

    //Eliminar Adjuntos del Ticket
    public function eliminar_adjunto($tipoArchivo,$Anio,$NoDepartamento,$Folio,$ID){
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");
        $this->_query = "
                        UPDATE BSHAdjuntos
                          SET Estatus=5,
                            UsuarioElimina='".$_SESSION['data_login']['NoUsuario']."',
                            FechaElimina='".$FechaActual."',
                            HoraElimina='".$HoraActual."'
                        WHERE NoDepartamento='$NoDepartamento' AND  Anio=$Anio AND Folio= $Folio AND ID = $ID AND TipoArchivo='".$tipoArchivo."'";
        $this->execute_query();
    }

    public function mostrar_adjuntos($folio,$AnioTicket,$NoDepartamento,$tipoArchivo){
        $query = "
                SELECT A.ID,A.NombreAdjunto,A.FechaAlta,A.HoraAlta,U.NombreDePila,A.UsuarioSube,A.RutaAdjunto
                FROM BSHAdjuntos AS A
                  JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                  ON A.UsuarioSube = U.NoUsuario 
                WHERE A.Folio= ".$folio." AND A.Estatus = 1 AND A.TipoArchivo = ".$tipoArchivo."
                    AND A.Anio = ".$AnioTicket." AND A.NoDepartamento = '$NoDepartamento'
                ORDER BY A.ID DESC"
        ;
        $this->_query = $query;
        $this->get_result_query();

            for($i = 0 ; $i < count($this->_rows); $i++){
                echo '<tr>
                        <td>' . $this->_rows[$i][0] . '</td>
                        <td>' . $this->_rows[$i][1] . '</td>
                        <td>' . $this->getFormatFecha( $this->_rows[$i][2], 2 ) . '</td>
                        <td>' . $this->_rows[$i][3] . '</td>
                        <td>' . $this->_rows[$i][4] . '</td>
                        <td>
                            <a class="btn btn-success btn-xs" style="width:5vh;color:#fff"
                                href="modules/01HelpDesk/Adjuntos/documents/' . $this->_rows[$i][1] . '" title="Descargar" download >
                                    <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                            <a class="btn btn-danger btn-xs" style="width:5vh;color:#fff"
                                onclick="fnsdEliminaAdjunto('.$this->_rows[$i][0].','.$folio.','.$AnioTicket.',\''.$NoDepartamento.'\','.$tipoArchivo.')" >
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
                        </tr>';
            }

    }

    public function reasignar_ticket($NoUsuarioAsignar,$Ticket,$AnioTicket,$NoDepartamento){

        // ReAsignacion de Ticket
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");

        $NoUsuario = $_SESSION['data_login']['NoUsuario'];


        $this->_query =
            "CALL sp_reasigna_ticket
            ('$AnioTicket','$Ticket','$NoDepartamento','$NoUsuario','$NoUsuarioAsignar','$FechaActual','$HoraActual')";


        if(!$this->execute_query()){

            $this->_confirm = true ;
        }

    }

    public function get_informacion_ticket($NoTicket,$AnioTicket,$DeptoTicket,$DeptoUser){


        // Informacion del Ticket
        $this->_query = "SELECT 
                            R.Anio,
                            R.Folio,
                            R.NoDepartamento,
                            D.Descripcion as NombreDepartamento,
                            R.Fecha,
                            R.HoraInicioReporte,
                            R.DescripcionReporte,
                            R.Reporte,
                            R.MedioContacto,
                            CC.Descripcion as NombreMedioContacto,
                            R.PrioridadTicket,
                            CC2.Descripcion as DescripcionPrioridad,
                            R.TipoMantenimiento,
                            CC3.Descripcion as DescripcionTipoMantenimiento,
                            R.NoUsuarioRecibe,
                            U.NombreDePila as NombreUsuarioRecibe,
                            R.NoUsuarioAsignado,
                            U2.NombreDePila as NombreUsuarioAsignado, 
                            R.Estatus, 
                            E.Descripcion as DescripcionEstado, 
                            R.NoArea, 
                            A.Descripcion as DescripcionArea, 
                            R.NoSucursal, 
                            S.Descripcion as NombreSucursal,
                            R.idEmpleado, 
                            R.Categoria, 
                            C.descripcion as NombreCategoria, 
                            R.FechaPromesa,
                            S.Domicilio,
                            S.Telefono01 as Telefono1Sucursal,
                            S.Telefono02 as Telefono2Sucursal,
                            S.Correo as CorreoSucursal,
                            concat_ws(' ',U3.Nombre,U3.ApPaterno,U3.ApMaterno) as NombreSolicitante
                        FROM BSHReportes AS R 
                            JOIN BGECatalogoDepartamentos AS D ON R.NoDepartamento = D.NoDepartamento 
                            LEFT JOIN BSHCatalogoCatalogos AS CC ON R.MedioContacto = CC.idDescripcion AND CC.idCatalogo = 2 
                            LEFT JOIN BSHCatalogoCatalogos AS CC2 ON R.PrioridadTicket = CC2.idDescripcion AND CC2.idCatalogo = 1
                            LEFT JOIN BSHCatalogoCatalogos AS CC3 ON R.TipoMantenimiento = CC3.idDescripcion AND CC3.idCatalogo = 5
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U ON R.NoUsuarioRecibe = U.NoUsuario 
                            LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios U2 ON R.NoUsuarioAsignado = U2.NoUsuario 
                            LEFT JOIN SINTEGRALGNL.BGEEmpleados as U3 ON R.idEmpleado = U3.idEmpleado
                            JOIN BSHCatalogoEstatus AS E ON R.Estatus = E.NoEstatus
                            JOIN BGECatalogoDepartamentos AS S ON R.NoSucursal = S.NoDepartamento
                            LEFT JOIN BSHCatalogoAreas AS A ON R.NoArea = A.NoArea AND R.NoDepartamento = A.NoDepartamento
                            LEFT JOIN BSHCatalogoCategoria AS C ON R.Categoria = C.nocategoria AND R.NoDepartamento = C.NoDepartamento
                        WHERE 
                          R.Anio=".$AnioTicket." AND 
                          R.NoDepartamento = '".$DeptoTicket."' AND 
                          Folio=".$NoTicket." 
                          ORDER BY Folio ASC ";

        $this->get_result_query();

    }

    public function Historial($AnioTicket,$NoDepartamento,$NoTicket){
        //Actualizaciones
        // 1.- Se Agrega el campo de Tipo de atencion para mostrar en el Seguimiento del Reporte.
        $this->_query = "SELECT R.FolioSeguimiento,R.Seguimiento,R.NoUsuarioSeguimiento, U.NombreDePila, R.NoEstatus, E.Descripcion, R.FechaSeguimiento,R.HoraInicioSeguimiento,CC.Descripcion
                    FROM BSHSeguimientoReportes AS R
                    LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios AS U
                      ON R.NoUsuarioSeguimiento = U.NoUsuario
                    JOIN BSHCatalogoEstatus AS E
                      ON R.NoEstatus = E.NoEstatus
                    JOIN BSHCatalogoCatalogos AS CC
                        ON R.TipoAtencion = CC.idDescripcion and CC.idCatalogo = 2
                    WHERE R.Anio= ".$AnioTicket." AND R.NoDepartamento= '$NoDepartamento' AND R.Folio= ".$NoTicket." ORDER by R.FolioSeguimiento DESC";
        $this->get_result_query();
    }

    public function getAlertFechaPromesa($FechaAlta,$FechaPromesa){
        //Validar si la Fecha Promesa es Igual o mayor a la Fecha Alta
        $fecha1 = date_create($FechaAlta);
        $fecha2 = date_create($FechaPromesa);

        $Dias = date_diff($fecha1, $fecha2);
        return $Dias->format('%R%a');
    }

    //Metodo para Retornar el Ultimo Ticket
    public function getTicketAnterior($NoDepartamento){
        $AnioActual = date('Y');
        $this->_query =
            "SELECT MAX(Folio)as Folio FROM BSHReportes WHERE NoDepartamento = '$NoDepartamento' AND Anio = '$AnioActual' ";

        $this->get_result_query();

        if(count($this->_rows)>0){
            $resultado = $this->_rows;
            return $this->getFormatFolio($resultado[0]['Folio'],4);
        }
    }

    public function getStatAdjuntosTicket($folio,$dpto,$AnioTicket,$estado){

        $this->_query = "SELECT ID FROM BSHAdjuntos WHERE Folio='".$folio."' AND NoDepartamento='".$dpto."' AND Estatus=1 AND Anio='".$AnioTicket."'";
        $this->get_result_query();

        if(count($this->_rows)>0){
            $iconAdjunto = '&nbsp;<span style="font-size:10px;" class="fa fa-paperclip  text-info"></span>';
        }else{
            $iconAdjunto="&nbsp;";
        }

        $this->_query = "SELECT * FROM BSHSeguimientoReportes WHERE Folio='".$folio."' AND NoDepartamento='".$dpto."' AND Anio='".$AnioTicket."'";
        $this->get_result_query();

        if(count($this->_rows) >=2){
            $iconSeg = '&nbsp;<span style="font-size:10px;" class="fa fa-share text-info"></span>';
        }else{
            $iconSeg="";
        }

        return $iconAdjunto.$iconSeg;
    }

    //Metodo para Calcular la Fecha Promesa
    public function getFechaPromesa($prioridad,$fechaAlta){

        $DiaSemanaActual = date("N"); //Dia de la Semana
        $UltimoDia = date("t");//date("t")Ultimo dia del Mes (ejem. 30,31, 28 o 29)

        $DiaActual = substr($fechaAlta,6,2); //Dia Actual
        $AnioActual = substr($fechaAlta,0,4); //A?o Actual
        $MesActual = substr($fechaAlta,4,2);  //Mes Actual

        switch ($prioridad){
            case 1:
                $dias = 4;
                break;
            case 2:
                $dias = 2;
                break;
            case 3:
                $dias = 1;
                break;
            case 4:
                $dias = 0;
                break;
        }
        $nuevafecha = strtotime ( '+'.$dias. 'day' , strtotime ( $fechaAlta ) ) ;
        $nuevafecha = date ( 'Ymd' , $nuevafecha );
        return $nuevafecha;

    }

    public function getPrioridadTicket($opc,$NoPrioridad){

        $this->_query = "SELECT idDescripcion,Descripcion FROM BSHCatalogoCatalogos where idCatalogo = 1 AND idDescripcion = $NoPrioridad";

        $this->get_result_query();

        if(count($this->_rows) > 0 ){
            $result = $this->_rows ;
            $hayDatos = true;
        }
        switch($opc){
            case 1: //Retornar el el Nombre de la Prioridad
                if($hayDatos){ return $result[0]['Descripcion'];}
                break;
            case 2://Retornar Nombre con Clase de label y badge
                if($hayDatos){
                    switch($result[0]['idDescripcion']){
                        case 1:
                            $label = "label label-info ";
                            break;
                        case 2:
                            $label = "label label-warning ";
                            break;
                        case 3:
                            $label = "label label-danger ";
                            break;
                    }
                    return "<span class='".$label."'>&nbsp;&nbsp;".substr($result[0]['idDescripcion'],0,1)."&nbsp;&nbsp;</span>";
                }
        }

    }
}