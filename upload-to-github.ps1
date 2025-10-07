# CCTV Streaming Dashboard - GitHub Upload Script
# PowerShell script to upload repository to GitHub

Write-Host "🚀 CCTV Streaming Dashboard - GitHub Upload Script" -ForegroundColor Green
Write-Host ""

# Colors for output
$Red = "Red"
$Green = "Green"
$Yellow = "Yellow"
$Blue = "Blue"
$Cyan = "Cyan"

function Write-Status {
    param($Message)
    Write-Host "[INFO] $Message" -ForegroundColor $Blue
}

function Write-Success {
    param($Message)
    Write-Host "[SUCCESS] $Message" -ForegroundColor $Green
}

function Write-Warning {
    param($Message)
    Write-Host "[WARNING] $Message" -ForegroundColor $Yellow
}

function Write-Error {
    param($Message)
    Write-Host "[ERROR] $Message" -ForegroundColor $Red
}

# Check if we're in the right directory
if (!(Test-Path "artisan") -or !(Test-Path "app")) {
    Write-Error "This doesn't appear to be a Laravel application directory"
    Write-Error "Please run this script from the streaming-app root directory"
    exit 1
}

Write-Status "Checking Git status..."

# Check if Git is initialized
if (!(Test-Path ".git")) {
    Write-Error "Git repository not initialized. Please run 'git init' first."
    exit 1
}

# Check if there are commits
$commitCount = (git rev-list --count HEAD 2>$null)
if ($commitCount -eq 0) {
    Write-Error "No commits found. Please make an initial commit first."
    exit 1
}

Write-Success "Git repository is ready with $commitCount commits"

Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor $Cyan
Write-Host "1. Go to https://github.com/new" -ForegroundColor $Yellow
Write-Host "2. Create a new repository named 'streaming-app'" -ForegroundColor $Yellow
Write-Host "3. Copy the repository URL (e.g., https://github.com/USERNAME/streaming-app.git)" -ForegroundColor $Yellow
Write-Host ""

# Prompt for GitHub repository URL
$repoUrl = Read-Host "Enter your GitHub repository URL (e.g., https://github.com/USERNAME/streaming-app.git)"

if ([string]::IsNullOrWhiteSpace($repoUrl)) {
    Write-Error "Repository URL is required"
    exit 1
}

Write-Status "Adding remote origin..."
try {
    git remote add origin $repoUrl
    Write-Success "Remote origin added successfully"
} catch {
    Write-Warning "Remote origin might already exist. Checking..."
    git remote set-url origin $repoUrl
    Write-Success "Remote origin updated successfully"
}

Write-Status "Setting default branch to main..."
try {
    git branch -M main
    Write-Success "Default branch set to main"
} catch {
    Write-Warning "Could not rename branch to main. Continuing with current branch..."
}

Write-Status "Pushing to GitHub..."
try {
    git push -u origin main
    Write-Success "Successfully pushed to GitHub!"
} catch {
    Write-Error "Failed to push to GitHub. Please check:"
    Write-Host "  - Repository URL is correct" -ForegroundColor $Yellow
    Write-Host "  - You have push access to the repository" -ForegroundColor $Yellow
    Write-Host "  - Your GitHub credentials are configured" -ForegroundColor $Yellow
    exit 1
}

Write-Host ""
Write-Success "🎉 Repository successfully uploaded to GitHub!"
Write-Host ""
Write-Host "📊 Repository Information:" -ForegroundColor $Cyan
Write-Host "  Repository: $repoUrl" -ForegroundColor $Green
Write-Host "  Branch: main" -ForegroundColor $Green
Write-Host "  Commits: $commitCount" -ForegroundColor $Green
Write-Host ""
Write-Host "🔗 You can now:" -ForegroundColor $Cyan
Write-Host "  - View your repository at: $repoUrl" -ForegroundColor $Yellow
Write-Host "  - Clone it on other machines" -ForegroundColor $Yellow
Write-Host "  - Share it with team members" -ForegroundColor $Yellow
Write-Host "  - Set up CI/CD pipelines" -ForegroundColor $Yellow
Write-Host ""
Write-Host "📚 Documentation included:" -ForegroundColor $Cyan
Write-Host "  - PRODUCTION_DEPLOYMENT.md (Deployment guide)" -ForegroundColor $Yellow
Write-Host "  - README_PRODUCTION.md (Production overview)" -ForegroundColor $Yellow
Write-Host "  - GIT_SETUP_INSTRUCTIONS.md (Git instructions)" -ForegroundColor $Yellow
Write-Host "  - deploy.sh (Deployment script)" -ForegroundColor $Yellow
Write-Host ""
Write-Success "CCTV Streaming Dashboard is now available on GitHub! 🚀"
