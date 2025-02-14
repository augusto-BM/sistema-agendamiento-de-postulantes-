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

    public function agendas($idrol, $nombreSede = null, $estado = null, $nombreReclutador = null, $query = null, $nombreUsuario = null, $fechaInicio = null, $fechaFin = null, $page = 1, $limit = 50)
    {
        $query = "%" . $query . "%";
        $offset = ($page - 1) * $limit;
        $sql = "";
        $totalRecords = 0;

        // Para roles 2 y 4 (consulta por defecto sin filtros adicionales)
        if ($idrol == 2 || $idrol == 4) {
            $sql .= "   SELECT 
                        a.idagenda as idagenda, a.postulante as postulante, a.tipodocumento as tipodocumento , 
                        a.numerodocumento as numerodocumento, a.edad as edad, a.celular as celular, a.celular2 as celular2,
                        a.distrito as distrito, a.fuente as fuente, a.contacto as contacto, a.observaciones as observaciones,
                        a.agenda as agenda, a.estado as estado, a.estadofinal as estadofinal, a.asistencia as asistencia,
                        a.fecharegistro as fecharegistro, a.horaregistro as horaregistro, a.fechaagenda as fechaagenda,
                        a.fechareprogramacion as fechareprogramacion, a.turno as turno, a.sede as sede, a.sedeprincipal as sedeprincipal,
                        a.idusuario as idusuario, u.nombreusuario as nombreusuario
                    FROM agenda a 
                    INNER JOIN usuario u ON a.idusuario = u.idusuario
                    WHERE 1 = 1"; // Esto es para no tener que poner un "WHERE" vacío

            // Filtrar por nombreSede si se ha especificado
            if ($nombreSede !== null) {
                $sql .= " AND a.sede = :sede";
            }

            // Filtrar por estado si se ha especificado
            if ($estado !== null) {
                $sql .= " AND a.estado = :estado";
            }

            // Filtrar por nombreReclutador si se ha especificado
            if ($nombreReclutador !== null) {
                $sql .= " AND u.nombreusuario = :nombreusuario";
            }

            // Filtrar por query si se ha especificado
            if ($query !== null) {
                $sql .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
            }

            // Filtro de fechas (si están presentes)
            if ($fechaInicio && $fechaFin) {
                $sql .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

            // Agregar el ORDER BY para ordenar por fecha de registro
            $sql .= " ORDER BY a.fecharegistro DESC"; // O ASC, según lo necesites

            // Agregar paginación
            $sql .= " LIMIT :limit OFFSET :offset";

            // Consulta de COUNT para obtener el total de registros
            $countQuery = "SELECT COUNT(*) as total 
                       FROM agenda a 
                       INNER JOIN usuario u ON a.idusuario = u.idusuario
                       WHERE 1 = 1";

            if ($nombreSede !== null) {
                $countQuery .= " AND a.sede = :sede";
            }

            if ($estado !== null) {
                $countQuery .= " AND a.estado = :estado";
            }

            if ($nombreReclutador !== null) {
                $countQuery .= " AND u.nombreusuario = :nombreusuario";
            }

            if ($query !== null) {
                $countQuery .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
            }

            if ($fechaInicio && $fechaFin) {
                $countQuery .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

            // Agregar el ORDER BY para la consulta de conteo (aunque no es estrictamente necesario)
            $countQuery .= " ORDER BY a.fecharegistro DESC"; // O ASC

            // Ejecutar la consulta para contar el total de registros
            $stmtCount = $this->PDO->prepare($countQuery);
            if ($nombreSede !== null) {
                $stmtCount->bindParam(':sede', $nombreSede, PDO::PARAM_STR);
            }

            if ($estado !== null) {
                $stmtCount->bindParam(':estado', $estado, PDO::PARAM_STR);
            }

            if ($nombreReclutador !== null) {
                $stmtCount->bindParam(':nombreusuario', $nombreReclutador, PDO::PARAM_STR);
            }

            if ($query !== null) {
                $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
            }

            if ($fechaInicio && $fechaFin) {
                $stmtCount->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmtCount->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmtCount->execute();
            $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

            // Ejecutar la consulta principal
            $stmt = $this->PDO->prepare($sql);

            // Vincular los parámetros de la consulta principal
            if ($nombreSede !== null) {
                $stmt->bindParam(':sede', $nombreSede, PDO::PARAM_STR);
            }

            if ($estado !== null) {
                $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            }

            if ($nombreReclutador !== null) {
                $stmt->bindParam(':nombreusuario', $nombreReclutador, PDO::PARAM_STR);
            }

            if ($query !== null) {
                $stmt->bindParam(':query', $query, PDO::PARAM_STR);
            }

            if ($fechaInicio && $fechaFin) {
                $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return ['agendas' => $data, 'total' => $totalRecords];
            }

            return false;
        } else if ($idrol == 1 || $idrol == 3) {
            // Si el rol es 1 o 3, se realiza una consulta similar, pero con filtros diferentes
            $sql .= "   SELECT 
                        a.idagenda as idagenda, a.postulante as postulante, a.tipodocumento as tipodocumento , 
                        a.numerodocumento as numerodocumento, a.edad as edad, a.celular as celular, a.celular2 as celular2,
                        a.distrito as distrito, a.fuente as fuente, a.contacto as contacto, a.observaciones as observaciones,
                        a.agenda as agenda, a.estado as estado, a.estadofinal as estadofinal, a.asistencia as asistencia,
                        a.fecharegistro as fecharegistro, a.horaregistro as horaregistro, a.fechaagenda as fechaagenda,
                        a.fechareprogramacion as fechareprogramacion, a.turno as turno, a.sede as sede, a.sedeprincipal as sedeprincipal,
                        a.idusuario as idusuario, u.nombreusuario as nombreusuario
                    FROM agenda a 
                    INNER JOIN usuario u ON a.idusuario = u.idusuario
                    WHERE 1 = 1"; // Esto es para no tener que poner un "WHERE" vacío

            // Filtrar por nombreUsuario si se ha especificado
            if ($nombreUsuario !== null) {
                $sql .= " AND u.nombreusuario = :nombreusuario";
            }

            // Filtrar por query si se ha especificado
            if ($query !== null) {
                $sql .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
            }

            // Filtro de fechas (si están presentes)
            if ($fechaInicio && $fechaFin) {
                $sql .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

            // Agregar el ORDER BY para ordenar por fecha de registro
            $sql .= " ORDER BY a.fecharegistro DESC"; // O ASC, según lo necesites

            // Agregar paginación
            $sql .= " LIMIT :limit OFFSET :offset";

            // Consulta de COUNT para obtener el total de registros
            $countQuery = "SELECT COUNT(*) as total 
                       FROM agenda a 
                       INNER JOIN usuario u ON a.idusuario = u.idusuario
                       WHERE 1 = 1";

            if ($nombreUsuario !== null) {
                $countQuery .= " AND u.nombreusuario = :nombreusuario";
            }

            if ($query !== null) {
                $countQuery .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
            }

            if ($fechaInicio && $fechaFin) {
                $countQuery .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

            // Agregar el ORDER BY para la consulta de conteo
            $countQuery .= " ORDER BY a.fecharegistro DESC"; // O ASC

            // Ejecutar la consulta para contar el total de registros
            $stmtCount = $this->PDO->prepare($countQuery);

            if ($nombreUsuario !== null) {
                $stmtCount->bindParam(':nombreusuario', $nombreUsuario, PDO::PARAM_STR);
            }

            if ($query !== null) {
                $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
            }

            if ($fechaInicio && $fechaFin) {
                $stmtCount->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmtCount->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmtCount->execute();
            $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

            // Ejecutar la consulta principal
            $stmt = $this->PDO->prepare($sql);

            // Vincular los parámetros de la consulta principal
            if ($nombreUsuario !== null) {
                $stmt->bindParam(':nombreusuario', $nombreUsuario, PDO::PARAM_STR);
            }

            if ($query !== null) {
                $stmt->bindParam(':query', $query, PDO::PARAM_STR);
            }

            if ($fechaInicio && $fechaFin) {
                $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return ['agendas' => $data, 'total' => $totalRecords];
            }

            return false;
        }

        return false;
    }

    public function verAgenda($idagenda)
    {
        $stament = $this->PDO->prepare("    SELECT 
                                                a.idagenda as idagenda, a.postulante as postulante, a.tipodocumento as tipodocumento , 
                                                a.numerodocumento as numerodocumento, a.edad as edad, a.celular as celular, a.celular2 as celular2,
                                                a.distrito as distrito, a.fuente as fuente, a.contacto as contacto, a.observaciones as observaciones,
                                                a.agenda as agenda, a.estado as estado, a.estadofinal as estadofinal, a.asistencia as asistencia,
                                                a.fecharegistro as fecharegistro, a.horaregistro as horaregistro, a.fechaagenda as fechaagenda,
                                                a.fechareprogramacion as fechareprogramacion, a.turno as turno, a.sede as sede, a.sedeprincipal as sedeprincipal,
                                                a.idusuario as idusuario, u.nombreusuario as nombreusuario
                                            FROM agenda a 
                                            INNER JOIN usuario u ON a.idusuario = u.idusuario
                                            WHERE a.idagenda = :idagenda limit 1
                                     ");
        $stament->bindParam(':idagenda', $idagenda);
        return ($stament->execute()) ? $stament->fetch(PDO::FETCH_OBJ) : false;
    }

    public function reclutadoress()
    {
        $stament = $this->PDO->prepare(
            "   SELECT DISTINCT 
                                                usuario.idusuario, 
                                                usuario.nombreusuario, 
                                                usuario.estado 
                                            FROM usuario 
                                            INNER JOIN agenda ON usuario.idusuario = agenda.idusuario
                                            WHERE usuario.estado = 2
                                            ORDER BY usuario.nombreusuario ASC;
                                        "
        );
        return ($stament->execute()) ? $stament->fetchAll(PDO::FETCH_OBJ) : false;
    }
}
