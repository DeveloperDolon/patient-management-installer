#!/bin/bash
# prepare_xampp.sh

source wine_helpers.sh

echo "Preparing XAMPP Portable..."

# Create source directory
mkdir -p src/xampp-portable

# Download XAMPP portable (PHP 8.2)
cd src
wget -O xampp-portable.zip "https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.4/xampp-portable-windows-x64-8.2.4-0-VS16.zip/download"

# Extract XAMPP
echo "Extracting XAMPP..."
unzip -q xampp-portable.zip -d xampp-portable/

# Remove unnecessary components to reduce size
echo "Removing unnecessary components..."
rm -rf xampp-portable/FileZillaFTP/
rm -rf xampp-portable/MercuryMail/
rm -rf xampp-portable/Tomcat/
rm -rf xampp-portable/perl/
rm -rf xampp-portable/sendmail/
rm -rf xampp-portable/webalizer/
rm -rf xampp-portable/anonymous/

# Clean up
rm xampp-portable.zip

echo "XAMPP preparation completed!"