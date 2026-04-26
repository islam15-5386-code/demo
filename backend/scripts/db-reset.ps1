$ErrorActionPreference = "Stop"

Set-Location -Path (Join-Path $PSScriptRoot "..")

Write-Host "Clearing config cache..."
php artisan config:clear

Write-Host "Clearing application cache..."
php artisan cache:clear

Write-Host "Refreshing database with seed data..."
php artisan migrate:fresh --seed

Write-Host "Database reset completed."
