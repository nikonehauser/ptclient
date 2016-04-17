#!/bin/bash
../lib/propel/generator/bin/propel-gen . reverse
php patch.php
../lib/propel/generator/bin/propel-gen
