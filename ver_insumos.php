<?php 
#Session
/*
session_start();
if ( $_SESSION['tipo_usuario'] != 'Tecnico' && $_SESSION['tipo_usuario'] != 'Secretaria') {
        header( 'Location: /SRC/index.php' );
    }
if ( ! isset( $_SESSION['user_id'] ) ) {
    header( 'Location: /SRC/index.php' );
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventario Insumos</title>
  <link rel="stylesheet" href="css_insumos/tablas.css">
</head>
<body>
    <center><h1>Inventario de insumos médicos</h1></center>


<a href="registrar_insumos.php"><input type="button" value="+ Ingresar nuevo insumo" class="boton_registrar"></a>

<!-- Cabecera de la tabla-->
<table class="tabla">
    <tr>
        <th>Nombre</th>
        <th>Categoría</th>
        <th >Lote</th>
        <th >Cantidad</th>
        <th >Acciones</th>
    </tr>
<?php

include ("conexion.php");
# Obtiene el total de insumos registrados
$sql_registro=mysqli_query($conexion,"SELECT COUNT(*) AS total_registro FROM SGL_INSUMO");
$resultado_sql_registro=mysqli_fetch_array($sql_registro);
$total_registro=$resultado_sql_registro['total_registro'];

#variable para guardar el total de registros que se mostraran por pagina
$cantidad_pagina=10;

if(empty($_GET['pagina'])){
    $pagina=1; #si el get esta vacio dirige a la primera pagina
}else{
    $pagina = $_GET['pagina']; #recupera el dato del get que es la pagina que corresponde
}
$desde = ($pagina-1)*$cantidad_pagina;# guarda desde que registro se mostrara en la tabla 
$total_paginas=ceil($total_registro/$cantidad_pagina);#total de paginas para todos los registros 

#obtiene los datos de insumos para la tabla ordenados por nombre y limitado por la cantidad de registros por paginas
    $query =
        "SELECT  S.SGL_INSUMO_IDENTIFICADOR,S.SGL_INSUMO_NOMBRE,C.SGL_CATEGORIA_INSUMO_NOMBRE,S.SGL_INSUMO_LOTE,S.SGL_INSUMO_STOCK FROM SGL_INSUMO S INNER JOIN SGL_CATEGORIA_INSUMO C ON S.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR=C.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR ORDER BY S.SGL_INSUMO_NOMBRE ASC LIMIT $desde,$cantidad_pagina";

    $result = mysqli_query($conexion, $query);
        if (!$result) {
            echo "Ocurrió un error.";
            exit;
        }
        while ($row =mysqli_fetch_array($result)) { ?> <!--obtiene el resultado de la consulta como array-->
            <tr><!--agrega los datos de la consulta a la tabla-->
                <td  ><?php echo "$row[1]" ?></td>
                <td ><?php echo "$row[2]" ?></td>
                <td ><?php echo "$row[3]" ?></td>
                <td ><?php echo "$row[4]" ?></td>
                <!-- botones para editar y modificar insumos, mandan como parametro el id de la fila-identificador del insumo-->
               <td> <a href="modificar_insumos.php?id=<?php echo "$row[0]" ?>" class="boton-editar">Editar </a>
               |
                <a href="eliminar_insumos.php?id=<?php echo "$row[0]" ?>" class="boton-eliminar">Eliminar</a></td>
            </tr>
            <?php
            }
            ?>
</table>
<div class="paginador">
    <ul>
        <?php
        #paginador- 
        if($pagina != 1){
        ?>
        <li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
        <li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
        <?php
        }
        for ($i=1; $i<=$total_paginas; $i++) { 
            if($i == $pagina){
                echo '<li class="pagina_seleccionada">'.$i.'</li>';
            }else{
                 echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
            }
        }
        if($pagina != $total_paginas){
        ?>
        <li><a href="?pagina=<?php echo $pagina+1; ?>">>></a></li>
        <li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
        <?php } ?> 
    </ul>
</div>
</body>
</html>
