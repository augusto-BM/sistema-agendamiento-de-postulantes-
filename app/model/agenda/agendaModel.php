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

    public function buscarUsuarios($query, $idempresa = null, $page = 1, $limit = 10)
    {
        $query = "%" . $query . "%";
        $offset = ($page - 1) * $limit;

        // Consulta de búsqueda de usuarios
        $sql = "SELECT idusuario, nombreusuario, dni, correo, celular, sede, fechaingreso, estado
                    FROM usuario WHERE (nombreusuario LIKE :query OR dni LIKE :query) AND estado = 2";

        if ($idempresa !== null) {
            $sql .= " AND idempresa = :idempresa";  // Filtro de idempresa en la búsqueda
        }

        // Consulta de COUNT para obtener el total de resultados
        $countQuery = "SELECT COUNT(*) as total
                           FROM usuario WHERE (nombreusuario LIKE :query OR dni LIKE :query) AND estado = 2";

        if ($idempresa !== null) {
            $countQuery .= " AND idempresa = :idempresa";  // Aquí también se incluye el filtro de idempresa
        }

        // Ejecutamos la consulta para contar el total de registros
        $stmtCount = $this->PDO->prepare($countQuery);
        $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
        if ($idempresa !== null) {
            $stmtCount->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        $stmtCount->execute();
        $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

        // Consulta para obtener los usuarios con LIMIT y OFFSET
        $sql .= " ORDER BY nombreusuario ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->PDO->prepare($sql);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);

        if ($idempresa !== null) {
            $stmt->bindParam(':idempresa', $idempresa, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return ['users' => $data, 'total' => $totalRecords];  // Devolver los usuarios y el total de registros
        }

        return false;
    }

}
