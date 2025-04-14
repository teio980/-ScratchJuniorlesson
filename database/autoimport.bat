@echo off
echo Importing database...

REM Set MySQL credentials
set dbUser=root
set dbPass=
set dbName=scratchjunior
set importPath=C:\xampp\htdocs\-ScratchJuniorlesson\database\scratchjunior.sql

REM Create the database if it doesn't exist
"c:\xampp\mysql\bin\mysql.exe" -u %dbUser% -p%dbPass% -e "CREATE DATABASE IF NOT EXISTS %dbName%;"

REM Import the SQL file into the database
"c:\xampp\mysql\bin\mysql.exe" -u %dbUser% -p%dbPass% %dbName% < "%importPath%"

echo Import complete!
pause
