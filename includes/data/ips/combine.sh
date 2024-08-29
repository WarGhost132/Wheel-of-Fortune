#!/bin/bash

# Initialize ip_list.txt
> ip_list.txt

# Regular expression pattern for matching IP addresses
regex='^([0-9]{1,3}\.){3}[0-9]{1,3}$'

# Loop through all .txt files in the current directory
for file in *.txt; do
    echo "Processing $file"
    while IFS= read -r line; do
        if [[ $line =~ $regex ]]; then
            echo "$line" >> ip_list.txt
        fi
    done < "$file"
    echo >> ip_list.txt
done

echo "All files with valid IP addresses have been combined into ip_list.txt"
