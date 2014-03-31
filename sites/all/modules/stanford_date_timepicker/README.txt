WHAT DOES THIS DO?
--------------------------------------------------------------------------------
This module overrides the time field on all date_popup fields with a timepicker widget.


REQUIREMENTS
--------------------------------------------------------------------------------
1. Libraries Module
2. jquery datetimepicker library from Trent Richardson. Please see Installation for more details.

https://github.com/trentrichardson/jQuery-Timepicker-Addon

INSTALLATION
--------------------------------------------------------------------------------
1. Download
https://github.com/trentrichardson/jQuery-Timepicker-Addon
Put the contents of the "src" directory into the libraries/jquery-ui-timepicker/ folder

Current supported version is 1.4

Final output should be:
sites/all/libraries/jquery-ui-timepicker/jquery-ui-timepicker-addon.js
OR
sites/default/libraries/jquery-ui-timepicker/jquery-ui-timepicker-addon.js

2. Configure
Set any field to date_popup with a time granularity of at least hours and you will see a timepicker when you click into the time field, instead of the default text-only javascript supported option.

Configuration page:
/admin/config/stanford-datetimepicker

Allows you to set some default formatting options for the timepicker plugin.
Currently they are not being used by the module.






