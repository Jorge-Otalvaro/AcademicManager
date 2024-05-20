````markdown
# AcademicManager

AcademicManager es una API RESTful desarrollada en Laravel que permite importar y exportar datos relacionados con estudiantes y sus calificaciones desde y hacia archivos CSV.

## Instalación

1. Clona este repositorio en tu máquina local:

```bash
git clone <https://github.com/Jorge-Otalvaro/AcademicManager>
```
````

2. Instala las dependencias de PHP utilizando Composer:

```bash
composer install
```

3. Copia el archivo de configuración `.env.example` a `.env` y configura las variables de entorno, incluyendo la conexión a la base de datos:

```bash
cp .env.example .env
```

4. Genera la clave de aplicación de Laravel:

```bash
php artisan key:generate
```

5. Ejecuta las migraciones para crear las tablas necesarias en la base de datos:

```bash
php artisan migrate
```

6. Inicia el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

## Uso

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

## Contribución

Si deseas contribuir a este proyecto, siéntete libre de enviar pull requests o abrir issues en el repositorio de GitHub.

## Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).

