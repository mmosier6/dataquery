#!/bin/tcsh -f

if (-e /local/spcwebsite/new/SPCNews/WRN/announcement-collapsible.html.`/bin/date +"%Y%m%d"`) then
  /bin/cp /local/spcwebsite/new/SPCNews/WRN/announcement-collapsible.html.`/bin/date +"%Y%m%d"` /local/spcwebsite/new/SPCNews/announcement-collapsible.html
else
  echo /local/spcwebsite/new/SPCNews/WRN/announcement-collapsible.html.`/bin/date +"%Y%m%d"` not found, restoring default
  /bin/cp /local/spcwebsite/new/SPCNews/WRN/announcement-collapsible.html.restored /local/spcwebsite/new/SPCNews/announcement-collapsible.html
endif
