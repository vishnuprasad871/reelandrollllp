#!/bin/bash
# ============================================================
#  Reel and Roll Photography — MySQL Backup Script
#  Usage:  bash database/backup.sh
#  Requires: mysqldump, read access to DB credentials
# ============================================================

# ── Config (edit these or set as environment variables) ────
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"       # leave blank to be prompted
DB_NAME="${DB_NAME:-reelandroll}"

BACKUP_DIR="$(dirname "$0")/backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.sql"
COMPRESSED="${BACKUP_FILE}.gz"

# ── Create backup directory ─────────────────────────────────
mkdir -p "$BACKUP_DIR"

echo "→ Starting backup of database: $DB_NAME"

# ── Build auth args ─────────────────────────────────────────
AUTH="-h${DB_HOST} -P${DB_PORT} -u${DB_USER}"
if [ -n "$DB_PASS" ]; then
    AUTH="${AUTH} -p${DB_PASS}"
fi

# ── Run mysqldump ───────────────────────────────────────────
mysqldump $AUTH \
    --databases "$DB_NAME" \
    --routines \           # stored procedures & functions
    --triggers \           # triggers
    --events \             # scheduled events
    --single-transaction \ # consistent snapshot (InnoDB)
    --set-gtid-purged=OFF \
    --column-statistics=0 \
    --add-drop-database \
    --add-drop-table \
    --create-options \
    --extended-insert \
    --quick \
    > "$BACKUP_FILE"

if [ $? -ne 0 ]; then
    echo "✗ Backup failed!"
    rm -f "$BACKUP_FILE"
    exit 1
fi

# ── Compress ────────────────────────────────────────────────
gzip "$BACKUP_FILE"
echo "✓ Backup saved: $COMPRESSED"
echo "  Size: $(du -sh "$COMPRESSED" | cut -f1)"

# ── Keep only last 10 backups ───────────────────────────────
ls -t "${BACKUP_DIR}"/*.sql.gz 2>/dev/null | tail -n +11 | xargs -r rm --
echo "✓ Old backups cleaned up (keeping last 10)"
