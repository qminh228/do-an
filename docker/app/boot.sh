#!/bin/sh
shutdown() {
  echo "shutting down container"

  # Dừng tất cả các dịch vụ của runit
  for _srv in $(ls -1 /etc/service); do
    sv force-stop ${_srv}
  done

  # Dừng runsvdir
  kill -HUP ${PID}
  wait ${PID}

  # Chờ một chút để đảm bảo các tiến trình dừng
  sleep 0.5

  # Kiểm tra và dừng các tiến trình còn sót lại
  for _pid  in $(ps -eo pid | grep -v PID  | tr -d ' ' | grep -v '^1$' | head -n -6); do
    timeout -t 5 /bin/sh -c "kill $_pid && wait $_pid || kill -9 $_pid"
  done
  exit
}

# --- Khởi động Supervisor nếu chưa chạy ---
if ! pgrep -x "supervisord" > /dev/null; then
    echo "Supervisor is not running. Starting supervisord..."
    /usr/bin/supervisord -c /etc/supervisord.conf
    sleep 2  # Chờ một chút để đảm bảo Supervisor khởi động
fi

# Thực hiện các lệnh Supervisor
echo "Running supervisorctl reread, update, and restart all..."
supervisorctl reread
supervisorctl update
supervisorctl restart all

echo "Supervisor tasks completed!"

# --- Khởi động runsvdir ---
exec env - PATH=$PATH runsvdir -P /etc/service &

PID=$!
echo "Started runsvdir, PID is $PID"
echo "wait for processes to start...."

sleep 5
for _srv in $(ls -1 /etc/service); do
    sv status ${_srv}
done

# --- Bắt tín hiệu shutdown ---
trap shutdown SIGTERM SIGHUP SIGQUIT SIGINT
wait ${PID}

shutdown