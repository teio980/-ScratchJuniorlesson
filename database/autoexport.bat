@echo off
echo Exporting database...

REM Set MySQL credentials
set dbUser=root
set dbPass=
set dbName=scratchjunior
set exportPath=C:\xampp\htdocs\-ScratchJuniorlesson\database\scratchjunior.sql

REM Export database
"c:\xampp\mysql\bin\mysqldump.exe" -u %dbUser% -p%dbPass% %dbName% > "%exportPath%"

echo Export complete: %exportPath%
pause
