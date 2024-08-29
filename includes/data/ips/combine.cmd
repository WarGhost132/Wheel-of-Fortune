@echo off
setlocal enabledelayedexpansion

rem Initialize ip_list.txt
echo. > ip_list.txt

rem Regular expression pattern for matching IP addresses
set "regex=[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}"

rem Loop through all .txt files in the current directory
for %%f in (*.txt) do (
    echo Processing %%f
    for /f "tokens=*" %%l in ('findstr /r /c:"!regex!" "%%f"') do (
        echo %%l >> ip_list.txt
    )
    echo. >> ip_list.txt
)

echo All files with valid IP addresses have been combined into ip_list.txt
pause
