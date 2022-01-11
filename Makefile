clean:
	rm -rf build

update:
	ppm --generate-package="src/vCaptcha"

build:
	mkdir build
	ppm --no-intro --compile="src/vCaptcha" --directory="build"

install:
	ppm --no-intro --no-prompt --fix-conflict --install="build/net.intellivoid.vcaptcha.ppm"

install_fast:
	ppm --no-intro --no-prompt --skip-dependencies --fix-conflict --install="build/net.intellivoid.vcaptcha.ppm"