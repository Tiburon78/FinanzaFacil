# FinanzaF\u00e1cil

FinanzaF\u00e1cil es una aplicaci\u00f3n web sencilla para administrar gastos, ingresos y ahorros. Est\u00e1 escrita en PHP y utiliza MySQL/MariaDB como base de datos.

## Requisitos

- PHP 8.x
- Servidor web (Apache o Nginx)
- MySQL o MariaDB

## Instalaci\u00f3n r\u00e1pida

1. Clon\u00e1 este repositorio y ubic\u00e1 el c\u00f3digo en el directorio p\u00fablico de tu servidor.
   ```bash
   git clone https://github.com/tuusuario/FinanzaFacil.git
   cd FinanzaFacil
   ```
2. Import\u00e1 el archivo `bd/finanzafacil.sql` en una base de datos vac\u00eda.
   ```bash
   mysql -u TU_USUARIO -p -e "CREATE DATABASE finanzafacil" 
   mysql -u TU_USUARIO -p finanzafacil < bd/finanzafacil.sql
   ```
3. Configur\u00e1 las credenciales de conexi\u00f3n editando `includes/db.php`:
   ```php
   <?php
   $conn = new mysqli('localhost', 'usuario', 'contrase\u00f1a', 'finanzafacil');
   if ($conn->connect_error) { die('Error de conexi\u00f3n: ' . $conn->connect_error); }
   ?>
   ```
4. Ejecut\u00e1 el proyecto con tu servidor web habitual o usando el servidor embebido de PHP:
   ```bash
   php -S localhost:8000
   ```
   Luego abr\u00ed `http://localhost:8000` en tu navegador.

## Uso b\u00e1sico

- Acced\u00e9 a `registro.php` para crear un usuario (se otorgan 7 d\u00edas de prueba).
- Ingres\u00e1 en `login.php` con tu cuenta.
- Utiliz\u00e1 el men\u00fa lateral para cargar movimientos, definir presupuestos y gestionar ahorros.
- Si tu periodo de prueba expira se mostrar\u00e1 `vencido.php` hasta suscribirte a un plan.

## Planes de suscripci\u00f3n

El dump `bd/finanzafacil.sql` define tres niveles en la tabla `usuarios.plan`:

- **gratuito**: registro inicial con 7 d\u00edas de prueba y funciones b\u00e1sicas.
- **standard**: habilita exportaci\u00f3n de reportes y presupuestos por grupo (pago mensual).
- **premium**: ofrece todas las caracter\u00edsticas, como asistente financiero y proyecciones de ahorro (pago anual).

El campo `prueba_activa` y la fecha `fecha_vencimiento` determinan si el usuario sigue en prueba o si necesita suscribirse.

---
Para m\u00e1s detalles consult\u00e1 el c\u00f3digo fuente y las capturas en `screens/`.
