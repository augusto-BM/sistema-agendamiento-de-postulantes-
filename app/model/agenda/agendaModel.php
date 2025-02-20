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

    public function insertarAgendas($postulante, $tipodocumento, $numerodocumento, $edad, $celular, $celular2, $distrito, $fuente, $contacto, $observaciones, $agenda, $asistencia, $fecharegistro, $horaregistro, $fechaagenda, $turno, $sede, $sedeprincipal, $idusuario)
    {
        //Verificar si el DNI o el celular ya existen en la BBDD
        $queryValidar = $this->PDO->prepare("SELECT numerodocumento, celular FROM agenda WHERE numerodocumento = :numerodocumento OR celular = :celular");
        $queryValidar->bindParam(':numerodocumento', $numerodocumento);
        $queryValidar->bindParam(':celular', $celular);
        $queryValidar->execute();
        $agendaExiste = $queryValidar->fetch(PDO::FETCH_ASSOC);

        if ($agendaExiste) {
            $errors = [];
            if ($agendaExiste['numerodocumento'] == $numerodocumento) {
                $errors[] = "El DNI ya está registrado.";
            }
            if ($agendaExiste['celular'] == $celular) {
                $errors[] = "El celular ya está registrado.";
            }
            return ["success" => false, "message" => $errors]; // Asegúrate de devolver un mensaje con errores
        }

        // Preparamos la consulta SQL para insertar los datos en la tabla
        $stament = $this->PDO->prepare(
            "INSERT INTO agenda 
                (postulante, tipodocumento, numerodocumento, edad, celular, celular2, distrito, fuente, contacto, observaciones, agenda, asistencia, fecharegistro, horaregistro, fechaagenda, turno, sede, sedeprincipal, idusuario) 
            VALUES 
                (:postulante, :tipodocumento, :numerodocumento, :edad, :celular, :celular2, :distrito, :fuente, :contacto, :observaciones, :agenda, :asistencia, :fecharegistro, :horaregistro, :fechaagenda, :turno, :sede, :sedeprincipal, :idusuario)"
        );


        // Vínculo de parámetros
        $stament->bindParam(':postulante', $postulante);
        $stament->bindParam(':tipodocumento', $tipodocumento);
        $stament->bindParam(':numerodocumento', $numerodocumento);
        $stament->bindParam(':edad', $edad);
        $stament->bindParam(':celular', $celular);
        $stament->bindParam(':celular2', $celular2);
        $stament->bindParam(':distrito', $distrito);
        $stament->bindParam(':fuente', $fuente);
        $stament->bindParam(':contacto', $contacto);
        $stament->bindParam(':observaciones', $observaciones);
        $stament->bindParam(':agenda', $agenda);
        $stament->bindParam(':asistencia', $asistencia);
        $stament->bindParam(':fecharegistro', $fecharegistro);
        $stament->bindParam(':horaregistro', $horaregistro);
        $stament->bindParam(':fechaagenda', $fechaagenda);
        $stament->bindParam(':turno', $turno);
        $stament->bindParam(':sede', $sede);
        $stament->bindParam(':sedeprincipal', $sedeprincipal);
        $stament->bindParam(':idusuario', $idusuario);

        // Ejecutar la consulta y retornar el mensaje de éxito o error
        if ($stament->execute()) {
            return ["success" => true, "message" => "Usuario registrado correctamente"];
        } else {
            return ["success" => false, "message" => "Hubo un error al registrar el usuario"];
        }
    }

    public function editarAgenda($idagenda, $postulante, $tipodocumento, $numerodocumento, $edad, $celular,  $distrito, $fuente, $contacto, $observaciones, $agenda, $estado, $fechaagenda, $fecha_agenda_original, $fechaagendamodificacion, $turno, $sede, $sedeprincipal, $idusuario)
    {
        // Verificar si el DNI o el celular ya existen en la BBDD
        $queryValidar = $this->PDO->prepare("SELECT numerodocumento, celular FROM agenda WHERE (numerodocumento = :numerodocumento OR celular = :celular) AND idagenda != :idagenda");
        $queryValidar->bindParam(':numerodocumento', $numerodocumento);
        $queryValidar->bindParam(':celular', $celular);
        $queryValidar->bindParam(':idagenda', $idagenda);
        $queryValidar->execute();
        $agendaExiste = $queryValidar->fetch(PDO::FETCH_ASSOC);

        if ($agendaExiste) {
            $errors = [];
            if ($agendaExiste['numerodocumento'] == $numerodocumento) {
                $errors[] = "El DNI ya está registrado.";
            }
            if ($agendaExiste['celular'] == $celular) {
                $errors[] = "El celular ya está registrado.";
            }
            return ["success" => false, "message" => $errors];
        }

        // Verificar si las fechas son diferentes
        if ($fechaagenda != $fecha_agenda_original) {
            // Si las fechas son distintas, incrementar fechaagendamodificacion
            $fechaagendamodificacion++;
        }

        // Consulta SQL para actualizar la agenda
        $sql = "UPDATE agenda 
                SET 
                    postulante = :postulante,
                    tipodocumento = :tipodocumento,
                    numerodocumento = :numerodocumento,
                    edad = :edad,
                    celular = :celular,
                    distrito = :distrito,
                    fuente = :fuente,
                    contacto = :contacto,
                    observaciones = :observaciones,
                    agenda = :agenda,
                    estado = :estado,
                    fechaagenda = :fechaagenda,
                    fechaagendamodificacion = :fechaagendamodificacion,  -- Aquí actualizamos el campo
                    turno = :turno,
                    sede = :sede,
                    sedeprincipal = :sedeprincipal,
                    idusuario = :idusuario
                WHERE 
                    idagenda = :idagenda";

        $stament = $this->PDO->prepare($sql);

        // Vincular los parámetros
        $stament->bindParam(':postulante', $postulante);
        $stament->bindParam(':tipodocumento', $tipodocumento);
        $stament->bindParam(':numerodocumento', $numerodocumento);
        $stament->bindParam(':edad', $edad);
        $stament->bindParam(':celular', $celular);
        $stament->bindParam(':distrito', $distrito);
        $stament->bindParam(':fuente', $fuente);
        $stament->bindParam(':contacto', $contacto);
        $stament->bindParam(':observaciones', $observaciones);
        $stament->bindParam(':agenda', $agenda);
        $stament->bindParam(':estado', $estado);
        $stament->bindParam(':fechaagenda', $fechaagenda);
        $stament->bindParam(':fechaagendamodificacion', $fechaagendamodificacion);  // Aquí también lo vinculamos
        $stament->bindParam(':turno', $turno);
        $stament->bindParam(':sede', $sede);
        $stament->bindParam(':sedeprincipal', $sedeprincipal);
        $stament->bindParam(':idusuario', $idusuario);
        $stament->bindParam(':idagenda', $idagenda);

        // Ejecutar la consulta y retornar el mensaje de éxito o error
        if ($stament->execute()) {
            return ["success" => true, "message" => "Agenda actualizada correctamente"];
        } else {
            return ["success" => false, "message" => "Hubo un error al actualizar la agenda"];
        }
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
                                                a.idusuario as idusuario, a.fechaagendamodificacion, u.nombreusuario as nombreusuario
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
