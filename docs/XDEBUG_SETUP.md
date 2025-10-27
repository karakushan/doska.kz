# Xdebug Configuration Guide

## Overview

Xdebug v3.4.7 is installed and configured for debugging PHP code running in the Docker container. This allows you to debug WordPress and plugin code directly from VS Code.

## Installation Status

✅ **Xdebug is installed and working!**

- Version: 3.4.7
- Mode: debug (Step Debugger enabled)
- Client: host.docker.internal:9003
- IDE Key: doska

## Files Modified

- **Dockerfile** - Custom image with Xdebug installed via PECL
- **docker-compose.yml** - Updated to build custom image and expose port 9003
- **xdebug.ini** - Xdebug configuration file
- **.vscode/launch.json** - VS Code debugging configuration

## Setup Instructions

### 1. Initial Setup

The Docker image was rebuilt with Xdebug installed:

```bash
# Stop old containers
docker-compose down

# Build new image with Xdebug
docker-compose build --no-cache

# Start containers
docker-compose up -d
```

This is already done. Xdebug is ready to use!

## Usage

### Starting Debugging

1. **Open VS Code** and go to the Run and Debug view (Ctrl+Shift+D)

2. **Select configuration:**
   - **"Listen for Xdebug (Docker)"** - For debugging WordPress in the container
   - **"Listen for Xdebug (Local)"** - For local PHP debugging
   
3. **Click the Start Debugging button** (or press F5)

4. **Set breakpoints** in your code by clicking on the line number

5. **Trigger a request** by navigating to `http://localhost:8080` in your browser

### Debugging Workflow

1. VS Code will **pause execution** at breakpoints
2. **Inspect variables** in the Variables panel
3. **Step through code** using:
   - F10 - Step Over
   - F11 - Step Into
   - Shift+F11 - Step Out
   - F5 - Continue

### Path Mapping

The configuration maps container paths to local paths:
```
/var/www/html (Docker) → ${workspaceRoot}/wordpress (Local)
```

This allows VS Code to show the correct file locations.

### Ignored Paths

The following paths are ignored to avoid debugging system files:
- `/var/www/html/wp-content/plugins/wordpress-seo/**`
- `/var/www/html/wp-includes/**`
- `/var/www/html/wp-admin/**`

You can modify the `ignore` array in `.vscode/launch.json` to add more paths.

## Xdebug Log

Xdebug logs are written to `/var/www/html/xdebug.log` in the container. To view:

```bash
docker exec doska_wordpress tail -f /var/www/html/xdebug.log
```

## Configuration Details

### Dockerfile Configuration

The custom Dockerfile:
1. Starts from `wordpress:latest`
2. Installs system tools: git, curl, wget, vim
3. Installs Xdebug via PECL: `pecl install xdebug`
4. Enables Xdebug: `docker-php-ext-enable xdebug`
5. Copies configuration files: xdebug.ini, uploads.ini
6. Exposes ports: 80 (HTTP), 9003 (Debugging)

### xdebug.ini Settings

| Setting | Value | Purpose |
|---------|-------|---------|
| `zend_extension` | `xdebug` | Enable the Xdebug extension |
| `mode` | `debug` | Enable debugging mode |
| `client_host` | `host.docker.internal` | Host to connect to (Docker bridge) |
| `client_port` | `9003` | Port to connect to |
| `start_with_request` | `yes` | Start debugging on every request |
| `idekey` | `doska` | IDE identifier |
| `log_level` | `10` | Verbose logging |
| `max_data` | `65535` | Max data length for variables |
| `var_display_max_depth` | `10` | Max depth for variable inspection |

### Docker Compose Ports

```yaml
ports:
  - "8080:80"      # HTTP traffic
  - "9003:9003"    # Xdebug debugging port
```

## Troubleshooting

### Verify Xdebug Installation

```bash
docker exec doska_wordpress php -m | grep xdebug
```

Should output:
```
xdebug
Xdebug
```

### Check Xdebug Configuration

```bash
docker exec doska_wordpress php -i | grep xdebug
```

Should show your configured settings with values.

### Debugger Not Connecting

1. **Verify VS Code is listening:**
   - Go to Run and Debug (Ctrl+Shift+D)
   - Select "Listen for Xdebug (Docker)"
   - Press F5
   - Status bar should show "Listening on port 9003"

2. **Check firewall:**
   - Ensure port 9003 is open on your machine
   - Check Docker Desktop firewall settings

3. **Verify container is running:**
   ```bash
   docker ps | grep doska_wordpress
   ```

### Performance Issues

If debugging is slow:
1. Reduce `max_data` value in `xdebug.ini`
2. Reduce `max_depth` for variable inspection
3. Add more paths to the `ignore` list in launch.json

### View Xdebug Logs

```bash
docker exec doska_wordpress tail -f /var/www/html/xdebug.log
```

## Performance Profiling

To enable performance profiling:

1. Add query parameter: `XDEBUG_PROFILE=1`
2. Access: `http://localhost:8080/?XDEBUG_PROFILE=1`
3. Profile files are saved in `/var/www/html/profiler/`

## References

- [Xdebug Documentation](https://xdebug.org/docs/)
- [VS Code PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixbecker.php-debug)
- [Docker PHP Official Image](https://hub.docker.com/_/php)
- [Xdebug v3 Breaking Changes](https://xdebug.org/docs/upgrade_guide)
