; inno_setup_script.iss - Advanced Configuration

#define MyAppName "Patient Management System"
#define MyAppVersion "2.0.0"
#define MyAppPublisher "DeveloperDolon"
#define MyAppURL "https://space-portolio-main-pi.vercel.app/"
#define MyAppExeName "start_app.bat"

[Setup]
AppId={{12345678-1234-1234-1234-123456789012}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppVerName={#MyAppName} {#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
AppSupportURL={#MyAppURL}/support
AppUpdatesURL={#MyAppURL}/updates
DefaultDirName={autopf}\{#MyAppName}
DefaultGroupName={#MyAppName}
AllowNoIcons=yes
LicenseFile=C:\temp\license.txt
OutputDir=C:\temp\output
OutputBaseFilename={#MyAppName}_Setup_v{#MyAppVersion}
SetupIconFile=C:\temp\assets\icon.ico
Compression=lzma2/ultra64
SolidCompression=yes
InternalCompressLevel=ultra64
CompressionThreads=auto
LZMAUseSeparateProcess=yes
LZMADictionarySize=1048576
PrivilegesRequired=admin
PrivilegesRequiredOverridesAllowed=dialog
ArchitecturesAllowed=x64
ArchitecturesInstallIn64BitMode=x64
MinVersion=6.1sp1
WizardStyle=modern
DisableProgramGroupPage=yes
DisableReadyPage=no
DisableFinishedPage=no
UsePreviousAppDir=yes
UsePreviousGroup=yes
UpdateUninstallLogAppName=yes
UsedUserAreasWarning=no

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"
Name: "spanish"; MessagesFile: "compiler:Languages\Spanish.isl"
Name: "french"; MessagesFile: "compiler:Languages\French.isl"

[Messages]
BeveledLabel=Patient Management System - Enterprise Edition

[Types]
Name: "full"; Description: "Full installation"
Name: "compact"; Description: "Compact installation"
Name: "custom"; Description: "Custom installation"; Flags: iscustom

[Components]
Name: "core"; Description: "Core Application Files"; Types: full compact custom; Flags: fixed
Name: "xampp"; Description: "XAMPP Web Server"; Types: full compact custom; Flags: fixed
Name: "samples"; Description: "Sample Data"; Types: full
Name: "docs"; Description: "Documentation"; Types: full

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked
Name: "quicklaunchicon"; Description: "{cm:CreateQuickLaunchIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked; OnlyBelowVersion: 6.1
Name: "firewall"; Description: "Configure Windows Firewall"; GroupDescription: "Security Settings"
Name: "autostart"; Description: "Start with Windows"; GroupDescription: "Startup Options"

[Files]
; Core application files
Source: "C:\temp\src\laravel-app\*"; DestDir: "{app}\app"; Components: core; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "C:\temp\src\xampp-portable\*"; DestDir: "{app}\xampp"; Components: xampp; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "C:\temp\src\installer\*"; DestDir: "{app}\installer"; Components: core; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "C:\temp\src\security\*"; DestDir: "{app}\security"; Components: core; Flags: ignoreversion recursesubdirs createallsubdirs

; Assets and documentation - UPDATED PATHS
Source: "C:\temp\src\assets\*"; DestDir: "{app}\assets"; Components: core; Flags: ignoreversion recursesubdirs createallsubdirs

; Configuration files - UPDATED PATHS
Source: "C:\temp\src\config\firewall_rules.bat"; DestDir: "{app}\config"; Components: core; Flags: ignoreversion


[Icons]
Name: "{group}\{#MyAppName}"; Filename: "{app}\installer\start_app.bat"; IconFilename: "{app}\assets\icon.ico"; WorkingDir: "{app}\installer"
Name: "{group}\Stop {#MyAppName}"; Filename: "{app}\installer\stop_app.bat"; IconFilename: "{app}\assets\stop_icon.ico"; WorkingDir: "{app}\installer"
Name: "{group}\{#MyAppName} Documentation"; Filename: "{app}\docs\index.html"; Components: docs
Name: "{group}\Uninstall {#MyAppName}"; Filename: "{uninstallexe}"
Name: "{autodesktop}\{#MyAppName}"; Filename: "{app}\installer\start_app.bat"; IconFilename: "{app}\assets\icon.ico"; WorkingDir: "{app}\installer"; Tasks: desktopicon
Name: "{userappdata}\Microsoft\Internet Explorer\Quick Launch\{#MyAppName}"; Filename: "{app}\installer\start_app.bat"; IconFilename: "{app}\assets\icon.ico"; WorkingDir: "{app}\installer"; Tasks: quicklaunchicon

[Registry]
Root: HKCU; Subkey: "Software\Microsoft\Windows\CurrentVersion\Run"; ValueType: string; ValueName: "{#MyAppName}"; ValueData: """{app}\installer\start_app.bat"""; Tasks: autostart
Root: HKLM; Subkey: "Software\{#MyAppPublisher}\{#MyAppName}"; ValueType: string; ValueName: "InstallPath"; ValueData: "{app}"
Root: HKLM; Subkey: "Software\{#MyAppPublisher}\{#MyAppName}"; ValueType: string; ValueName: "Version"; ValueData: "{#MyAppVersion}"
Root: HKLM; Subkey: "Software\{#MyAppPublisher}\{#MyAppName}"; ValueType: dword; ValueName: "Installed"; ValueData: 1

[Run]
Filename: "{app}\config\firewall_rules.bat"; Parameters: "add"; StatusMsg: "Configuring Windows Firewall..."; Tasks: firewall; Flags: runhidden waituntilterminated
Filename: "{app}\installer\post_install.bat"; StatusMsg: "Completing installation..."; Flags: runhidden waituntilterminated
Filename: "{app}\installer\start_app.bat"; Description: "{cm:LaunchProgram,{#StringChange(MyAppName, '&', '&&')}}"; Flags: nowait postinstall skipifsilent

[UninstallRun]
Filename: "taskkill"; Parameters: "/F /IM httpd.exe"; Flags: runhidden; RunOnceId: "KillApache"
Filename: "taskkill"; Parameters: "/F /IM mysqld.exe"; Flags: runhidden; RunOnceId: "KillMySQL"
Filename: "{app}\config\firewall_rules.bat"; Parameters: "remove"; Flags: runhidden waituntilterminated; RunOnceId: "RemoveFirewall"
Filename: "{app}\config\uninstall_cleanup.bat"; Flags: runhidden waituntilterminated; RunOnceId: "CleanupFiles"

[UninstallDelete]
Type: filesandordirs; Name: "{app}\logs"
Type: filesandordirs; Name: "{app}\temp"
Type: filesandordirs; Name: "{app}\xampp\logs"
Type: filesandordirs; Name: "{app}\xampp\tmp"


[Code]
var
  DataDirPage: TInputDirWizardPage;

procedure InitializeWizard;
begin
  // Simple directory selection page
  DataDirPage := CreateInputDirPage(wpSelectDir,
    'Select Installation Directory',
    'Where should the application be installed?',
    'Select the folder where the application files should be installed, then click Next.',
    False, '');
  DataDirPage.Add('Application files:');
  DataDirPage.Values[0] := ExpandConstant('{autopf}\{#MyAppName}');
end;

function NextButtonClick(CurPageID: Integer): Boolean;
begin
  Result := True;
  // Basic directory validation
  if (CurPageID = DataDirPage.ID) and 
     ((DataDirPage.Values[0] = '') or (not DirExists(ExtractFileDir(DataDirPage.Values[0])))) then
  begin
    MsgBox('Please select a valid installation directory.', mbError, MB_OK);
    Result := False;
  end;
end;

procedure CurStepChanged(CurStep: TSetupStep);
var
  ResultCode: Integer;
begin
  if CurStep = ssPostInstall then
  begin
    // Simply run the application after installation
    if not Exec(ExpandConstant('{app}\{#MyAppExeName}'), '', '', SW_SHOW, ewNoWait, ResultCode) then
    begin
      MsgBox('Failed to start the application. Please run it manually.', mbError, MB_OK);
    end;
  end;
end;

procedure CurUninstallStepChanged(CurUninstallStep: TUninstallStep);
begin
  // Basic cleanup during uninstallation
  if CurUninstallStep = usPostUninstall then
  begin
    RegDeleteKeyIncludingSubkeys(HKLM, 'Software\{#MyAppPublisher}\{#MyAppName}');
    RegDeleteValue(HKCU, 'Software\Microsoft\Windows\CurrentVersion\Run', '{#MyAppName}');
  end;
end;
