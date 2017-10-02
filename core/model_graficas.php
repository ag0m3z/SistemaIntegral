<?php
/**
 * Created by PhpStorm.
 * User: alejandro.gomez
 * Date: 10/04/2017
 * Time: 11:53 AM
 */

namespace core;
include "seguridad.php";

class model_graficas extends seguridad
{
    public function MyReports_for_dia($opc,$fchInicial,$fchFinal,$NoDepartamento,$Perfil){
        //Query para traer los Reportes del Usuario Por Fecha o por dia.
        if($opc == 1){
            $this->_query ="SELECT a.NombreDePila,IFNULL(b.Asignados,0) Asignados,IFNULL(c.Cerrados,0) Cerrados,IFNULL(d.Recibe,0) Registra,a.NombreDePila
	                FROM SINTEGRALGNL.BGECatalogoUsuarios as a
                    LEFT JOIN
                    (SELECT NoUsuarioAsignado,COUNT(Folio) as Asignados
                            FROM BSHReportes
                        WHERE NoDepartamento = $NoDepartamento AND Estatus <= 3 AND  Fecha >= '".$fchInicial."' AND Fecha <= '".$fchFinal."' GROUP BY NoUsuarioAsignado

                    ) as b
                    ON a.NoUsuario = b.NoUsuarioAsignado AND a.NoDepartamento = $NoDepartamento
                    LEFT JOIN (SELECT NoUsuarioCierre ,COUNT(Folio) AS Cerrados
                            FROM BSHReportes
                                WHERE NoDepartamento = $NoDepartamento AND Estatus = 4 AND FechaCierre >= '".$fchInicial."' AND FechaCierre <= '".$fchFinal."'
                            group by NoUsuarioCierre  ORDER BY Cerrados DESC)as c
                    ON a.NoUsuario = c.NoUsuarioCierre AND a.NoDepartamento = $NoDepartamento
                    LEFT JOIN (SELECT NoUsuarioRecibe ,COUNT(Folio) AS Recibe
                            FROM BSHReportes
                                WHERE NoDepartamento = $NoDepartamento AND Fecha >= '".$fchInicial."' AND Fecha <= '".$fchFinal."'
                            group by NoUsuarioRecibe  ORDER BY Recibe DESC)as d
                    ON a.NoUsuario = d.NoUsuarioRecibe AND a.NoEstado = 1 AND a.NoDepartamento = '".$NoDepartamento."'
                WHERE a.NoDepartamento = $NoDepartamento AND NoEstado = 1 ORDER BY a.NombreDePila ASC";

            $this->get_result_query();
            $query = $this->_rows;


            if(count($this->_rows)>0){
                return $query;
            }else{
                $array = array(0,0,0);
                return $query;
            }
        }
    }

    //Traer los reportes de los meses del Departamento
    public function MyReports_for_month($Mes,$NoDepartamento,$Perfil,$Anio,$opc=null){
        $this->_query = "SELECT Folio FROM BSHReportes
                     WHERE NoDepartamento = '$NoDepartamento'
                            AND month(Fecha)=".$Mes." AND Anio =$Anio";
        if($opc == 1){
            $sql = "SELECT Folio FROM BSHReportes
                     WHERE NoDepartamento = '$NoDepartamento'
                            AND month(Fecha)=".$Mes." AND NoUsuarioCierre = $Perfil AND Anio =$Anio";
        }
        $this->get_result_query();

        return count($this->_rows);
    }
    //Traer los Reportes de todos los años del DEpartamento
    public function MyReports_grafica4($NoDepartamento,$Perfil,$opc){
        if($opc == 1){
            $this->_query = "SELECT Anio Ttl FROM BSHReportes WHERE NoDepartamento = '$NoDepartamento' GROUP BY Anio ORDER BY Anio";
        }else{
            $this->_query = "SELECT COUNT(Folio)as Ttl FROM BSHReportes WHERE NoDepartamento = '$NoDepartamento' GROUP BY Anio ORDER BY Anio ";
        }
        $this->get_result_query();
        return $this->_rows;
    }

    //Extrart los Reportes de usuarios por mes del Departamento
    public function MyReports_for_UserMensual($NoDepartamento,$Perfil,$Anio){
        $this->_query =
            "SELECT a.NombreDePila,ifnull(b.Enero,0),ifnull(c.Febrero,0),ifnull(d.Marzo,0),ifnull(e.Abril,0),ifnull(f.Mayo,0),ifnull(g.Junio,0),ifnull(h.Julio,0),ifnull(i.Agosto,0),ifnull(j.Septiembre,0),ifnull(k.Octubre,0),ifnull(l.Noviembre,0),ifnull(m.Diciembre,0) FROM SINTEGRALGNL.BGECatalogoUsuarios  a
                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Enero FROM BSHReportes
                 WHERE MONTH(Fecha)=1 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) b
                ON a.NoUsuario = b.NoUsuarioCierre AND a.NoDepartamento = b.NoDepartamento
                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Febrero FROM BSHReportes
                 WHERE MONTH(Fecha)=2 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) c
                ON a.NoUsuario = c.NoUsuarioCierre AND a.NoDepartamento = c.NoDepartamento
                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Marzo FROM BSHReportes
                 WHERE MONTH(Fecha)=3 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) d
                ON a.NoUsuario = d.NoUsuarioCierre AND a.NoDepartamento = d.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Abril FROM BSHReportes
                 WHERE MONTH(Fecha)=4 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) e
                ON a.NoUsuario = e.NoUsuarioCierre AND a.NoDepartamento = e.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Mayo FROM BSHReportes
                 WHERE MONTH(Fecha)=5 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) f
                ON a.NoUsuario = f.NoUsuarioCierre AND a.NoDepartamento = f.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Junio FROM BSHReportes
                 WHERE MONTH(Fecha)=6 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) g
                ON a.NoUsuario = g.NoUsuarioCierre AND a.NoDepartamento = g.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Julio FROM BSHReportes
                 WHERE MONTH(Fecha)=7 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) h
                ON a.NoUsuario = h.NoUsuarioCierre AND a.NoDepartamento = h.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Agosto FROM BSHReportes
                 WHERE MONTH(Fecha)=8 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) i
                ON a.NoUsuario = i.NoUsuarioCierre AND a.NoDepartamento = i.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Septiembre FROM BSHReportes
                 WHERE MONTH(Fecha)=9 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) j
                ON a.NoUsuario = j.NoUsuarioCierre AND a.NoDepartamento = j.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Octubre FROM BSHReportes
                 WHERE MONTH(Fecha)=10 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) k
                ON a.NoUsuario = k.NoUsuarioCierre AND a.NoDepartamento = k.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Noviembre FROM BSHReportes
                 WHERE MONTH(Fecha)=11 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) l
                ON a.NoUsuario = l.NoUsuarioCierre AND a.NoDepartamento = l.NoDepartamento

                LEFT JOIN (SELECT NoUsuarioCierre,NoDepartamento,COUNT(Folio) as Diciembre FROM BSHReportes
                 WHERE MONTH(Fecha)=12 AND Anio=$Anio Group by NoUsuarioCierre,NoDepartamento) m
                ON a.NoUsuario = m.NoUsuarioCierre AND a.NoDepartamento = m.NoDepartamento
            WHERE a.NoDepartamento = '$NoDepartamento' AND a.NoEstado = 1";

        $this->get_result_query();
        $data = $this->_rows;

        for($i=0;$i < count($data);$i++){
            echo "{
                    name: '".$data[$i][0]."',
                    data: [
                        ".$data[$i][1].", 
                        ".$data[$i][2].", 
                        ".$data[$i][3].", 
                        ".$data[$i][4].", 
                        ".$data[$i][5].", 
                        ".$data[$i][6].", 
                        ".$data[$i][7].", 
                        ".$data[$i][8].", 
                        ".$data[$i][9].", 
                        ".$data[$i][10].", 
                        ".$data[$i][11].", 
                        ".$data[$i][12]."]
                    },";
        }
    }

    public function MyReports_for_Estatus($Estatus,$NoDepartamento,$Anio){
        $this->_query = "SELECT Folio FROM BSHReportes WHERE NoDepartamento = $NoDepartamento AND Anio = $Anio AND Estatus = $Estatus ORDER BY Folio ASC";
        $this->get_result_query();

        return count($this->_rows);
    }

    public function MyReports_for_SinAsignar($NoDepartamento,$Anio,$Mes,$Perfil){
        $this->_query ="SELECT Folio FROM BSHReportes WHERE NoUsuarioAsignado = 0 AND Anio = $Anio AND NoDepartamento = $NoDepartamento ORDER BY Folio DESC";
        $this->get_result_query();

        return count($this->_rows);
    }

    public function AnualReports($NoDepartamento,$Perfil,$Anio){
        echo '<table class="table table-hover table-condensed table-condensed" style="font-size: 12px;">';
        echo '<thead><tr><th>#</th><th>Mes</th><th>Cerrados</th><th>Total</th><th><span class="pull-right"> Promedio</span></th></tr></thead><tbody>';
        $this->_query = "SELECT month(Fecha) as Mes,count(Folio),(SELECT COUNT(Estatus) FROM BSHReportes WHERE Estatus = 4 AND month(Fecha)= Mes AND NoDepartamento = $NoDepartamento AND Anio =$Anio ) as CERRADOS FROm BSHReportes WHERE NoDepartamento = $NoDepartamento AND Anio =$Anio GROUP BY Mes";
        $this->get_result_query();
        $data = $this->_rows;

        $n=1;
        for($i=0;$i < count($data);$i++){
            switch($data[$i][0]){
                case 1:
                    $Mes = "Enero";
                    break;
                case 2:
                    $Mes = "Febrero";
                    break;
                case 3:
                    $Mes = "Marzo";
                    break;
                case 4:
                    $Mes = "Abril";
                    break;
                case 5:
                    $Mes = "Mayo";
                    break;
                case 6:
                    $Mes = "Junio";
                    break;
                case 7:
                    $Mes = "Julio";
                    break;
                case 8:
                    $Mes = "Agosto";
                    break;
                case 9:
                    $Mes = "Septiembre";
                    break;
                case 10:
                    $Mes = "Octubre";
                    break;
                case 11:
                    $Mes = "Noviembre";
                    break;
                case 12:
                    $Mes = "Diciembre";
                    break;
            }
            echo "<tr><td>".$n++."</td><td>".$Mes."</td><td><span class='badge'>".$this->getFormatFolio($data[$i][2],4)."</span></td><td><span class='badge'>".$this->getFormatFolio($data[$i][1],4)."</span></td><td><span class='pull-right label label-info' style='font-size:12px;width:60px;padding:4px'>".round( $Promedio = ($data[$i][2] * 100)/$data[$i][1], 1)."%</span></td></tr>";
        }

    }

    public function MyReports_for_User($Anio,$Mes,$Perfil,$NoDepartamento){
        if($Mes <> 13){
            $this->_query = "SELECT C.NombreDePila, R.Total 
            	FROM 
                (SELECT NoUsuarioCierre,COUNT(Folio) AS Total FROM BSHReportes WHERE Anio =$Anio AND month(Fecha)=".$Mes." AND NoDepartamento = '$NoDepartamento' AND Estatus = 4 group by NoUsuarioCierre  ORDER BY Total DESC) R
             INNER JOIN SINTEGRALGNL.BGECatalogoUsuarios AS C
            	ON C.NoUsuario = R.NoUsuarioCierre ";
        }else{
            $this->_query = "SELECT C.NombreDePila, R.Total 
            	FROM 
                (SELECT NoUsuarioCierre,COUNT(Folio) AS Total FROM BSHReportes WHERE Anio =$Anio AND NoDepartamento = '$NoDepartamento' AND Estatus = 4 group by NoUsuarioCierre  ORDER BY Total DESC) R
             INNER JOIN SINTEGRALGNL.BGECatalogoUsuarios AS C
            	ON C.NoUsuario = R.NoUsuarioCierre";
        }

        $this->get_result_query();
        return $this->_rows;

    }

    public function MyReports_for_year($NoDepartamento,$Perfil){
        $this->_query = "SELECT Anio,COUNT(Folio)as Ttl FROM BSHReportes WHERE NoDepartamento = '$NoDepartamento' GROUP BY Anio ORDER BY Anio DESC";
        $this->get_result_query();
        return $this->_rows;
    }

    public function MyReports_GraficaCategorias($NoDepartamento,$Mes,$Anio,$Perfil,$NoArea,$opc){
        if($Mes == 13){
            $this->_query = "SELECT C.Descripcion,COUNT(Folio) 
                                	FROM BSHReportes  as R
                                INNER JOIN BSHCatalogoCategoria AS C
                                	ON R.Categoria = C.nocategoria
                                WHERE R.NoArea = $NoArea AND R.Anio = $Anio AND R.NoDepartamento = '$NoDepartamento' GROUP BY R.Categoria ORDER BY R.Categoria";
            $this->get_result_query();
            $data = $this->_rows;

            $this->_query = "SELECT C.Descripcion,COUNT(Folio) as Total
                                	FROM BSHReportes  as R
                                INNER JOIN BSHCatalogoCategoria AS C
                                	ON R.Categoria = C.nocategoria
                                WHERE R.Anio = $Anio AND R.NoDepartamento = $NoDepartamento GROUP BY R.Categoria  ORDER BY Total DESC";
            $this->get_result_query();
            $data2 = $this->_rows;

        }else{
            $this->_query = "SELECT C.Descripcion,COUNT(Folio) 
                                	FROM BSHReportes  as R
                                INNER JOIN BSHCatalogoCategoria AS C
                                	ON R.Categoria = C.nocategoria
                                WHERE R.NoArea = $NoArea AND MONTH(Fecha)=$Mes AND R.Anio = $Anio AND R.NoDepartamento = '$NoDepartamento' GROUP BY R.Categoria ORDER BY R.Categoria";
            $this->get_result_query();
            $data = $this->_rows;

            $this->_query = "SELECT C.Descripcion,COUNT(Folio) as Total
                                	FROM BSHReportes  as R
                                INNER JOIN BSHCatalogoCategoria AS C
                                	ON R.Categoria = C.nocategoria
                                WHERE MONTH(Fecha)=$Mes AND R.Anio = $Anio AND R.NoDepartamento = $NoDepartamento GROUP BY R.Categoria  ORDER BY Total DESC";
            $this->get_result_query();
            $data2 = $this->_rows;
        }

        if($opc == 1){

            for($i=0;$i < count($data);$i++){
                echo "['".$data[$i][0]."',   ".$data[$i][1]."],";
            }
        }else{
            return $data2;
        }

    }

    public function MyReports_FechaPromesa($Opc,$NoDepartamento,$Anio,$Perfil){
        switch($Opc){

            case 1:
                //Tickets Cerrados
                $this->_query = "SELECT count(Folio) FROM BSHReportes WHERE Estatus = 4 AND NoDepartamento = '$NoDepartamento' AND Anio =$Anio ORDER BY Folio DESC";
                $this->get_result_query();

                return $this->getFormatFolio($this->_rows[0][0],4);
                break;
            case 2:
                //Reportes cerrados en tiempo
                $this->_query = "SELECT count(Folio) FROM BSHReportes WHERE FechaCierre <= FechaPromesa AND Estatus = 4 AND NoDepartamento = '$NoDepartamento' AND Anio =$Anio ORDER BY Folio DESC";
                $this->get_result_query();

                return $this->getFormatFolio($this->_rows[0][0],4);
                break;
            case 3:
                //Reportes Fuera de Tiempo
                $this->_query = "SELECT count(Folio) FROM BSHReportes WHERE FechaCierre >= FechaPromesa AND Estatus = 4 AND NoDepartamento = '$NoDepartamento' AND Anio =$Anio ORDER BY Folio DESC";
                $this->get_result_query();

                return $this->getFormatFolio($this->_rows[0][0],4);
                break;
            case 4:
                //Reportes Pendientes
                $this->_query = "SELECT count(Folio) FROM BSHReportes WHERE Estatus <= 3 AND NoDepartamento = '$NoDepartamento' AND Anio =$Anio ORDER BY Folio DESC";
                $this->get_result_query();

                return $this->getFormatFolio($this->_rows[0][0],4);
                break;
        }

    }

    public function Myreports_Prioridad($NoDepartamento,$Perfil,$Anio,$Mes,$opc){
        $this->_query = "SELECT C.Descripcion,PrioridadTicket,COUNT(Folio) AS Total
                                	FROM BSHReportes  AS R
                                JOIN BSHCatalogoCatalogos AS C
                                	ON R.PrioridadTicket = C.idDescripcion AND C.idCatalogo = 1
                                WHERE Anio=$Anio AND NoDepartamento = '$NoDepartamento' GROUP BY PrioridadTicket";
        $this->get_result_query();
        $data = $this->_rows;
        $n = 1;
        for($i=0;$i < count($data); $i++){
            echo "<tr><td>".$n++."</td><td>". $data[$i][0]."</td><td><span class='pull-right badge'>".$this->getFormatFolio($data[$i][2],4)."</span></td></t>";
        }
    }

    public function MyReports_GraficaPrioridad($NoDepartamento,$Perfil,$Anio,$Mes,$opc){

        $this->_query = "SELECT C.Descripcion,PrioridadTicket,COUNT(Folio) AS Total
                                	FROM BSHReportes  AS R
                                JOIN BSHCatalogoCatalogos AS C
                                	ON R.PrioridadTicket = C.idDescripcion AND C.idCatalogo = 1
                                WHERE Anio=$Anio AND NoDepartamento = '$NoDepartamento' GROUP BY PrioridadTicket";
        $this->get_result_query();
        $data = $this->_rows;

        for($i=0;$i < count($data);$i++){
            echo "['".$data[$i][0]."',   ".$data[$i][2]."],";
        }
    }

    public function MyReports_GraficaSucursales($NoDepartamento,$Perfil,$Anio,$Mes,$opc){
        /* Opc = Opcion
            opc = 1 - Muestra Datos en Formato para Grafica
            opc = 2 - Muestra Datos en Formato para Tabla
        */
        if($opc == 1){ $Limit15 = 15; }else{ $Limit15 = 20000;}
        if($Mes == 13){
            $this->_query = "SELECT S.Descripcion,count(Folio) as Total
                                        FROM BSHReportes AS R
                                    LEFT JOIN BGECatalogoDepartamentos AS S
                                        ON R.NoSucursal = S.NoDepartamento
                                    WHERE R.Anio=$Anio AND R.NoDepartamento = '$NoDepartamento' GROUP BY R.NoSucursal ORDER BY Total DESC LIMIT $Limit15";
        }else{
            $this->_query = "SELECT S.Descripcion,count(Folio) as Total
                                        FROM BSHReportes AS R
                                    LEFT JOIN BGECatalogoDepartamentos AS S
                                        ON R.NoSucursal = S.NoDepartamento
                                    WHERE R.Anio=$Anio AND MONTH(R.Fecha)=$Mes AND R.NoDepartamento = '$NoDepartamento' GROUP BY R.NoSucursal ORDER BY Total DESC LIMIT $Limit15";
        }

        $this->get_result_query();
        $data = $this->_rows;

        switch($opc){
            case 1:
                for($i=0;$i<count($data);$i++){
                    echo "['".$data[$i][0]."',   ".$data[$i][1]."],";
                }
                break;
            case 2:
                $n=1;
                for($i=0;$i<count($data);$i++){
                    echo "<tr><td>".$n++."</td><td>".$data[$i][0]."</td><td><span class='pull-right badge'>".$this->getFormatFolio($data[$i][1],4)."</span></td></tr>";
                }
                break;
        }
    }



}