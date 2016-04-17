#!/bin/bash
../lib/propel/generator/bin/propel-gen /srv/miltype/entities/ reverse
php /srv/miltype/entities/patch.php
../lib/propel/generator/bin/propel-gen