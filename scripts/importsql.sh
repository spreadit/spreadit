#!/bin/sh

for i in {0..100} do
	php createsql.php | mysql -uroot spreadit
done
