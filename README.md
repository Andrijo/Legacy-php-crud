# Práctica Inicial

Aplicación web PHP para gestión de clientes con operaciones CRUD básicas, búsqueda, paginación y exportación XML.

## Tecnologías

- **PHP 7.x+** con PDO
- **MySQL / MariaDB**
- **Bootstrap 5.3.3**
- **JavaScript** vanilla

## Requisitos

- Servidor web (Apache / Nginx)
- PHP 7.3+
- MySQL 5.7+ / MariaDB 10.1+

## Instalación

1. Clonar el repositorio:

   ```bash
   git clone <repo-url>
   ```

2. Importar la base de datos desde `archivo/clientes.xml` o ejecutar:

   ```sql
   CREATE DATABASE proyecto;
   CREATE TABLE clientes (
     id int(10) NOT NULL AUTO_INCREMENT,
     clave varchar(7) NOT NULL,
     nombre tinytext NOT NULL,
     apellido tinytext NOT NULL,
     correo varchar(50) NOT NULL,
     telefono varchar(10) NOT NULL,
     sexo char(1) NOT NULL,
     UNIQUE KEY ID (id)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
   ```

3. Configurar credenciales en `conexion.php`.

4. Iniciar el servidor:
   ```bash
   php -S localhost:8000
   ```

## Estructura del proyecto

```
practica/
├── archivo/
│   └── clientes.xml        # Exportación de BD
├── css/
│   └── style.css           # Estilos personalizados
├── js/
│   └── script.js           # Mostrar/ocultar tabla
├── conexion.php            # Conexión PDO a MySQL
├── index.php               # Página principal (listado + formulario)
├── search.php              # Búsqueda de clientes
├── .gitignore
└── README.md
```

## Funcionalidades

- Listado paginado de clientes en tabla Bootstrap
- Búsqueda por nombre, apellido, correo o clave
- Añadir nuevos clientes vía formulario modal
- Mostrar/ocultar tabla
- Descarga de XML con datos de clientes

## Mejoras aplicadas (Julio 2026)

| Problema                              | Solución                                         |
| ------------------------------------- | ------------------------------------------------ |
| `search.php` no existía               | Se creó con prepared statements y XSS protection |
| Doble conexión a BD (PDO + MySQLi)    | Unificado a PDO únicamente                       |
| Paginación rota y warnings de `$_GET` | Validación completa con `max()`/`min()`          |
| Formulario sin backend                | POST handler con validación server-side          |
| `<tbody>` dentro del bucle            | Movido fuera del `foreach`                       |
| Select sexo sin valores               | `value="H"` / `value="M"` correctos              |
| `lang="en"`                           | Cambiado a `lang="es"`                           |
| jQuery sin uso                        | Eliminado                                        |
| Bootstrap 5.1.3 → 5.3.3               | Actualizado (Popper incluido en bundle)          |
| Credenciales hardcodeadas             | Movidas a `.env`                                 |
| Sin protección contra XSS             | `htmlspecialchars()` en todas las salidas        |
| Sin `.gitignore`                      | Creado                                           |
| CSS `display: flexbox` (inválido)     | Corregido a `display: flex`                      |
