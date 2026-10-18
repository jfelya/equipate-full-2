@echo off
cd /d "%~dp0..\.."
echo Servidor web en http://localhost:8080/apps/web/index.html
start "" http://localhost:8080/apps/web/index.html
py -m http.server 8080
