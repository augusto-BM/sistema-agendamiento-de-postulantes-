<?php

// Conexion a la base de datos
require_once '../../../config/conexion/conexion.php';

// Usamos la clase Database y obtenemos la conexión
$db = Database::getInstance();
$conn = $db->getConnection();

// Modelo función buscar usuario
function buscarUsuarioPorEmail($conn, $email) {
    try {
        // Se asegura de que el email esté limpio
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        
        // Consulta para buscar el usuario en la base de datos
        $select =   "   SELECT 
                            usuario.idusuario  AS idusuario,
                            usuario.nombreusuario AS nombreusuario, 
                            usuario.tipodocumento AS tipodocumento, 
                            usuario.dni AS dni, 
                            usuario.correo AS correo, 
                            usuario.pass AS pass,
                            usuario.celular AS celular,
                            usuario.turno AS turno,
                            usuario.estado AS estado,
                            usuario.fechaingreso AS fechaingreso,

                            empresa.idempresa  AS idempresa,
                            empresa.nombreempresa  AS nombreempresa,
                            empresa.estado  AS empresaestado,
 
                            rol.idrol  AS idrol,
                            rol.nombrerol AS nombrerol,

                            sede.idsede AS idsede,
                            sede.nombresede AS nombresede,
                            sede.metaagenda AS metaagenda 

                        FROM usuario
                            INNER JOIN empresa ON usuario.idempresa = empresa.idempresa
                            INNER JOIN sede ON empresa.idsede = sede.idsede
                            INNER JOIN rol ON usuario.idrol  = rol.idrol 
                            
                        WHERE (usuario.dni = :email OR usuario.correo = :email)
                    ";
        
        // Preparar la consulta
        $stmt = $conn->prepare($select);
        
        // Vincular el parámetro de forma segura
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Retornar los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Capturar cualquier error y devolver un mensaje
        echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    } finally {
        $conn = null;
    }
}

?>
