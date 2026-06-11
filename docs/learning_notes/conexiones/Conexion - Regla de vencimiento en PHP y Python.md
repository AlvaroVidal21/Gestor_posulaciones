---
tipo: conexion
proyecto: Gestor de Postulaciones
tags:
  - aprendizaje
  - conexion
  - regla-negocio
  - php
  - python
estado: vigente
relacionado:
  - "[[Regla - Vencimiento automatico a los 15 dias]]"
  - "[[Concepto - Estado calculado vs almacenado]]"
  - "[[Decision - Vencido se calcula no se almacena]]"
archivos:
  - app/postulacion.php
  - scripts/analytics.py
---

# Conexion - Regla de vencimiento en PHP y Python

## Idea central

La misma regla de negocio (vencimiento a los 15 dias) se implementa en dos lenguajes distintos: PHP para la aplicacion web y Python para el dashboard de terminal. Ambas producen el mismo resultado porque parten del mismo calculo.

## Regla o flujo de negocio

Si una postulacion esta en estado "Postulado" y pasaron mas de 15 dias desde `fecha_ultima_actualizacion`, su estado real es "Vencido". Si esta "Rechazado", se respeta.

## Representacion en el codigo

**PHP** (`app/postulacion.php`):

```php
function calcular_estado(array $postulacion): string {
    if ($postulacion['estado'] === 'Rechazado') {
        return 'Rechazado';
    }
    if ($postulacion['estado'] === 'Postulado') {
        $diferencia = (new DateTime())->diff(
            new DateTime($postulacion['fecha_ultima_actualizacion'])
        )->days;
        if ($diferencia > 15) {
            return 'Vencido';
        }
    }
    return 'Postulado';
}
```

**Python** (`scripts/analytics.py`):

```python
def _estado(row):
    if row["estado"] == "Rechazado":
        return "Rechazado"
    if row["estado"] == "Postulado":
        dias = (hoy - row["fecha_ultima_actualizacion"]).days
        if dias > DIAS_VENCIMIENTO:
            return "Vencido"
    return "Postulado"
```

## Por que importa

Tener la regla en dos lenguajes muestra que la logica de negocio es independiente de la tecnologia. Si en el futuro quisieras agregar una API en Node.js o un bot en Go, sabrias exactamente que calcular copiando la misma estructura logica.

Ademas, el script de Python consume la misma base de datos SQLite que la web. No hay duplicacion de datos, solo de logica.

## Que recordar

- La constante `DIAS_VENCIMIENTO = 15` existe en ambos codigos
- Ambos usan el mismo origen de datos: `gestor_postulaciones.sqlite`
- La logica es identica: primero verificar Rechazado, luego calcular diferencia de dias
- Si la regla cambia, hay que actualizar ambos archivos

## Relacionado

- [[Regla - Vencimiento automatico a los 15 dias]]
- [[Concepto - Estado calculado vs almacenado]]
- [[Decision - Vencido se calcula no se almacena]]
