# Deployment Setup Guide

This guide explains how to set up automated deployment for the PSP Laravel + Vue application.

## Overview

The project uses GitHub Actions for automated deployment to BeGet hosting. When you push to the `main` branch, the workflow automatically:

1. Builds frontend assets (npm run build)
2. Commits the built assets to the repository
3. Connects to the server via SSH
4. Runs `deploy.sh` script which pulls changes and updates dependencies

## Prerequisites

- GitHub repository with Actions enabled
- SSH access to BeGet server
- SSH key pair for authentication

## Setup Steps

### 1. Generate SSH Key (if not already done)

On your local machine:

```bash
# Generate a new SSH key pair (if needed)
ssh-keygen -t ed25519 -C "github-actions@psp-deploy" -f ~/.ssh/psp_deploy

# Copy the public key to the server
ssh-copy-id -i ~/.ssh/psp_deploy.pub abrobe14_psp@psp.realeaststudio.site
```

### 2. Add SSH Private Key to GitHub Secrets

1. Go to your GitHub repository
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Name: `SSH_PRIVATE_KEY`
5. Value: Content of your private key file

```bash
# Display the private key content
cat ~/.ssh/psp_deploy
```

Copy the entire output (including `-----BEGIN` and `-----END` lines) and paste it as the secret value.

### 3. Test the Workflow

Push a commit to the `main` branch:

```bash
git add .
git commit -m "Test automated deployment"
git push origin main
```

Go to the **Actions** tab in GitHub to see the workflow running.

### 4. Verify Deployment

After the workflow completes:

```bash
# Check the live site
curl -I https://psp.realeasystudio.site/

# Or visit in browser
open https://psp.realeasystudio.site/
```

## Workflow Details

The GitHub Actions workflow (`.github/workflows/deploy.yml`) performs these steps:

1. **Checkout code**: Gets the latest code from the repository
2. **Setup Node.js**: Installs Node.js 20 with npm caching
3. **Install dependencies**: Runs `npm ci` for clean install
4. **Build frontend**: Runs `npm run build` to compile Vue components
5. **Commit assets**: Commits `public/build/` to repository (with `[skip ci]` to avoid loops)
6. **Deploy to server**: SSHs into BeGet and runs `deploy.sh`

## Troubleshooting

### Workflow fails with "Permission denied (publickey)"

- Check that SSH_PRIVATE_KEY secret is correctly set in GitHub
- Verify the public key is added to the server: `ssh abrobe14_psp@psp.realeasystudio.site 'cat ~/.ssh/authorized_keys'`
- Test SSH connection manually: `ssh -i ~/.ssh/psp_deploy abrobe14_psp@psp.realeasystudio.site`

### Build assets not updating

- Check if `npm run build` completed successfully in the workflow logs
- Verify `public/build/` directory exists in the repository
- Check `.gitignore` - `public/build/` should NOT be ignored

### Deploy script fails on server

- SSH into the server and run manually: `bash deploy.sh`
- Check PHP version: `/usr/local/bin/php8.4 -v`
- Check Composer: `/usr/local/bin/php8.4 ~/bin/composer --version`

### "No such file or directory" errors

The deploy script expects to run from `/home/a/abrobe14/psp.realeasystudio.site/public_html`.
Verify the path in GitHub Actions matches the actual path on the server.

## Manual Deployment (Fallback)

If GitHub Actions is unavailable, you can deploy manually:

```bash
# Build locally
npm run build

# Commit and push
git add public/build
git commit -m "Build assets"
git push origin main

# Deploy on server
ssh abrobe14_psp@psp.realeasystudio.site 'cd /home/a/abrobe14/psp.realeasystudio.site/public_html && bash deploy.sh'
```

## Environment Configuration

On the remote server, ensure `.env` file has correct settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (generated via php artisan key:generate)
SESSION_DRIVER=file
```

## Next Steps

- Consider adding deployment notifications (Slack, Discord, email)
- Add deployment rollback capability
- Set up staging environment for testing before production
- Add automated tests to the workflow
