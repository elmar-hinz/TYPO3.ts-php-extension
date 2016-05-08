<?php

class ExpectationTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$iterator = $this->findFixtureFiles(
			dirname(__FILE__) . "/fixtures/");
		$listOfContents = $this->readFixtureFiles($iterator);
		$this->fixtures = $this->parseFixtures($listOfContents);
	}

	public function testTrue()
	{
		foreach($this->fixtures as $filename => $fixture) {
			$result = $this->lex($fixture['typoscript']);
			$msg = sprintf("Error happened in file: %s", $filename);
			$this->assertEquals($fixture['lexer'], $result, $msg);
		}
	}

	private function lex($input)
	{
		$output = "";
		$prog = 'bin/lexer';
		if(!is_file($prog))
			 throw new Exception("No file: ".$prog);
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin
		   1 => array("pipe", "w"),  // stdout
		   2 => array("file", "/tmp/error-output.txt", "a")
		);
		$process = proc_open($prog, $descriptorspec, $pipes);
		if (is_resource($process)) {
			 fwrite($pipes[0],$input);
			 fclose($pipes[0]);
			 $output = stream_get_contents($pipes[1]);
			 fclose($pipes[1]);
		} else {
			 throw new Exception("Could not execute ".$prog.".");
		}
		$return_value = proc_close($process);
		return trim($output) . "\n";
	}

	private function findFixtureFiles($directory)
	{
		return new DirectoryIterator($directory);
	}

	private function readFixtureFiles($iterator)
	{
		$contents = array();
		foreach($iterator as $info) {
			if($info->getExtension() == "tst") {
				if($info->isFile() && $info->isReadable()) {
					$file = $info->openFile();
					$contents[$info->getFilename()] = $file->fread($file->getSize());
				}
			}
		}
		return $contents;
	}

	private function parseFixtures($contents)
	{
		$fixtures = array();
		foreach($contents as $filename => $content) {
			$fixtures[$filename] = $this->parseFixture($content);
		}
		return $fixtures;
	}

	private function parseFixture($content)
	{
		$fixture = array();
		foreach(['typoscript', 'lexer', 'php'] as $name) {
			$fixture[$name] =
				trim($this->matchInFixture($name, $content)) . "\n";
		}
		return $fixture;
	}

	private function matchInFixture($name, $content) {
		$template = '/%%%%\s*((?i)' . '%s' .
			')\s*\n((.|\n)*)(\n%%%%|$)/U';
		$pattern = sprintf($template, $name);
		if(preg_match($pattern, $content, $matches)) {
			return $matches[2];
		} else
			return "";
	}
}
