# AGENTS.md

## Filosofía del Proyecto
* Comprensión antes que velocidad.
* Simplicidad antes que complejidad.
* El sistema debe mantenerse pequeño, entendible y fácil de explicar.
* Toda decisión debe priorizar la trazabilidad de las postulaciones laborales.
* El objetivo no es crear un CRM empresarial, sino un gestor personal de postulaciones.

## Fuentes de Verdad
Antes de implementar cualquier cambio, leer en este orden:

1. docs/00_contexto.md
2. docs/01_modelo_negocio.md
3. docs/02_reglas_negocio.md
4. docs/03_entidades.md
5. docs/04_casos_uso.md
6. docs/05_decisiones.md

## Stack Tecnológico
* PHP puro.
* SQLite.
* HTML.
* CSS.
* Bootstrap.
* JavaScript solo si es necesario para interacciones simples.

## Convenciones de Desarrollo
* Todo el código y la documentación deben estar en español.
* Usar nombres descriptivos.
* Priorizar código simple antes que abstracciones innecesarias.
* Mantener una estructura fácil de entender para un proyecto académico y de portafolio.
* No usar frameworks adicionales salvo autorización explícita.
  * Si quieres usar frameworks primero debes solicitarme a mí y justificarlo.

## Reglas Importantes del Dominio
* La entidad principal del sistema es Postulación.
* Empresa es un atributo de Postulación, no una entidad independiente.
* Una postulación en estado "Postulado" pasa automáticamente a estado "Vencido" cuando transcurren más de 15 días desde su última actualización.
* Una postulación en estado "Vencido" puede volver a estado "Postulado" cuando recibe una nueva actualización.
* Los estados válidos son:
  * Postulado
  * Rechazado
  * Vencido

## Restricciones
* No crear nuevas entidades sin justificación explícita.
* No agregar autenticación o gestión de usuarios.
* No agregar funcionalidades relacionadas con becas, cursos o certificaciones.
* No agregar complejidad orientada a múltiples usuarios.
* No convertir el sistema en un CRM empresarial.
* No sobreingenierizar el proyecto.
* No modificar reglas de negocio sin autorización explícita.

## Flujo de Trabajo
* Primero entender la tarea.
* Luego revisar la documentación relacionada.
* Después proponer o ejecutar cambios pequeños.
* No implementar funcionalidades grandes de golpe.
* Si una tarea es ambigua, pedir aclaración antes de modificar código.
* Si se implementa algo relevante, evaluar si corresponde crear una Learning Note.

## Carpetas de Trabajo
* docs/ contiene la documentación base del proyecto.
* database/ contiene archivos relacionados con SQLite.
* public/ contiene las páginas visibles del sistema.
* app/ contiene la lógica PHP reutilizable.

## Objetivo del Sistema
Permitir registrar, consultar, buscar, filtrar y dar seguimiento a postulaciones laborales para mantener trazabilidad del proceso de búsqueda de empleo.
