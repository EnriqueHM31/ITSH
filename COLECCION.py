import pandas as pd
from openpyxl import Workbook

# Datos de entrada en formato de lista de tuplas (convertido a mayúsculas y sin columna "TABLA")
data = [
    ('ID_CARRERA', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('NOMBRE_CARRERA', 'VARCHAR', '25', 'NO', '', None, ''),
    ('ID_TIPO_CARRERA', 'INT', None, 'NO', 'MUL', None, ''),
    ('ID_CARRERA', 'INT', None, 'NO', 'PRI', None, ''),
    ('ID_MODALIDAD', 'INT', None, 'NO', 'PRI', None, ''),
    ('ID_CODIGO', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('DATOS_CODIGO', 'TEXT', '65535', 'NO', '', None, ''),
    ('URL', 'TEXT', '65535', 'NO', '', None, ''),
    ('ID_ESTADO', 'INT', None, 'NO', 'MUL', None, ''),
    ('ID_ESTADO', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('NOMBRE_ESTADO', 'ENUM', '9', 'NO', '', None, ''),
    ('ID_USUARIO', 'VARCHAR', '10', 'NO', 'PRI', None, ''),
    ('ID_CARRERA', 'INT', None, 'NO', 'MUL', None, ''),
    ('ID_MODALIDAD', 'INT', None, 'NO', 'MUL', None, ''),
    ('GRUPO', 'VARCHAR', '4', 'NO', '', None, ''),
    ('ID_GRUPO', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('ID_CARRERA', 'INT', None, 'NO', 'MUL', None, ''),
    ('NUMERO_GRUPOS', 'INT', None, 'NO', '', None, ''),
    ('CLAVE_GRUPO', 'INT', None, 'NO', '', None, ''),
    ('ID_USUARIO', 'VARCHAR', '10', 'NO', 'PRI', None, ''),
    ('ID_CARRERA', 'INT', None, 'NO', 'MUL', None, ''),
    ('ID_JUSTIFICANTE', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('ID_ESTUDIANTE', 'VARCHAR', '10', 'NO', 'MUL', None, ''),
    ('ID_JEFE', 'VARCHAR', '10', 'NO', 'MUL', None, ''),
    ('ID_CODIGO', 'INT', None, 'NO', 'MUL', None, ''),
    ('FECHA_CREACION', 'TIMESTAMP', None, 'NO', '', 'CURRENT_TIMESTAMP', 'DEFAULT_GENERATED'),
    ('NOMBRE_JUSTIFICANTE', 'TEXT', '65535', 'NO', '', None, ''),
    ('ID_MODALIDAD', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('NOMBRE_MODALIDAD', 'VARCHAR', '20', 'NO', '', None, ''),
    ('ID_ROL', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('ROL', 'ENUM', '15', 'NO', '', None, ''),
    ('ID_SOLICITUD', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('ID_ESTUDIANTE', 'VARCHAR', '10', 'NO', 'MUL', None, ''),
    ('ID_JEFE', 'VARCHAR', '10', 'NO', 'MUL', None, ''),
    ('MOTIVO', 'ENUM', '8', 'NO', '', None, ''),
    ('FECHA_AUSENCIA', 'DATE', None, 'NO', '', None, ''),
    ('ID_ESTADO', 'INT', None, 'NO', 'MUL', None, ''),
    ('EVIDENCIA', 'TEXT', '65535', 'NO', '', None, ''),
    ('ID_TIPO_CARRERA', 'INT', None, 'NO', 'PRI', None, 'AUTO_INCREMENT'),
    ('NOMBRE_TIPO_CARRERA', 'VARCHAR', '20', 'NO', '', None, ''),
    ('ID_USUARIO', 'VARCHAR', '10', 'NO', 'PRI', None, ''),
    ('NOMBRE', 'VARCHAR', '30', 'NO', '', None, ''),
    ('APELLIDOS', 'VARCHAR', '30', 'NO', '', None, ''),
    ('CORREO', 'VARCHAR', '40', 'NO', 'UNI', None, ''),
    ('ID_ROL', 'INT', None, 'NO', 'MUL', None, '')
]

# Crear el DataFrame
columns = ['COLUMNA', 'TIPO_DE_DATO', 'TAMANO', 'PERMITE_NULL', 'CLAVE', 'VALOR_POR_DEFECTO', 'EXTRA']
df = pd.DataFrame(data, columns=columns)

# Crear un archivo Excel
wb = Workbook()
ws = wb.active
ws.title = "Tablas"

# Asignar nombre de tabla (en mayúsculas) a partir del patrón de las columnas
# Agrupamos por la primera palabra de cada fila (la parte de la tabla)
for tabla, group in df.groupby(df['COLUMNA'].str.split('_').str[0].str.upper()):
    # Título de la tabla (nombre de la tabla en mayúsculas)
    ws.append([f"TÍTULO: {tabla}"])

    # Agregar encabezados
    ws.append(group.columns.tolist())

    # Agregar filas de datos correspondientes
    for index, row in group.iterrows():
        ws.append(row.tolist())
    
    # Agregar una línea en blanco entre las tablas
    ws.append([])

# Guardar el archivo Excel
excel_path = "diccionario_datos_mysql_mayusculas_sin_tabla.xlsx"
wb.save(excel_path)

print(f"El archivo Excel ha sido creado en: {excel_path}")
