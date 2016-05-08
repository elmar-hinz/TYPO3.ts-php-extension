<?php

task('default', 'list');

desc('List all tasks.');
task('list', function($application){
	$task_list = $application->get_task_list();
	if (count($task_list)) {
		$max = max(array_map('strlen', array_keys($task_list)));
		foreach ($task_list as $name => $desc) {
			if($name != 'default')
				echo str_pad($name, $max + 4) . $desc . "\n";
		}
	}
});

desc('Clean up all generated files.');
task('clean', function() {
	execute('rm -rf ./build/*');
	execute('rm -rf ./bin/*');
});

desc('Clean, also purge the vendor directory.
	Afterwards run: **composer install**');
task('purge', 'clean', function() {
	execute('rm -rf ./vendor/*');
	mkdir('./vendor/bin/');
});

desc('Clean, build the lexer.');
task('lexer', 'clean', function(){
	execute('flex -o build/lexer.c src/typoscript.lex');
	execute('gcc -ll build/lexer.c -o bin/lexer');
});

desc('Clean, build, run tests.');
task('test', 'lexer', function() {
	passthru('./phpunit ./tests/');
});

group('brew', function() {
	desc('Brew flex');
	task('flex', function() {
		$source = "https://downloads.sourceforge.net/flex/flex-2.6.0.tar.bz2";
		$save = getcwd();
		$prefix = $save . "/vendor/flex";
		if(!is_dir($prefix)) mkdir($prefix);
		$symlink = $save . "/vendor/bin/flex";
		if(!is_file($symlink)) execute("ln -s ../flex/bin/flex ".$symlink );
		try {
			chdir("./build");
			$target = "flex.bz2";
			if(!is_file($target)) {
				printf("Downloading ...");
				file_put_contents($target, file_get_contents($source));
			}
			execute("tar -xjf ".$target);
			chdir("./flex-2.6.0");
			$configure = "./configure --disable-dependency-tracking " .
				" --disable-shared --prefix=" . $prefix;
			execute($configure);
			execute ("make");
			execute ("make install");
		} catch (Excecption $e) {
			throw new Excecption("Failed to brew flex.");
		} finally {
			chdir($save);
		}
		print(getcwd());
	});
});

function execute($cmd) {
	printf("Executing: %s\n", $cmd);
	exec($cmd);
}

