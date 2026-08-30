
set -e

# --- Locate the backend root (this script lives in the project parent folder) ---
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="${REMIT_BACKEND_DIR:-$SCRIPT_DIR/remitsystem}"

if [ ! -f "$BACKEND_DIR/artisan" ]; then
  echo "ERROR: Could not find Laravel backend at: $BACKEND_DIR" >&2
  echo "Set the REMIT_BACKEND_DIR environment variable to the backend folder if it lives elsewhere." >&2
  exit 1
fi

# --- Read host/port from .env (fall back to Laravel defaults) ---
ENV_FILE="$BACKEND_DIR/.env"
APP_URL="${APP_URL:-}"
HOST="127.0.0.1"
PORT="8000"
if [ -f "$ENV_FILE" ]; then
  APP_URL="$(grep -E '^APP_URL=' "$ENV_FILE" | head -1 | cut -d= -f2-)"
fi
if [ -n "$APP_URL" ]; then
  # http://host:port    -> host, port
  HOST="$(echo "$APP_URL" | sed -E 's#^https?://##; s#:.*$##; s#/.*$##')"
  PORT="$(echo "$APP_URL" | sed -E 's#^.*:([0-9]+)/?$#\1#')"
fi
# Guard: if sed failed to squeeze a port out, fall back to 8000
case "$PORT" in
  ''|*[!0-9]*) PORT="8000" ;;
esac
HOST="${HOST:-127.0.0.1}"

BASE_URL="http://${HOST}:${PORT}"
LOGIN_URL="$BASE_URL/login"
DASHBOARD_URL="$BASE_URL/dashboard"

# --- Helper: is the server already running? ---
server_pid() {
  pgrep -f "artisan serve.*$PORT" | head -1 || true
}

start_server() {
  if [ -n "$(server_pid)" ]; then
    echo "Backend already running on $BASE_URL (pid $(server_pid))."
    echo
    print_urls
    return 0
  fi

  # 1. Make sure MySQL is up (brew on macOS). Fail gracefully if not present.
  if command -v mysqladmin >/dev/null 2>&1; then
    if ! mysqladmin ping --silent 2>/dev/null; then
      echo "Starting MySQL..."
      if command -v brew >/dev/null 2>&1; then
        brew services start mysql >/dev/null 2>&1 || true
      fi
      # give it a moment
      for _ in $(seq 1 15); do
        mysqladmin ping --silent 2>/dev/null && break
        sleep 1
      done
    fi
  fi

  # 2. Check dependencies are installed
  if [ ! -d "$BACKEND_DIR/vendor" ]; then
    echo "vendor/ not found. Running: composer install"
    (cd "$BACKEND_DIR" && composer install --no-interaction)
  fi

  # 3. (Re)generate application key if missing
  if ! grep -qE '^APP_KEY=.+' "$ENV_FILE" 2>/dev/null; then
    (cd "$BACKEND_DIR" && php artisan key:generate --force)
  fi

  echo "Starting Remit System backend server..."
  if [ "$1" = "-f" ]; then
    (cd "$BACKEND_DIR" && php artisan serve --host="$HOST" --port="$PORT")
    # when run in foreground via this function we should not return; but it is used only for -f
  else
    (cd "$BACKEND_DIR" && nohup php artisan serve --host="$HOST" --port="$PORT" >/tmp/remitsystem_serve.log 2>&1 </dev/null & disown)
    # Wait until the server responds
    echo -n "Waiting for server"
    for _ in $(seq 1 20); do
      if curl -s -o /dev/null "$LOGIN_URL" 2>/dev/null; then
        echo " up."
        echo
        print_urls
        return 0
      fi
      echo -n "."
      sleep 1
    done
    echo
    echo "Server did not respond within timeout. See log: /tmp/remitsystem_serve.log"
    return 1
  fi
}

print_urls() {
  echo "==========================================================="
  echo "  Remit System Backend is running"
  echo "==========================================================="
  echo "  Admin Login page : $LOGIN_URL"
  echo "  Admin Dashboard  : $DASHBOARD_URL"
  echo "  (Dashboard requires sign-in and redirects to /login if not logged in)"
  echo "-----------------------------------------------------------"
  echo "  To stop the server:  $0 stop"
  echo "==========================================================="
}

stop_server() {
  local pid
  pid="$(server_pid)"
  if [ -n "$pid" ]; then
    echo "Stopping backend (pid $pid)..."
    kill "$pid" 2>/dev/null || true
    sleep 1
    echo "Stopped."
  else
    echo "Backend is not running."
  fi
}

status_server() {
  if [ -n "$(server_pid)" ]; then
    echo "Backend is RUNNING: $BASE_URL"
    print_urls
  else
    echo "Backend is NOT running. Start it with: $0"
  fi
}

case "${1:-start}" in
  start|-f)
    start_server "${1:-start}"
    ;;
  stop)
    stop_server
    ;;
  status)
    status_server
    ;;
  *)
    echo "Usage: $0 [start|stop|status|-f]"
    exit 1
    ;;
esac
