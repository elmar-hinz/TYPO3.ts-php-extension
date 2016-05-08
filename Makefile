test: lexer
	../TYPO3.v7/app/vendor/bin/phpunit tests/

lexer: lexer.c
	gcc -ll build/lexer.c -o bin/lexer

lexer.c: src/typoscript.lex
	flex -o build/lexer.c src/typoscript.lex

