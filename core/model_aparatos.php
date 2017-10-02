<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 21/02/2017
 * Time: 11:33 AM
 */

namespace core;

include "seguridad.php";


class model_aparatos extends seguridad
{

    //funcion para registrar las nuevas marcas
    public function set_nueva_marca($data = array()){

        //Validar parametros correctos
        if(
            array_key_exists('nocategoria',$data) &&
            array_key_exists('descripcion',$data)
        ){
            //Valudar que no exista ya el nuevo tipo de producto

            $this->_query = "SELECT Descripcion FROM BGECatalogoGeneral WHERE Descripcion = '$data[descripcion]'  AND Numero2 = '$data[nocategoria]' AND CodCatalogo = 6  ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                $this->_confirm = false;
                $this->_message = "La marca ya existe";
            }else{

                //Ultimo id
                $this->_query = "SELECT ifnull(MAX(OpcCatalogo),0) + 1 FROM BGECatalogoGeneral where CodCatalogo = 6 AND Numero2 = '$data[nocategoria]' AND OpcCatalogo <> 99";
                $this->get_result_query();
                $IdOpcCatalogo = $this->_rows[0][0];

                if($IdOpcCatalogo == NULL && $IdOpcCatalogo <= 0 ){
                    // Error en traer el ultimo ID
                    $this->_confirm = false;
                    $this->_message = "No se logro extraer el ultimo id";
                }else{

                    $this->_query = "
                    INSERT INTO BGECatalogoGeneral 
                    VALUES (
                    6,
                    '$IdOpcCatalogo',
                    '$data[descripcion]',
                    'Marcas',
                    '1',
                    '$data[abreviacion]',
                    '$data[nocategoria]',
                    '0',
                    '0',
                    '0',
                    '0',
                    '1',
                    '$data[NoUsuarioAlta]',
                    '$data[NoUsuarioAlta]',
                    '$data[FechaAlta]',
                    '$data[HoraAlta]',
                    '$data[FechaAlta]',
                    '$data[HoraAlta]'
                    )
                    ";

                    $this->execute_query();
                    $this->_confirm = true;
                    $this->_message = "Registrado correctamente";
                    return $IdOpcCatalogo;
                }


            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error no se encontraron los parametros para el registro";

        }

    }

    //Funcion para registrar los nuevos tipod de producots
    public function set_nuevo_tipo_producto($data = array()){

        //Validar parametros correctos
        if(
            array_key_exists('nocategoria',$data) &&
            array_key_exists('tipo_aparato',$data) &&
            array_key_exists('descripcion',$data)
        ){
            //Valudar que no exista ya el nuevo tipo de producto

            $this->_query = "SELECT Descripcion FROM BGECatalogoGeneral WHERE Descripcion = '$data[descripcion]'  AND Numero2 = '$data[nocategoria]' AND CodCatalogo = 5  ";
            $this->get_result_query();

            if(count($this->_rows) > 0 ){
                $this->_confirm = false;
                $this->_message = "El nuevo tipo producto ya existe";
            }else{

                //Ultimo id
                $this->_query = "SELECT ifnull(MAX(OpcCatalogo),0) + 1 FROM BGECatalogoGeneral where CodCatalogo = 5 AND Numero2 = '$data[nocategoria]' AND OpcCatalogo <> 99";
                $this->get_result_query();
                $IdOpcCatalogo = $this->_rows[0][0];

                if($IdOpcCatalogo == NULL && $IdOpcCatalogo <= 0 ){
                    // Error en traer el ultimo ID
                    $this->_confirm = false;
                    $this->_message = "El en extraer el ultimo id";
                }else{

                    $this->_query = "
                    INSERT INTO BGECatalogoGeneral 
                    VALUES (
                    5,
                    '$IdOpcCatalogo',
                    '$data[descripcion]',
                    'TipoAparato',
                    '$data[tipo_aparato]',
                    '$data[abreviacion]',
                    '$data[nocategoria]',
                    '0',
                    '0',
                    '0',
                    '0',
                    '1',
                    '$data[NoUsuarioAlta]',
                    '$data[NoUsuarioAlta]',
                    '$data[FechaAlta]',
                    '$data[HoraAlta]',
                    '$data[FechaAlta]',
                    '$data[HoraAlta]'
                    )
                    ";

                    $this->execute_query();
                    $this->_confirm = true;
                    $this->_message = "Registrado correctamente";
                    return $IdOpcCatalogo;
                }


            }

        }else{
            $this->_confirm = false;
            $this->_message = "Error no se encontraron los parametros para el registro";

        }

    }

    public function getVerPorcentajeClasificacion($LetraClasificacion,$opcEmpenoCompra,$Categoria_Producto){
        $this->_query = "SELECT Numero1N,Numero2N,Numero3N,Numero4N FROM BGECatalogoGeneral WHERE Descripcion = '".$LetraClasificacion."' AND CodCatalogo = 7 AND Numero2 =  '".$Categoria_Producto." '";
        $this->get_result_query();

        $result = $this->_rows[0];

        switch($opcEmpenoCompra){
            case 1:
                return $result[0];
                break;
            case 2:
                return $result[1];
                break;
            case 3:
                return $result[2];
                break;
            case 4:
                return $result[3];
                break;
        }

    }
    //funcion para redondear y retornar valor con formato
    public function getRoundEmpeno($PrecioProducto,$PorcentajeEmpeno,$PorcentajeCompra,$Clasificacion,$opc,$PorcentajeCompra01,$PorcentajeCompra02,$Categoria_Producto){
        $Empeno = $PorcentajeEmpeno;
        $Compra = $PorcentajeCompra;
        $Compra01 = $PorcentajeCompra02;
        $Compra02 = $PorcentajeCompra01;

        $ValorTotalEmpeno = round(($PrecioProducto * $Empeno ), -1);
        $ValorTotalCompra = round(($PrecioProducto * $Compra), -1);
        $ValorTotalCompra01 = round(($PrecioProducto * $Compra01), -1);
        $ValorTotalCompra02 = round(($PrecioProducto * $Compra02), -1);

        $ValorPorClasificacionAempeno = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('A',1,$Categoria_Producto)),-1);
        $ValorPorClasificacionACompra = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('A',2,$Categoria_Producto)),-1);
        $ValorPorClasificacionACompra01 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('A',3,$Categoria_Producto)),-1);
        $ValorPorClasificacionACompra02 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('A',4,$Categoria_Producto)),-1);

        $ValorPorClasificacionBempeno = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('B',1,$Categoria_Producto)),-1);
        $ValorPorClasificacionBCompra = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('B',2,$Categoria_Producto)),-1);
        $ValorPorClasificacionBCompra01 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('B',3,$Categoria_Producto)),-1);
        $ValorPorClasificacionBCompra02 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('B',4,$Categoria_Producto)),-1);

        $ValorPorClasificacionCempeno = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('C',1,$Categoria_Producto)),-1);
        $ValorPorClasificacionCCompra = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('C',2,$Categoria_Producto)),-1);
        $ValorPorClasificacionCCompra01 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('C',3,$Categoria_Producto )),-1);
        $ValorPorClasificacionCCompra02 = round(($PrecioProducto * $this->getVerPorcentajeClasificacion('C',4,$Categoria_Producto)),-1);

        if($Clasificacion == 'C'){
            $td_row = "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCempeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra."</td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
        }

        if($Clasificacion == 'B'){
            $td_row = "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCempeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra."</td>";

            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorTotalEmpeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra."</td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 90px;'></td>";
        }

        if($Clasificacion == 'A'){
            $td_row = "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCempeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionCCompra."</td>";

            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBempeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionBCompra."</td>";

            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionAempeno."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionACompra02."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionACompra01."</td>";
            $td_row .= "<td class='tdhideImporte right currency text-right' style='display: none;min-width: 90px;'>".$ValorPorClasificacionACompra."</td>";
        }

        if($Clasificacion == 'D'){
            $td_row = "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
            $td_row .= "<td class='tdhideImporte' style='display: none;min-width: 70px;'></td>";
        }



        switch($opc){
            case 1:
                return $ValorTotalEmpeno;
                break;
            case 2:
                return $ValorTotalCompra;
                break;
            case 3:
                echo $td_row;
                break;
            case 4:
                // retornar c Empeno
                return $ValorPorClasificacionCempeno;
                break;
            case 5:
                // retornar c Empeno
                return $ValorPorClasificacionCCompra02;
                break;
            case 6:
                // retornar c Empeno
                return $ValorPorClasificacionCCompra01;
                break;
            case 7:
                // retornar c Empeno
                return $ValorPorClasificacionCCompra;
                break;
            case 8:
                // retornar b Empeno
                return $ValorPorClasificacionBempeno;
                break;
            case 9:
                // retornar b Empeno
                return $ValorPorClasificacionBCompra02;
                break;
            case 10:
                // retornar b Empeno
                return $ValorPorClasificacionBCompra01;
                break;
            case 11:
                // retornar b Empeno
                return $ValorPorClasificacionBCompra;
                break;
            case 12:
                // retornar a Empeno
                return $ValorPorClasificacionAempeno;
                break;
            case 13:
                // retornar a Empeno
                return $ValorPorClasificacionACompra02;
                break;
            case 14:
                // retornar a Empeno
                return $ValorPorClasificacionACompra01;
                break;
            case 15:
                // retornar a Empeno
                return $ValorPorClasificacionACompra;
                break;
        }

    }

    //Mostrar Bitacora
    public function mostrar_bitacora($NoArticulo){
        $this->_query = "SELECT a.id_Rubro,c.Descripcion,a.Operacion,a.FormularioRegistra,a.Campo2,a.DatoActual,a.DatoNuevo,a.Descripcion,b.NombreDePila,
        FechaActualizacion,HoraActualizacion
        FROM BGEBitacora as a
        LEFT JOIN SINTEGRALGNL.BGECatalogoUsuarios as b
        ON a.NoUsuario = b.NoUsuario
        LEFT JOIN BGECatalogoGeneral as c
        ON a.NoRubro = c.OpcCatalogo AND c.CodCatalogo = 12
        WHERE a.LlaveRegistro = $NoArticulo ORDER BY a.id_Rubro DESC";

        $this->get_result_query();

        return $this->_rows;
    }

    //funcion para registrar productos
    public function RegistraArticulo($Opcion,$CodigoProducto,$Descripcion,$Clasificacion01,$Clasificacion02,$Clasificacion03,$Clasificacion04,$Clasificacion05,$Clasificacion06,$Clasificacion07,$Clasificacion08,$Clasificacion09,$Clasificacion10,$ImporteVenta,$Importe01,$Importe02,$Importe03,$Importe04,$Importe05,$NombreFoto,$NoEstatus,$NoUsuarioAlta,$NoUsuarioUM,$FechaAlta,$HoraAlta,$FechaUM,$HoraUM){

        //Funcion para Registrar los Articulos
        $this->_query = "CALL sp_BOP_RegistraArticulo('$Opcion','$CodigoProducto','$Descripcion','$Clasificacion01','$Clasificacion02',
        '$Clasificacion03','$Clasificacion04','$Clasificacion05','$Clasificacion06','$Clasificacion07','$Clasificacion08',
        '$Clasificacion09','$Clasificacion10','$ImporteVenta','$Importe01','$Importe02','$Importe03','$Importe04','$Importe05',
        '$NombreFoto','$NoEstatus','$NoUsuarioAlta','$NoUsuarioUM','$FechaAlta','$HoraAlta','$FechaUM','$HoraUM',@N_NumFolio)";

        $this->execute_query();
    }

    // listar productos
    public function listar_aparatos($filtro,$Order,$limitMin,$limitMax,$where){

        if(trim($where) == ""){
            $where = "WHERE a.CodigoProducto <> 9999 AND a.CodigoProducto <> 99999 AND a.CodigoProducto <> 99993 ";
        }

        $this->_query = "SELECT a.Descripcion,a.CodigoProducto,a.Clasificacion02,b.Descripcion,a.Clasificacion03,c.Descripcion,a.Clasificacion04,a.ImporteVenta,a.Importe01,a.Importe02,
            a.FechaAlta,a.FechaUM,d.OpcCatalogo,d.Numero1N,d.Numero2N,a.NoEstatus,d.Numero3N,d.Numero4N,a.Clasificacion01,e.Descripcion
            FROM BOPCatalogoProductos as a
            LEFT JOIN BGECatalogoGeneral as b
            ON a.Clasificacion02 = b.OpcCatalogo AND b.CodCatalogo = 5 AND a.Clasificacion01 = b.Numero2
            LEFT JOIN BGECatalogoGeneral as c
            ON a.Clasificacion03 = c.OpcCatalogo AND c.CodCatalogo = 6 AND a.Clasificacion01 = c.Numero2
            left JOIN BGECatalogoGeneral as d
            ON a.Clasificacion04 = d.Descripcion AND d.CodCatalogo = 7 AND a.Clasificacion01 = d.Numero2
            left JOIN BGECatalogoGeneral as e
            ON a.Clasificacion01 = e.OpcCatalogo AND e.CodCatalogo = 9
            $where ORDER BY convert(a.CodigoProducto,signed) $Order LIMIT $limitMin,$limitMax";

        $this->get_result_query();
        $rows = $this->_rows;

        for($i=0; $i < count($rows);$i++){


            if($rows[$i][18] == 3){
                //Si Son Relojes no se Toma como porcentaje
                $rowData[] = array(
                    "hProducto"=>" <a href='javascript:void(0)' onclick='fnpv_ver_producto(".$rows[$i][1].",".$rows[$i][18].")' ><span class='text text-primary' >".$this->getFormatFolio($rows[$i][1],5)."</span></a>",
                    "hDescripcion"=>utf8_encode($rows[$i][0]),
                    "hCategoria"=>$rows[$i][19],
                    "hTProducto"=>$rows[$i][3],
                    "hMarca"=>$rows[$i][5],
                    "hPrecioNvo"=>$rows[$i][7],
                    "hClasificaNvo"=>$rows[$i][6],
                    "hcEmpeno"=>"0",
//                    "hcEmpeno"=>$this->getRoundEmpeno($rows[7],$rows[13],$rows[14],$rows[6],4,$rows[16],$rows[17].$rows[18] ).".00",
                    "hcexcompra"=>"0",
                    "hcbuecompra"=>"0",
                    "hcmaxcompra"=>"0",
                    "hbEmpeno"=>"0",
                    "hbexcompra"=>"0",
                    "hbbuecompra"=>"0",
                    "hbmaxcompra"=>"0",
                    "haEmpeno"=>$rows[$i][8],
                    "haexcompra"=>"0",
                    "habuecompra"=>"0",
                    "hamaxcompra"=>$rows[$i][9],
                    "hFechaA"=>$this->getFormatFecha($rows[$i][10],2),
                    "hFechaU"=>$this->getFormatFecha($rows[$i][11],2),
                    "hPrecio"=>$rows[$i][7],
                    "hClasifica"=>$rows[$i][6],
                    "hCodigoProducto"=>$rows[$i][1]

                );
            }else{

                $rowData[] = array(
                    "hProducto"=>" <a href='javascript:void(0)' onclick='fnpv_ver_producto(".$rows[$i][1].",".$rows[$i][18].")' ><span class='text text-primary' >".$this->getFormatFolio($rows[$i][1],5)."</span></a>",
                    "hDescripcion"=>utf8_encode($rows[$i][0]),
                    "hCategoria"=>$rows[$i][19],
                    "hTProducto"=>$rows[$i][3],
                    "hMarca"=>$rows[$i][5],
                    "hPrecioNvo"=>$rows[$i][7],
                    "hClasificaNvo"=>$rows[$i][6],
                    "hcEmpeno"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],6,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
//                    "hcEmpeno"=>$this->getRoundEmpeno($rows[7],$rows[13],$rows[14],$rows[6],4,$rows[16],$rows[17].$rows[18] ).".00",
                    "hcexcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],5,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hcbuecompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],6,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hcmaxcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],7,$rows[$i][16],$rows[$i][17], $rows[$i][18]).".00",
                    "hbEmpeno"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],8,$rows[$i][16],$rows[$i][17], $rows[$i][18]).".00",
                    "hbexcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],9,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hbbuecompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],10,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hbmaxcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],11,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "haEmpeno"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],12,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "haexcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],13,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "habuecompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],14,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hamaxcompra"=>$this->getRoundEmpeno($rows[$i][7],$rows[$i][13],$rows[$i][14],$rows[$i][6],15,$rows[$i][16],$rows[$i][17],$rows[$i][18]).".00",
                    "hFechaA"=>$this->getFormatFecha($rows[$i][10],2),
                    "hFechaU"=>$this->getFormatFecha($rows[$i][11],2),
                    "hPrecio"=>$rows[$i][7],
                    "hClasifica"=>$rows[$i][6],
                    "hCodigoProducto"=>$rows[$i][1]

                );

            }


        }

        return $rowData;

    }

}