#!/bin/bash
../propel/generator/bin/propel-gen . reverse
php patch.php
../propel/generator/bin/propel-gen
