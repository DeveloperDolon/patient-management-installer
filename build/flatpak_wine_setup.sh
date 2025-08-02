#!/bin/bash
# flatpak_wine_setup.sh

set -e

echo "Setting up Flatpak Wine environment..."

# Install Flatpak if not already installed
if ! command -v flatpak &> /dev/null; then
    echo "Installing Flatpak..."
    sudo apt update
    sudo apt install flatpak -y
    
    # Add Flathub repository
    sudo flatpak remote-add --if-not-exists flathub https://flathub.org/repo/flathub.flatpakrepo
fi

# Install Wine via Flatpak
echo "Installing Wine via Flatpak..."
flatpak install -y flathub org.winehq.Wine

# Install necessary runtime dependencies
flatpak install -y flathub org.freedesktop.Platform.Compat.i386
flatpak install -y flathub org.freedesktop.Platform.GL32.default

# Create Wine prefix directory
export WINE_PREFIX="$HOME/.var/app/org.winehq.Wine/data/wine"
mkdir -p "$WINE_PREFIX"

echo "Configuring Wine..."
# Initialize Wine with Windows 10 compatibility
flatpak run org.winehq.Wine winecfg

echo "Installing Windows components..."
# Install Visual C++ Redistributables and .NET Framework
flatpak run org.winehq.Wine msiexec /i /quiet /norestart https://aka.ms/vs/17/release/vc_redist.x64.exe

# Download and install Inno Setup
echo "Installing Inno Setup..."
cd /tmp
wget -O innosetup.exe "https://jrsoftware.org/download.php/is.exe"
flatpak run org.winehq.Wine innosetup.exe /SILENT /NORESTART

# Wait for installation to complete
sleep 30

echo "Flatpak Wine setup completed!"
echo "Wine Prefix: $WINE_PREFIX"