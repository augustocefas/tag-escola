#!/bin/bash

CLIENT_DIST="../client/dist"
PUBLIC_CLIENT="../public/spa"

echo "➡️ Sincronizando client build..."

rsync -av --delete \
  --exclude="index.php" \
  --exclude="robots.txt" \
  --exclude=".htaccess" \
  "$CLIENT_DIST"/ "$PUBLIC_CLIENT"/

echo "✅ Deploy concluído"
