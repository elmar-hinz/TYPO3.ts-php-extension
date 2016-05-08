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
	touch('./vendor/bin/.gitkeep');
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
	desc('Brew Bison');
	task('bison', function() {
	    $source = "http://ftpmirror.gnu.org/bison/bison-3.0.4.tar.gz";
		// setup directories
		$base = getcwd();
		$vendor = "bison";
		$program = "bison";
		$relativeBin = "bin/bison";
		$prefix = setupVendorProgramPath($base, $vendor, $program, $relativeBin);
		// get sources
		$target = getSources($base, $source);
		// brew
		try {
			chdir("./build");
			execute("tar -xjf ".$target);
			chdir("./bison-3.0.4");
			$configure = "./configure --disable-dependency-tracking " .
				" --prefix=" . $prefix;
			execute($configure);
			execute ("make");
			execute ("make install");
		} catch (Excecption $e) {
			throw new Excecption("Failed to brew bison.");
		} finally {
			chdir($base);
		}
	});
	desc('Brew Flex');
	task('flex', function() {
		$source = "https://downloads.sourceforge.net/flex/flex-2.6.0.tar.bz2";
		// setup directories
		$base = getcwd();
		$vendor = "flex";
		$program = "flex";
		$relativeBin = "bin/flex";
		$prefix = setupVendorProgramPath($base, $vendor, $program, $relativeBin);
		// get sources
		$target = getSources($base, $source);
		// brew
		try {
			chdir("./build");
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
			chdir($base);
		}
	});
});

function setupVendorProgramPath($base, $vendor, $program, $relativeBin = "") {
	$id = $vendor . "/" . $program;
	$prefix = $base . "/vendor/" . $id;
	if(!is_dir($prefix)) mkdir($prefix, 0777, true);
	$symlink = $base . "/vendor/bin/" . $program;
	$cmd = "ln -s ../". $id . "/" . $relativeBin . " " . $symlink;
	if($relativeBin != "" && !is_file($symlink)) execute($cmd);
	return $prefix;
}

function getSources($base, $url) {
	$downloads = $base . "/downloads";
	$build = $base . "/build";
	$filename = basename($url);
	$downname = $downloads . "/" . $filename;
	$buildname = $build . "/" . $filename;
	if(!is_file($downname)) {
		try {
			printf("Downloading ...");
			file_put_contents($downname, file_get_contents($url));
		} catch (Excecption $e) {
			throw new Excecption("Failed to download: " . $url);
		}
	}
	if(!copy($downname, $buildname))
		throw new Excecption("Faild to copy file from downloads/ to build/.");
	return $filename;
}

function execute($cmd) {
	printf("Executing: %s\n", $cmd);
	exec($cmd);
}

