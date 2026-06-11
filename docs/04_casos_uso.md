# CU-001 Registrar Postulación

## Objetivo
Registrar una nueva postulación laboral.

## Actor
Usuario.

## Entradas
- Empresa
- Puesto
- Plataforma
- URL de oferta (opcional)
- Fecha de postulación
- Estado
- Notas (opcional)

## Resultado esperado
La postulación queda guardada en el sistema.


# CU-002 Visualizar Dashboard
## Objetivo

Visualizar un resumen general de las postulaciones registradas.

## Actor
Usuario.

## Entradas
Ninguna.

## Flujo principal
- El usuario accede al dashboard.
- El sistema calcula las métricas generales.
- El sistema muestra las métricas y la lista de postulaciones.

## Resultado esperado
El usuario obtiene una visión general de su proceso de búsqueda laboral.


# CU-003 Buscar Postulaciones

## Objetivo
Encontrar postulaciones mediante una búsqueda textual.

## Actor
Usuario.

## Entradas
Texto de búsqueda.

## Flujo principal
- El usuario ingresa un texto de búsqueda.
- El sistema busca coincidencias en las postulaciones registradas.
- El sistema muestra los resultados encontrados.

## Resultado esperado
El usuario encuentra rápidamente postulaciones relacionadas con el texto ingresado.


# CU-004 Filtrar Postulaciones

## Objetivo
Visualizar únicamente las postulaciones que cumplen determinados criterios.

## Actor
Usuario.

## Entradas
- Empresa (opcional)
- Estado (opcional)
- Plataforma (opcional)
- Fecha de postulación (opcional)

## Flujo principal
- El usuario selecciona uno o más filtros.
- El sistema aplica los filtros seleccionados.
- El sistema muestra las postulaciones que cumplen los criterios.

## Resultado esperado
El usuario visualiza un subconjunto específico de postulaciones.


# CU-005 Actualizar Postulación

## Objetivo
Modificar la información de una postulación existente.

## Actor
Usuario.

## Entradas
Datos modificados de la postulación.

## Flujo principal
- El usuario selecciona una postulación.
- El sistema muestra la información actual.
- El usuario modifica los datos deseados.
- El sistema actualiza la información.
- El sistema actualiza la fecha de última actualización.

## Resultado esperado
La postulación queda actualizada con la información más reciente.


# CU-006 Eliminar Postulación

## Objetivo
Eliminar una postulación registrada.

## Actor
Usuario.

## Entradas
- Identificador de la postulación.

## Flujo principal
- El usuario selecciona una postulación.
- El usuario solicita eliminarla.
- El sistema elimina la postulación.
- El sistema actualiza la lista de postulaciones.

## Resultado esperado
La postulación deja de existir en el sistema.


# CU-007 Visualizar Postulaciones Vencidas

## Objetivo
Identificar las postulaciones consideradas vencidas.

## Actor
Usuario.

## Entradas
Ninguna.

## Flujo principal
- El usuario solicita visualizar las postulaciones vencidas.
- El sistema evalúa las reglas de vencimiento.
- El sistema identifica las postulaciones vencidas.
- El sistema muestra los resultados.

## Resultado esperado
El usuario identifica las postulaciones que no han tenido actividad reciente.