#!/usr/bin/env bash
# Проверка гонки: два параллельных запроса "Взять в работу".
# Ожидается: один ответ 200, второй 409.

set -e
BASE_URL="${BASE_URL:-http://localhost:8000}"
REQUEST_ID="${REQUEST_ID:-2}"
COOKIE_FILE=$(mktemp)
trap "rm -f $COOKIE_FILE" EXIT

echo "=== Race test: take in work (request_id=$REQUEST_ID, base_url=$BASE_URL) ==="

# 1) GET login page, save cookies and extract CSRF token
LOGIN_PAGE=$(curl -s -c "$COOKIE_FILE" -b "$COOKIE_FILE" "$BASE_URL/login")
TOKEN=$(echo "$LOGIN_PAGE" | sed -n 's/.*name="_token"[^>]*value="\([^"]*\)".*/\1/p' | head -1)
if [ -z "$TOKEN" ]; then
  echo "Could not extract CSRF token from login page."
  exit 1
fi

# 2) Login as master
curl -s -c "$COOKIE_FILE" -b "$COOKIE_FILE" -X POST "$BASE_URL/login" \
  -d "email=master1@repair.local" \
  -d "password=password" \
  -d "_token=$TOKEN" \
  -d "remember=0" \
  -L -o /dev/null -w "%{http_code}" | grep -q 200 || true

# 3) Two parallel POSTs to take in work
echo "Sending two parallel POSTs to /api/requests/take..."
RESP1=$(mktemp)
RESP2=$(mktemp)
trap "rm -f $COOKIE_FILE $RESP1 $RESP2" EXIT

curl -s -w "\n%{http_code}" -b "$COOKIE_FILE" -X POST "$BASE_URL/api/requests/take" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"request_id\": $REQUEST_ID}" -o "$RESP1" &
CURL1_PID=$!
curl -s -w "\n%{http_code}" -b "$COOKIE_FILE" -X POST "$BASE_URL/api/requests/take" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"request_id\": $REQUEST_ID}" -o "$RESP2" &
CURL2_PID=$!
wait $CURL1_PID
wait $CURL2_PID

CODE1=$(tail -1 "$RESP1")
CODE2=$(tail -1 "$RESP2")
BODY1=$(head -n -1 "$RESP1")
BODY2=$(head -n -1 "$RESP2")

echo "Response 1: HTTP $CODE1"
echo "$BODY1" | head -5
echo "Response 2: HTTP $CODE2"
echo "$BODY2" | head -5

# One must be 200, the other 409
SUCCESS=0
if [ "$CODE1" = "200" ] && [ "$CODE2" = "409" ]; then
  SUCCESS=1
fi
if [ "$CODE1" = "409" ] && [ "$CODE2" = "200" ]; then
  SUCCESS=1
fi

if [ $SUCCESS -eq 1 ]; then
  echo "=== OK: One request succeeded (200), the other rejected (409). Race condition handled. ==="
  exit 0
else
  echo "=== UNEXPECTED: Expected one 200 and one 409, got $CODE1 and $CODE2. ===="
  exit 1
fi
