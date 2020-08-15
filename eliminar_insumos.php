<?php 
include ("conexion.php");
/*
session_start();
if ( $_SESSION['tipo_usuario'] != 'Tecnico' && $_SESSION['tipo_usuario'] != 'Secretaria') {
        header( 'Location: /SRC/index.php' );
    }
if ( ! isset( $_SESSION['user_id'] ) ) {
    header( 'Location: /SRC/index.php' );
}
*/

if(!empty($_POST)){
  $alerta='';
  #recupera el id enviado a la pagina y hace la consulta para eliminarlo
  $idinsumo=$_POST['idinsumo'];
  $sql_eliminar=mysqli_query($conexion,"DELETE FROM SGL_INSUMO WHERE SGL_INSUMO_IDENTIFICADOR='$idinsumo'");
  if($sql_eliminar){
header( 'Location: ver_insumos.php' );
  }else{
$alerta= '<p class="msg_error"><strong>Error en la eliminación del registro</strong></p>';
  }
}

if(empty($_REQUEST['id'])){
 header( 'Location: ver_insumos.php' );
}else{
  $idinsumo=$_GET['id'];
  #se guarda el id enviado a la pagina y se traen sus datos desde la BD
  $sql_mostrar=mysqli_query($conexion,"SELECT S.SGL_INSUMO_IDENTIFICADOR,S.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR,C.SGL_CATEGORIA_INSUMO_NOMBRE, S.SGL_PROVEEDOR_IDENTIFICADOR,P.SGL_PROVEEDOR_NOMBRE,S.SGL_INSUMO_NOMBRE,S.SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA,S.SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA,S.SGL_INSUMO_STOCK,S.SGL_INSUMO_LOTE  FROM SGL_INSUMO S , SGL_CATEGORIA_INSUMO C, SGL_PROVEEDOR P WHERE S.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR=C.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR AND S.SGL_PROVEEDOR_IDENTIFICADOR=P.SGL_PROVEEDOR_IDENTIFICADOR AND SGL_INSUMO_IDENTIFICADOR='$idinsumo'");

  $respuesta=mysqli_num_rows($sql_mostrar);

  if($respuesta > 0){
    #recuperacion de los datos
    while($datos=mysqli_fetch_array($sql_mostrar)){
    $id_insumo = $datos['SGL_INSUMO_IDENTIFICADOR'];
    $nombre_categoria = $datos['SGL_CATEGORIA_INSUMO_NOMBRE'];
    $nombre_proveedor= $datos['SGL_PROVEEDOR_NOMBRE'];
    $insumo_nombre = $datos['SGL_INSUMO_NOMBRE'];
    $insumo_t_min = $datos['SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA'];
    $insumo_t_max = $datos['SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA'];
    $insumo_stock = $datos['SGL_INSUMO_STOCK'];
    $insumo_lote = $datos['SGL_INSUMO_LOTE'];
    }
  }else{
    header( 'Location: ver_insumos.php' );
  }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar insumo</title>
  <link rel="stylesheet" href="css_insumos/formulario.css">
</head>
<body>

<section class="container">
<!--mostrar los datos del id que se quiere eliminar-->
<div class="form_registro_insumo">
<h2>¿Está seguro de eliminar el registro?</h2>
<div class="alerta"> <?php echo isset($alerta) ? $alerta : ''; ?> </div>
  <form action="" method="POST">
  <p>Nombre:<span><?php echo  $insumo_nombre;?> </span></p>
  <p>Categoría:<span><?php echo  $nombre_categoria;?> </span></p>
  <p>Proveedor:<span><?php echo  $nombre_proveedor;?> </span></p>
  <p>Cantidad:<span><?php echo  $insumo_stock;?> </span></p>
  <p>Lote:<span><?php echo  $insumo_lote;?> </span></p>
<input type="hidden" name="idinsumo" value="<?php echo  $idinsumo;?>">
  <input type="submit" value="Eliminar Registro" class="boton_borrar">

        <a href="ver_insumos.php"><input type="button" value="Cancelar" class="boton_cancelar"></a>
  </form>
</div>
</section>
  
</body>
</html>