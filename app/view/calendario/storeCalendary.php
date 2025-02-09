<?php
// Función para formatear el array de datos en el formato deseado
function formatData($data) {
    $formattedData = '[' . PHP_EOL;
    foreach ($data as $index => $date) {
        $formattedData .= '    { "year": ' . $date['year'] . ', "month": ' . $date['month'] . ', "day": ' . $date['day'] . ' }';
        if ($index < count($data) - 1) {
            $formattedData .= ',' . PHP_EOL;
        }
    }
    $formattedData .= PHP_EOL . ']';
    return $formattedData;
}

// Leer los datos JSON enviados en la solicitud
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

// Ruta al archivo JSON donde se guardan las fechas
$filePath = 'fechasCalendario.json';

// Leer el contenido actual del archivo (si existe)
$existingData = [];
if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
    if (!is_array($existingData)) {
        $existingData = [];
    }
}

if ($requestData) {
    // Caso: limpiar fechas anteriores a hoy
    if (isset($requestData['cleanDates']) && $requestData['cleanDates'] === true && isset($requestData['dates'])) {
        $newData = $requestData['dates'];
        $formattedData = formatData($newData);
        if (file_put_contents($filePath, $formattedData)) {
            echo json_encode(["success" => true, "message" => "Fechas anteriores a hoy eliminadas correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudieron actualizar las fechas"]);
        }
        exit;
    }
    
    // Caso 1: Se envían todos los domingos del mes actual
    if (isset($requestData['sundays'])) {
        $sundays = $requestData['sundays'];
        $currentYear = date('Y');
        $currentMonth = date('n');
        foreach ($sundays as $sunday) {
            // Solo procesamos los domingos que pertenezcan al mes actual
            if ($sunday['year'] == $currentYear && $sunday['month'] == $currentMonth) {
                $exists = false;
                foreach ($existingData as $date) {
                    if ($date['year'] == $sunday['year'] && $date['month'] == $sunday['month'] && $date['day'] == $sunday['day']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $existingData[] = $sunday;
                }
            }
        }
        $formattedData = formatData($existingData);
        if (file_put_contents($filePath, $formattedData)) {
            echo json_encode(["success" => true, "message" => "Domingos del mes actual procesados correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo guardar los domingos del mes actual"]);
        }
    }
    // Caso 2: Se envía una fecha individual (clic en un día)
    else if (isset($requestData['year'], $requestData['month'], $requestData['day'])) {
        $year = $requestData['year'];
        $month = $requestData['month'];
        $day = $requestData['day'];
        $found = false;
        foreach ($existingData as $key => $date) {
            if ($date['year'] == $year && $date['month'] == $month && $date['day'] == $day) {
                $found = true;
                unset($existingData[$key]);
                break;
            }
        }
        if (!$found) {
            $existingData[] = ["year" => $year, "month" => $month, "day" => $day];
        }
        $existingData = array_values($existingData);
        $formattedData = formatData($existingData);
        if (file_put_contents($filePath, $formattedData)) {
            if ($found) {
                echo json_encode(["success" => true, "message" => "Fecha eliminada correctamente"]);
            } else {
                echo json_encode(["success" => true, "message" => "Fecha guardada correctamente"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo actualizar la fecha"]);
        }
    }
    // Caso 3: Se envían los domingos del mes siguiente
    else if (isset($requestData['sundaysNext'])) {
        $sundaysNext = $requestData['sundaysNext'];
        foreach ($sundaysNext as $sunday) {
            // Aquí se agregan directamente, sin filtrar por mes actual
            $exists = false;
            foreach ($existingData as $date) {
                if ($date['year'] == $sunday['year'] && $date['month'] == $sunday['month'] && $date['day'] == $sunday['day']) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $existingData[] = $sunday;
            }
        }
        $formattedData = formatData($existingData);
        if (file_put_contents($filePath, $formattedData)) {
            echo json_encode(["success" => true, "message" => "Domingos del mes siguiente procesados correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo guardar los domingos del mes siguiente"]);
        }
    }
    // Si los datos no tienen el formato esperado
    else {
        echo json_encode(["success" => false, "message" => "Datos inválidos"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No se recibió datos"]);
}
?>