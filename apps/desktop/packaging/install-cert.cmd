@echo off
rem Instala el certificado publico en el almacen de confianza del usuario actual.
rem Evita el aviso de "editor desconocido" al abrir el .exe en este equipo.
setlocal
set "CER=%~dp0EquipateFull.cer"
if not exist "%CER%" (
  echo No existe %CER%
  echo Genera primero la firma con: powershell -File packaging\sign.ps1
  exit /b 1
)
certutil -user -addstore TrustedPeople "%CER%"
echo.
echo Certificado instalado. Reinicia el Explorador si el aviso persiste.
