# 📚 Biblioteca PHP

Proyecto de Fin de Grado — Aplicación web de gestión de una biblioteca 
---

## 🚀 Tecnologías utilizadas
- 🐘 **PHP**  
- 🗄️ **MySQL**  
- 🎨 **HTML5, CSS3**  
- ⚡ **JavaScript**  
- 🖥️ **XAMPP / Apache** (para entorno local)

---

## 📂 Funcionalidades principales
- 📖 Gestión de libros (alta, baja, modificación, búsqueda).  
- 👤 Gestión de usuarios de la biblioteca.  
- 📅 Registro de préstamos y devoluciones.  
- 🔐 Sistema básico de login con autenticación.  

---

## 🚀 Manual de Instalación

### 🔧 Requisitos del Entorno
- [XAMPP](https://www.apachefriends.org/es/index.html) (Apache, PHP y MySQL).  
- **phpMyAdmin** para la administración de la base de datos.  
- **Visual Studio Code** u otro editor de código.  
- Un navegador moderno (Chrome, Firefox, Edge...).  

### 📥 Instalación paso a paso
1. **Instalar XAMPP**: Descárgalo e instálalo.  
2. **Importar la base de datos**:  
   - Abre la consola de XAMPP.  
   - Conéctate a MySQL:  
     ```bash
     mysql -u root -p
     ```
   - Crea la base de datos:  
     ```sql
     CREATE DATABASE bibliotecapersonal;
     ```
   - Sal de MySQL:  
     ```bash
     EXIT;
     ```
   - Importa el archivo `bibliotecapersonal.sql`:  
     ```bash
     mysql -u root -p bibliotecapersonal < bibliotecapersonal.sql
     ```
3. **Copiar archivos**: Copia el contenido del proyecto dentro de la carpeta `htdocs/` de XAMPP (⚠️ no copies la carpeta raíz, solo su contenido).  
4. **Iniciar XAMPP**: Activa los servicios `Apache` y `MySQL`.  
5. **Abrir en navegador**: Ve a [http://localhost/index.php](http://localhost/index.php)  

---

## 🔑 Credenciales de acceso

### 👩‍💻 Administrador
- **Usuario:** `AndreaMM`  
- **Contraseña:** `admin`  

### 👥 Usuarios de prueba
- `AdrianJS` — contraseña: `test`  
- `AlbaBM` — contraseña: `test`  
- `LuisAF` — contraseña: `test`  

---

## 📂 Estructura del proyecto
```
htdocs/
├── config/ # Configuración de la app
├── controlador/ # Controladores PHP
├── imagenes/ # Recursos estáticos
├── modelo/ # Modelos de datos
├── vista/ # Vistas (interfaz)
├── bibliotecapersonal.sql # Base de datos
├── favicon.png
└── index.php

```
--- 

## ✨ Funcionalidades
- Registro e inicio de sesión de usuarios.  
- Administración de libros (CRUD).  
- Gestión diferenciada entre administrador y usuarios normales.  
- Interfaz sencilla y funcional.  

---

✍️ **Autor:** Andrea Maña Moreno — *Proyecto Fin de Grado en Desarrollo de Aplicaciones Web*.
