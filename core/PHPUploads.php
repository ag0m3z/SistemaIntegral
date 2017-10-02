<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 11/02/2017
 * Time: 01:25 PM
 */

namespace core;
include "model_tickets.php";


class PHPUploads extends model_tickets
{
    public $FolderName;
    public $FolderNameTemp;

    public $NameFile;
    public $NamFileTemp;

    public $FileType;
    public $FileSize;
    public $File;
    public $FileExte;

    public $Extencion;
    public $PregMatch;

    public function UploadDocument($tipoDocumento,$fl,$dpto,$Anio,$NoSucursal){

        //Adjuntar Documento al Ticket
        $PartesNombre = explode('.',$this->NameFile);
        $extFile = end($PartesNombre);
        $ext_correcta = in_array($extFile,$this->Extencion);

        //Expresion para Sacar el Filtro
        $tipo_correcto = preg_match($this->PregMatch,$this->FileType);

        if(in_array($extFile,$this->Extencion)){
            //Validar que el Archivo no este Danado
            if($this->File['error'] > 0){
                echo "Error al Subir el Archivo".$this->File['error'].$this->File['size'];
            }else{
                //Archivo Correcto y en Buen estado
                $Nombre = $this->GeneraNombre($tipoDocumento,$extFile,$fl,$dpto,$Anio,$NoSucursal);

               if(! move_uploaded_file($this->NamFileTemp,$this->FolderName.$Nombre)){

                   return false;

               }else{

                   $ip = "127.0.0.1";
                   $ip2 = "127.0.0.1";

                   // Llamar a  La Funcion Adjuntar_Archivos()
                   $this->Adjuntar_Archivo($NoSucursal,$Anio,$fl,$dpto,date("Ymd"),date("H:i:s"),$this->FolderName,$Nombre,$_SESSION['data_login']['NoUsuario'],$ip,$ip2,$tipoDocumento);

               }

            }
        }else{
            //Extencion Invalida
            echo "<script language='JavaScript'>MyAlert('Tipo de documento invalido','error');</script>";
        }
    }

    public function GeneraNombre($tipoDoc,$extencion,$folio,$dpto,$Anio,$NoSucursal){

        switch ($tipoDoc){
            case 1:
                $NombreFinal = str_pad($folio,4,"0",STR_PAD_LEFT)."_".str_pad($dpto,2,"0",STR_PAD_LEFT)."_".date("Ymd")."_1.$extencion";
                $this->_query = "SELECT ID FROM BSHAdjuntos WHERE Folio =$folio AND Anio=$Anio AND NoSucursal= '$NoSucursal'";
                $this->get_result_query();
                $maxID = count($this->_rows);
                if($maxID>=1){
                    $tt=($maxID + 1);
                    $NombreFinal = str_pad($folio,4,"0",STR_PAD_LEFT)."_".str_pad($dpto,2,"0",STR_PAD_LEFT)."_".date("Ymd") ."_$tt.$extencion";
                }
                return $NombreFinal;
                break;
            case 2:
                $NombreFinal = str_pad($folio,4,"0",STR_PAD_LEFT)."_".str_pad($dpto,2,"0",STR_PAD_LEFT)."_".date("Ymd")."_1.$extencion";
                $this->_query = "SELECT ID FROM BSHAdjuntos WHERE Folio =$folio AND Anio=$Anio AND NoSucursal= '$NoSucursal' ";
                $this->get_result_query();
                $maxID = count($this->_rows);

                if($maxID>=1){
                    $tt=($maxID + 1);
                    $NombreFinal = str_pad($folio,4,"0",STR_PAD_LEFT)."_".str_pad($dpto,2,"0",STR_PAD_LEFT)."_".date("Ymd") ."_$tt.$extencion";
                }
                return $NombreFinal;
                break;
            case 3:
                break;
            default:

                break;
        }
    }

    public function UploadEquipos($tipo,$folio){
        //Adjuntar Documento al Ticket
        $PartesNombre = explode('.',$this->NameFile);
        $extFile = end($PartesNombre);
        $ext_correcta = in_array($extFile,$this->Extencion);

        //Expresion para Sacar el Filtro
        $tipo_correcto = preg_match($this->PregMatch,$this->FileType);

        if(in_array($extFile,$this->Extencion)){
            //Validar que el Archivo no este Danado
            if($this->File['error'] > 0){
                echo "Error al Subir el Archivo".$this->File['error'];
            }else{
                //Archivo Correcto y en Buen estado
                $NombreFinal = str_pad($folio,4,"0", STR_PAD_LEFT)."_".date('Ymd')."_1.$extFile";

                //Si el Archivo Ya Existe en la BD.
                $this->_query = "SELECT COUNT(Folio) FROM BSHAdjuntosEquipos WHERE Folio = '$folio' ORDER BY Folio ASC";
                $this->get_result_query();


                $maxID = $this->_rows[0];

                if($maxID[0] >=1 ){
                    $tt=($maxID[0] + 1);
                    $NombreFinal = str_pad($folio,4,"0",STR_PAD_LEFT)."_".date("Ymd")."_$tt.$extFile";
                }

                if(!move_uploaded_file($this->NamFileTemp,$this->FolderName.$NombreFinal) ){

                    return false;

                }else{

                    return  $NombreFinal;

                }

            }
        }else{
            //Extencion Invalida
            echo "<script language='JavaScript'>MyAlert('Tipo de documento invalido','error');</script>";
        }
    }


}