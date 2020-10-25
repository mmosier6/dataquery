#!/bin/csh

set home = /local/spcwebsite/new/pwp
cd ${home}

#first get the hazard matrix data

#cleanup
rm -f ${home}/ImpactTable.html

set YYYY = `/bin/date -u +"%Y"`
set CheckTime = 300
set PopAC = /nfsops/ops_users/archive/OUTLOOK/${YYYY}/popdata
set php = "/usr/bin/php"

set D1otlk = `/usr/bin/find ${PopAC} -maxdepth 1 -type f -name 'cat_day1_gem_*.pop' | /usr/bin/tail -1`

if ($D1otlk != "") then
  echo "New D1 otlk file"
  set D1otlkTS = `/bin/basename ${D1otlk} .pop | /usr/bin/awk -F_ '{print $5}'`
  ${php} ./MakeSITableOtlk-enh.php 1 ${D1otlkTS}
endif

#Creat the web page
set perl = "/usr/bin/perl"

${perl} ./PWPgen.pl

exit

