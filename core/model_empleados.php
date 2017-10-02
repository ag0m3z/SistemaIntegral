<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 31/01/2017
 * Time: 04:47 PM
 */

namespace core;

include "seguridad.php";

class model_empleados extends seguridad
{

    //Generar Numero de Empleado para externo
    public function set_genera_NoEmpleado(){

        $this->_query =
            "SELECT NoEmpleado,(substring(NoEmpleado,4,6) + 1),
                    (substring(NoEmpleado,5)+1) as EmpleadoExterno
            FROM SINTEGRALGNL.BGEEmpleados
            WHERE length(NoEmpleado) >= 9 AND substring(NoEmpleado,3,2) = '29' order by EmpleadoExterno DESC LIMIT 1
            ";

        $this->get_result_query();

        if(count($this->_rows) >= 1){

            $dataRow = $this->_rows[0];

            return $dataRow[1];

        }else{
            return "900001";
        }
    }

    //Funcion para realizar la busqueda de empleado
    public function buscar_empleado($opcion,$String){

        $this->_query = "SELECT a.NoDepartamento,
                        b.Descripcion,
                        a.NoEmpleado,
                        a.Nombre,
                        a.ApPaterno,
                        a.ApMaterno,
                        IF(a.NoEstado = 1,'Activo','Desactivado'),
                        a.FechaAlta,
                        a.FechaUM,
                        idEmpleado
        FROM SINTEGRALGNL.BGEEmpleados as a LEFT JOIN BGECatalogoDepartamentos AS b
        ON a.NoDepartamento = b.NoDepartamento
        WHERE 
          a.NoEmpleado LIKE '%$String%' OR 
          b.Descripcion  LIKE '%$String%' OR 
          CONCAT_WS(' ',a.Nombre,a.ApPaterno,a.ApMaterno) LIKE '%$String%'  ;
        ";


        $this->get_result_query();

        $rowData = $this->_rows;

        if(count($rowData) > 0 ){
            for($i = 0; $i < count($rowData); $i++){
                if($rowData[$i][6] == "Desactivado"){

                    $Estatus = "<span class='text-danger'>".$rowData[$i][6]."</span>";
                    $Departamento = "<span class='text-danger'>".$rowData[$i][1]."</span>";
                    $NombreCompleto = "<span class='text-danger'>".$rowData[$i][3]." ".$rowData[$i][4]." ".$rowData[$i][5]."</span>";
                    $FechaAlta = "<span class='text-danger'>".$this->getFormatFecha($rowData[$i][7],2)."</span>";
                    $FechaUM = "<span class='text-danger'>".$this->getFormatFecha($rowData[$i][8],2)."</span>";
                }else{
                    $Estatus = $rowData[$i][6];
                    $Departamento = $rowData[$i][1];
                    $NombreCompleto = $rowData[$i][3]." ".$rowData[$i][4]." ".$rowData[$i][5];
                    $FechaAlta = $this->getFormatFecha($rowData[$i][7],2);
                    $FechaUM = $this->getFormatFecha($rowData[$i][8],2);
                }

                $NoEmpleado = $this->getFormatFolio($rowData[$i][2],9);

                if($opcion == 1){
                    if($rowData[$i][6] == 'Activo'){

                        $data[] = array(
                            "rFila00"=>"<span class='text-info btn-link' onclick='jsSdSeleccionarEmpleado(\"".$rowData[$i][0]."\",\"".$rowData[$i][1]."\",\"".$rowData[$i][9]."\",\"".$rowData[$i][3].' '.$rowData[$i][4].' '.$rowData[$i][5]."\",\"modalbtnclose\",\"".$rowData[$i][2]."\")' >$NoEmpleado</span>",
                            "rFila01"=>$Departamento,
                            "rFila02"=>$NombreCompleto,
                            "rFila03"=>$Estatus,
                            "rFila04"=>$FechaAlta,
                            "rFila05"=>$FechaUM
                        );

                    }

                }elseif($opcion == 2){
                    if($rowData[$i][6] == 'Activo'){
                        $data[] = array(
                            "rFila00"=>"<span class='text-info btn-link' onclick='jgCambiarEmpleado(1,\"".$rowData[$i][0]."\",\"".$rowData[$i][1]."\",\"".$rowData[$i][9]."\",\"".$rowData[$i][3].' '.$rowData[$i][4].' '.$rowData[$i][5]."\",\"modalbtnclose\")' >$NoEmpleado</span>",
                            "rFila01"=>$Departamento,
                            "rFila02"=>$NombreCompleto,
                            "rFila03"=>$Estatus,
                            "rFila04"=>$FechaAlta,
                            "rFila05"=>$FechaUM
                        );
                    }
                }
            }

            return $data;

        }else{
            return array();
        }


    }

    //Funcion para registrar empleados
    public function set_empleado($data_empleado = array()){

        $NoUsuario = $_SESSION['data_login']['NoUsuario'];
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");
        $this->_confirm = false;

        //validar las llaves para el alta de empleado
        if(array_key_exists('NoEmpleado',$data_empleado) || array_key_exists('NoDepartamento',$data_empleado)|| array_key_exists('nombre_empleado',$data_empleado) ||array_key_exists('appaterno',$data_empleado)){

            //validar que el usuario no exista ya registrado
            $this->_query = "SELECT NoEmpleado FROM SINTEGRALGNL.BGEEmpleados WHERE NoEmpleado = $data_empleado[NoEmpleado] ";
            $this->get_result_query();

            if(!count($this->_rows) > 0){

                //Query para realizar el registro del empleado
                $this->_query =
                    "CALL SINTEGRALGNL.sp_alta_empleado(
                    '$data_empleado[idEmpleado]',
                    '$data_empleado[NoDepartamento]',
                    '$data_empleado[NoEmpleado]',
                    '$data_empleado[nombre_empleado]',
                    '$data_empleado[appaterno]',
                    '$data_empleado[apmaterno]',
                    '$data_empleado[correo_empleado]',
                    '$data_empleado[direccion_empleado]',
                    '$data_empleado[telefono01]',
                    '$data_empleado[telefono02]',
                    '$data_empleado[telefono03]',
                    '$data_empleado[telefono04]',
                    '$data_empleado[telefono05]',
                    '$data_empleado[foto_empleado]',
                    '$NoUsuario',
                    '$NoUsuario',
                    '$data_empleado[puesto_empleado]',
                    '$data_empleado[estatus]',
                    '$FechaActual',
                    '$HoraActual',
                    '$FechaActual',
                    '$HoraActual'
                    )";

                //Ejecutar Query
                $this->execute_query();
                //Extraer el ultimo id
                $this->_query = "SELECT @@identity AS idEmpleado";
                //Guardarlo en el Arreglo $this->_rows
                $this->get_result_query();

                //enviar como true la operacion de registro de nuevo empleado;
                $this->_confirm = true;

            }else{
                //el Usuario con la Nomina Ingresada, ya existe

                $this->_confirm = false;
                $this->_message = "El empleado con la Nomina: ".$data_empleado['NoEmpleado']." Ya existe.";

            }


        }else{
            //error en las llaves para el alta de empledo, no existen o estan incompletas
            $this->_confirm = false;
            $this->_message = "Error no se encontraron las llaves, para el alta de empleado";
        }



    }

    public function listar_empleados($filtro,$condicion=null){
        $FechaActual = date("Ymd");

        switch($filtro){
            case 1:
                //Mostrar todos los empleados sin condicion
                $where = "";
                break;
            case 2:
                //Mostrar solo los empleados Activos
                $where = " WHERE a.NoEstado = 1 ORDER BY a.idEmpleado DESC";
                break;
            case 3:
                //Mostrar solo los empleados inactivos
                $where = " WHERE a.NoEstado = 0 ORDER BY a.idEmpleado DESC";
                break;
            case 4:
                //Mostrar solo los ultimos 50 registros
                $where = " ORDER BY a.idEmpleado DESC LIMIT 0,50";
                break;
            case 5:
                //Mostrar solo los ultimos 100 registros
                $where = " ORDER BY a.idEmpleado DESC LIMIT 0,100";
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
                $where = " WHERE a.idEmpleado LIKE ".$condicion." OR CONCAT_WS(' ',a.Nombre,a.ApPaterno,a.ApMaterno) LIKE ".$condicion." OR a.NoEmpleado LIKE ".$condicion." OR b.Descripcion LIKE ".$condicion." AND a.NoEstado = 1 ORDER BY a.idEmpleado DESC";
                break;
            case 9:
                //Mostrar solo los registros actualizados del dia de hoy
                $where = " WHERE ".$condicion." ORDER BY a.idEmpleado DESC";
                break;
            default:
                $where = '';
                break;
        }

        $textSql  = "
        SELECT a.idEmpleado,a.NoEmpleado,CONCAT_WS(' ',a.Nombre,a.ApPaterno,a.ApMaterno),a.NoDepartamento,
        if(a.NoEstado = 1,'Activo','Inactivo'),a.Correo,a.Telefonoe01,a.Telefonoe02,a.Telefonoe03,
        a.FechaAlta,a.FechaUM,b.Descripcion,c.NombreDePila,d.NombreDePila
        FROM SINTEGRALGNL.BGEEmpleados as a
        LEFT JOIN BGECatalogoDepartamentos as b
        ON a.NoDepartamento = b.NoDepartamento
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as c
        ON a.NoUsuarioAlta = c.NoUsuario
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as d
        ON a.NoUsuarioUM = d.NoUsuario
    ";

        $this->_query = $textSql . $where;
        $this->get_result_query();


        if(count($this->_rows) > 0){
            for($i=0 ; $i < count($this->_rows);$i++){

                $NombreEstatus = $this->_rows[$i][4];


                if($this->_rows[$i][4] == 'Activo'){$estatus = "<span class='label label-success'>$NombreEstatus &nbsp;&nbsp;&nbsp;</span>";}else{$estatus = "<span class='label label-danger'>$NombreEstatus</span>";}

                $dataRow[] = array(
                    "hIdEmpleado"=>"<a href='javascript:void(0)' onclick='fnCatEditarEmpleado(".$this->_rows[$i][0].",1,1)'><span class='text text-primary'>".$this->getFormatFolio($this->_rows[$i][0],4)."</span></a>",
                    "hNoEmpleado"=>$this->_rows[$i][1],
                    "hNombre"=>$this->_rows[$i][2],
                    "hDepto"=>$this->_rows[$i][11],
                    "hEstado"=>$estatus,
                    "hCorreo"=>$this->_rows[$i][5],
                    "hTelefono1"=>$this->_rows[$i][6],
                    "hTelefono2"=>$this->_rows[$i][7],
                    "hTelefono3"=>$this->_rows[$i][8],
                    "hFechaA"=>$this->getFormatFecha($this->_rows[$i][9],2),
                    "hNoUsuarioAlta"=>$this->_rows[$i][12],
                    "hFechaU"=>$this->getFormatFecha($this->_rows[$i][10],2),
                    "hNoUsuarioUM"=>$this->_rows[$i][13]
                );
            }
            return $dataRow;
        }

    }

    // Funcion para listar empleados en formato de tarjeta de contacto
    public function list_card_contacts($data_contacto = array()){

        if(!array_key_exists('nombre_contacto',$data_contacto)){

            echo "No se encontro la llave";

        }else {

            $this->_query = "
              select * from (SELECT 
                a.NoDepartamento,b.Descripcion,a.NoEmpleado,
                SUBSTRING_INDEX(a.Nombre, ' ', 1 ) as NombreContacto,SUBSTRING_INDEX(a.ApPaterno, ' ', 1 )as ApPaternoContacto,
                a.ApMaterno,a.NoEstado,a.FechaAlta,a.FechaUM,
                a.idEmpleado,a.Correo,a.Direccion,a.Telefonoe01,
                a.Telefonoe02,a.Telefonoe03,a.idphoto,b.NoTipo as TipoDepartamento,b.AsignarReportes,b.Domicilio as DomicilioDepartamento,
                b.Telefono01 as TelDepto01,b.Telefono02 as TelDepto02,b.Telefono03 as TelDepto03,b.Telefono04 as TelDepto04,b.Correo as CorreoDepto
              FROM 
                SINTEGRALGNL.BGEEmpleados as a 
                JOIN BGECatalogoDepartamentos AS b
                ON a.NoDepartamento = b.NoDepartamento
              WHERE 
               (a.NoEstado = 1 AND 
               CONCAT_WS(' ',a.Nombre,a.ApPaterno,a.ApMaterno) LIKE '%$data_contacto[nombre_contacto]%') OR 
               b.Descripcion LIKE '%$data_contacto[nombre_contacto]%'  
               ORDER BY b.Descripcion,NombreContacto ASC  $data_contacto[limite] ) as Tabla WHERE Tabla.NoEstado = 1 ;

              ";

            $this->get_result_query();

            if (count($this->_rows) > 0) {

                $this->_confirm = true;
                $this->_message = "Se encontraron datos para procesar";
            } else {
                $this->_confirm = false;
                $this->_message = "No se encontraron datos para procesar";
            }
        }

    }


    //funcion para editar el empleado
    public function editar_empleado($data_empleado = array()){

        $NoUsuario = $_SESSION['data_login']['NoUsuario'];
        $FechaActual = date("Ymd");
        $HoraActual = date("H:i:s");

        if(array_key_exists('NoEmpleado',$data_empleado) || array_key_exists('NoDepartamento',$data_empleado)|| array_key_exists('nombre_empleado',$data_empleado) ||array_key_exists('appaterno',$data_empleado)){

        }else{

            //error en las llaves para el alta de empledo, no existen o estan incompletas
            $this->_confirm = false;
            $this->_message = "Error no se encontraron las llaves, para el alta de empleado";

        }


        $this->_query = "CALL SINTEGRALGNL.sp_editar_empleado(
        '$data_empleado[idEmpleado]',
        '$data_empleado[idEmpresa]',
        '$data_empleado[TipoEmpleado]',
            '$data_empleado[NoDepartamento]',
            '$data_empleado[NoEmpleado]',
            '$data_empleado[NombreEmpleado]',
            '$data_empleado[aPaterno]',
            '$data_empleado[aMaterno]',
            '$data_empleado[Correo]',
            '$data_empleado[Telefono01]',
            '$data_empleado[Telefono02]',
            '$data_empleado[Telefono03]',
            '$data_empleado[Direccion]',
            '$data_empleado[Telefono04]',
            '$data_empleado[Telefono05]',
            '$data_empleado[idFoto]',
            '$data_empleado[NoEstatus]',
            '$NoUsuario',
            '$data_empleado[NoPuesto]',
            '$FechaActual',
            '$HoraActual'
            )";

        $this->execute_query();

        $this->_confirm = true;



    }


}