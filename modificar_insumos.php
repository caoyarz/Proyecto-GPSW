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
  if(empty($_POST['nombre']) || empty($_POST['stock'])|| empty($_POST['lote'])){
 $alerta= '<p class="msg_error"><strong>Falta ingresar datos</strong></p>';
 
  }else{
    #recibe los datos
    $idInsumo=$_POST['idinsumo'];
    $nombre=$_POST['nombre'];
    $categoria=$_POST['categoria'];
    $proveedor=$_POST['proveedor'];
    $t_minima=$_POST['t_minima'];
    $t_maxima=$_POST['t_maxima'];
    $stock=$_POST['stock'];
    $lote=$_POST['lote'];
#sql para acutalizar el insumo con los nuevos datos ingresados
    $actualizar=mysqli_query($conexion,"UPDATE SGL_INSUMO SET SGL_CATEGOTIA_INSUMO_IDENTIFICADOR='$categoria',SGL_PROVEEDOR_IDENTIFICADOR='$proveedor',SGL_INSUMO_NOMBRE='$nombre',SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA='$t_minima',SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA='$t_maxima',SGL_INSUMO_STOCK='$stock',SGL_INSUMO_LOTE='$lote' WHERE SGL_INSUMO_IDENTIFICADOR = '$idInsumo'");

    if($actualizar){
    
     $alerta= '<p class="msg_save"><strong>Actualización exitosa</strong></p>';
   
    }else{
      $alerta= '<p class="msg_error"><strong>Error en la actualización.</strong></p>';
       
    }
  }
}

if(empty($_GET['id'])){
 header( 'Location: ver_insumos.php' );
}
$idinsumo=$_GET['id']; #guarda el id recibido de la pagina ver_insumos
#trae de la BD los datos correspondientes al id recuperado 
$sql=mysqli_query($conexion,"SELECT S.SGL_INSUMO_IDENTIFICADOR,S.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR,C.SGL_CATEGORIA_INSUMO_NOMBRE, S.SGL_PROVEEDOR_IDENTIFICADOR,P.SGL_PROVEEDOR_NOMBRE,S.SGL_INSUMO_NOMBRE,S.SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA,S.SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA,S.SGL_INSUMO_STOCK,S.SGL_INSUMO_LOTE  FROM SGL_INSUMO S , SGL_CATEGORIA_INSUMO C, SGL_PROVEEDOR P WHERE S.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR=C.SGL_CATEGOTIA_INSUMO_IDENTIFICADOR AND S.SGL_PROVEEDOR_IDENTIFICADOR=P.SGL_PROVEEDOR_IDENTIFICADOR AND SGL_INSUMO_IDENTIFICADOR='$idinsumo'");

$respuesta=mysqli_num_rows($sql);
if($respuesta==0){ #si no encuentra respuesta de la consulta redirige a la lista de insumos
  header('Location: ver_insumos.php');
}else{
  while($datos=mysqli_fetch_array($sql)){
    #recupera los datos de la consulta para mostrarlos en los inputs
    $id_insumo = $datos['SGL_INSUMO_IDENTIFICADOR'];
    $nombre_categoria = $datos['SGL_CATEGORIA_INSUMO_NOMBRE'];
    $nombre_proveedor= $datos['SGL_PROVEEDOR_NOMBRE'];
    $insumo_nombre = $datos['SGL_INSUMO_NOMBRE'];
    $insumo_t_min = $datos['SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA'];
    $insumo_t_max = $datos['SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA'];
    $insumo_stock = $datos['SGL_INSUMO_STOCK'];
    $insumo_lote = $datos['SGL_INSUMO_LOTE'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualización de insumos</title>
  <link rel="stylesheet" href="css_insumos/formulario.css">
</head>
<body>
  <section class="container">
    <div class="form_registro_insumo">
      <h1>Actualización datos insumo</h1>
      <hr>
      <div class="alerta"> <?php echo isset($alerta) ? $alerta : ''; ?> </div>
      <!-- en los value se ingresan los datos recuperados en la consulta-->
      <form action="" method="POST">
        <input type="hidden" name="idinsumo" value="<?php echo $id_insumo; ?>">
      <!--ingresar nombre  -->
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo $insumo_nombre; ?>" required>
      <!-- ingresar categorias-->
        <label for="categoria">Categoria</label>
        <select name="categoria" id="categoria" required>
      <!--traer categorias de la BD -->
        <?php
          
          $consulta_categorias ="SELECT * FROM SGL_CATEGORIA_INSUMO";
          $resultado_categorias = mysqli_query($conexion, $consulta_categorias);
          if (!$resultado_categorias) {
            echo "Ocurrió un error.";
            exit;
          }
          while ($row =mysqli_fetch_array($resultado_categorias)) { ?>
            <option value=" <?php echo $row[0]; ?>" > <?php echo $row[1]; ?></option>
            
         <?php 
            } 
          ?>
        </select>

        <!-- ingresar proveedores-->
        <label for="proveedor">Proveedor</label>
        <select name="proveedor" id="proveedor" required>
      <!--traer proveedores de la BD -->
        <?php
          $consulta_proevedores ="SELECT * FROM SGL_PROVEEDOR";
          $resultado_proveedores = mysqli_query($conexion, $consulta_proevedores);
          if (!$resultado_proveedores) {
            echo "Ocurrió un error.";
            exit;
          }
          while ($row =mysqli_fetch_array($resultado_proveedores)) { ?>
            <option value="<?php echo $row[0]; ?>" > <?php echo $row[1]; ?></option>
         <?php 
            } 
          ?>
        </select>
        
        <!--  ingreso temperatura minima-->
        <label for="t_minima">Temperatura mínima</label>
        <input type="number" id="t_minima" name="t_minima" value="<?php echo $insumo_t_min; ?>" min="0" max="100" required>
        <!--  ingreso temperatura maxima-->
          <label for="t_maxima">Temperatura máxima</label>
        <input type="number" id="t_maxima" name="t_maxima" value="<?php echo $insumo_t_max; ?>" min="0" max="100" required>
        <!--  ingreso stock-->
          <label for="stock">Cantidad</label>
        <input type="number" id="stock" name="stock" value="<?php echo $insumo_stock; ?>" min="0" required>
        <!--  ingreso lote-->
          <label for="lote">Lote</label>
        <input type="text" id="lote" name="lote" value="<?php echo $insumo_lote; ?>" required>

        <input type="submit" value="Actualizar" class="boton_agregar">

        <a href="ver_insumos.php"><input type="button" value="Cancelar" class="boton_cancelar"></a>
</form>
    </div>
  </section>
</body>
</html>
