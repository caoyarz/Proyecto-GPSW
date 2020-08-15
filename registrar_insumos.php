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
  #si el post no viene vacio ,deja el mensaje de alerta vacio, recupera los parametros enviados del formulario a esta misma pagina

    $nombre=$_POST['nombre'];
    $categoria=$_POST['categoria'];
    $proveedor=$_POST['proveedor'];
    $t_minima=$_POST['t_minima'];
    $t_maxima=$_POST['t_maxima'];
    $stock=$_POST['stock'];
    $lote=$_POST['lote'];

#sql para la insersion de los datos a la base de datos
    $insertar=mysqli_query($conexion,"INSERT into SGL_INSUMO (SGL_CATEGOTIA_INSUMO_IDENTIFICADOR,SGL_PROVEEDOR_IDENTIFICADOR,SGL_INSUMO_NOMBRE,SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MINIMA,SGL_INSUMO_TEMPERATURA_ALMACENAMIENTO_MAXIMA,SGL_INSUMO_STOCK,SGL_INSUMO_LOTE) values ($categoria,'$proveedor','$nombre','$t_minima','$t_maxima','$stock','$lote')");

    #mensajes de respuesta 
    if($insertar){
    
     $alerta= '<p class="msg_save"><strong>Registro exitoso</strong></p>';
   
    }else{
      $alerta= '<p class="msg_error"><strong>El nombre de insumo ya se encuentra registrado</strong></p>';
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de nuevo insumo</title>
  <link rel="stylesheet" href="css_insumos/formulario.css">
</head>
<body>
  <section class="container">
    <div class="form_registro_insumo">
      <h1>Registro insumo</h1>
      <hr>
      <div class="alerta"> <?php echo isset($alerta) ? $alerta : ''; ?> </div>
      <form action="" method="POST">
      <!--ingresar nombre  -->
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>
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
        <input type="number" id="t_minima" name="t_minima" min="0" max="100" required>
        <!--  ingreso temperatura maxima-->
          <label for="t_maxima">Temperatura máxima</label>
        <input type="number" id="t_maxima" name="t_maxima" min="0" max="100" required>
        <!--  ingreso stock-->
          <label for="stock">Cantidad</label>
        <input type="number" id="stock" name="stock" min="0" required>
        <!--  ingreso lote-->
          <label for="lote">Lote</label>
        <input type="text" id="lote" name="lote" required>

        <input type="submit" value="Agregar insumo" class="boton_agregar">

        <a href="ver_insumos.php"><input type="button" value="Cancelar" class="boton_cancelar"></a>
</form>
    </div>
  </section>
</body>
</html>

