@echo off
setlocal enabledelayedexpansion

rem Initialize ip_list.txt
echo. > ip_list.txt

rem Loop through all .txt files in the current directory
for %%f in (*.txt) do (
    echo Processing %%f
    type "%%f" >> ip_list.txt
    echo. >> ip_list.txt
)

echo All files have been combined into ip_list.txt
pause