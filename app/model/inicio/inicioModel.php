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
}
