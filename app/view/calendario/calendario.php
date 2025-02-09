<div class="card calendario" style="max-width: 90%; margin: 0 auto; margin-top: 180px;">
    <div class="calendar-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="calendar">
            <div class="calendar-header">
                <button class="button" id="prev-month">←</button>
                <span id="calendar-year-month"></span>
                <button class="button" id="next-month">→</button>
            </div>
            <div class="calendar-days" id="calendar-days"></div>
        </div>
    </div>
</div>

<script>
    // Declaración de variables globales
    /* const  */fechaActual = new Date();
    /* let  */diaSeleccionado = null;
    /* const  */dias = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
    /* const  */meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    // Función auxiliar para obtener la fecha de hoy (con hora normalizada a 00:00:00)
    function obtenerDiaHoy() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return today;
    }

    // Función para obtener las fechas guardadas del calendario
    async function obtenerFechasGuardadas() {
        try {
            const response = await $.ajax({
                url: "../calendario/fechasCalendario.json",
                type: "GET",
                dataType: "json"
            });
            return response;
        } catch (error) {
            console.error("Error al obtener las fechas guardadas:", error);
            return [];
        }
    }

    // Función para limpiar los domingos pasados del mes actual
    async function limpiarDomingosPasadosEsteMes(savedDates) {
        const currentYear = fechaActual.getFullYear();
        const currentMonth = fechaActual.getMonth(); // 0 a 11

        const validSundays = savedDates.filter(
            (date) => date.year === currentYear && date.month === currentMonth + 1
        );

        if (validSundays.length > 0) {
            try {
                await $.ajax({
                    url: "../calendario/storeCalendary.php",
                    type: "POST",
                    data: JSON.stringify({
                        sundays: validSundays
                    }),
                    contentType: "application/json"
                });
                //console.log("Fechas actualizadas correctamente");
            } catch (error) {
                console.error("Hubo un error al actualizar las fechas:", error);
            }
        }

        return validSundays;
    }

    // Función para renderizar el calendario
    function renderizarCalendario() {
        const yearMonth = $("#calendar-year-month");
        const calendarDays = $("#calendar-days");

        const year = fechaActual.getFullYear();
        const month = fechaActual.getMonth();

        fechaActual.setFullYear(year, month, 1);

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();

        yearMonth.text(`${meses[month]} ${year}`);

        calendarDays.empty();

        dias.forEach((day) => calendarDays.append(`<div>${day}</div>`));

        const firstDayWeekday = firstDayOfMonth.getDay();
        for (let i = 0; i < firstDayWeekday; i++) {
            calendarDays.append("<div></div>");
        }

        obtenerFechasGuardadas().then(async (savedDates) => {
            const validSundays = await limpiarDomingosPasadosEsteMes(savedDates);

            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(year, month, i);
                const dayElement = $(`<div class="calendar-day">${i}</div>`);

                if (date.toDateString() === obtenerDiaHoy().toDateString()) {
                    dayElement.addClass("today");
                }

                const dateExists = validSundays.some(
                    (savedDate) =>
                    savedDate.year === year &&
                    savedDate.month === month + 1 &&
                    savedDate.day === i
                );
                if (dateExists) {
                    dayElement.addClass("red");
                }

                if (date < obtenerDiaHoy()) {
                    dayElement.addClass("disabled");
                } else if (date.getDay() === 0) {
                    dayElement.addClass("red disabled");
                } else {
                    dayElement.on("click", function() {
                        diaSeleccionado = dayElement;
                        const isSelected = dayElement.hasClass("red");
                        const diaSeleccionadoInfo = {
                            year: date.getFullYear(),
                            month: date.getMonth() + 1,
                            day: date.getDate(),
                        };

                        if (isSelected) {
                            dayElement.removeClass("red");
                        } else {
                            dayElement.addClass("red");
                        }

                        $.ajax({
                            url: "../calendario/storeCalendary.php",
                            type: "POST",
                            data: JSON.stringify(diaSeleccionadoInfo),
                            contentType: "application/json",
                            success: function(response) {
                                //console.log("Fecha actualizada:", response);
                            },
                            error: function(xhr, status, error) {
                                console.error("Hubo un error al guardar la fecha:", error);
                            },
                        });
                    });
                }

                calendarDays.append(dayElement);
            }
        }).catch(error => console.error("Error al obtener las fechas guardadas:", error));
    }

    // Función para cambiar de mes
    function cambiarMes(increment) {
        let newMonth = fechaActual.getMonth() + increment;
        let newYear = fechaActual.getFullYear();

        if (newMonth < 0) {
            newMonth = 11;
            newYear--;
        } else if (newMonth > 11) {
            newMonth = 0;
            newYear++;
        }

        fechaActual.setFullYear(newYear);
        fechaActual.setMonth(newMonth);
        fechaActual.setDate(1);
        renderizarCalendario();
    }

    // Función para obtener los domingos de un mes
    function obtenerDomingos(year, month) {
        const sundays = [];
        const lastDayOfMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 1; i <= lastDayOfMonth; i++) {
            const date = new Date(year, month, i);
            if (date.getDay() === 0) {
                sundays.push({
                    year: date.getFullYear(),
                    month: date.getMonth() + 1,
                    day: date.getDate(),
                });
            }
        }
        return sundays;
    }

    // Función para guardar los domingos de este mes
    async function domingosEsteMes() {
        const year = fechaActual.getFullYear();
        const month = fechaActual.getMonth();
        let sundays = obtenerDomingos(year, month);

        sundays = sundays.filter((sunday) => {
            const sundayDate = new Date(sunday.year, sunday.month - 1, sunday.day);
            return sundayDate >= obtenerDiaHoy();
        });

        try {
            await $.ajax({
                url: "../calendario/storeCalendary.php",
                type: "POST",
                data: JSON.stringify({
                    sundays
                }),
                contentType: "application/json"
            });
            //console.log("Domingos guardados:", sundays);
        } catch (error) {
            console.error("Hubo un error al guardar los domingos:", error);
        }
    }

    // Función para guardar los domingos del siguiente mes
    async function domingosSiguenteMes() {
        let nextMonth = fechaActual.getMonth() + 1;
        let nextYear = fechaActual.getFullYear();
        if (nextMonth > 11) {
            nextMonth = 0;
            nextYear++;
        }

        const sundaysNextMonth = obtenerDomingos(nextYear, nextMonth);

        try {
            await $.ajax({
                url: "../calendario/storeCalendary.php",
                type: "POST",
                data: JSON.stringify({
                    sundaysNext: sundaysNextMonth
                }),
                contentType: "application/json"
            });
            //console.log("Domingos del mes siguiente guardados:", sundaysNextMonth);
        } catch (error) {
            console.error("Hubo un error al guardar los domingos del mes siguiente:", error);
        }
    }

    // Función para remover días pasados de hoy
    async function removerDiasPasadosDeHoy() {
        try {
            const response = await $.ajax({
                url: "../calendario/fechasCalendario.json",
                type: "GET",
                dataType: "json"
            });

            const filteredDates = response.filter((date) => {
                const dateObj = new Date(date.year, date.month - 1, date.day);
                return dateObj >= obtenerDiaHoy();
            });

            if (filteredDates.length !== response.length) {
                await $.ajax({
                    url: "../calendario/storeCalendary.php",
                    type: "POST",
                    data: JSON.stringify({
                        cleanDates: true,
                        dates: filteredDates,
                    }),
                    contentType: "application/json"
                });
                //console.log("Fechas antiguas eliminadas:", filteredDates);
                renderizarCalendario();
            }
        } catch (error) {
            console.error("Error al obtener las fechas guardadas:", error);
        }
    }

    // Función para validar el calendario
    async function validarCalendario() {
        try {
            const response = await $.ajax({
                url: "../calendario/fechasCalendario.json",
                type: "GET",
                dataType: "json"
            });
            return Array.isArray(response);
        } catch (error) {
            console.error("Error al obtener las fechas:", error);
            return false;
        }
    }

    // Función principal para iniciar el calendario
    async function iniciarCalendario() {
        const validacionExitosa = await validarCalendario();

        if (validacionExitosa) {
            await removerDiasPasadosDeHoy();
            renderizarCalendario();
            await domingosEsteMes();
            await domingosSiguenteMes();

            $("#prev-month").on("click", function() {
                cambiarMes(-1);
            });
            $("#next-month").on("click", function() {
                cambiarMes(1);
            });
        } else {
            console.log("No se pueden ejecutar las acciones. Las validaciones no han sido completadas.");
        }
    }

    // Iniciar el calendario al cargar la página
    $(document).ready(iniciarCalendario);
</script>