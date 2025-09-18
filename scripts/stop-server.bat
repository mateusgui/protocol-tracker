@echo off
echo Procurando pelo processo do servidor PHP na porta 8000...

for /f "tokens=2 delims==" %%a in ('wmic process where "name='php.exe' and commandline like '%%-S localhost:8000%%'" get processid /value') do (
    set pid=%%a
)

if defined pid (
    echo Servidor encontrado com PID: %pid%. Finalizando...
    taskkill /F /PID %pid%
    echo Servidor finalizado com sucesso.
) else (
    echo Servidor PHP nao parece estar em execucao.
)

echo.
pause
exit