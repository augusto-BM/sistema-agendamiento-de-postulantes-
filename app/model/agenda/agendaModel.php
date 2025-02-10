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

    public function agendas($idrol, $nombreSede = null, $estado = null, $nombreReclutador = null, $nombreUsuario = null, $fechaInicio = null, $fechaFin = null, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;  // Calcular el offset
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

            // Filtro de fechas (si están presentes)
            if ($fechaInicio && $fechaFin) {
                $sql .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

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

            if ($fechaInicio && $fechaFin) {
                $countQuery .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

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

            if ($fechaInicio && $fechaFin) {
                $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return ['users' => $data, 'total' => $totalRecords];
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

            // Filtro de fechas (si están presentes)
            if ($fechaInicio && $fechaFin) {
                $sql .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

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

            if ($fechaInicio && $fechaFin) {
                $countQuery .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
            }

            // Ejecutar la consulta para contar el total de registros
            $stmtCount = $this->PDO->prepare($countQuery);

            if ($nombreUsuario !== null) {
                $stmtCount->bindParam(':nombreusuario', $nombreUsuario, PDO::PARAM_STR);
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

            if ($fechaInicio && $fechaFin) {
                $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
                $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return ['users' => $data, 'total' => $totalRecords];
            }

            return false;
        }

        return false;
    }



    public function buscarAgendas($query = null, $idrol, $nombreSede = null, $estado = null, $nombreReclutador = null, $fechaInicio = null, $fechaFin = null, $page = 1, $limit = 10)
    {
        $query = "%" . $query . "%";
        $offset = ($page - 1) * $limit;
        $sql = "";

        // Para roles 2 y 4 (consulta por defecto sin filtros adicionales)
        if ($idrol == 2 || $idrol == 4) {
            $sql .= "   SELECT 
                        a.idagenda as idagenda, a.postulante as postulante, a.tipodocumento as tipodocumento , 
                        a.numerodocumento as numerodocumento, a.edad as edad, a.celular as celular, a.celular2 as celular2,
                        a.distrito as distrito, a.fuente as fuente, a.contacto as contacto, a.observaciones as observaciones,
                        a.agenda as agenda, a.estado as estado, a.estadofinal as estadofinal, a.asistencia as asistencia,
                        a.fecharegistro as fecharegistro, a.horaregistro as horaregistro, a.fechaagenda as fechaagenda,
                        a.fechareprogramacion as fechareprogramacion, a.turno as turno, a.sede as sede, a.sedeprincipal as sedeprincipal,
                        a.idusuario as idusuario, u.nombreReclutador as nombreReclutador
                    FROM agenda a INNER JOIN usuario u ON a.idusuario = u.idusuario
                    WHERE 1 = 1"; // Esto es para no tener que poner un "WHERE" vacío

            if ($query !== null) {
                $sql .= " AND (postulante LIKE :query OR numerodocumento LIKE :query OR celular LIKE :query)";
            }

            if ($nombreSede !== null) {
                $sql .= " AND sede = :sede";  // Usar nombreSede en lugar de sede
            }

            if ($nombreReclutador !== null) {
                $sql .= " AND nombreReclutador = :nombreReclutador";
            }

            if ($estado !== null) {
                $sql .= " AND estado = :estado";  // Filtro por estado solo para roles 2 y 4
            }
        }

        // Para rol 1 o 2 (con filtros más específicos)
        else if ($idrol == 1 || $idrol == 2) {
            $sql .= "   SELECT 
                        a.idagenda as idagenda, a.postulante as postulante, a.tipodocumento as tipodocumento , 
                        a.numerodocumento as numerodocumento, a.edad as edad, a.celular as celular, a.celular2 as celular2,
                        a.distrito as distrito, a.fuente as fuente, a.contacto as contacto, a.observaciones as observaciones,
                        a.agenda as agenda, a.estado as estado, a.estadofinal as estadofinal, a.asistencia as asistencia,
                        a.fecharegistro as fecharegistro, a.horaregistro as horaregistro, a.fechaagenda as fechaagenda,
                        a.fechareprogramacion as fechareprogramacion, a.turno as turno, a.sede as sede, a.sedeprincipal as sedeprincipal,
                        a.idusuario as idusuario, u.nombreReclutador as nombreReclutador 
                    FROM agenda a INNER JOIN usuario u ON a.idusuario = u.idusuario
                    WHERE 1 = 1"; // Similar a antes, para evitar problemas con un WHERE vacío

            if ($nombreReclutador !== null) {
                $sql .= " AND u.nombreReclutador = :nombreReclutador"; // Usar = si es exacto
            }

            if ($query !== null) {
                // Filtrado por query (si lo hay)
                $sql .= " AND (postulante LIKE :query OR numerodocumento LIKE :query OR celular LIKE :query)";
            }
        }

        // Filtro de fechas (si están presentes)
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
        }

        // Consulta para contar el total de resultados
        $countQuery = "SELECT COUNT(*) as total
                       FROM agenda a INNER JOIN usuario u ON a.idusuario = u.idusuario
                       WHERE 1 = 1";

        // Agregar filtros a la consulta COUNT según los parámetros
        if ($query !== null) {
            $countQuery .= " AND (postulante LIKE :query OR numerodocumento LIKE :query OR celular LIKE :query)";
        }

        if ($nombreSede !== null) {
            $countQuery .= " AND sede = :sede";  // Usar nombreSede en lugar de sede
        }

        if ($nombreReclutador !== null) {
            $countQuery .= " AND u.nombreReclutador = :nombreReclutador";  // = para coincidencia exacta
        }

        // Filtro de estado solo se agregará si es aplicable en la consulta principal
        if ($estado !== null) {
            $countQuery .= " AND estado = :estado";  // Filtro por estado solo para roles 2 y 4
        }

        if ($fechaInicio && $fechaFin) {
            $countQuery .= " AND a.fecharegistro BETWEEN :fechaInicio AND :fechaFin";
        }

        // Ejecutar la consulta de contar el total
        $stmtCount = $this->PDO->prepare($countQuery);
        $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);

        if ($nombreSede !== null) {
            $stmtCount->bindParam(':sede', $nombreSede, PDO::PARAM_STR);
        }

        if ($nombreReclutador !== null) {
            $stmtCount->bindParam(':nombreReclutador', $nombreReclutador, PDO::PARAM_STR);
        }

        if ($estado !== null) {
            $stmtCount->bindParam(':estado', $estado, PDO::PARAM_STR);  // Filtro de estado
        }

        if ($fechaInicio && $fechaFin) {
            $stmtCount->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
            $stmtCount->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
        }

        $stmtCount->execute();
        $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

        // Consulta final con LIMIT y OFFSET
        $sql .= " ORDER BY a.fechaagenda DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->PDO->prepare($sql);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);

        if ($nombreSede !== null) {
            $stmt->bindParam(':sede', $nombreSede, PDO::PARAM_STR);
        }

        if ($nombreReclutador !== null) {
            $stmt->bindParam(':nombreReclutador', $nombreReclutador, PDO::PARAM_STR);
        }

        if ($estado !== null) {
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);  // Filtro de estado
        }

        if ($fechaInicio && $fechaFin) {
            $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);
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
