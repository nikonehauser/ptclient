#!/bin/bash
../propel/generator/bin/propel-gen . reverse
sed -i -f patch.sed schema.xml
../propel/generator/bin/propel-gen
