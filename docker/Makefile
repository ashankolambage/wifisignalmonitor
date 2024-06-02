SHELL = /bin/sh
UID := $(shell id -u)
GID := $(shell id -g)
down:
	UID=${UID} GID=${GID} docker-compose -f docker-compose.yml down --remove-orphans
shell:
	UID=${UID} GID=${GID} docker-compose -f docker-compose.yml exec -u ${UID}:${GID} wifisignalmonitor-app sh
up:
	UID=${UID} GID=${GID} docker-compose -f docker-compose.yml up --build -d --remove-orphans