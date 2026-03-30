#!/bin/bash
# Daily database backup for the Martian Republic
# Add to crontab: 0 3 * * * /home/martianrepublic/scripts/backup-db.sh

BACKUP_DIR="/home/martianrepublic/backups"
DB_USER="serverusr"
DB_NAME="mrswalletdb"
KEEP_DAYS=14

mkdir -p "$BACKUP_DIR"

FILENAME="$BACKUP_DIR/${DB_NAME}-$(date +%Y%m%d-%H%M%S).sql.gz"
mysqldump -u "$DB_USER" "$DB_NAME" --single-transaction --quick 2>/dev/null | gzip > "$FILENAME"

if [ -s "$FILENAME" ]; then
    echo "$(date): Backup OK — $(ls -lh "$FILENAME" | awk '{print $5}')" >> "$BACKUP_DIR/backup.log"
else
    echo "$(date): BACKUP FAILED" >> "$BACKUP_DIR/backup.log"
    exit 1
fi

# Prune old backups
find "$BACKUP_DIR" -name "*.sql.gz" -mtime +$KEEP_DAYS -delete
