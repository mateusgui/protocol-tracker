@echo off

cd /d "%~dp0..\"

echo Iniciando servidor PHP de forma invisivel na porta 8000...

wscript.exe ".\scripts\run-silent.vbs" "php -S localhost:8000 -t public"

:: Espera 1 segundo para dar tempo do servidor iniciar
timeout /t 1 /nobreak > nul

echo Abrindo a aplicacao no navegador...
start http://localhost:8000

exit