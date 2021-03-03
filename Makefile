TAG?=12-alpine

docker:
	docker build \
		. \
		-t nystudio107/richvariables-buildchain:${TAG} \
		--build-arg TAG=${TAG} \
		--no-cache
npm:
	docker container run \
		--name richvariables-buildchain \
		--network plugindev_default \
		--rm \
		-t \
		-p 8080:8080 \
		-v `pwd`:/app \
		nystudio107/richvariables-buildchain:${TAG} \
		$(filter-out $@,$(MAKECMDGOALS))
%:
	@:
# ref: https://stackoverflow.com/questions/6273608/how-to-pass-argument-to-makefile-from-command-line
