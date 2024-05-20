# AcademicManager

AcademicManager es una API RESTful desarrollada en Laravel que permite importar y exportar datos relacionados con estudiantes y sus calificaciones desde y hacia archivos CSV.

## Instalación

1. Clona este repositorio en tu máquina local:

```bash
git clone https://github.com/Jorge-Otalvaro/AcademicManager
```

2. Accede al directorio del proyecto:

```bash
cd AcademicManager
```

3. Instala las dependencias del proyecto con Composer:

```bash
composer install
```

4. Copia el archivo de configuración `.env.example` a `.env`:

```bash
cp .env.example .env
```

5. Genera una nueva clave de aplicación:

```bash
php artisan key:generate
```

6. Configura tu base de datos en el archivo `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_de_datos
DB_USERNAME=usuario_base_de_datos
DB_PASSWORD=contraseña_base_de_datos
```

7. Ejecuta las migraciones para crear las tablas en la base de datos:

```bash
php artisan migrate
```

8. El proyecto está listo para ser utilizado.

## Uso

### Importación de Estudiantes desde CSV

1. Envía una solicitud POST a `/students/import-csv` con un archivo CSV que contenga los datos de los estudiantes.
2. El formato del archivo CSV debe seguir la siguiente estructura:

```
Identification,Name,Age,Grade,Subject,GradeValue
12345601,Student 1,18,A,Math,4.5
12345601,Student 1,18,A,Science,4.0
12345601,Student 1,18,A,English,3.5
```

3. Los estudiantes serán importados a la base de datos y se evitarán duplicados.

## Apis

Una vez que el servidor esté en funcionamiento, puedes usar los siguientes endpoints de la API:

-   `POST /students/import-csv`: Importa estudiantes desde un archivo CSV. Envía un archivo CSV con los datos de los estudiantes.
-   `GET /students/export-csv`: Exporta estudiantes a un archivo CSV. Recibirás un archivo CSV con los datos de los estudiantes.

Asegúrate de enviar las solicitudes con los encabezados adecuados y los datos necesarios para cada endpoint.

## Ejemplo

Aquí tienes un ejemplo de cómo importar estudiantes desde un archivo CSV utilizando cURL:

```bash
curl -X POST -F "file=@students.csv" http://localhost:8000/students/import-csv
```

Este comando enviará un archivo CSV llamado `students.csv` al endpoint de importación de la API.

### Exportación de Estudiantes a CSV

1. Envía una solicitud GET a `/students/export-csv` para exportar todos los estudiantes a un archivo CSV.
2. Se descargará un archivo CSV con los datos de los estudiantes en el formato mencionado anteriormente.

## Pruebas Unitarias

Se incluyen pruebas unitarias para garantizar el correcto funcionamiento de la API. Para ejecutar las pruebas, utiliza el siguiente comando:

```bash
php artisan test
```

---

Puedes personalizar este README según las necesidades específicas de tu proyecto, incluyendo más detalles sobre la API y su funcionamiento si lo consideras necesario.