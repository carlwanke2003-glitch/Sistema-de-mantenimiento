# Desarrollo de un Sistema Web de Mantenimiento Industrial Predictivo y Preventivo Basado en la Arquitectura MVC: Caso Práctico "PredictiveMaintain"

**Autores (Estudiantes de 6to Semestre):**
*   **Samuel Rivadeneira Alcívar** (Desarrollador Backend)
*   **Carl Wanke Cedeño** (Desarrollador Frontend)
*   **Angello Sabando Varela** (Líder Técnico)
*   **Diana Icaza Lino** (Gestora de Base de Datos)
*   **Eliel Barco Figueroa** (Desarrollador Frontend)
*   **Martín Santos Borja** (Arquitecto del Sistema)
*   **Isaac Salazar Bonoso** (Documentación)
*   **Joao Jama Villagrán** (QA / Testing)
*   **Ángel De la Torre Murillo** (Desarrollador Frontend)
*   **Erick Zavala Troya** (DevOps)

**Filiación Institucional:**  
Carrera de Ingeniería en Software, Facultad de Ciencias Matemáticas y Físicas, Universidad de Guayaquil  
Guayaquil, Ecuador  
*Asignatura:* Construcción de Software (Curso Sof-NO-6-7)  
*Docente Revisor:* Ing. Parrales Bravo Franklin Ricardo, M.Sc.  
*Año:* 2026  

---

## Resumen
En este artículo presentamos el diseño, desarrollo e implementación de **PredictiveMaintain**, un sistema web transaccional desarrollado por nosotros como estudiantes de sexto semestre de la carrera de Ingeniería en Software de la Universidad de Guayaquil. Nuestro objetivo es optimizar los procesos de mantenimiento preventivo y predictivo de equipos industriales mediante tecnologías accesibles y de código abierto (PHP y MySQL). El sistema utiliza una arquitectura Modelo-Vista-Controlador (MVC) para separar de forma limpia la interfaz de usuario de la lógica de negocio y las consultas de la base de datos. Implementamos alertas automatizadas mediante un procedimiento almacenado que detecta de forma proactiva cuándo una máquina supera su umbral de horas de uso configurado. Para validar el funcionamiento del sistema, realizamos una simulación de implementación en una planta local de extrusión plástica, logrando organizar de manera oportuna los turnos técnicos y consolidar un histórico digital de incidentes y pagos.

**Palabras clave:** Mantenimiento Predictivo, Modelo-Vista-Controlador, PHP, Base de Datos MySQL, Proyecto Académico, Universidad de Guayaquil.

## Abstract
In this paper, we present the design, development, and implementation of **PredictiveMaintain**, a transactional web system developed by us as sixth-semester Software Engineering students from the University of Guayaquil. Our goal is to optimize the preventive and predictive maintenance processes of industrial machinery using accessible open-source technologies (PHP and MySQL). The system utilizes a Model-View-Controller (MVC) architecture to cleanly separate the user interface from the business logic and database queries. We implemented automated alerts using a stored procedure that proactively detects when a machine exceeds its configured operating hours threshold. To validate the system's performance, we simulated its deployment in a local plastic extrusion plant, successfully scheduling technical shifts on time and consolidating a digital history of incidents and payments.

**Keywords:** Predictive Maintenance, Model-View-Controller, PHP, MySQL Database, Academic Project, University of Guayaquil.

---

## I. Introducción
Como estudiantes de la carrera de Ingeniería en Software de la Universidad de Guayaquil, en este sexto semestre nos enfrentamos al reto de aplicar los conocimientos teóricos adquiridos en el aula para resolver problemas del sector productivo. Nuestra universidad, en su Estatuto Orgánico, nos impulsa a formar profesionales con un alto sentido de responsabilidad social, ética y rigor científico, capaces de liderar la transformación tecnológica y la innovación sostenible en el país. Asimismo, el perfil de egreso de nuestra carrera en la Facultad de Ciencias Matemáticas y Físicas nos prepara para analizar, diseñar, implementar y mantener sistemas de software que optimicen procesos bajo estándares de calidad y seguridad.

En la materia de *Construcción de Software*, desarrollamos la plataforma web **PredictiveMaintain** como el fruto de este aprendizaje integrado. Identificamos que muchas pequeñas y medianas empresas (PYMEs) locales no cuentan con recursos para pagar licencias costosas de software industrial (como SAP o IBM Maximo). Debido a esto, terminan gestionando el mantenimiento de sus equipos en cuadernos de anotaciones o tablas de Excel, lo que genera desorganización, olvidos y costosas paradas imprevistas en sus máquinas.

Con este proyecto, nuestro grupo diseñó y programó una solución ligera, segura y económica. Utilizamos programación orientada a objetos en PHP para el backend y una base de datos relacional MySQL. Este trabajo demuestra cómo, desde las aulas de la Universidad de Guayaquil, podemos proponer soluciones de ingeniería de software que ayuden a modernizar la operación de las industrias locales, facilitando el control preventivo y reduciendo el riesgo de fallas costosas.

---

## II. Trabajo Relacional (Estado del Arte y Aplicación Empresarial)
### A. Estado del Arte en Sistemas de Mantenimiento Predictivo
Durante nuestra investigación en la literatura científica y tecnológica, revisamos estudios clave para entender cómo se abordan los sistemas de mantenimiento web en la actualidad. Diversos autores sostienen que digitalizar las bitácoras e historiales de mantenimiento mediante sistemas centralizados en la web reduce de manera significativa el tiempo que tardan los técnicos en atender los reportes en comparación con los métodos físicos tradicionales.

Por otra parte, se destaca que utilizar el patrón de diseño Modelo-Vista-Controlador (MVC) en aplicaciones de monitoreo industrial es fundamental, ya que nos permite mantener el código modular. Esto hace que en el futuro podamos conectar sensores de hardware (IoT) o algoritmos de Machine Learning sin tener que reescribir toda la interfaz del sistema. Además, el software libre y las bases de datos relacionales como MySQL son la mejor opción para las PYMEs, ya que eliminan las barreras económicas y garantizan la integridad de datos transaccionales (como los registros de pagos y turnos técnicos) mediante restricciones de llave foránea (`FOREIGN KEY`).

### B. Simulación e Implementación en un Entorno Empresarial Real
Para poner a prueba nuestro sistema, simulamos su despliegue y uso en la planta de producción de la empresa **"Ecuaplas S.A."**, una mediana fábrica de Guayaquil dedicada a elaborar empaques plásticos biodegradables.

#### 1) Situación Inicial de la Empresa:
Ecuaplas S.A. trabajaba bajo un esquema puramente reactivo: solo llamaban al técnico cuando una máquina inyectora o extrusora de plástico fallaba. Las horas de uso se anotaban en una pizarra manual en el taller, lo que ocasionaba:
*   Olvidos recurrentes de las fechas de cambio de piezas de desgaste (husillos y boquillas).
*   Paradas imprevistas de la fábrica, con pérdidas estimadas en $800 por cada hora de línea de producción detenida.
*   Falta de un historial digital de fallas para cumplir con las auditorías de calidad de la norma ISO 9001.

#### 2) Nuestra Propuesta de Valor con PredictiveMaintain:
El modelo de nuestro sistema ofrece una alternativa superior a lo existente gracias a tres pilares que programamos:
*   **Control Inteligente de Horas:** La base de datos compara continuamente el uso acumulado ($H_{uso}$) frente al umbral máximo seguro ($U_{lim}$) configurado para cada equipo industrial.
*   **Dashboard con Alertas Prioritarias:** Si un equipo supera su umbral seguro ($H_{uso} > U_{lim}$), el sistema lo aísla visualmente en una tabla de color en la parte superior de la pantalla del administrador para programar su revisión técnica urgente.
*   **Flujo Integrado de Gestión:** Un técnico o cliente puede reportar incidentes, agendar turnos preventivos en el calendario, registrar el abono del pago del servicio y enviar mensajes a soporte técnico desde un mismo portal web integrado.

---

## III. Metodología y Diseño de Arquitectura
Para construir el sistema de forma ordenada y limpia, aplicamos el patrón de arquitectura Modelo-Vista-Controlador (MVC).

### A. Requerimientos del Sistema (Estándar IEEE 830)
Para asegurar el correcto diseño técnico del software, definimos los requerimientos basados en los lineamientos del estándar IEEE 830:

| ID | Requerimiento / Atributo | Validación Técnica Programada |
| :--- | :--- | :--- |
| **RF1** | CRUD de Equipos Industriales | El administrador puede crear, editar, eliminar y cambiar visibilidad (visible=0) de la maquinaria mediante formularios HTML validados en el backend. |
| **RF2** | Aislamiento Automático de Alertas | El sistema resalta de manera automática en una tabla superior los activos cuyas horas de uso superen su umbral. |
| **RF3** | Control de Accesos por Sesión | Ocultamos o mostramos las opciones de edición y creación dinámicamente en el frontend según el rol del usuario (`admin` o `usuario`). |
| **RNF1** | Almacenamiento Seguro de Claves | Encriptamos las contraseñas de los usuarios en la base de datos utilizando el hash irreversible `BCRYPT` de PHP. |
| **RNF2** | Protección contra Inyecciones SQL | Implementamos el uso de consultas preparadas (`Prepared Statements`) con la extensión MySQLi de PHP en todos nuestros modelos. |

---

### B. Estructura Lógica de Paquetes
Para organizar la estructura física de nuestro código fuente, agrupamos las clases del sistema en paquetes lógicos independientes con sus relaciones de dependencia interna, como se observa en el **Diagrama de Paquetes UML (Fig. 1)**.

*(AQUÍ SE INSERTA LA FIGURA 1)*

#### Descripción detallada de cada Paquete y sus Clases:
Como se ilustra en el **Diagrama de Paquetes (Fig. 1)**, estructuramos el sistema lógico de PredictiveMaintain en cinco paquetes específicos:
*   **Paquete Gestión de Equipos:** Gobierna la información operativa de la maquinaria de la planta.
    *   *Clase Interfaz Grafica:* Administra los elementos de interfaz, renderizando los campos del formulario de creación y modificando los estilos visuales en el panel según el estado.
    *   *Clase Equipo:* Representa el modelo lógico del activo físico. Define los atributos esenciales de persistencia: `id` (identificador único), `nombre` (descripción del equipo), `horas_uso` (tiempo acumulado de trabajo), `umbral` (límite operativo seguro) y `visible` (estado de borrado lógico). Contiene los métodos para listar, crear, actualizar y cambiar el estado lógico del equipo.
    *   *Clase Procedimiento:* Invoca de forma directa en el gestor MySQL la rutina del procedimiento almacenado `sp_equipos_necesitan_mantenimiento()`, abstrayendo la consulta de alertas predictivas.
*   **Paquete Gestión de Incidentes:** Administra los registros de fallas de la planta.
    *   *Clase Interfaz Grafica:* Proporciona los cuadros de texto y selectores HTML para ingresar incidentes y cambiar su prioridad.
    *   *Clase Incidente:* Mapea el reporte físico de fallas. Contiene los campos `id`, `descripcion` (detalle del problema), `estado` (abierto, cerrado o en progreso), `fecha` (marca de tiempo), `equipo_id` (llave foránea relacional) y `visible` (estado lógico). Contiene los métodos operativos de creación y listado.
*   **Paquete Gestión de Turnos:** Maneja el calendario de revisiones técnicas preventivas.
    *   *Clase Interfaz Grafica:* Carga el componente de calendario del cliente y la tabla interactiva de estados de citas.
    *   *Clase Turno:* Mapea la reserva de servicio técnico. Define los campos `id`, `usuario_id`, `descripcion`, `fecha` programada, `estado` (pendiente, aprobado o cancelado) y `creado_en`. Proporciona métodos para agendar turnos de un usuario y auditar o cambiar estados desde el perfil administrador.
*   **Paquete Gestión de Usuarios:** Controla la seguridad, perfiles y autenticación.
    *   *Clase Interfaz Grafica:* Renderiza los formularios de acceso y registro de cuentas de operador.
    *   *Clase Usuario:* Maneja las credenciales y privilegios. Sus atributos son `id`, `nombre`, `email`, `password` (encriptado con BCRYPT), `rol` (admin o usuario) y `creado_en`. Contiene la lógica para validar las credenciales de inicio de sesión y comprobar privilegios administrativos.
*   **Paquete Módulo de Pagos:** Registra las transacciones financieras por el servicio de mantenimiento.
    *   *Clase Interfaz Grafica:* Muestra la ventana de pagos para registrar nuevos abonos.
    *   *Clase Pago:* Representa el registro transaccional financiero. Almacena `id`, `usuario_id` (cliente emisor), `descripcion` de la factura, `monto` pagado en formato decimal y `fecha`. Administra la creación de registros y generación de recibos digitales.

---

### C. Diccionario de Datos de la Base de Datos
Diseñamos una base de datos relacional llamada `predictive_maintain` compuesta por 6 tablas con llaves foráneas y reglas de integridad. A continuación, detallamos la estructura de las tablas principales que programamos:

#### Tabla: `equipos`
Esta tabla guarda la lista de la maquinaria y sus horas de operación acumuladas.
*   `id` (INT, PRIMARY KEY, Auto_Increment): Identificador único de cada equipo.
*   `nombre` (VARCHAR(255), NOT NULL): Nombre identificador de la máquina.
*   `horas_uso` (INT, Default 0): Horas totales acumuladas de operación.
*   `umbral` (INT, Default 200): Límite seguro de horas antes de mantenimiento preventivo.
*   `visible` (TINYINT(1), Default 1): Estado de visibilidad (1 = activo/visible, 0 = eliminado lógico/oculto).

#### Tabla: `incidentes`
Registra los reportes de averías o fallas asociadas a una máquina.
*   `id` (INT, PRIMARY KEY, Auto_Increment): Código único del incidente.
*   `descripcion` (TEXT, NOT NULL): Explicación del problema o falla detectada.
*   `estado` (ENUM('abierto', 'cerrado', 'en_progreso'), Default 'abierto'): Estado de atención del reporte.
*   `fecha` (DATETIME, Default CURRENT_TIMESTAMP): Fecha de registro automático.
*   `equipo_id` (INT, FOREIGN KEY): Relación con `equipos(id)`. Si se elimina un equipo, se borran sus incidentes (`ON DELETE CASCADE`).
*   `visible` (TINYINT(1), Default 1): Visibilidad lógica del incidente.

---

### D. Infraestructura de Hardware y Red (Despliegue)
Para probar y ejecutar nuestra aplicación web, configuramos un entorno local simulado. El flujo físico de los componentes y su despliegue en nodos físicos se detalla en el **Diagrama de Despliegue UML (Fig. 2)**.

*(AQUÍ SE INSERTA LA FIGURA 2)*

#### Descripción detallada de cada Nodo y sus Componentes:
Como se representa en el **Diagrama de Despliegue (Fig. 2)**, la arquitectura de hardware y software del sistema se compone de tres nodos de ejecución:
*   **Nodo Cliente (PC / Terminal):** Representa el dispositivo del usuario final (computadora, tablet o laptop de operarios e ingenieros de planta) encargado de la interacción local.
    *   *Componente Interfaz de Usuario:* Los elementos HTML dinámicos y la botonera de control que ve el operario.
    *   *Componente Buscador (Browser):* El navegador web (Google Chrome, Firefox o Microsoft Edge) instalado en el sistema operativo del cliente que interpreta el marcado de hipertexto.
    *   *Componente AJAX / JS Engine:* El motor interno de JavaScript del navegador que procesa la lógica en el lado del cliente y realiza peticiones web en segundo plano sin interrumpir la experiencia de usuario.
*   **Nodo Servidor Web (Apache):** El servidor local en el host que compila y despacha la aplicación.
    *   *Componente Entorno PHP (8.x):* El intérprete de lenguaje PHP configurado en el servidor web que procesa los archivos PHP estructurados y orientados a objetos.
    *   *Componente Controladores del Sistema:* Las clases backend que reciben las solicitudes enviadas por el motor AJAX del cliente, validan privilegios de seguridad y dirigen el flujo lógico de ejecución.
    *   *Componente Modelos del Sistema:* Las clases PHP de lógica de negocio que interactúan con la base de datos mapeando las tablas.
*   **Nodo Servidor BD (MySQL):** El nodo de base de datos relacional encargado de la persistencia segura y la consistencia ACID.
    *   *Componente Motor InnoDB (MySQL):* El motor de almacenamiento relacional de MySQL encargado de manejar transacciones seguras, indexar búsquedas rápidas y forzar restricciones de llaves externas.
    *   *Componente Base de Datos predictive_maintain:* El esquema lógico físico de la base de datos que contiene el almacenamiento real de los datos en disco de las tablas de equipos, incidentes, turnos, usuarios, pagos y correspondencia de mensajes.
    *   *Componente Procedimientos Almacenados:* Las rutinas de optimización SQL compiladas en el servidor, como la función encargada de aislar de manera predictiva a las máquinas críticas.

#### Canales y Protocolos de Red:
1.  **Enlace Cliente - Servidor Apache:** La comunicación se establece a través de peticiones HTTP en el puerto estándar 80 (o HTTPS en el puerto 443) para despachar vistas estáticas e intercambiar datos estructurados JSON.
2.  **Enlace Servidor Apache - Servidor MySQL:** La capa backend PHP se comunica con el DBMS MySQL mediante una conexión persistente a través del protocolo del conector nativo MySQLi en el puerto de red 3306.

---

## IV. Resultados y Funcionalidad del Sistema (Evidencia Visual)
El sistema que programamos funciona de forma fluida. A continuación, detallamos cómo interactúan los usuarios según su rol asignado, haciendo referencia a las capturas originales del sistema que guardamos en la carpeta `imagenes_sistema/` de nuestro archivo `.zip`:

### A. Pantallas del Administrador (Rol: admin)
El administrador tiene privilegios totales para gestionar la información de la planta:
1.  **Registro Rápido de Activos e Incidentes:** En la parte superior del panel, creamos dos formularios sencillos para agregar nuevos equipos e incidentes (Figura 1: [01_admin_nuevo_equipo.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/01_admin_nuevo_equipo.png)).
2.  **Dashboard de Mantenimiento Predictivo:** Creamos una lógica en la base de datos mediante el procedimiento almacenado `sp_equipos_necesitan_mantenimiento()`. El sistema ejecuta la siguiente consulta:
    $$\text{Seleccionar } \{id, nombre, horas\_uso, umbral\} \text{ de } equipos \text{ donde } horas\_uso > umbral \text{ y } visible = 1$$
    Como programamos esta regla de negocio en el motor MySQL, el frontend aísla inmediatamente en la parte superior a los equipos críticos "Maquina A" (250 horas / umbral 200) y "Maquina C" (300 horas / umbral 250) en color para llamar la atención del supervisor técnico (Figura 2: [02_admin_incidentes_equipos.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/02_admin_incidentes_equipos.png)).
3.  **Control Total y Auditoría Lógica:** En las tablas inferiores, el administrador puede visualizar todo el inventario de equipos e incidentes. Para mantener la integridad histórica, programamos botones para ocultar los registros (`visible = 0`) en lugar de borrarlos definitivamente de la base de datos (Figura 3: [03_admin_todos_equipos_incidentes.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/03_admin_todos_equipos_incidentes.png)).
4.  **Confirmación de Turnos y Auditoría Técnica:** El administrador tiene una bandeja global para ver todos los turnos solicitados por los clientes, con la capacidad de cambiar su estado a "confirmado" o "cancelado" (Figura 4: [04_admin_turnos.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/04_admin_turnos.png) y Figura 5: [05_admin_historial.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/05_admin_historial.png)).
5.  **Buzón de Pagos y Contacto:** Un visor consolida los abonos económicos que ingresan los clientes (Figura 6: [06_admin_pago_servicio.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/06_admin_pago_servicio.png)) y permite responder las consultas marcándolas como leídas (Figura 7: [07_admin_contacto.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/07_admin_contacto.png)).

### B. Pantallas del Cliente (Rol: usuario)
El operador de planta o cliente interactúa con una interfaz limpia que deshabilita los botones de modificación:
1.  **Dashboard Informativo del Cliente:** Muestra los equipos que necesitan atención de manera idéntica al administrador, pero sin los formularios de creación rápida ni los botones de editar/eliminar por razones de seguridad de datos (Figura 8: [08_cliente_dashboard.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/08_cliente_dashboard.png)).
2.  **Solicitud de Turnos Propios:** El cliente puede llenar un formulario para agendar un turno, seleccionando la fecha con un calendario. Filtramos la base de datos para que el usuario solo visualice sus turnos agendados en su portal (Figura 9: [09_cliente_turnos.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/09_cliente_turnos.png) y Figura 10: [10_cliente_historial.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/10_cliente_historial.png)).
3.  **Registro de Pagos y Formulario de Contacto:** El cliente puede notificar los abonos realizados por los servicios (Figura 11: [11_cliente_pagos.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/11_cliente_pagos.png)) y enviar correos de consulta mediante un formulario directo (Figura 12: [12_cliente_contacto.png](file:///c:/xampp/htdocs/Sistema-de-mantenimiento/proyecto/proyecto/documentacion/imagenes_sistema/12_cliente_contacto.png)).

---

## V. Conclusión
El desarrollo de **PredictiveMaintain** nos ha permitido consolidar como estudiantes de Ingeniería en Software los aprendizajes teóricos y prácticos de la materia de *Construcción de Software*. Logramos diseñar y codificar una plataforma web robusta, aplicando el patrón de arquitectura Modelo-Vista-Controlador (MVC), separando de forma limpia las responsabilidades del sistema y protegiendo la base de datos frente a vulnerabilidades comunes con sentencias SQL preparadas.

A nivel académico, este proyecto integrador nos ayudó a validar las competencias de nuestro perfil de egreso en la Universidad de Guayaquil. Demostramos que, mediante tecnologías de software libre, podemos construir herramientas viables que resuelvan necesidades reales en las PYMEs, permitiendo la migración hacia una cultura de mantenimiento preventivo y ayudando a evitar paradas operativas que afecten su economía.

Como trabajos futuros, planeamos expandir el sistema conectando microcontroladores físicos (ESP32 con sensores de vibración e IoT) para que las horas de uso de la maquinaria se actualicen automáticamente en la base de datos relacional, además de agregar exportación de reportes técnicos a formato PDF y enlazar pasarelas de pago digitales (como Stripe o PayPal) para agilizar las liquidaciones del servicio preventivo.
