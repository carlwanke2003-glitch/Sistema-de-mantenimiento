# Guía de Ejecución - PredictiveMaintain

## Requisitos Previos

- **XAMPP** instalado (Apache + MySQL + PHP)
- **Navegador web** (Chrome, Firefox, Edge, etc.)
- **phpMyAdmin** (incluido en XAMPP)

---

## Paso 1: Activar XAMPP

1. Abre el **Control Panel de XAMPP**
2. Haz clic en **Start** para:
   - **Apache**
   - **MySQL**

---

## Paso 2: Importar la Base de Datos

### Opción A: Usar phpMyAdmin (Recomendado)

1. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
2. Haz clic en **Importar** (esquina superior)
3. Selecciona el archivo: `proyecto/proyecto/código/database/mysql_stored/script.sql`
4. Haz clic en **Continuar**
5. Espera a que se complete la importación

### Opción B: Usar MySQL CLI

Abre PowerShell o CMD y ejecuta:

```bash
mysql -u root -p < "C:\ruta\a\script.sql"
```

Si no pide contraseña, solo presiona **Enter**.

---

## Paso 3: Acceder a la Aplicación

### URL Principal

Abre tu navegador y accede a:

```
http://localhost/proyecto/proyecto/código/frontend/vista/login.php
```

O también puedes usar:

```
http://localhost/proyecto/proyecto/código/frontend/vista/index.php
```

---

## Paso 4: Iniciar Sesión

### Cuenta de Prueba - Usuario Normal

- **Email:** `user@predictive.com`
- **Contraseña:** `123456`

### Cuenta de Prueba - Administrador

- **Email:** `admin@predictive.com`
- **Contraseña:** `admin123`

---

## Paso 5: Crear Nueva Cuenta (Opcional)

1. Desde la página de login, haz clic en **"Regístrate aquí"**
2. Completa el formulario con:
   - Nombre completo
   - Email
   - Contraseña
3. Haz clic en **"Crear cuenta"**
4. Automáticamente iniciarás sesión

---

## Estructura de la Aplicación

### Navegación Principal (Barra Superior)

- **Dashboard** - Panel principal con equipos e incidentes
- **Turno** - Gestión de turnos de mantenimiento
- **Historial de Revisiones** - Ver historial de turnos e incidentes
- **Pagar** - Registrar y ver pagos
- **Contacto** - Enviar mensajes (Admin ve todos)

---

## Funcionalidades por Rol

### Usuario Normal (user@predictive.com)

✅ Ver equipos disponibles  
✅ Ver incidentes visibles  
✅ Crear turnos de mantenimiento  
✅ Editar/eliminar sus propios turnos  
✅ Ver su historial de turnos  
✅ Registrar pagos  
✅ Enviar mensajes de contacto  

### Administrador (admin@predictive.com)

✅ Todas las funciones del usuario, más:  
✅ **Dashboard:** Agregar/editar/eliminar equipos e incidentes  
✅ **Dashboard:** Ocultar/mostrar equipos e incidentes  
✅ **Historial:** Ver y eliminar todos los incidentes  
✅ **Historial:** Ocultar/mostrar incidentes  
✅ **Pagar:** Ver todos los pagos registrados  
✅ **Contacto:** Ver todos los mensajes recibidos  
✅ **Contacto:** Marcar mensajes como leídos  

---

## Ejemplo de Uso - Usuario Normal

### 1. Registrar un Turno

1. Inicia sesión
2. Ve a **"Turno"**
3. Completa:
   - Descripción (ej: "Mantenimiento de Máquina A")
   - Fecha y hora (ej: 2026-05-20 10:00)
4. Haz clic en **"Solicitar turno"**
5. Verás tu turno en la tabla abajo

### 2. Editar tu Turno

1. En la tabla de turnos, haz clic en **"Editar"** en la fila de tu turno
2. Modifica los datos
3. Haz clic en **"Guardar cambios"**

### 3. Eliminar tu Turno

1. Haz clic en **"Eliminar"**
2. Confirma cuando aparezca el diálogo

---

## Ejemplo de Uso - Administrador

### 1. Agregar un Nuevo Equipo

1. Inicia sesión como admin
2. Ve a **"Dashboard"**
3. En la sección **"Administración rápida"**, completa:
   - Nombre del equipo
   - Horas de uso
   - Umbral (horas máximas)
4. Haz clic en **"Agregar equipo"**

### 2. Ocultar un Equipo

1. En la tabla **"Todos los Equipos"**
2. Haz clic en **"Ocultar"** en la fila del equipo
3. El equipo dejará de ser visible para los usuarios

### 3. Ver Mensajes de Contacto

1. Ve a **"Contacto"**
2. Verás todos los mensajes enviados por los usuarios
3. Marca como leído cuando leas un mensaje

---

## Solución de Problemas

### Error 404 - Página no encontrada

**Solución:** Verifica que la URL sea:
```
http://localhost/proyecto/proyecto/c%C3%B3digo/frontend/vista/login.php
```

### Error de Conexión a Base de Datos

**Solución:**
1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Verifica que la base de datos `predictive_maintain` existe
3. Si no existe, importa el script SQL nuevamente

### No puedo iniciar sesión

**Solución:**
1. Verifica que usas las credenciales correctas
2. Si registraste un nuevo usuario, usa esas credenciales
3. El email debe estar registrado en la base de datos

### Las páginas se cargan lentamente

**Solución:**
1. Verifica que Apache y MySQL estén corriendo en XAMPP
2. Reinicia los servicios

---

## Rutas Importantes

```
C:\xampp\htdocs\proyecto\proyecto\código\
├── frontend/
│   └── vista/
│       ├── login.php        ← Página de inicio de sesión
│       ├── register.php     ← Crear cuenta
│       ├── dashboard.php    ← Panel principal (admin)
│       ├── turno.php        ← Gestión de turnos
│       ├── historial.php    ← Historial
│       ├── pagar.php        ← Pagos
│       ├── contacto.php     ← Mensajes
│       └── auth.php         ← Funciones de autenticación
├── backend/
│   ├── control/
│   │   ├── EquipoControlador.php
│   │   └── IncidenteControlador.php
│   └── modelo/
│       ├── UsuarioModelo.php
│       ├── TurnoModelo.php
│       ├── PagoModelo.php
│       ├── MensajeModelo.php
│       ├── EquipoModelo.php
│       └── IncidenteModelo.php
└── database/
    └── mysql_stored/
        └── script.sql       ← Script de base de datos
```

---

## Datos de Ejemplo

### Usuarios Predefinidos

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@predictive.com | admin123 | Admin |
| user@predictive.com | 123456 | Usuario |

### Equipos de Ejemplo

- Máquina A (250 horas, umbral 200)
- Máquina B (150 horas, umbral 200)
- Máquina C (300 horas, umbral 250)

### Turnos de Ejemplo

- Turno 1: "Mantenimiento preventivo" - 2026-05-20 10:00
- Turno 2: "Revisión general" - 2026-05-22 15:30

---

## Características Principales

### Dashboard
- Equipos que necesitan mantenimiento
- Listado completo de equipos
- Listado de incidentes

### Gestión de Turnos
- Crear nuevos turnos
- Editar turnos propios (usuarios) o todos (admin)
- Eliminar turnos
- Ver estado del turno (pendiente, confirmado, cancelado)

### Historial
- Ver turnos registrados
- Ver historial de incidentes
- Admin puede editar/eliminar incidentes

### Pagos
- Usuarios registran pagos
- Admin ve todos los pagos

### Contacto
- Usuarios envían mensajes
- Admin recibe y gestiona mensajes

---

## Notas Importantes

- ⚠️ Los datos se guardan en la base de datos MySQL
- ⚠️ Si reiniciarás el servidor, los datos persisten
- ⚠️ El rol del usuario determina qué funcionalidades ve
- ⚠️ Los equipos/incidentes ocultos no se ven para usuarios normales
- ⚠️ Las contraseñas se guardan encriptadas (bcrypt)

---

## Soporte

Si tienes problemas:

1. Verifica que XAMPP esté corriendo
2. Verifica la URL (incluye `proyecto/proyecto`)
3. Limpia el caché del navegador (Ctrl+Shift+Supr)
4. Reinicia XAMPP
5. Reimporta la base de datos si es necesario

---

**Versión:** 1.0  
**Última actualización:** 15 de mayo de 2026
