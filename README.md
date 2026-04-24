установлены телескоп и пульс для мониторинга

/telescope

/pulse

есть автотесты 

перед работай ознакомься с системой event'ов

используй seeders для заполнения бд тестовыми данными

используй makefile

обрати внимание на gitlab ci

for wss certificates use following commands:
```
openssl genrsa -des3 -out ca.key 2048
openssl req -new -x509 -days 1826 -key ca.key -out ca.crt
openssl genrsa -out server.key 2048
openssl req -new -out server.csr -key server.key
openssl x509 -req -in server.csr -CA ca.crt -CAkey ca.key -CAcreateserial -out server.crt -days 360
openssl x509 -in server.crt -sha1 -noout -fingerprint
```
