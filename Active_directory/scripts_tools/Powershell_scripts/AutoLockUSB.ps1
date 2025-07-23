# === SETTINGS ===
$TargetDeviceID = "VID_17EF&PID_60FF"  # Your Lenovo Receiver's VID/PID
$CheckIntervalSeconds = 2

function Is-DevicePresent {
    $devices = Get-WmiObject Win32_USBControllerDevice | ForEach-Object {
        ([WMI]$_.Dependent).DeviceID
    }
    return ($devices -match $TargetDeviceID)
}

function Lock-Workstation {
    rundll32.exe user32.dll,LockWorkStation
    Write-Host ">> Device missing - Forcing lock!" -ForegroundColor Red
}

Write-Host "Monitoring USB device: $TargetDeviceID every $CheckIntervalSeconds seconds..."
Write-Host "Unplug the receiver to trigger lock."

while ($true) {
    if (-not (Is-DevicePresent)) {
        # If missing -> immediately lock every time
        Lock-Workstation
    } else {
        Write-Host ">> Device present - System stays unlocked." -ForegroundColor Green
    }
    Start-Sleep -Seconds $CheckIntervalSeconds
}
