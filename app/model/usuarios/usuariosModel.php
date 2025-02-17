<?php
class usuariosModel
{
    private $PDO;

    public function __construct()
    {
        require_once '../../../config/conexion/conexion.php';
        $con = Database::getInstance();
        $this->PDO = $con->getConnection();
    }

    public function usuarios($idempresa = null, $page = 1, $limit = 50, $query = "")
    {
        $offset = ($page - 1) * $limit;  // Calcular el offset

        // Consulta de usuarios
        $sqlQuery = "SELECT idusuario, nombreusuario, dni, correo, celular, sede, fechaingreso, estado
                 FROM usuario WHERE estado = 2";

        // Si idempresa es proporcionado, se filtra en la consulta
        if ($idempresa !== null) {
            $sqlQuery .= " AND idempresa = :idempresa";
        }

        // Si hay query de búsqueda, agregar el filtro
        if (!empty($query)) {
            // Agregar los comodines "%" para hacer una búsqueda parcial
            $query = "%" . $query . "%";  // Esto se asegura que la búsqueda sea parcial
            $sqlQuery .= " AND (nombreusuario LIKE :query OR dni LIKE :query)";
        }

        // Consulta de COUNT para obtener el total de registros
        $countQuery = "SELECT COUNT(*) as total FROM usuario WHERE estado = 2";

        if ($idempresa !== null) {
            $countQuery .= " AND idempresa = :idempresa";
        }

        // Si hay query de búsqueda, agregar el filtro
        if (!empty($query)) {
            $countQuery .= " AND (nombreusuario LIKE :query OR dni LIKE :query)";
        }

        // Ejecutamos la consulta para contar el total de registros
        $stmtCount = $this->PDO->prepare($countQuery);
        if ($idempresa !== null) {
            $stmtCount->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        if (!empty($query)) {
            $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
        }
        $stmtCount->execute();
        $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

        // Consulta para obtener los usuarios con LIMIT y OFFSET
        $sqlQuery .= " ORDER BY nombreusuario ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->PDO->prepare($sqlQuery);

        if ($idempresa !== null) {
            $stmt->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        if (!empty($query)) {
            $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return ['users' => $data, 'total' => $totalRecords];
        }

        return false;
    }

    public function cambiarEstadoUsuario($idusuario, $estado)
    {
        $stament = $this->PDO->prepare("UPDATE usuario SET estado = :estado WHERE idusuario = :idusuario");
        $stament->bindParam(':estado', $estado);
        $stament->bindParam(':idusuario', $idusuario);
        return ($stament->execute()) ? $idusuario : false;
    }

    public function insertarUsuarios($nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $sede, $idempresa, $turno, $estado, $idrol, $fechaingreso)
    {
        // Preparamos la consulta SQL para insertar los datos en la tabla
        $stament = $this->PDO->prepare(
            "INSERT INTO usuario 
                (nombreusuario, tipodocumento, dni, correo, pass, celular, sede, idempresa, turno, estado, idrol, fechaingreso) 
                VALUES 
                (:nombreusuario, :tipodocumento, :dni, :correo, :pass, :celular, :sede, :idempresa, :turno, :estado, :idrol, :fechaingreso)"
        );

        // Vínculo de parámetros
        $stament->bindParam(':nombreusuario', $nombreusuario);
        $stament->bindParam(':tipodocumento', $tipodocumento);
        $stament->bindParam(':dni', $dni);
        $stament->bindParam(':correo', $correo);
        $stament->bindParam(':pass', $pass);
        $stament->bindParam(':celular', $celular);
        $stament->bindParam(':sede', $sede);
        $stament->bindParam(':idempresa', $idempresa);
        $stament->bindParam(':turno', $turno);
        $stament->bindParam(':estado', $estado);
        $stament->bindParam(':idrol', $idrol);
        $stament->bindParam(':fechaingreso', $fechaingreso);

        // Ejecutar la consulta y retornar el último ID insertado si tiene éxito, o false si falla
        return ($stament->execute()) ? $this->PDO->lastInsertId() : false;
    }

    public function verUsuario($idusuario)
    {
        $stament = $this->PDO->prepare("    SELECT 
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

                                            WHERE usuario.idusuario = :idusuario limit 1
                                     ");
        $stament->bindParam(':idusuario', $idusuario);
        return ($stament->execute()) ? $stament->fetch(PDO::FETCH_OBJ) : false;
    }

    public function editarUsuario($idusuario, $nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $idempresa, $turno, $idrol, $fechaingreso)
    {
        $sql = "UPDATE 
                    usuario 
                SET 
                    nombreusuario = :nombreusuario,
                    tipodocumento = :tipodocumento,
                    dni = :dni,
                    correo = :correo,
                    pass = :pass,
                    celular = :celular,
                    idempresa = :idempresa,
                    turno = :turno,
                    idrol = :idrol,
                    fechaingreso = :fechaingreso
                WHERE 
                    idusuario = :idusuario";

        $stament = $this->PDO->prepare($sql);

        $stament->bindParam(':nombreusuario', $nombreusuario);
        $stament->bindParam(':tipodocumento', $tipodocumento);
        $stament->bindParam(':dni', $dni);
        $stament->bindParam(':correo', $correo);
        $stament->bindParam(':pass', $pass);
        $stament->bindParam(':celular', $celular);
        $stament->bindParam(':idempresa', $idempresa);
        $stament->bindParam(':turno', $turno);
        $stament->bindParam(':idrol', $idrol);
        $stament->bindParam(':fechaingreso', $fechaingreso);
        $stament->bindParam(':idusuario', $idusuario);

        return ($stament->execute()) ? $idusuario : false;
    }

    public function usuariosInactivos($idempresa = null, $page = 1, $limit = 50, $query = "")
    {
        $offset = ($page - 1) * $limit;  // Calcular el offset

        // Consulta de usuarios
        $sqlQuery = "SELECT idusuario, nombreusuario, dni, correo, celular, sede, fechaingreso, estado
                 FROM usuario WHERE estado = 3";

        // Si idempresa es proporcionado, se filtra en la consulta
        if ($idempresa !== null) {
            $sqlQuery .= " AND idempresa = :idempresa";
        }

        // Si hay query de búsqueda, agregar el filtro
        if (!empty($query)) {
            // Agregar los comodines "%" para hacer una búsqueda parcial
            $query = "%" . $query . "%";  // Esto se asegura que la búsqueda sea parcial
            $sqlQuery .= " AND (nombreusuario LIKE :query OR dni LIKE :query)";
        }

        // Consulta de COUNT para obtener el total de registros
        $countQuery = "SELECT COUNT(*) as total FROM usuario WHERE estado = 3";

        if ($idempresa !== null) {
            $countQuery .= " AND idempresa = :idempresa";
        }

        // Si hay query de búsqueda, agregar el filtro
        if (!empty($query)) {
            $countQuery .= " AND (nombreusuario LIKE :query OR dni LIKE :query)";
        }

        // Ejecutamos la consulta para contar el total de registros
        $stmtCount = $this->PDO->prepare($countQuery);
        if ($idempresa !== null) {
            $stmtCount->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        if (!empty($query)) {
            $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
        }
        $stmtCount->execute();
        $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

        // Consulta para obtener los usuarios con LIMIT y OFFSET
        $sqlQuery .= " ORDER BY nombreusuario ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->PDO->prepare($sqlQuery);

        if ($idempresa !== null) {
            $stmt->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        if (!empty($query)) {
            $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return ['users' => $data, 'total' => $totalRecords];
        }

        return false;
    }
    /* public function delete($id){
        $stament = $this->PDO->prepare("DELETE FROM username WHERE id = :id");
        $stament->bindParam(':id', $id);
        return ($stament->execute()) ? true : false;
    } */
}
