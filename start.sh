#!/bin/bash
set -e

echo "=== NitroSpark WP Plugin - Dev Environment Startup ==="

# 1. Ensure Podman machine is running
echo "[1/5] Checking Podman machine..."
if ! podman machine list | grep -q "Currently running"; then
    echo "       Starting Podman machine..."
    podman machine start
else
    echo "       Podman machine already running."
fi

# 2. Install composer dependencies (via container, no local PHP needed)
echo "[2/5] Installing Composer dependencies..."
if [ ! -d "vendor" ]; then
    podman run --rm -v "$(pwd)":/app -w /app composer:latest install
else
    echo "       vendor/ already exists, skipping. (Delete vendor/ to force reinstall)"
fi

# 3. Install npm dependencies
echo "[3/5] Installing npm dependencies..."
if [ ! -d "node_modules" ]; then
    npm install
else
    echo "       node_modules/ already exists, skipping. (Delete node_modules/ to force reinstall)"
fi

# 4. Build frontend assets
echo "[4/5] Building admin frontend..."
npm run build:admin

# 5. Start wp-env
echo "[5/5] Starting WordPress environment..."
npx @wordpress/env start

echo ""
echo "=== Ready! ==="
echo "WordPress site:  http://localhost:8888"
echo "Admin login:     admin / password"
echo "Admin panel:     http://localhost:8888/wp-admin/"
