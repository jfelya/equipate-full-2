@echo off
rem Servidor de desarrollo de Astro (sitio web).
cd /d "%~dp0"
call npm.cmd run dev -- --open
