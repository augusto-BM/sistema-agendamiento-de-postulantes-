<?php
class inicioModel
{
  private $PDO;

  public function __construct()
  {
    require_once '../../../config/conexion/conexion.php';
    $con = Database::getInstance();
    $this->PDO = $con->getConnection();
  }

  /*   public function metaDiaria($idusuario){
    $stament = $this->PDO->prepare("SELECT * FROM metas WHERE idusuario = :idusuario");
    $stament->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    return ($stament->execute()) ? $stament->fetch(PDO::FETCH_OBJ) : false;
  } */


  //LISTAR EL TOP DE 5 COLABORADORES CON MAS AGENDADOS DEL DIA
  public function top5Diario($next, $nombresede)
  {
    $stament = $this->PDO->prepare("  SELECT a.idusuario AS idusuario, u.nombreusuario AS nombreusuario, COUNT(*) AS contar
                                            FROM agenda a
                                            INNER JOIN usuario u ON a.idusuario = u.idusuario
                                            WHERE ((a.agenda = 'AGENDADO' AND a.fechaagenda = :next)  
                                                OR (a.agenda = 'REPROGRAMADO' AND a.fechareprogramacion = :next))
                                            AND a.sede = :nombresede
                                            AND a.estado = ''
                                            GROUP BY a.idusuario, u.nombreusuario
                                            HAVING COUNT(*) > 0
                                            ORDER BY contar DESC
                                            LIMIT 0, 5
                                        ");

    $stament->bindParam(':next', $next, PDO::PARAM_STR);
    $stament->bindParam(':nombresede', $nombresede, PDO::PARAM_STR);

    if ($stament->execute()) {
      $data = $stament->fetchAll(PDO::FETCH_OBJ);
      return ['usersTop5Diario' => $data ?: []];
    }
  }

  //LISTAR EL TOP DE 5 COLABORADORES CON MAS AGENDADOS DEL DIA
  public function topAsistenciaDiario($now, $nombresede)
  {
    $stament = $this->PDO->prepare("    SELECT a.idusuario AS idusuario, u.nombreusuario AS nombreusuario, COUNT(*) AS contara 
                                            FROM agenda a
                                            INNER JOIN usuario u ON a.idusuario = u.idusuario
                                            WHERE a.estado = 'CONFIRMADO' AND a.asistencia = 'SI' AND (a.fechaagenda = :now OR a.fechareprogramacion = :now) AND a.sede = :nombresede
                                            GROUP BY a.idusuario, u.nombreusuario
                                            HAVING COUNT(*) > 0
                                            ORDER BY contara 
                                            DESC LIMIT 0, 5
                                     ");

    $stament->bindParam(':now', $now, PDO::PARAM_STR);
    $stament->bindParam(':nombresede', $nombresede, PDO::PARAM_STR);

    if ($stament->execute()) {
      $data = $stament->fetchAll(PDO::FETCH_OBJ);
      return ['usersTop5AsistenciaDiario' => $data ?: []];
    }
  }

  //
  public function cardObjetivosAgendas($next, $nombresede, $turno, $idrol, $idusuario)
  {
    // Array para almacenar las consultas
    $queries = [];

    // Parámetros comunes
    $params = [
      ':turno'      => $turno,
      ':next'       => $next,
      ':nombresede' => $nombresede,
    ];

    try {
      // Si el rol es Moderador o Usuario, añadimos más parámetros
      if ($idrol == 2 || $idrol == 3) { // MODERADOR (2) y USUARIO (3)
        $params[':idusuario'] = $idusuario;
        $params[':sedeprincipal'] = $nombresede;

        $queries['voy'] = "SELECT COUNT(*) FROM agenda 
                      WHERE turno= :turno 
                      AND idusuario= :idusuario 
                      AND (agenda='AGENDADO' OR agenda='REPROGRAMADO') 
                      AND (fechaagenda= :next OR fechareprogramacion= :next) 
                      AND sedeprincipal = :nombresede";
      }

      // Consulta general para todos los roles
      $queries['vamos'] = "SELECT COUNT(*) FROM agenda 
                      WHERE turno= :turno 
                      AND (agenda='AGENDADO' OR agenda='REPROGRAMADO') 
                      AND (fechaagenda= :next OR fechareprogramacion= :next) 
                      AND sede = :nombresede";

      // Ejecutar todas las consultas
      $results = [];
      foreach ($queries as $key => $query) {
        $stmt = $this->PDO->prepare($query);
        // Se enlazan los parámetros (los que no se requieran en la consulta se ignoran)
        foreach ($params as $param => $value) {
          // Verifica si el parámetro está en la consulta antes de vincularlo
          if (strpos($query, $param) !== false) {
            $stmt->bindValue($param, $value);
          }
        }
        $stmt->execute();
        $results[$key] = $stmt->fetchColumn();
      }

      return $results;
    } catch (PDOException $e) {
      // Manejo de errores
      return ['error' => 'Error en la consulta: ' . $e->getMessage()];
    }
  }


  //CONTAR CARD DE ESTADOS DE AGENDAS
  public function cardEstadosAgendas($next, $now, $nombresede, $turno, $idrol, $idusuario)
  {
    // Array para almacenar las consultas
    $queries = [];
    // Parámetros comunes
    $params = [
      ':turno'      => $turno,
      ':now'        => $now,
      ':next'       => $next,
      ':nombresede' => $nombresede,
    ];

    if ($idrol == 1 || $idrol == 4) {
      // ADMINISTRADOR (4) y AUXILIAR (1)
      $queries['agenda'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND (agenda = 'AGENDADO' OR agenda = 'REPROGRAMADO')
                  AND (fechaagenda = :next OR fechareprogramacion = :next)
                  AND sede = :nombresede";

      $queries['entrevista'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND ((agenda = 'AGENDADO' AND fechaagenda = :now) OR (agenda = 'REPROGRAMADO' AND fechareprogramacion = :now))
                  AND sede = :nombresede";

      $queries['confirmados'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'CONFIRMADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['asistieron'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'CONFIRMADO'
                  AND asistencia = 'SI'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['no_responde'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'NORESPONDE'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['no_interesado'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'NOINTERESADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";
    } else if ($idrol == 2) {
      // MODERADOR (2)
      $queries['agenda'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND ((agenda = 'AGENDADO' OR agenda = 'REPROGRAMADO') AND estado = '')
                  AND (fechaagenda = :next OR fechareprogramacion = :next)
                  AND sede = :nombresede";

      $queries['entrevista'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND ((agenda = 'AGENDADO' AND fechaagenda = :now) OR (agenda = 'REPROGRAMADO' AND fechareprogramacion = :now))
                  AND sede = :nombresede";

      $queries['confirmados'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'CONFIRMADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['asistieron'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'CONFIRMADO'
                  AND asistencia = 'SI'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['no_responde'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'NORESPONDE'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";

      $queries['no_interesado'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND estado = 'NOINTERESADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sede = :nombresede";
    } else if ($idrol == 3) {
      // USUARIO (3)
      // Para usuarios se utiliza 'sedeprincipal' y se restringe por 'idusuario'
      $params[':idusuario'] = $idusuario;
      $params[':sedeprincipal'] = $nombresede;

      $queries['agenda'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND (agenda = 'AGENDADO' OR agenda = 'REPROGRAMADO')
                  AND (fechaagenda = :next OR fechareprogramacion = :next)
                  AND sedeprincipal = :nombresede";

      $queries['entrevista'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND ((agenda = 'AGENDADO' AND fechaagenda = :now) OR (agenda = 'REPROGRAMADO' AND fechareprogramacion = :now))
                  AND sedeprincipal = :nombresede";

      $queries['confirmados'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND estado = 'CONFIRMADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)";

      $queries['asistieron'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND estado = 'CONFIRMADO'
                  AND asistencia = 'SI'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)";

      $queries['no_responde'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND estado = 'NORESPONDE'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sedeprincipal = :nombresede";

      $queries['no_interesado'] = "SELECT COUNT(*) FROM agenda 
                WHERE turno = :turno 
                  AND idusuario = :idusuario
                  AND estado = 'NOINTERESADO'
                  AND (fechaagenda = :now OR fechareprogramacion = :now)
                  AND sedeprincipal = :nombresede";
    } else {
      // En caso de un rol no válido, se puede retornar un error o un array vacío
      return ['error' => 'Rol no válido'];
    }

    // Consulta común para LISTA NEGRA (sin condiciones de sede, turno, etc.)
    $queries['lista_negra'] = "SELECT COUNT(*) FROM agenda WHERE estado = 'LISTANEGRA'";

    // Ejecutar todas las consultas
    $results = [];
    foreach ($queries as $key => $query) {
      $stmt = $this->PDO->prepare($query);
      // Se enlazan los parámetros (los que no se requieran en la consulta se ignoran)
      foreach ($params as $param => $value) {
        // Verifica si el parámetro está en la consulta antes de vincularlo
        if (strpos($query, $param) !== false) {
          $stmt->bindValue($param, $value);
        }
      }
      $stmt->execute();
      $results[$key] = $stmt->fetchColumn();
    }

    return $results;
  }

  //CONTAR CARD DE AGENDADOS DE REDES
  public function cardAgendadosRedes($next, $nombresede, $turno, $idrol, $idusuario)
  {
    // Array para almacenar las consultas
    $queries = [];
    // Parámetros comunes
    $params = [
      ':turno'      => $turno,
      ':next'       => $next,
      ':nombresede' => $nombresede,
    ];

    if ($idrol == 1 || $idrol == 4) {
      // ADMINISTRADOR y AUXILIAR: se filtra por 'sede'
      $queries['facebook'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'FACEBOOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['computrabajo'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'COMPUTRABAJO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['instagram'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'INSTAGRAM' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['tiktok'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'TIKTOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['referido'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'REFERIDO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['bumeran'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'BUMERAN' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['otros'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'OTROS' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['reagendados'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND agenda = 'REPROGRAMADO' 
              AND fechareprogramacion = :next 
              AND sede = :nombresede";
    } else if ($idrol == 2) {
      // MODERADOR: estructura similar, usando 'sede'
      $queries['facebook'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'FACEBOOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['computrabajo'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'COMPUTRABAJO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['instagram'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'INSTAGRAM' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['tiktok'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'TIKTOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['referido'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'REFERIDO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['bumeran'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'BUMERAN' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['otros'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND fuente = 'OTROS' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sede = :nombresede";

      $queries['reagendados'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND agenda = 'REPROGRAMADO' 
              AND fechareprogramacion = :next 
              AND sede = :nombresede";
    } else if ($idrol == 3) {
      // USUARIO: se filtra por 'sedeprincipal' y por idusuario
      $params[':idusuario'] = $idusuario;
      $params[':sedeprincipal'] = $nombresede;

      $queries['facebook'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'FACEBOOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['computrabajo'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'COMPUTRABAJO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['instagram'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'INSTAGRAM' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['tiktok'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'TIKTOK' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['referido'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'REFERIDO' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['bumeran'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'BUMERAN' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['otros'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND fuente = 'OTROS' 
              AND fechaagenda = :next 
              AND agenda != 'REPROGRAMADO'
              AND sedeprincipal = :nombresede";

      $queries['reagendados'] = "SELECT COUNT(*) FROM agenda 
            WHERE turno = :turno 
              AND idusuario = :idusuario 
              AND agenda = 'REPROGRAMADO' 
              AND fechareprogramacion = :next 
              AND sedeprincipal = :nombresede";
    } else {
      return ['error' => 'Rol no válido'];
    }

    // Ejecutar todas las consultas
    $results = [];
    foreach ($queries as $key => $query) {
      $stmt = $this->PDO->prepare($query);
      foreach ($params as $param => $value) {
        if (strpos($query, $param) !== false) {
          $stmt->bindValue($param, $value);
        }
      }
      $stmt->execute();
      $results[$key] = $stmt->fetchColumn();
    }

    return $results;
  }

  //LISTA DE AGENDADOS DEL DIA
  public function agenda($idrol, $nombreSede, $turno, $next, $query = null, $idUsuario = null, $page = 1, $limit = 50)
  {
    $query = "%" . $query . "%";
    $offset = ($page - 1) * $limit;
    $sql = "";
    $totalRecords = 0;

    // Consulta principal
    $sql = "SELECT 
         a.idagenda as idagenda, 
         a.postulante as postulante, 
         a.numerodocumento as numerodocumento, 
         a.celular as celular,
         a.agenda as agenda, 
         a.fecharegistro as fecharegistro, 
         a.fechaagenda as fechaagenda,
         a.fechareprogramacion as fechareprogramacion,
         a.turno as turno, 
         a.sede as sede, 
         a.sedeprincipal as sedeprincipal,
         a.idusuario as idusuario, 
         u.nombreusuario as nombreusuario
     FROM agenda a 
     INNER JOIN usuario u ON a.idusuario = u.idusuario
     WHERE 1 = 1"; // Esto es para no tener que poner un "WHERE" vacío

    // Filtrar por turno, agenda y fecha
    $sql .= " AND a.turno = :turno";
    if ($idrol == 3) {
      $sql .= " AND a.idusuario = :idUsuario";
    }
    $sql .= " AND (a.agenda = 'AGENDADO' OR a.agenda = 'REPROGRAMADO')";
    $sql .= " AND (a.fechaagenda = :next OR a.fechareprogramacion = :next)";
    $sql .= " AND a.sede = :nombreSede";

    // Filtrar por query si se ha especificado
    if ($query !== null) {
      $sql .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
    }

    // Agregar el ORDER BY para ordenar por fecha de agenda
    $sql .= " ORDER BY a.fechaagenda"; // O ASC, según lo necesites

    // Agregar paginación
    $sql .= " LIMIT :limit OFFSET :offset";

    // Consulta de COUNT para obtener el total de registros
    $countQuery = "SELECT COUNT(*) as total 
            FROM agenda a 
            INNER JOIN usuario u ON a.idusuario = u.idusuario
            WHERE 1 = 1";

    // Filtrar por turno, agenda y fecha
    $countQuery .= " AND a.turno = :turno";
    if ($idrol == 3) {
      $countQuery .= " AND a.idusuario = :idUsuario";
    }
    $countQuery .= " AND (a.agenda = 'AGENDADO' OR a.agenda = 'REPROGRAMADO')";
    $countQuery .= " AND (a.fechaagenda = :next OR a.fechareprogramacion = :next)";
    $countQuery .= " AND a.sede = :nombreSede";

    // Filtrar por query si se ha especificado
    if ($query !== null) {
      $countQuery .= " AND (a.postulante LIKE :query OR a.numerodocumento LIKE :query OR a.celular LIKE :query)";
    }

    // Ejecutar la consulta para contar el total de registros
    $stmtCount = $this->PDO->prepare($countQuery);

    // Vincular los parámetros para la consulta de conteo
    if ($turno !== null) {
      $stmtCount->bindParam(':turno', $turno, PDO::PARAM_STR);
    }
    if ($idrol == 3) {
      if ($idUsuario !== null) {
        $stmtCount->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
      }
    }
    if ($next !== null) {
      $stmtCount->bindParam(':next', $next, PDO::PARAM_STR);
    }
    if ($nombreSede !== null) {
      $stmtCount->bindParam(':nombreSede', $nombreSede, PDO::PARAM_STR);
    }
    if ($query !== null) {
      $stmtCount->bindParam(':query', $query, PDO::PARAM_STR);
    }

    $stmtCount->execute();
    $totalRecords = $stmtCount->fetch(PDO::FETCH_OBJ)->total;

    // Ejecutar la consulta principal
    $stmt = $this->PDO->prepare($sql);

    // Vincular los parámetros para la consulta principal
    if ($turno !== null) {
      $stmt->bindParam(':turno', $turno, PDO::PARAM_STR);
    }
    if ($idrol == 3) {
      if ($idUsuario !== null) {
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
      }
    }
    if ($next !== null) {
      $stmt->bindParam(':next', $next, PDO::PARAM_STR);
    }
    if ($nombreSede !== null) {
      $stmt->bindParam(':nombreSede', $nombreSede, PDO::PARAM_STR);
    }
    if ($query !== null) {
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $data = $stmt->fetchAll(PDO::FETCH_OBJ);
      return ['agenda' => $data, 'total' => $totalRecords];
    }
    return false;
  }
}
