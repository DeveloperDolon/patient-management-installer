#!/bin/bash
# wine_helpers.sh

# Function to run Wine commands via Flatpak
run_wine() {
    flatpak run org.winehq.Wine "$@"
}

# Function to run Wine with specific prefix
run_wine_prefix() {
    local prefix="$1"
    shift
    WINEPREFIX="$prefix" flatpak run org.winehq.Wine "$@"
}

# Function to get Wine program files directory
get_wine_program_files() {
    echo "$HOME/.var/app/org.winehq.Wine/data/wine/drive_c/Program Files (x86)"
}

# Function to get Wine drive C directory
get_wine_drive_c() {
    echo "$HOME/.var/app/org.winehq.Wine/data/wine/drive_c"
}

# Function to convert Linux path to Wine path
linux_to_wine_path() {
    local linux_path="$1"
    # Convert /path/to/file to Z:\path\to\file
    echo "Z:$(echo "$linux_path" | sed 's|/|\\|g')"
}

# Export functions for use in other scripts
export -f run_wine run_wine_prefix get_wine_program_files get_wine_drive_c linux_to_wine_path