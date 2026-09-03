# API del Colector — Sistema de Cursos

API REST en PHP que conecta la APK Android con la base de datos existente
(MySQL para cursos/inscripciones, MongoDB para consultas).

## Antes de usarla

1. Ejecutar la migración SQL una sola vez:
   ```
   mysql -u root sistema_cursos < sql/alter_inscripciones_api.sql
   ```
2. Cambiar la API key por defecto en `api/config_api.php` (constante `API_KEY`).
3. Publicar el proyecto en un servidor accesible desde el celular
   (mismo Wi-Fi, o un hosting/VPS con PHP + MySQL + MongoDB).

Todos los endpoints requieren el header:
```
X-API-KEY: academia-innova-colector-2026
```

## Endpoints

### GET /api/cursos.php
Devuelve el catálogo completo de cursos. La APK lo descarga y guarda
localmente para poder mostrarlo sin conexión.

### GET /api/alumnos.php
Devuelve los alumnos ya registrados (derivados de inscripciones), para
que la APK los tenga como referencia offline.

### POST /api/consultas.php
Recibe una consulta generada en la APK (offline) y la guarda en MongoDB.
Requiere `uuid` generado en el dispositivo para evitar duplicados si se
reintenta el envío.

```json
{
  "uuid": "c290b7f0-...",
  "curso_id": 3,
  "curso_titulo": "Redes y Cableado Estructurado",
  "nombre": "Juan Pérez",
  "email": "juan@correo.com",
  "mensaje": "Quisiera saber horarios",
  "dispositivo_id": "android-abc123"
}
```

### POST /api/inscripciones.php
Recibe una inscripción generada en la APK (offline) y la guarda en MySQL.
Requiere `uuid` por el mismo motivo.

```json
{
  "uuid": "9d3c1a20-...",
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan@correo.com",
  "edad": 22,
  "curso_id": 3,
  "dispositivo_id": "android-abc123"
}
```

## Flujo de la APK

1. Al abrir (con conexión): descarga cursos y alumnos → los guarda en SQLite local (Room).
2. En el campo (con o sin conexión): el operador carga consultas/inscripciones → se guardan en SQLite local marcadas como "pendiente".
3. Cuando hay conexión: WorkManager sube en background lo pendiente a `/api/consultas.php` y `/api/inscripciones.php`, y marca como "sincronizado" lo que el servidor confirma.
