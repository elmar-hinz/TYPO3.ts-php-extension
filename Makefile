.PHONY: list
list:
	@echo ""
	@$(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

test: lexer
	./vendor/bin/phpunit tests/

lexer: lexer.c
	gcc -ll build/lexer.c -o bin/lexer

lexer.c: src/typoscript.lex
	flex -o build/lexer.c src/typoscript.lex

install_flex:
	cd build
	wget "https://downloads.sourceforge.net/flex/flex-2.6.0.tar.bz2"

