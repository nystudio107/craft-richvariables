TAG?=16-alpine
CONTAINER?=$(shell basename $(dir $(CURDIR)))-buildchain
DOCKERRUN=docker container run \
	--name ${CONTAINER} \
	--rm \
	-t \
	--network plugindev_default \
	-p 3001:3001 \
	-v "${CURDIR}"/../:/app \
	${CONTAINER}:${TAG}

.PHONY: build dev docker install clean npm

build: docker install
	${DOCKERRUN} \
		run build
dev: docker install
	${DOCKERRUN} \
		run dev
docker:
	docker build \
		. \
		-t ${CONTAINER}:${TAG} \
		--build-arg TAG=${TAG} \
		--no-cache
install: docker
	${DOCKERRUN} \
		install
clean:
	rm -rf node_modules/
	rm -f package-lock.json
npm: docker
	${DOCKERRUN} \
		$(filter-out $@,$(MAKECMDGOALS))
%:
	@:
# ref: https://stackoverflow.com/questions/6273608/how-to-pass-argument-to-makefile-from-command-line
