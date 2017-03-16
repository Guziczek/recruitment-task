## How to run the tool

Execute following command within same as this file directory:

```
$ php src/sum.php --input="data/file.xml" --output="results/result.txt"

```
Paths are relative to current CLI directory, not to script directory.

You can omit `--output` parameter, output will be redirected to `stdout`.

To display detailed help message, run script without any parameters:

```
$ php src/sum.php
```

## Additional info
I skipped obvious method / class / patterns documentation to save some time.

Everything what's relevant is under `\ThirdBridge` namespace, classes under `\Bajcik` are my personal helper libraries you should not bother.

There is only some smoke tests, which don't cover everything, again, to save some time.
