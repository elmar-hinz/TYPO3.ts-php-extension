File Extension
==============

The extension of the test fixture files is ``.tst``. It means
**TypoScript Test** in contrary to ``.ts``.

File Format
===========

The first part of the test file is the TypoScript to test.
The second part is the expected output, when running by the lexer.
The third part is the expected output of the compiler written as PHP array.

Each part is identified by a header sarting with ``%%`` at the very beginning
of the line.

::
    %% TypoScript
    %% Lexer
    %% PHP

The part ``%%TypoScript`` is mandatory. If another part is missing a warning
is generated. If a part is present but empty, the empty string is the expected
output.

All parts are trimmed and a newline appended. Same with the results.
Hence, surrounding whitespace isn't tested and should not matter with TS.

The trailing newline simplfies parsing, because <EOF> doesn't need to
be considered. The final parser shall take the same approach internally.

