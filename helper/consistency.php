<?php

exec( 'php consistency1.php', $output1);
exec( 'start php consistency2.php', $output2);

echo "FINISHED";

print_r('<pre>');
print_r($output1);
print_r('</pre>');
print_r('<pre>');
print_r($output2);
print_r('</pre>');

?>