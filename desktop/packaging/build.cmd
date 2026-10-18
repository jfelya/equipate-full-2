@echo off
rem Compila el .exe standalone. Por defecto usa Python 32-bit para que
rem el mismo ejecutable corra en Windows de 32 y 64 bits.
rem Uso:  build.cmd            (32-bit, recomendado)
rem       build.cmd py -3.14   (forzar otro interprete)
setlocal
cd /d "%~dp0.."
set "PYLAUNCH=%~1"
if "%PYLAUNCH%"=="" set "PYLAUNCH=py -3.12-32"

echo Interprete: %PYLAUNCH%
%PYLAUNCH% -c "import struct,sys;bits=struct.calcsize('P')*8;print('Arquitectura de compilacion:',bits,'bits')"
if errorlevel 1 (
  echo.
  echo No se encontro el interprete indicado.
  echo Instala Python 32-bit con:
  echo   winget install Python.Python.3.12 --architecture x86 --scope user
  echo   py -3.12-32 -m pip install pyinstaller
  exit /b 1
)

%PYLAUNCH% -m PyInstaller --noconfirm --clean --distpath dist --workpath build packaging\EquipateFull.spec
if errorlevel 1 (
  echo.
  echo ERROR: fallo la compilacion.
  exit /b 1
)

echo.
echo Listo:  desktop\dist\EquipateFull.exe
dir /b dist
if exist "..\public" (
  copy /y dist\EquipateFull.exe ..\public\EquipateFull.exe >nul
  echo Copiado a public\EquipateFull.exe
)
