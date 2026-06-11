# /// script
# requires-python = ">=3.10"
# dependencies = ["pandas", "plotext", "rich"]
# ///

"""
scripts/analytics.py
Dashboard de análisis en terminal para el Gestor de Postulaciones.

Procesa los datos de la base de datos SQLite y genera:
  - Métricas generales (totales por estado)
  - Distribución por plataforma (gráfico de barras)
  - Línea de tiempo de postulaciones por mes
  - Top empresas más postuladas (ranking)
  - Antigüedad de postulaciones activas (histograma)

Ejecución:
    uv run scripts/analytics.py
"""

import sqlite3
from datetime import date, datetime
from pathlib import Path

import pandas as pd
import plotext as plt
from rich.box import MINIMAL
from rich.console import Console
from rich.panel import Panel
from rich.table import Table

RUTA_BD = Path(__file__).resolve().parent.parent / "database" / "gestor_postulaciones.sqlite"
DIAS_VENCIMIENTO = 15


def conectar_bd() -> pd.DataFrame:
    """Lee la base de datos SQLite y devuelve un DataFrame con las postulaciones."""
    if not RUTA_BD.exists():
        print(f"✖ No se encontró la base de datos: {RUTA_BD}")
        print("  Ejecuta primero: php database/init.php")
        exit(1)

    conn = sqlite3.connect(str(RUTA_BD))
    df = pd.read_sql_query("SELECT * FROM postulaciones ORDER BY fecha_postulacion", conn)
    conn.close()
    return df


def calcular_estado_real(df: pd.DataFrame) -> pd.DataFrame:
    """
    Replica la regla de negocio de Vencido (docs/02_reglas_negocio.md).

    Si han pasado más de 15 días desde fecha_ultima_actualizacion
    y el estado almacenado es "Postulado", el estado real es "Vencido".
    """
    df = df.copy()
    hoy = date.today()

    # Convertir a datetime.date para poder comparar
    df["fecha_ultima_actualizacion"] = pd.to_datetime(df["fecha_ultima_actualizacion"]).dt.date
    df["fecha_postulacion"] = pd.to_datetime(df["fecha_postulacion"]).dt.date

    def _estado(row):
        if row["estado"] == "Rechazado":
            return "Rechazado"
        if row["estado"] == "Postulado":
            dias = (hoy - row["fecha_ultima_actualizacion"]).days
            if dias > DIAS_VENCIMIENTO:
                return "Vencido"
        return "Postulado"

    df["estado_real"] = df.apply(_estado, axis=1)
    return df


# ─── Secciones ──────────────────────────────────────────────────────────────


def seccion_metricas(console: Console, df: pd.DataFrame):
    """Tabla resumen con totales por estado."""
    total = len(df)
    conteo = df["estado_real"].value_counts()

    tabla = Table(title="Métricas generales", box=MINIMAL)
    tabla.add_column("Métrica", style="cyan")
    tabla.add_column("Cantidad", justify="right", style="bold")

    tabla.add_row("Total", str(total))
    tabla.add_row("Postulaciones activas", str(conteo.get("Postulado", 0)), style="green")
    tabla.add_row("Postulaciones vencidas", str(conteo.get("Vencido", 0)), style="yellow")
    tabla.add_row("Postulaciones rechazadas", str(conteo.get("Rechazado", 0)), style="red")

    console.print(tabla)
    console.print()


def seccion_plataformas(df: pd.DataFrame):
    """Gráfico de barras: distribución por plataforma."""
    conteo = df["plataforma"].value_counts().sort_values(ascending=True)

    plt.clf()
    plt.bar(list(conteo.index), list(conteo.values), orientation="horizontal")
    plt.title("Postulaciones por plataforma")
    plt.xlabel("Cantidad")
    plt.ylabel("Plataforma")
    plt.theme("dark")
    plt.show()
    print()


def seccion_timeline(df: pd.DataFrame):
    """Gráfico de barras: timeline de postulaciones por mes."""
    conteo_mensual = (
        df.groupby(df["fecha_postulacion"].apply(lambda f: f.strftime("%Y-%m")))
        .size()
        .reset_index(name="cantidad")
    )
    conteo_mensual.columns = ["mes", "cantidad"]

    plt.clf()
    plt.bar(list(conteo_mensual["mes"]), list(conteo_mensual["cantidad"]))
    plt.title("Postulaciones por mes")
    plt.xlabel("Mes")
    plt.ylabel("Cantidad")
    plt.theme("dark")
    plt.show()
    print()


def seccion_top_empresas(df: pd.DataFrame, top_n: int = 5):
    """Ranking de empresas más postuladas."""
    conteo = df["empresa"].value_counts().head(top_n).sort_values(ascending=True)

    plt.clf()
    plt.bar(list(conteo.index), list(conteo.values), orientation="horizontal")
    plt.title(f"Top {top_n} empresas más postuladas")
    plt.xlabel("Cantidad")
    plt.ylabel("Empresa")
    plt.theme("dark")
    plt.show()
    print()


def seccion_antiguedad(df: pd.DataFrame):
    """Histograma: días desde la postulación para las activas."""
    activas = df[df["estado_real"] == "Postulado"].copy()

    if activas.empty:
        Console().print("[yellow]No hay postulaciones activas para analizar antigüedad.[/yellow]")
        print()
        return

    hoy = date.today()
    activas["dias"] = activas["fecha_postulacion"].apply(lambda f: (hoy - f).days)

    plt.clf()
    plt.hist(list(activas["dias"]), bins=5)
    plt.title(f"Días de antigüedad (postulaciones activas, n={len(activas)})")
    plt.xlabel("Días desde la postulación")
    plt.ylabel("Cantidad")
    plt.theme("dark")
    plt.show()
    print()


# ─── Main ────────────────────────────────────────────────────────────────────


def main():
    console = Console()

    console.print(Panel.fit(
        "[bold cyan]Gestor de Postulaciones — Dashboard de análisis[/bold cyan]",
        border_style="cyan",
    ))
    print()

    df = conectar_bd()

    if df.empty:
        console.print("[yellow]La base de datos está vacía. Registra postulaciones primero.[/yellow]")
        return

    df = calcular_estado_real(df)

    seccion_metricas(console, df)
    seccion_plataformas(df)
    seccion_timeline(df)
    seccion_top_empresas(df)
    seccion_antiguedad(df)

    console.print("[green]✔ Análisis completado.[/green]")


if __name__ == "__main__":
    main()
