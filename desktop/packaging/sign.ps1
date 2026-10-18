# Firma el .exe con un certificado autofirmado del usuario actual.
# Uso:  powershell -ExecutionPolicy Bypass -File packaging\sign.ps1
param(
    [string]$Exe = "$PSScriptRoot\..\dist\EquipateFull.exe",
    [string]$Subject = "CN=Equipate Full (jfelya)"
)

$ErrorActionPreference = "Stop"

$cert = Get-ChildItem Cert:\CurrentUser\My |
    Where-Object { $_.Subject -eq $Subject } |
    Select-Object -First 1

if (-not $cert) {
    Write-Host "Creando certificado autofirmado..."
    $cert = New-SelfSignedCertificate -Type Custom -Subject $Subject `
        -KeyUsage DigitalSignature -FriendlyName "Equipate Full" `
        -CertStoreLocation "Cert:\CurrentUser\My" `
        -TextExtension @("2.5.29.37={text}1.3.6.1.5.5.7.3.3", "2.5.29.19={text}")
}

$cerPath = Join-Path $PSScriptRoot "EquipateFull.cer"
Export-Certificate -Cert $cert -FilePath $cerPath | Out-Null

Set-AuthenticodeSignature -FilePath $Exe -Certificate $cert -TimestampServer "http://timestamp.digicert.com" |
    Format-List Status, StatusMessage

Write-Host "Certificado publico: $cerPath"
Write-Host "Instalalo con: packaging\install-cert.cmd"
